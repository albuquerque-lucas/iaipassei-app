<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\ExamStatisticsTrait;
;

class PublicExamController extends Controller
{
    use ExamStatisticsTrait;

    public function show($slug, Request $request)
    {
        try {
            $exam = Exam::where('slug', $slug)->firstOrFail();
            $user = Auth::user();
            if ($user->cannot('canAccessExam', $exam)) {
                $title = $exam->title;
                return view('public.exams.not-enrolled', compact('exam', 'title'));
            }

            $page = $request->input('page', 1);
            $questions = ExamQuestion::where('exam_id', $exam->id)->paginate(5);

            $markedAlternatives = auth()->user()->markedAlternatives()
                ->whereIn('user_question_alternatives.exam_question_id', $questions->pluck('id'))
                ->pluck('question_alternative_id', 'user_question_alternatives.exam_question_id');

            $title = $exam->title;
            return view('public.exams.show', compact('exam', 'questions', 'title', 'markedAlternatives'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar o exame: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao carregar o exame. Por favor, tente novamente mais tarde.');
        }
    }

    public function submit(Request $request, $slug)
    {
        try {
            $exam = Exam::where('slug', $slug)->firstOrFail();
            $questions = ExamQuestion::where('exam_id', $exam->id)->paginate(5);

            foreach ($questions as $question) {
                $selectedAlternativeId = $request->input('question_' . $question->id);

                if ($selectedAlternativeId) {
                    auth()->user()->markedAlternatives()
                        ->wherePivot('exam_question_id', $question->id)
                        ->detach();

                    auth()->user()->markedAlternatives()->attach($selectedAlternativeId, ['exam_question_id' => $question->id]);
                }
            }

            if ($request->input('page') == $questions->lastPage()) {
                return redirect()->route('public.exams.results', ['exam' => $slug])->with('success', 'As respostas foram enviadas com sucesso');
            } else {
                return redirect()->route('public.exams.show', ['exam' => $slug, 'page' => $request->input('page') + 1])->with('success', 'As respostas foram enviadas com sucesso');
            }
        } catch (Exception $e) {
            Log::error('Erro ao enviar as respostas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao enviar as respostas. Por favor, tente novamente mais tarde.');
        }
    }

    public function results($slug)
    {
        try {
            $exam = $this->getExamBySlug($slug);
            $user = auth()->user();

            $markedAlternatives = $this->getMarkedAlternatives($user, $exam);
            $markedAlternatives = $this->sortAlternativesByQuestionNumber($markedAlternatives);

            $statistics = $this->calculateAlternativeStatistics($markedAlternatives);
            $userRankings = $this->calculateUserRankings($exam->id);

            $title = "Resultados | $exam->title";
            return view('public.exams.results', compact('exam', 'title', 'markedAlternatives', 'statistics', 'userRankings'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar os resultados do exame: ' . $e->getMessage());
            return redirect()->route('public.exams.show', $slug)->with('error', 'Ocorreu um erro ao carregar os resultados. Por favor, tente novamente mais tarde.');
        }
    }

    private function getExamBySlug($slug)
    {
        return Exam::where('slug', $slug)->firstOrFail();
    }
}
