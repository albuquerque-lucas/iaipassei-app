<?php

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\VerifyEmail;

class EmailService
{
    public function processEmailUpdate(&$validated, User $user)
    {
        if (isset($validated['email']) && $validated['email'] !== $user->email) {
            $newEmail = $validated['email'];

            $user->new_email = $newEmail;
            $user->save();

            Notification::route('mail', $newEmail)
                ->notify(new VerifyEmail($newEmail, $user->id));

            unset($validated['email']);
            return true;
        }

        return false;
    }
}
