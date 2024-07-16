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

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showPublicLoginForm()
    {
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
                // 'sex' => $data['sex'],
                // 'sexual_orientation' => $data['sexual_orientation'],
                // 'gender' => $data['gender'],
                // 'race' => $data['race'],
                // 'disability' => $data['disability'],
            ]);

            // Autenticar o usuário após a criação
            Auth::login($user);

            return redirect()->route('public.profile.index', ['slug' => $user->slug])->with('success', 'Conta criada com sucesso!');
        } catch (Exception $e) {
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
            return redirect()->route('admin.login.index')->withErrors([
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

            return redirect()->route('welcome')->with('success', 'Logout realizado com sucesso.');
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
