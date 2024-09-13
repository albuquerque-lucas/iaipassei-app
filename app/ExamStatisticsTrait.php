<?php

namespace App;

use App\Models\User;
use App\Models\Exam;
use App\Models\Ranking;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

trait ExamStatisticsTrait
{
    public function calculateAlternativeStatistics($markedAlternatives): Collection
    {
        $groupedByQuestion = $this->groupAlternativesByQuestion($markedAlternatives);
        $statistics = collect();

        foreach ($groupedByQuestion as $questionId => $alternatives) {
            $statistics = $this->calculateStatisticsForAlternatives($alternatives, $statistics);
        }

        return $statistics;
    }

    private function groupAlternativesByQuestion($markedAlternatives): Collection
    {
        return $markedAlternatives->groupBy('exam_question_id');
    }

    private function calculateStatisticsForAlternatives($alternatives, $statistics): Collection
    {
        // $startTime = microtime(true);

        $examQuestionId = $alternatives->first()->exam_question_id;

        $alternativeCounts = DB::table('user_question_alternatives')
            ->select('question_alternative_id', DB::raw('count(*) as count'))
            ->where('exam_question_id', $examQuestionId)
            ->groupBy('question_alternative_id')
            ->get();

        $totalResponses = $alternativeCounts->sum('count');
        $proportions = $alternativeCounts->mapWithKeys(function($item) use ($totalResponses) {
            return [$item->question_alternative_id => $item->count / $totalResponses];
        });

        $maxProportion = $proportions->max();
        $maxAlternativeId = $proportions->search($maxProportion);

        foreach ($alternatives as $alternative) {
            if (isset($alternative->id)) {
                $isMax = $alternative->id == $maxAlternativeId;

                $statistics->put($alternative->id, [
                    'percentage' => $proportions[$alternative->id] * 100,
                    'users_with_alternative' => $proportions[$alternative->id] * $totalResponses,
                    'total_users_for_question' => $totalResponses,
                    'is_max' => $isMax,
                    'alternative' => $alternative
                ]);
            } else {
                throw new Exception('Invalid alternative object: Missing ID.');
            }
        }

        // $endTime = microtime(true);
        // $executionTime = $endTime - $startTime;

        // Log::info('Tempo de execução do método calculateStatisticsForAlternatives: ' . $executionTime . ' segundos');

        return $statistics;
    }


    private function calculatePercentage($alternative): array
    {
        $totalUsersForQuestion = User::whereHas('markedAlternatives', function ($query) use ($alternative) {
            $query->where('user_question_alternatives.exam_question_id', $alternative->exam_question_id);
        })->count();

        $usersWithSameAlternative = User::whereHas('markedAlternatives', function ($query) use ($alternative) {
            $query->where('user_question_alternatives.exam_question_id', $alternative->exam_question_id)
                ->where('user_question_alternatives.question_alternative_id', $alternative->id);
        })->count();

        $percentage = $totalUsersForQuestion > 0 ? ($usersWithSameAlternative / $totalUsersForQuestion) * 100 : 0;

        return [$percentage, $usersWithSameAlternative, $totalUsersForQuestion];
    }

    public function calculateUserRankings($examId): Collection
    {
        try {
            // Log::info("Iniciando o cálculo de rankings para o exame {$examId}");
            $users = $this->getUsersForExam($examId);
            // Log::info("Usuários obtidos para o exame {$examId}: " . $users->pluck('id'));

            $rankings = $this->calculateRankings($users, $examId);
            // Log::info("Rankings calculados com sucesso para o exame {$examId}");

            foreach ($rankings as $index => $ranking) {
                $user = $ranking['user'];
                $correctAnswers = $ranking['correct_answers'];
                $position = $index + 1;

                $existingRanking = Ranking::where('exam_id', $examId)->where('user_id', $user->id)->first();

                if ($existingRanking) {
                    $existingRanking->update([
                        'position' => $position,
                        'correct_answers' => $correctAnswers,
                    ]);
                    Log::info("Ranking atualizado para o usuário {$user->id} no exame {$examId}");
                } else {
                    Ranking::create([
                        'exam_id' => $examId,
                        'user_id' => $user->id,
                        'position' => $position,
                        'correct_answers' => $correctAnswers,
                    ]);
                    Log::info("Ranking criado para o usuário {$user->id} no exame {$examId}");
                }
            }

            return $this->assignPositions($rankings);
        } catch (Exception $e) {
            Log::error("Erro ao calcular o ranking dos usuários: " . $e->getMessage());
            throw $e;
        }
    }


    private function getUsersForExam($examId): Collection
    {
        $users = User::whereHas('exams', function ($query) use ($examId) {
            $query->where('exams.id', $examId);
        })->get();

        return $users;
    }

    private function calculateRankings(Collection $users, $examId): Collection
    {
        $totalQuestions = Exam::findOrFail($examId)->examQuestions->count();

        return $users->map(function ($user) use ($examId, $totalQuestions) {

            $markedAlternatives = $this->getMarkedAlternatives($user, $examId);
            $answeredQuestionsCount = $markedAlternatives->groupBy('exam_question_id')->count();

            if ($answeredQuestionsCount < $totalQuestions) {
                return null;
            }


            $statistics = $this->calculateAlternativeStatistics($markedAlternatives);

            $correctAnswers = $markedAlternatives->filter(function ($alternative) use ($statistics) {
                if (isset($statistics[$alternative->id])) {
                    return $statistics[$alternative->id]['is_max'];
                } else {
                    Log::error('Estatística ausente para alternativa', ['alternativeId' => $alternative->id]);
                    return false;
                }
            })->count();

            return [
                'user' => $user,
                'correct_answers' => $correctAnswers
            ];
        })->filter()->sortBy([
            ['correct_answers', 'desc'],
            ['user.slug', 'asc'],
        ])->values();
    }

    private function assignPositions(Collection $rankings): Collection
    {
        $positionedRankings = collect();
        $currentPosition = 1;

        foreach ($rankings as $index => $ranking) {
            if ($index > 0 && $ranking['correct_answers'] < $rankings[$index - 1]['correct_answers']) {
                $currentPosition = $index + 1;
            }

            $positionedRankings->push([
                'position' => $currentPosition,
                'user' => $ranking['user'],
                'correct_answers' => $ranking['correct_answers']
            ]);
        }

        return $positionedRankings;
    }

    public function sortAlternativesByQuestionNumber($markedAlternatives)
    {
        return $markedAlternatives->sortBy(function ($alternative) {
            return $alternative->examQuestion->question_number;
        });
    }

    public function getMarkedAlternatives($user, $exam)
    {
        if (is_int($exam)) {
            $exam = Exam::findOrFail($exam);
        }

        return $user->markedAlternatives()
            ->whereHas('examQuestion', function($query) use ($exam) {
                $query->where('exam_id', $exam->id);
            })
            ->with(['examQuestion'])
            ->get();
    }
}
