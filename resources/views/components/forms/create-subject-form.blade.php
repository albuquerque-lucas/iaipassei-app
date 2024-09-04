<form action="{{ route('admin.subjects.store') }}" method="POST" class="mt-4">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control rounded-0" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="education_level_id" class="form-label">Nível Educacional</label>
        <select class="form-select rounded-0" id="education_level_id" name="education_level_id" required>
            <option value="" selected disabled>Selecione um nível educacional</option>
            @foreach($educationLevels as $level)
                <option value="{{ $level->id }}">{{ $level->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-dark edit-btn w-25 rounded-0">Adicionar Matéria</button>
</form>
