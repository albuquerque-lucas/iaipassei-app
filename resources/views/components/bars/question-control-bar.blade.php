@props(['exam'])

<div class="control-bar d-flex justify-content-end">
    <form action="{{ route('admin.exam_questions.store') }}" method="POST" class="mx-2"  x-show="editMode">
        @csrf
        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
        <button type="submit" class="btn btn-dark edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar Questão">
            <i class="fa-solid fa-plus-circle"></i> Questão
        </button>
    </form>
    <form action="{{ route('admin.exam_questions.delete_last') }}" method="POST"  x-show="editMode">
        @csrf
        @method('DELETE')
        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
        <button type="submit" class="btn btn-secondary delete-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Retirar Questão">
            <i class="fa-solid fa-minus-circle"></i>
        </button>
    </form>
</div>
