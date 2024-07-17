<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function show()
    {
        $title = 'Confirme seu email';
        $slug = auth()->check() ? auth()->user()->slug : null;

        return view('auth.verify-email', compact('title', 'slug'));
    }


    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }
}
