<form method="POST" action="{{ route('admin.exams.store') }}">
    @csrf
    <input type="hidden" name="examination_id" value="{{ $examination->id }}">
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="num_questions" class="form-label">Quantidade de Questões</label>
        <input type="number" class="form-control" id="num_questions" name="num_questions" required>
    </div>
    <div class="mb-3">
        <label for="num_alternatives" class="form-label">Quantidade de Alternativas por Questão</label>
        <input type="number" class="form-control" id="num_alternatives" name="num_alternatives" required>
    </div>
    <button type="submit" class="btn btn-dark">Criar Prova</button>
</form>
