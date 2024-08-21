<?php

namespace App;
use App\Models\User;
use Illuminate\Support\Collection;

trait AlternativeStatisticsTrait
{
    public function calculateAlternativeStatistics($markedAlternatives): Collection
    {
        $groupedByQuestion = $this->groupAlternativesByQuestion($markedAlternatives);
        $statistics = collect();

        foreach ($groupedByQuestion as $questionId => $alternatives) {
            // dd($questionId);
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
        // 1. Identificar os diferentes question_alternative_id e contar quantos são
        $examQuestionId = $alternatives->first()->exam_question_id; // Assumindo que todos os alternatives têm o mesmo exam_question_id
        $alternativeCounts = \DB::table('user_question_alternatives')
            ->select('question_alternative_id', \DB::raw('count(*) as count'))
            ->where('exam_question_id', $examQuestionId)
            ->groupBy('question_alternative_id')
            ->get();

        // 2. Calcular a proporção de cada question_alternative_id
        $totalResponses = $alternativeCounts->sum('count');
        $proportions = $alternativeCounts->mapWithKeys(function($item) use ($totalResponses) {
            return [$item->question_alternative_id => $item->count / $totalResponses];
        });

        // 3. Identificar a maior proporção
        $maxProportion = $proportions->max();
        $maxAlternativeId = $proportions->search($maxProportion);

        // 4. Comparar o question_alternative_id que o usuário marcou com a maior proporção
        foreach ($alternatives as $alternative) {
            $isMax = $alternative->id == $maxAlternativeId;

            $statistics->put($alternative->id, [
                'percentage' => $proportions[$alternative->id] * 100, // Proporção em percentual
                'users_with_alternative' => $proportions[$alternative->id] * $totalResponses,
                'total_users_for_question' => $totalResponses,
                'is_max' => $isMax
            ]);
        }

        return $statistics;
    }

    private function calculatePercentage($alternative): array
    {
        $totalUsersForQuestion = User::whereHas('markedAlternatives', function($query) use ($alternative) {
            $query->where('user_question_alternatives.exam_question_id', $alternative->exam_question_id);
        })->count();

        $usersWithSameAlternative = User::whereHas('markedAlternatives', function($query) use ($alternative) {
            $query->where('user_question_alternatives.exam_question_id', $alternative->exam_question_id)
                ->where('user_question_alternatives.question_alternative_id', $alternative->id);
        })->count();

        $percentage = $totalUsersForQuestion > 0 ? ($usersWithSameAlternative / $totalUsersForQuestion) * 100 : 0;

        return [$percentage, $usersWithSameAlternative, $totalUsersForQuestion];
    }
}
