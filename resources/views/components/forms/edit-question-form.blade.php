@props(['question'])

<form action="{{ route('admin.exam_questions.update', $question->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="exam_id" value="{{ $question->exam_id }}">
    <div class="mb-3">
        <label for="question_number-{{ $question->id }}" class="form-label">Número da Questão</label>
        <input type="number" id="question_number-{{ $question->id }}" name="question_number" class="form-control" value="{{ $question->question_number }}">
    </div>
    <div class="mb-3">
        <label for="statement-{{ $question->id }}" class="form-label">Enunciado</label>
        <input type="text" id="statement-{{ $question->id }}" name="statement" class="form-control" value="{{ $question->statement }}">
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#edit-question-{{ $question->id }}">Cancelar</button>
</form>
