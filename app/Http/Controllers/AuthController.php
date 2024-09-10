<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicRegisterFormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showPublicLoginForm()
    {
        if (auth()->check()) {
            $slug = auth()->user()->slug;
            return redirect()->route('public.profile.index', ['slug' => $slug]);
        }

        $title = 'Login | IaiPassei';
        return view('auth.public_login', compact('title'));
    }

    public function showPublicRegisterForm()
    {
        $title = 'Cadastro | IaiPassei';
        return view('auth.public_register', compact('title'));
    }

    public function publicRegister(PublicRegisterFormRequest $request)
    {
        $data = $request->validated();

        try {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'account_plan_id' => 1,
                'password' => Hash::make($data['password']),
                'google_id' => $data['google_id'] ?? null,
            ]);
            // Disparar evento de confirmação de e-mail
            event(new Registered($user));

            // Autenticar o usuário após a criação
            Auth::login($user);
            return redirect()->route('verification.notice')->with('success', 'Conta criada com sucesso!');
        } catch (Exception $e) {
            Log::error("Erro ao criar o usuário: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withErrors([
                'error' => 'Ocorreu um erro ao tentar criar a conta: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        try {

            if (Auth::attempt($request->only('username', 'password'))) {
                $user = Auth::user();
                return redirect()->route('admin.profile.index', ['slug' => $user->slug])->with('success', 'Login realizado com sucesso.');
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

    public function publicLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        try {

            if (Auth::attempt($request->only('username', 'password'))) {
                $user = Auth::user();
                return redirect()->route('public.profile.index', ['slug' => $user->slug])->with('success', 'Login realizado com sucesso.');
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

            $firstName = $googleUser->user['given_name'];
            $lastName = $googleUser->user['family_name'];
            $email = $googleUser->getEmail();
            $googleId = $googleUser->getId();

            $user = User::where('email', $email)->first();

            if (!$user) {
                return redirect()->route('public.register.index')->withInput([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'google_id' => $googleId,
                ]);
            }

            if (is_null($user->google_id)) {
                $user->google_id = $googleId;
                $user->save();
            }

            Auth::login($user, true);

            return redirect()->route('public.profile.index', ['slug' => $user->slug])->with('success', 'Você está logado.');
        } catch (Exception $e) {
            return redirect()->route('public.login.index')->withErrors([
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

            return redirect()->route('admin.login.index')->with('success', 'Logout realizado com sucesso.');
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->withErrors([
                'error' => 'Ocorreu um erro ao tentar fazer logout: ' . $e->getMessage(),
            ]);
        }
    }

    public function publicLogout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('public.home')->with('success', 'Logout realizado com sucesso.');
        } catch (Exception $e) {
            return redirect()->route('public.login.index')->withErrors([
                'error' => 'Ocorreu um erro ao tentar fazer logout: ' . $e->getMessage(),
            ]);
        }
    }

    public function profile($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        return view('auth.profile', compact('user', 'slug'));
    }
}
