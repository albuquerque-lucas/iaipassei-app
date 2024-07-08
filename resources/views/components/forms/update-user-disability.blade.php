@props(['user'])

<form method="POST" action="{{ route('admin.users.update', $user->slug) }}" class="mt-4">
    @csrf
    @method('PATCH')

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h5>Informações de Deficiência</h5>
        <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="collapse" data-bs-target="#disabilityCollapse" aria-expanded="false" aria-controls="disabilityCollapse">
            <i class="fa-solid fa-cog" aria-hidden="true"></i>
        </button>
    </div>

    <div class="collapse" id="disabilityCollapse">
        <div class="mb-3">
            <label for="disability" class="form-label">Deficiência</label>
            <input id="disability" type="text" class="form-control @error('disability') is-invalid @enderror" name="disability" placeholder="{{ old('disability', $user->disability) }}">
            @error('disability')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-dark w-25">Atualizar</button>
        </div>
    </div>
</form>
