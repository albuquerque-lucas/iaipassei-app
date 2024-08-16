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
        $maxPercentage = 0;
        $maxAlternativeId = null;

        foreach ($alternatives as $alternative) {
            [$percentage, $usersWithSameAlternative, $totalUsersForQuestion] = $this->calculatePercentage($alternative);

            if ($percentage > $maxPercentage) {
                $maxPercentage = $percentage;
                $maxAlternativeId = $alternative->id;
            }

            $statistics->put($alternative->id, [
                'percentage' => $percentage,
                'users_with_alternative' => $usersWithSameAlternative,
                'total_users_for_question' => $totalUsersForQuestion,
                'is_max' => false
            ]);
        }

        if ($maxAlternativeId !== null) {
            $statistics->put($maxAlternativeId, array_merge($statistics->get($maxAlternativeId), ['is_max' => true]));
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
