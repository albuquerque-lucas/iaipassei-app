@props(['user'])

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <h5>Alterar Senha</h5>
        <label for="password" class="form-label">Senha</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
        @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-dark w-25">Atualizar</button>
    </div>
</form>
