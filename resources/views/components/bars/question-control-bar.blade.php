<!-- resources/views/components/bars/question-control-bar.blade.php -->
<div class="control-bar d-flex justify-content-end p-2">
    <form action="{{ route('admin.exam_questions.store') }}" method="POST" class="mx-2">
        @csrf
        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
        <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar Questão">
            <i class="fa-solid fa-plus-circle"></i>
        </button>
    </form>
    <form action="{{ route('admin.exam_questions.delete_last') }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
        <button type="submit" class="btn btn-sm btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Retirar Questão">
            <i class="fa-solid fa-minus-circle"></i>
        </button>
    </form>
</div>
