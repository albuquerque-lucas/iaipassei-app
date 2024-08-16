<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Policies\UserPolicy;

class PublicExamController extends Controller
{
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
                return redirect()->route('public.exams.results', ['exam' => $slug])->with('success', 'Respostas enviadas com sucesso!');
            } else {
                return redirect()->route('public.exams.show', ['exam' => $slug, 'page' => $request->input('page') + 1])->with('success', 'Respostas enviadas com sucesso');
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

            $title = "Resultados | $exam->title";
            return view('public.exams.results', compact('exam', 'title', 'markedAlternatives', 'statistics'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar os resultados do exame: ' . $e->getMessage());
            return redirect()->route('public.exams.show', $slug)->with('error', 'Ocorreu um erro ao carregar os resultados. Por favor, tente novamente mais tarde.');
        }
    }


    private function getExamBySlug($slug)
    {
        return Exam::where('slug', $slug)->firstOrFail();
    }

    private function getMarkedAlternatives($user, $exam)
    {
        return $user->markedAlternatives()
            ->whereHas('examQuestion', function($query) use ($exam) {
                $query->where('exam_id', $exam->id);
            })
            ->with(['examQuestion'])
            ->get();
    }

    private function sortAlternativesByQuestionNumber($markedAlternatives)
    {
        return $markedAlternatives->sortBy(function($alternative) {
            return $alternative->examQuestion->question_number;
        });
    }

    private function calculateAlternativeStatistics($markedAlternatives)
    {
        // Agrupar as alternativas por questão
        $groupedByQuestion = $markedAlternatives->groupBy('exam_question_id');

        $statistics = collect();

        // Para cada grupo de alternativas (por questão)
        foreach ($groupedByQuestion as $questionId => $alternatives) {
            $maxPercentage = 0;

            // Primeiro, calcular a maior porcentagem
            foreach ($alternatives as $alternative) {
                $totalUsersForQuestion = User::whereHas('markedAlternatives', function($query) use ($alternative) {
                    $query->where('user_question_alternatives.exam_question_id', $alternative->exam_question_id);
                })->count();

                $usersWithSameAlternative = User::whereHas('markedAlternatives', function($query) use ($alternative) {
                    $query->where('user_question_alternatives.exam_question_id', $alternative->exam_question_id)
                        ->where('user_question_alternatives.question_alternative_id', $alternative->id);
                })->count();

                $percentage = $totalUsersForQuestion > 0 ? ($usersWithSameAlternative / $totalUsersForQuestion) * 100 : 0;

                // Atualizar a maior porcentagem encontrada
                if ($percentage > $maxPercentage) {
                    $maxPercentage = $percentage;
                }

                $statistics->put($alternative->id, [
                    'percentage' => $percentage,
                    'users_with_alternative' => $usersWithSameAlternative,
                    'total_users_for_question' => $totalUsersForQuestion,
                    'is_max' => false // Inicialmente, não é o máximo
                ]);
            }

            // Atualizar o campo 'is_max' para a alternativa com a maior porcentagem
            foreach ($alternatives as $alternative) {
                if ($statistics->get($alternative->id)['percentage'] == $maxPercentage) {
                    $statistics->put($alternative->id, array_merge($statistics->get($alternative->id), ['is_max' => true]));
                }
            }
        }

        return $statistics;
    }


}
