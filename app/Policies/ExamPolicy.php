<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Exam;

class ExamPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function canAccessExam(User $user, Exam $exam): bool
    {
        return $user->examinations()->where('examinations.id', $exam->examination_id)->exists();
    }
}
