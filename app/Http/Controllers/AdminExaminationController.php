<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Notice;
use App\Models\StudyArea;
use App\Models\EducationLevel;
use App\Models\QuestionAlternative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\BulkDeleteTrait;
use App\Http\Requests\ExaminationManualStoreFormRequest;
use Smalot\PdfParser\Parser;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminExaminationController extends Controller
{
    use BulkDeleteTrait;

    public function index(Request $request)
    {
        try {
            $order = $request->get('order', 'desc');
            $orderBy = $request->get('order_by', 'id');
            $search = $request->get('search', '');

            $query = Examination::query()->when($search, function ($query, $search) {
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

            $importedData = session('imported_data');
            return view('admin.examinations.create', compact('importedData'));
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
                'title' => $validated['title'],
                'institution' => $validated['institution'],
                'active' => $request->has('active') ? $validated['active'] : false,
            ]);

            if ($request->hasFile('notice')) {
                // Criar o diretório com o slug do exame para armazenar o edital
                $file = $request->file('notice');
                $folderPath = 'notices/' . $examination->slug;
                $filePath = $file->store($folderPath, 'public');

                // Criar o novo Notice associado ao exame
                Notice::create([
                    'examination_id' => $examination->id,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'publication_date' => now(),
                ]);
            }

            // Criar as provas associadas ao concurso (Exams)
            for ($i = 1; $i <= $validated['num_exams']; $i++) {
                $exam = Exam::create([
                    'education_level_id' => EducationLevel::where('slug', 'ensino-superior')->first()->id,
                    'examination_id' => $examination->id,
                    'title' => "P-$i",
                ]);

                // Criar questões e alternativas associadas a cada prova
                for ($j = 1; $j <= $validated['num_questions_per_exam']; $j++) {
                    $examQuestion = ExamQuestion::create([
                        'exam_id' => $exam->id,
                        'question_number' => $j,
                    ]);

                    for ($k = 0; $k < $validated['num_alternatives_per_question']; $k++) {
                        QuestionAlternative::create([
                            'exam_question_id' => $examQuestion->id,
                            'letter' => chr(97 + $k), // a, b, c, d, etc.
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.examinations.index')->with('success', 'Concurso criado com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao criar o concurso: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Erro ao criar o concurso: ' . $e->getMessage());
        }
    }

    public function edit($slug)
    {
        try {
            $examination = Examination::with([
                'exams.educationLevel',
                'exams.examQuestions.alternatives',
                'studyAreas',
                'notice'
            ])->where('slug', $slug)->firstOrFail();

            $allStudyAreas = StudyArea::all();
            $allEducationLevels = EducationLevel::all();

            $numExams = $examination->exams->count();
            $numQuestionsPerExam = $numExams > 0 ? $examination->exams->first()->examQuestions->count() : 0;
            $numAlternativesPerQuestion = $numQuestionsPerExam > 0 ? $examination->exams->first()->examQuestions->first()->alternatives->count() : 0;

            return view('admin.examinations.edit', compact('examination', 'numExams', 'numQuestionsPerExam', 'numAlternativesPerQuestion', 'allStudyAreas', 'allEducationLevels'));
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->with('error', 'Erro ao carregar o concurso para edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $slug)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'study_areas' => 'array|exists:study_areas,id',
            ]);

            $examination = Examination::where('slug', $slug)->firstOrFail();

            $examination->update($validated);

            if ($request->has('study_areas')) {
                $examination->studyAreas()->sync($request->study_areas);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Concurso atualizado com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar o concurso: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Erro ao atualizar o concurso: ' . $e->getMessage());
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
}
