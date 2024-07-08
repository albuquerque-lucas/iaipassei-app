@props(['user'])

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <h5>Informações de Deficiência</h5>
        <label for="disability" class="form-label">Deficiência</label>
        <input id="disability" type="text" class="form-control @error('disability') is-invalid @enderror" name="disability" value="{{ old('disability', $user->disability) }}">
        @error('disability')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-dark w-25">Atualizar</button>
    </div>
</form>
