<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Examination;
use App\Models\ExamQuestion;
use App\Models\QuestionAlternative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\BulkDeleteTrait;
use Exception;

class AdminExamController extends Controller
{
    use BulkDeleteTrait;

    public function index(Request $request)
    {
        try {
            $order = $request->get('order', 'desc');
            $orderBy = $request->get('order_by', 'id');
            $search = $request->get('search', '');

            $query = Exam::with('examination')
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%{$search}%");
                })
                ->orderBy($orderBy, $order);

            $exams = $query->paginate();

            return view('admin.exams.index', [
                'exams' => $exams,
                'paginationLinks' => $exams->appends($request->except('page'))->links('pagination::bootstrap-4'),
                'editRoute' => 'admin.exams.edit',
                'deleteRoute' => 'admin.exams.destroy',
                'bulkDeleteRoute' => 'admin.exams.bulkDelete',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar provas: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $examinations = Examination::all();
            return view('admin.exams.create', compact('examinations'));
        } catch (Exception $e) {
            return redirect()->route('admin.exams.index')->with('error', 'Erro ao abrir o formulário de criação: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'examination_id' => 'required|exists:examinations,id',
                'title' => 'required|string|max:255',
                'num_questions' => 'required|integer|min:1',
                'num_alternatives' => 'required|integer|min:1',
            ]);

            $exam = Exam::create([
                'examination_id' => $validated['examination_id'],
                'title' => $validated['title'],
            ]);

            for ($i = 1; $i <= $validated['num_questions']; $i++) {
                $examQuestion = ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_number' => $i,
                ]);

                for ($j = 0; $j < $validated['num_alternatives']; $j++) {
                    QuestionAlternative::create([
                        'exam_question_id' => $examQuestion->id,
                        'letter' => chr(97 + $j),
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Prova criada com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar a prova: ' . $e->getMessage());
        }
    }

    public function edit($slug)
    {
        try {
            $exam = Exam::with(['examination', 'examQuestions.alternatives'])->where('slug', $slug)->firstOrFail();
            $examinations = Examination::all();

            $numQuestions = $exam->examQuestions->count();
            $numAlternativesPerQuestion = $numQuestions > 0 ? $exam->examQuestions->first()->alternatives->count() : 0;

            $examQuestions = $exam->examQuestions()->orderBy('question_number', 'asc')->paginate(5);
            return view('admin.exams.edit', compact('exam', 'examinations', 'numQuestions', 'numAlternativesPerQuestion', 'examQuestions'));
        } catch (Exception $e) {
            return redirect()->route('admin.exams.index')->with('error', 'Erro ao carregar a prova para edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $slug)
    {
        try {
            $validated = $request->validate([
                'examination_id' => 'nullable|integer|exists:examinations,id',
                'title' => 'nullable|string|max:255',
                'date' => 'nullable|date',
                'description' => 'nullable|string|max:1000',
            ]);

            $exam = Exam::where('slug', $slug)->firstOrFail();
            $exam->update($validated);

            return redirect()->route('admin.exams.edit', $slug)->with('success', 'Prova atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar a prova: ' . $e->getMessage());
        }
    }

    public function destroy($slug)
    {
        try {
            $exam = Exam::where('slug', $slug)->firstOrFail();
            $exam->delete();

            return redirect()->back()->with('success', 'Prova excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir a prova: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            return $this->bulkDeletes($request, Exam::class, 'admin.exams.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir provas em massa: ' . $e->getMessage());
        }
    }
}
