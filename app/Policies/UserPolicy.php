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
        Log::info('viewSensitiveInfo policy called');
        return $authenticatedUser->id === $user->id;
    }
}
