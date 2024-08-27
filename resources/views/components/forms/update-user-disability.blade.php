@props(['user'])

<form method="POST" action="{{ route('admin.users.update', $user->slug) }}" class="mt-4">
    @csrf
    @method('PATCH')

    <div class="mb-3 d-flex justify-content-between align-items-center"
    data-bs-toggle="collapse"
    data-bs-target="#disabilityCollapse"
    aria-expanded="false"
    aria-controls="disabilityCollapse"
    style="cursor: pointer;">
        <h5>Informações de Deficiência</h5>
        <button type="button" class="btn btn-dark btn-sm rounded-0">
            <i class="fa-solid fa-chevron-down"></i>
        </button>
    </div>

    <div class="collapse" id="disabilityCollapse">
        <div class="mb-3">
            <label for="disability" class="form-label">Deficiência</label>
            <input id="disability" type="text" class="form-control rounded-0 @error('disability') is-invalid @enderror" name="disability" placeholder="{{ old('disability', $user->disability) }}">
            @error('disability')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-dark w-15 rounded-0">Atualizar</button>
        </div>
    </div>
</form>
