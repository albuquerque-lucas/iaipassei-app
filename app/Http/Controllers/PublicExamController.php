<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\CalculateRankingJob;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $title = $exam->title;

            if ($user->cannot('canAccessExam', $exam)) {
                return view('public.exams.not-enrolled', compact('exam', 'title'));
            }

            // Buscar todas as questões sem paginação
            $questions = ExamQuestion::where('exam_id', $exam->id)->get();

            // Buscar as alternativas já marcadas pelo usuário, incluindo a letra
            $markedAlternatives = $user->markedAlternatives()
                ->whereIn('user_question_alternatives.exam_question_id', $questions->pluck('id'))
                ->withPivot('exam_question_id')
                ->get()
                ->keyBy('exam_question_id');

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
            $questions = ExamQuestion::where('exam_id', $exam->id)->get();

            foreach ($questions as $question) {
                $selectedAlternativeLetter = $request->input('question_' . $question->id);

                if ($selectedAlternativeLetter) {
                    $selectedAlternative = $question->alternatives()->where('letter', $selectedAlternativeLetter)->first();

                    if ($selectedAlternative) {
                        auth()->user()->markedAlternatives()
                            ->wherePivot('exam_question_id', $question->id)
                            ->detach();

                        auth()->user()->markedAlternatives()->attach($selectedAlternative->id, ['exam_question_id' => $question->id]);
                    }
                }
            }

            CalculateRankingJob::dispatch($exam);
            return redirect()->route('public.exams.results', ['exam' => $slug])->with('success', 'As respostas foram enviadas com sucesso');
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

            $totalQuestions = $exam->examQuestions->count();
            $userAnsweredAllQuestions = $markedAlternatives->count() === $totalQuestions;

            $statistics = $this->calculateAlternativeStatistics($markedAlternatives);

            $userRankings = $exam->rankings()->get();

            $title = "Resultados | $exam->title";

            return view('public.exams.results', compact('exam', 'title', 'markedAlternatives', 'statistics', 'userAnsweredAllQuestions'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar os resultados do exame: ' . $e->getMessage());
            return redirect()->route('public.exams.show', $slug)->with('error', 'Ocorreu um erro ao carregar os resultados. Por favor, tente novamente mais tarde.');
        }
    }





    private function getExamBySlug($slug)
    {
        return Exam::where('slug', $slug)->firstOrFail();
    }

    public function subscribe($examId)
    {
        try {
            $user = auth()->user();
            $exam = Exam::findOrFail($examId);

            // Verifica se o usuário já está inscrito no examination associado ao exam
            if (!$user->examinations->contains($exam->examination_id)) {
                // Inscreve o usuário no examination
                $user->examinations()->attach($exam->examination_id);
            }

            // Verifica se o usuário já está inscrito no exam
            if (!$user->exams->contains($examId)) {
                // Inscreve o usuário no exam
                $user->exams()->attach($examId);
                return redirect()->back()->with('success', 'Inscrição realizada com sucesso.');
            }

            return redirect()->back()->with('info', 'Você já está inscrito nesta prova.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao realizar inscrição: ' . $e->getMessage());
        }
    }


    public function unsubscribe(Request $request, $examId)
    {
        $user = $request->user();
        $exam = Exam::findOrFail($examId);

        if ($user->exams->contains($exam->id)) {
            $examQuestionIds = $exam->examQuestions->pluck('id');

            DB::table('user_question_alternatives')
                ->where('user_id', $user->id)
                ->whereIn('exam_question_id', $examQuestionIds)
                ->delete();

            Ranking::where('exam_id', $exam->id)
                ->where('user_id', $user->id)
                ->delete();

            $user->exams()->detach($exam->id);

            $remainingExams = $user->exams()
                ->where('examination_id', $exam->examination_id)
                ->count();

            if ($remainingExams === 0) {
                $user->examinations()->detach($exam->examination_id);
                return redirect()->back()->with('success', 'Sua inscrição na prova e no concurso foram removidas com sucesso.');
            }

            return redirect()->back()->with('success', 'Sua inscrição na prova foi removida com sucesso.');
        }

        return redirect()->back()->with('info', 'Você não está inscrito nesta prova.');
    }



}
