<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\EducationLevel;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Notice;
use App\Models\StudyArea;
use App\Models\QuestionAlternative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\BulkDeleteTrait;
use App\Http\Requests\ExaminationManualStoreFormRequest;
use Exception;

class AdminExaminationController extends Controller
{
    use BulkDeleteTrait;

    public function index(Request $request)
    {
        try {
            $order = $request->get('order', 'desc');
            $orderBy = $request->get('order_by', 'id');
            $search = $request->get('search', '');

            $query = Examination::with('educationLevel')
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%{$search}%");
                })
                ->orderBy($orderBy, $order);

            $examinations = $query->paginate();

            return view('admin.examinations.index', [
                'examinations' => $examinations,
                'paginationLinks' => $examinations->appends($request->except('page'))->links('pagination::bootstrap-4'),
                'editRoute' => 'admin.examinations.edit',
                'deleteRoute' => 'admin.examinations.destroy',
                'bulkDeleteRoute' => 'admin.examinations.bulkDelete',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar concursos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $educationLevels = EducationLevel::all();
            return view('admin.examinations.create', compact('educationLevels'));
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->with('error', 'Erro ao abrir o formulário de criação: ' . $e->getMessage());
        }
    }

    public function store(ExaminationManualStoreFormRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            $examination = Examination::create([
                'education_level_id' => $validated['education_level_id'],
                'title' => $validated['title'],
                'institution' => $validated['institution'],
                'active' => $request->has('active') ? $validated['active'] : false,
            ]);

            if ($request->hasFile('notice')) {
                $file = $request->file('notice');
                $filePath = $file->store('notices', 'public');

                Notice::create([
                    'examination_id' => $examination->id,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'publication_date' => now(),
                ]);
            }

            for ($i = 1; $i <= $validated['num_exams']; $i++) {
                $exam = Exam::create([
                    'examination_id' => $examination->id,
                    'title' => "P-$i",
                ]);

                for ($j = 1; $j <= $validated['num_questions_per_exam']; $j++) {
                    $examQuestion = ExamQuestion::create([
                        'exam_id' => $exam->id,
                        'question_number' => $j,
                    ]);

                    for ($k = 0; $k < $validated['num_alternatives_per_question']; $k++) {
                        QuestionAlternative::create([
                            'exam_question_id' => $examQuestion->id,
                            'letter' => chr(97 + $k),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.examinations.index')->with('success', 'Concurso criado com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar o concurso: ' . $e->getMessage());
        }
    }




    public function edit($id)
    {
        try {
            $examination = Examination::with(['educationLevel', 'exams.examQuestions.alternatives', 'studyAreas', 'notice'])->findOrFail($id);
            $educationLevels = EducationLevel::all();
            $allStudyAreas = StudyArea::all();

            $numExams = $examination->exams->count();
            $numQuestionsPerExam = $numExams > 0 ? $examination->exams->first()->examQuestions->count() : 0;
            $numAlternativesPerQuestion = $numQuestionsPerExam > 0 ? $examination->exams->first()->examQuestions->first()->alternatives->count() : 0;

            return view('admin.examinations.edit', compact('examination', 'educationLevels', 'numExams', 'numQuestionsPerExam', 'numAlternativesPerQuestion', 'allStudyAreas'));
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->with('error', 'Erro ao carregar o concurso para edição: ' . $e->getMessage());
        }
    }



    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'education_level_id' => 'required|exists:education_levels,id',
                'title' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'study_areas' => 'array|exists:study_areas,id',
            ]);

            $examination = Examination::findOrFail($id);
            $examination->update($validated);

            if ($request->has('study_areas')) {
                $examination->studyAreas()->sync($request->study_areas);
            }

            return redirect()->back()->with('success', 'Concurso atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar o concurso: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $examination = Examination::findOrFail($id);
            $examination->delete();

            return redirect()->route('admin.examinations.index')->with('success', 'Concurso excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->with('error', 'Erro ao excluir o concurso: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            return $this->bulkDeletes($request, Examination::class, 'admin.examinations.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir concursos em massa: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:pdf|max:2048',
    ]);

    $file = $request->file('file');

    $data = $this->processFileWithAI($file);

    return redirect()->route('admin.examinations.create')
                    ->with('success', 'Arquivo importado com sucesso!')
                    ->with('data', $data);
}

private function processFileWithAI($file)
{
    // Lógica para enviar o arquivo para um serviço de IA e obter os dados processados
    // Esta é uma implementação simulada:

    $data = [
        'education_level_id' => 1,
        'title' => 'Título do Concurso',
        'institution' => 'Nome da Instituição',
        'num_exams' => 3,
        'num_questions_per_exam' => 50,
        'num_alternatives_per_question' => 5,
    ];

    return $data;
}
}
