@props(['educationLevels', 'importedData' => null])

<form method="POST" action="{{ route('admin.examinations.store') }}" id="examinationForm" enctype="multipart/form-data">
    @csrf
    <div id="loadingBar" class="progress" style="height: 5px; display: none;">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%;"></div>
    </div>

    <div class="mb-3">
        <label for="education_level_id" class="form-label">Nível Educacional</label>
        <select class="form-select rounded-0 shadow-sm" id="education_level_id" name="education_level_id" required>
            @foreach($educationLevels as $level)
                <option value="{{ $level->id }}" {{ old('education_level_id', $importedData['education_level_id'] ?? '') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
            @endforeach
        </select>
        @error('education_level_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control rounded-0 p-2 shadow-sm" id="title" name="title" required value="{{ old('title', $importedData['title'] ?? '') }}">
        @error('title')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="institution" class="form-label">Instituição</label>
        <input type="text" class="form-control rounded-0 p-2 shadow-sm" id="institution" name="institution" required value="{{ old('institution', $importedData['institution'] ?? '') }}">
        @error('institution')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="num_exams" class="form-label">Número de Provas</label>
        <input type="number" class="form-control rounded-0 p-2 shadow-sm" id="num_exams" name="num_exams" required value="{{ old('num_exams', $importedData['num_exams'] ?? '') }}">
        @error('num_exams')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="num_questions_per_exam" class="form-label">Número de Questões por Prova</label>
        <input type="number" class="form-control rounded-0 p-2 shadow-sm" id="num_questions_per_exam" name="num_questions_per_exam" required value="{{ old('num_questions_per_exam', $importedData['num_questions_per_exam'] ?? '') }}">
        @error('num_questions_per_exam')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="num_alternatives_per_question" class="form-label">Número de Alternativas por Questão</label>
        <input type="number" class="form-control rounded-0 p-2 shadow-sm" id="num_alternatives_per_question" name="num_alternatives_per_question" required value="{{ old('num_alternatives_per_question', $importedData['num_alternatives_per_question'] ?? '') }}">
        @error('num_alternatives_per_question')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="notice" class="form-label">Edital do Concurso</label>
        <input type="file" class="form-control rounded-0 p-2" id="notice" name="notice">
        @error('notice')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-dark rounded-0 w-25">Criar</button>
</form>
