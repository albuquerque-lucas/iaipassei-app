<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        try {
            if (Auth::attempt($request->only('username', 'password'))) {
                return redirect()->route('admin.examinations.index')->with('success', 'Login realizado com sucesso.');
            }

            return back()->withErrors([
                'username' => 'As credenciais fornecidas não correspondem aos nossos registros.',
            ]);
        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao tentar fazer login: ' . $e->getMessage(),
            ]);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]
            );

            Auth::login($user, true);

            return redirect()->route('admin.examinations.index')->with('success', 'Login com Google realizado com sucesso.');
        } catch (Exception $e) {
            return redirect()->route('admin.login')->withErrors([
                'error' => 'Ocorreu um erro ao tentar fazer login com Google: ' . $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('success', 'Logout realizado com sucesso.');
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->withErrors([
                'error' => 'Ocorreu um erro ao tentar fazer logout: ' . $e->getMessage(),
            ]);
        }
    }
}
