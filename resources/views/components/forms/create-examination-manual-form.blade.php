<form method="POST" action="{{ route('admin.examinations.store') }}" id="examinationForm">
    @csrf
    <div id="loadingBar" class="progress" style="display: none; height: 5px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%;"></div>
    </div>
    <div class="mb-3">
        <label for="education_level_id" class="form-label">Nível Educacional</label>
        <select class="form-select" id="education_level_id" name="education_level_id" required>
            @foreach($educationLevels as $level)
                <option value="{{ $level->id }}">{{ $level->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="institution" class="form-label">Instituição</label>
        <input type="text" class="form-control" id="institution" name="institution" required>
    </div>
    <div class="mb-3">
        <label for="num_exams" class="form-label">Número de Provas</label>
        <select class="form-select" id="num_exams" name="num_exams" required>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>
    <div class="mb-3">
        <label for="num_questions_per_exam" class="form-label">Número de Questões por Prova</label>
        <input type="number" class="form-control" id="num_questions_per_exam" name="num_questions_per_exam" required>
    </div>
    <div class="mb-3">
        <label for="num_alternatives_per_question" class="form-label">Número de Alternativas por Questão</label>
        <input type="number" class="form-control" id="num_alternatives_per_question" name="num_alternatives_per_question" required>
    </div>
    <button type="submit" class="btn btn-primary">Criar Exame</button>
</form>

<script>
    document.getElementById('examinationForm').addEventListener('submit', function() {
        document.getElementById('loadingBar').style.display = 'block';
    });
</script>
