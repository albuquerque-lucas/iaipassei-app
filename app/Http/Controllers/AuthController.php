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
                return redirect()->route('admin.profile.index')->with('success', 'Login realizado com sucesso.');
            }

            return back()->withErrors([
                'username' => 'As credenciais fornecidas não correspondem aos nossos registros.',
            ])->withInput();
        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao tentar fazer login: ' . $e->getMessage(),
            ])->withInput();
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
                    'first_name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'profile_img' => $googleUser->getAvatar(),
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

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $data = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:255',
                // Add other fields as necessary
            ]);

            $user->update($data);
            return redirect()->route('admin.profile')->with('success', 'Perfil atualizado com sucesso.');
        } catch (Exception $e) {
            return redirect()->route('admin.profile')->withErrors([
                'error' => 'Falha ao atualizar perfil: ' . $e->getMessage(),
            ]);
        }
    }
}
