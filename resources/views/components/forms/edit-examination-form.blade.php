<form method="POST" action="{{ route('admin.examinations.update', $examination->id) }}" class="ëxamination-edit-form">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="education_level_id" class="form-label">Nível Educacional</label>
        <select class="form-select" id="education_level_id" name="education_level_id" required>
            @foreach($educationLevels as $level)
                <option value="{{ $level->id }}" {{ $examination->education_level_id == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ $examination->title }}" required>
    </div>
    <div class="mb-3">
        <label for="institution" class="form-label">Instituição</label>
        <input type="text" class="form-control" id="institution" name="institution" value="{{ $examination->institution }}" required>
    </div>
    <button type="submit" class="btn btn-dark">Salvar Alterações</button>
</form>
