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
use Smalot\PdfParser\Parser;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
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

    public function edit($slug)
    {
        try {
            $examination = Examination::with(['educationLevel', 'exams.examQuestions.alternatives', 'studyAreas', 'notice'])->where('slug', $slug)->firstOrFail();
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

    public function update(Request $request, $slug)
    {
        try {
            $validated = $request->validate([
                'education_level_id' => 'required|exists:education_levels,id',
                'title' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'study_areas' => 'array|exists:study_areas,id',
            ]);

            $examination = Examination::where('slug', $slug)->firstOrFail();
            $examination->update($validated);

            if ($request->has('study_areas')) {
                $examination->studyAreas()->sync($request->study_areas);
            }

            return redirect()->back()->with('success', 'Concurso atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar o concurso: ' . $e->getMessage());
        }
    }

    public function destroy($slug)
    {
        try {
            $examination = Examination::where('slug', $slug)->firstOrFail();
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

        // Processar o arquivo PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($file->getPathname());
        $text = $pdf->getText();

        // Chamar a API da OpenAI para analisar o texto
        $data = $this->processTextWithAI($text);

        // Retornar os dados extraídos como JSON
        return response()->json($data);
    }


    private function processTextWithAI($text)
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/engines/davinci/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'prompt' => "Extract the following information from the text: title, organization, number of exams, number of questions per exam, number of alternatives per question. Text: $text",
                'max_tokens' => 150,
            ],
        ]);

        $result = json_decode($response->getBody(), true);
        $analysis = $result['choices'][0]['text'];

        // Parse the analysis text to extract the required fields
        return [
            'title' => $this->extractField('title', $analysis),
            'institution' => $this->extractField('organization', $analysis),
            'num_exams' => $this->extractField('number of exams', $analysis),
            'num_questions_per_exam' => $this->extractField('number of questions per exam', $analysis),
            'num_alternatives_per_question' => $this->extractField('number of alternatives per question', $analysis),
        ];
    }


    private function extractField($field, $text)
    {
        // Implement your parsing logic here based on the response format
        $pattern = "/$field: (.*?)(\n|$)/i"; // 'i' for case-insensitive matching
        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }


}
