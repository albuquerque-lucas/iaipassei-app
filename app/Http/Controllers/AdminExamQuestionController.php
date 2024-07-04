<?php

namespace App\Http\Controllers;

use App\Models\ExamQuestion;
use App\Models\Exam;
use App\Models\QuestionAlternative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminExamQuestionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $order = $request->get('order', 'desc');
            $orderBy = $request->get('order_by', 'id');
            $search = $request->get('search', '');

            $query = ExamQuestion::with('exam')
                ->when($search, function ($query, $search) {
                    return $query->where('statement', 'like', "%{$search}%");
                })
                ->orderBy($orderBy, $order);

            $examQuestions = $query->paginate();

            return view('admin.exam_questions.index', [
                'examQuestions' => $examQuestions,
                'paginationLinks' => $examQuestions->appends($request->except('page'))->links('pagination::bootstrap-4'),
                'editRoute' => 'admin.exam_questions.edit',
                'deleteRoute' => 'admin.exam_questions.destroy',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar questões: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $exams = Exam::all();
            return view('admin.exam_questions.create', compact('exams'));
        } catch (Exception $e) {
            return redirect()->route('admin.exam_questions.index')->with('error', 'Erro ao abrir o formulário de criação: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'exam_id' => 'required|exists:exams,id',
            ]);

            $lastQuestion = ExamQuestion::where('exam_id', $validated['exam_id'])
                                ->orderBy('question_number', 'desc')
                                ->first();

            $newQuestionNumber = $lastQuestion ? $lastQuestion->question_number + 1 : 1;

            $examQuestion = ExamQuestion::create([
                'exam_id' => $validated['exam_id'],
                'question_number' => $newQuestionNumber,
            ]);

            $letters = ['a', 'b', 'c', 'd', 'e'];
            foreach ($letters as $letter) {
                QuestionAlternative::create([
                    'exam_question_id' => $examQuestion->id,
                    'letter' => $letter,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Questão criada com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar a questão: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        try {
            $examQuestion = ExamQuestion::with('exam', 'alternatives')->findOrFail($id);
            $exams = Exam::all();
            return view('admin.exam_questions.edit', compact('examQuestion', 'exams'));
        } catch (Exception $e) {
            return redirect()->route('admin.exam_questions.index')->with('error', 'Erro ao carregar a questão para edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'question_number' => 'nullable|integer|min:1',
                'statement' => 'nullable|string|max:1000',
                'alternatives' => 'nullable|array|min:1',
                'alternatives.*.id' => 'nullable|integer|exists:question_alternatives,id',
                'alternatives.*.letter' => 'nullable|string|max:1',
                'alternatives.*.text' => 'nullable|string|max:255',
            ]);

            $examQuestion = ExamQuestion::findOrFail($id);
            $examQuestion->update($validated);

            if (isset($validated['alternatives'])) {
                foreach ($validated['alternatives'] as $alternative) {
                    $questionAlternative = QuestionAlternative::findOrFail($alternative['id']);
                    $questionAlternative->update([
                        'letter' => $alternative['letter'],
                        'text' => $alternative['text'],
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Questão atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar a questão: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $examQuestion = ExamQuestion::findOrFail($id);
            $examQuestion->delete();

            return redirect()->back()->with('success', 'Questão excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir a questão: ' . $e->getMessage());
        }
    }

    public function deleteLastQuestion(Request $request)
    {
        try {
            $validated = $request->validate([
                'exam_id' => 'required|exists:exams,id',
            ]);

            $lastQuestion = ExamQuestion::where('exam_id', $validated['exam_id'])
                                ->orderBy('question_number', 'desc')
                                ->first();

            if ($lastQuestion) {
                $lastQuestion->delete();
                return redirect()->back()->with('success', 'Última questão excluída com sucesso!');
            }

            return redirect()->back()->with('error', 'Não há questões para excluir.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir a última questão: ' . $e->getMessage());
        }
    }
}
