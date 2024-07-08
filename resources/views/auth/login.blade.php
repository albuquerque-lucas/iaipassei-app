@extends('adminLayout')

@section('main-content')
    <section class="admin-login-page container d-flex justify-content-center align-items-center vh-100">
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
                    <form method="POST" action="{{ route('admin.login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('Username') }}</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">
                                {{ __('Login') }}
                            </button>
                        </div>
                        @if (Route::has('password.request'))
                        <div class="mt-3 text-center">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
