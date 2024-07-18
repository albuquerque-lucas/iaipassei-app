@extends('publicLayout')

@section('main-content')
    <section class="public-register-page container d-flex justify-content-center align-items-center vh-100">
        <div class="w-100" style="max-width: 600px;">
            <div class="card">
                <div class="card-header text-center">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <x-cards.flash-message-card type="danger" :message="session('error')" />
                    @endif
                    @if (session('success'))
                        <x-cards.flash-message-card type="success" :message="session('success')" />
                    @endif
                    <form method="POST" action="{{ route('public.register.store') }}">
                        @csrf
                        @if (old('google_id'))
                            <input type="hidden" name="google_id" value="{{ old('google_id') }}">
                        @endif
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Nome</label>
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $userData['first_name'] ?? '') }}" required autocomplete="first_name" autofocus>
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Sobrenome</label>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $userData['last_name'] ?? '') }}" required autocomplete="last_name">
                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuário</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username">
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $userData['email'] ?? '') }}" required autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Número de Telefone</label>
                            <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="phone_number">
                            @error('phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">Confirme a Senha</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Registrar</button>
                        </div>
                        <div class="mt-3 text-center">
                            <a class="btn btn-secondary w-100" href="{{ route('public.login.index') }}">
                                Já tem uma conta? Faça login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
