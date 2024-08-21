<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Exam;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    public function viewSensitiveInfo(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id;
    }

    public function viewAdminInfo(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->accountPlan->name === 'Admin';
    }
}
