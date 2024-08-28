@extends('publicLayout')

@section('main-content')
    <section class="public-login-page container d-flex justify-content-center align-items-center vh-100">
        <div class="w-100" style="max-width: 600px;">
            <div class="card">
                <div class="card-header text-center">
                    <h4>{{ __('Login') }}</h4>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <x-cards.flash-message-card type="danger" :message="session('error')" />
                    @endif
                    @if (session('success'))
                        <x-cards.flash-message-card type="success" :message="session('success')" />
                    @endif
                    <form method="POST" action="{{ route('public.login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('Usuário') }}</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Senha') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember"> Lembrar </label>
                        </div>
                        <div class="d-grid mb-2">
                            <button type="submit" class="btn bg-indigo-900 rounded-0">
                                Login
                            </button>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('auth.google') }}" class="btn btn-primary d-flex justify-content-center align-items-center rounded-0">
                                <i class="fa-brands fa-google mx-3"></i>
                                Logar com o Google
                            </a>
                        </div>
                        @if (Route::has('password.request'))
                        <div class="mt-3 text-center">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Esqueceu sua senha?') }}
                            </a>
                        </div>
                        @endif
                        <div class="mt-3 text-center">
                            <a class="btn btn-indigo-500 w-100 rounded-0" href="{{ route('public.register.index') }}">
                                {{ __('Crie uma Conta') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
