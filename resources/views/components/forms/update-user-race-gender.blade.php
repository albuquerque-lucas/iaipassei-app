@props(['user'])

<form method="POST" action="{{ route('admin.users.update', $user->slug) }}" class="mt-4">
    @csrf
    @method('PATCH')

    <div
        class="mb-3 d-flex justify-content-between align-items-center"
        data-bs-toggle="collapse"
        data-bs-target="#raceGenderCollapse"
        aria-expanded="false"
        aria-controls="raceGenderCollapse"
        style="cursor: pointer;"
        >
        <h5>Informações de Raça e Gênero</h5>
        <button type="button" class="btn btn-dark btn-sm rounded-0">
            <i class="fa-solid fa-chevron-down"></i>
        </button>
    </div>

    <div class="collapse" id="raceGenderCollapse">
        <div class="mb-3">
            <label for="race" class="form-label">Raça</label>
            <select id="race" class="form-control rounded-0 @error('race') is-invalid @enderror" name="race">
                <option value="Branco" {{ old('race', $user->race) == 'Branco' ? 'selected' : '' }}>Branco</option>
                <option value="Preto" {{ old('race', $user->race) == 'Preto' ? 'selected' : '' }}>Preto</option>
                <option value="Pardo" {{ old('race', $user->race) == 'Pardo' ? 'selected' : '' }}>Pardo</option>
                <option value="Amarelo" {{ old('race', $user->race) == 'Amarelo' ? 'selected' : '' }}>Amarelo</option>
                <option value="Indígena" {{ old('race', $user->race) == 'Indígena' ? 'selected' : '' }}>Indígena</option>
            </select>
            @error('race')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="sexual_orientation" class="form-label">Orientação Sexual</label>
            <select id="sexual_orientation" class="form-control rounded-0 @error('sexual_orientation') is-invalid @enderror" name="sexual_orientation">
                <option value="Heterossexual" {{ old('sexual_orientation', $user->sexual_orientation) == 'Heterossexual' ? 'selected' : '' }}>Heterossexual</option>
                <option value="Homossexual" {{ old('sexual_orientation', $user->sexual_orientation) == 'Homossexual' ? 'selected' : '' }}>Homossexual</option>
                <option value="Bissexual" {{ old('sexual_orientation', $user->sexual_orientation) == 'Bissexual' ? 'selected' : '' }}>Bissexual</option>
                <option value="Assexual" {{ old('sexual_orientation', $user->sexual_orientation) == 'Assexual' ? 'selected' : '' }}>Assexual</option>
            </select>
            @error('sexual_orientation')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Gênero</label>
            <select id="gender" class="form-control rounded-0 @error('gender') is-invalid @enderror" name="gender">
                <option value="Homem Cis" {{ old('gender', $user->gender) == 'Homem Cis' ? 'selected' : '' }}>Homem Cis</option>
                <option value="Mulher Cis" {{ old('gender', $user->gender) == 'Mulher Cis' ? 'selected' : '' }}>Mulher Cis</option>
                <option value="Homem Trans" {{ old('gender', $user->gender) == 'Homem Trans' ? 'selected' : '' }}>Homem Trans</option>
                <option value="Mulher Trans" {{ old('gender', $user->gender) == 'Mulher Trans' ? 'selected' : '' }}>Mulher Trans</option>
            </select>
            @error('gender')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-dark w-15 rounded-0">Atualizar</button>
        </div>
    </div>
</form>
