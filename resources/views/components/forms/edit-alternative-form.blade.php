@props(['alternative'])

<form action="{{ route('admin.question_alternatives.update', $alternative->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="exam_question_id" value="{{ $alternative->exam_question_id }}">
    <div class="mb-3 w-25">
        <label for="alternative_letter-{{ $alternative->id }}" class="form-label">Letra da Alternativa</label>
        <input type="text" id="alternative_letter-{{ $alternative->id }}" name="letter" class="form-control" value="{{ $alternative->letter }}">
    </div>
    <div class="mb-3 w-25">
        <label for="alternative_text-{{ $alternative->id }}" class="form-label">Texto da Alternativa</label>
        <input type="text" id="alternative_text-{{ $alternative->id }}" name="text" class="form-control" value="{{ $alternative->text }}">
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#edit-alternative-{{ $alternative->id }}" aria-expanded="false" aria-controls="edit-alternative-{{ $alternative->id }}" onclick="toggleAlternativeText({{ $alternative->id }})">Cancelar</button>
</form>
