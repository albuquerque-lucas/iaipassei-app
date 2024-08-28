<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Exam;

class ExamPolicy
{
    /**
     * Verifica se o usuário pode acessar o exame.
     */
    public function canAccessExam(User $user, Exam $exam): bool
    {
        // Verifica se o usuário está inscrito no exame
        return $user->exams()->where('exams.id', $exam->id)->exists();
    }
}
