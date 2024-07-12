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
use GuzzleHttp\Exception\RequestException;

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

            $importedData = session('imported_data');
            // if (isset($importedData)) {
            //     dd($importedData);
            // }
            return view('admin.examinations.create', compact('educationLevels', 'importedData'));
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
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:500000',
            ]);

            $file = $request->file('file');

            // Processar o arquivo PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();

            // Limpar o texto para evitar caracteres estranhos
            $cleanedText = $this->cleanText($text);

            // Limitar o texto para o máximo de 1000 tokens
            $limitedText = $this->limitTextToTokens($cleanedText, 1000);

            // Chamar a API da OpenAI para analisar o texto
            $data = $this->processTextWithAI($limitedText);

            session(['imported_data' => $data]);	// Salvar os dados extraídos na sessão

            return redirect()->back()->with('success', 'Texto analisado com sucesso!');

            // Retornar os dados extraídos como JSON
            // return response()->json($data);
        } catch (RequestException $e) {
            // Capturar e exibir detalhes adicionais do erro
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            return redirect()->back()->with('error', "Erro: $statusCode - $responseBody");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    private function cleanText($text)
    {
        try {
            // Remover ou substituir caracteres estranhos
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            // Remover caracteres de controle, exceto nova linha
            $text = preg_replace('/[^\P{C}\n]+/u', '', $text);
            // Substituir múltiplos espaços por um único espaço
            $text = preg_replace('/\s+/', ' ', $text);
            return $text;
        } catch (Exception $e) {
            throw new Exception("Erro na limpeza do texto: " . $e->getMessage());
        }
    }

    private function limitTextToTokens($text, $maxTokens)
    {
        try {
            $words = explode(' ', $text);
            $currentTokens = 0;
            $limitedText = '';

            foreach ($words as $word) {
                $currentTokens += strlen($word) / 4; // Aproximação: 1 token por 4 caracteres
                if ($currentTokens > $maxTokens) {
                    break;
                }
                $limitedText .= $word . ' ';
            }

            return trim($limitedText);
        } catch (Exception $e) {
            throw new Exception("Erro ao limitar o texto para tokens: " . $e->getMessage());
        }
    }

    private function processTextWithAI($text)
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Você é um assistente que extrai informações específicas de textos.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Texto: $text. O texto apresentado é um edital de concurso público. Extraia para mim os seguintes dados em formato de lista:
                        - Título do edital
                        - Instituição organizadora
                        - Qual a quantidade exata de provas marcadas como Objetivas? Responda apenas um número."
                    ]
                ],
                'max_tokens' => 200, // Limite de tokens na resposta
            ],
        ]);

        $result = json_decode($response->getBody(), true);
        $analysis = $result['choices'][0]['message']['content'];

        // Extrair os dados específicos da resposta
        $titulo = '';
        $instituicao = '';
        $quantidade_provas = '';

        if (preg_match('/- Título do edital\s*:\s*(.*)/', $analysis, $matches)) {
            $titulo = $matches[1];
        }
        if (preg_match('/- Instituição organizadora\s*:\s*(.*)/', $analysis, $matches)) {
            $instituicao = $matches[1];
        }
        if (preg_match('/- Quantidade exata de provas marcadas como Objetivas\s*:\s*(\d+)/', $analysis, $matches)) {
            $quantidade_provas = $matches[1];
        }

        return [
            'titulo' => $titulo,
            'instituicao' => $instituicao,
            'quantidade_provas' => $quantidade_provas,
        ];
    }



}
