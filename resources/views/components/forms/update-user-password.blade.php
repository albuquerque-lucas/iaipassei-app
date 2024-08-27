@props(['user'])

<form method="POST" action="{{ route('admin.users.update', $user->slug) }}" class="mt-4">
    @csrf
    @method('PATCH')

    <div class="mb-3 d-flex justify-content-between align-items-center"
    data-bs-toggle="collapse"
    data-bs-target="#passwordCollapse"
    aria-expanded="false"
    aria-controls="passwordCollapse"
    style="cursor: pointer;">
        <h5>Alterar Senha</h5>
        <button type="button" class="btn btn-dark btn-sm rounded-0">
            <i class="fa-solid fa-chevron-down"></i>
        </button>
    </div>

    <div class="collapse" id="passwordCollapse">
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input id="password" type="password" class="form-control rounded-0 @error('password') is-invalid @enderror" name="password">
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
            <input id="password_confirmation" type="password" class="form-control rounded-0" name="password_confirmation">
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-dark w-15 rounded-0">Atualizar</button>
        </div>
    </div>
</form>
