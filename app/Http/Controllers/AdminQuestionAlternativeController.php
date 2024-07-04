<?php

namespace App\Http\Controllers;

use App\Models\QuestionAlternative;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminQuestionAlternativeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $order = $request->get('order', 'desc');
            $orderBy = $request->get('order_by', 'id');
            $search = $request->get('search', '');

            $query = QuestionAlternative::with('examQuestion')
                ->when($search, function ($query, $search) {
                    return $query->where('text', 'like', "%{$search}%");
                })
                ->orderBy($orderBy, $order);

            $questionAlternatives = $query->paginate();

            return view('admin.question_alternatives.index', [
                'questionAlternatives' => $questionAlternatives,
                'paginationLinks' => $questionAlternatives->appends($request->except('page'))->links('pagination::bootstrap-4'),
                'editRoute' => 'admin.question_alternatives.edit',
                'deleteRoute' => 'admin.question_alternatives.destroy',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar alternativas de questão: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $examQuestions = ExamQuestion::all();
            return view('admin.question_alternatives.create', compact('examQuestions'));
        } catch (Exception $e) {
            return redirect()->route('admin.question_alternatives.index')->with('error', 'Erro ao abrir o formulário de criação: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'exam_question_id' => 'required|exists:exam_questions,id',
            ]);

            $lastAlternative = QuestionAlternative::where('exam_question_id', $validated['exam_question_id'])
                ->orderBy('letter', 'desc')
                ->first();

            $nextLetter = $lastAlternative ? chr(ord($lastAlternative->letter) + 1) : 'a';

            QuestionAlternative::create([
                'exam_question_id' => $validated['exam_question_id'],
                'letter' => $nextLetter,
                'text' => '',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Alternativa criada com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar a alternativa: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        try {
            $questionAlternative = QuestionAlternative::with('examQuestion')->findOrFail($id);
            $examQuestions = ExamQuestion::all();
            return view('admin.question_alternatives.edit', compact('questionAlternative', 'examQuestions'));
        } catch (Exception $e) {
            return redirect()->route('admin.question_alternatives.index')->with('error', 'Erro ao carregar a alternativa para edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'exam_question_id' => 'nullable|integer|exists:exam_questions,id',
                'letter' => 'nullable|string|max:1',
                'text' => 'nullable|string|max:255',
            ]);

            $questionAlternative = QuestionAlternative::findOrFail($id);
            $questionAlternative->update($validated);

            return redirect()->back()->with('success', 'Alternativa atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar a alternativa: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $alternative = QuestionAlternative::findOrFail($id);
            $alternative->delete();

            return redirect()->back()->with('success', 'Alternativa excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir a alternativa: ' . $e->getMessage());
        }
    }

}
