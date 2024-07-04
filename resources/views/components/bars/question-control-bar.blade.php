@props(['exam'])

<div class="control-bar d-flex justify-content-end p-2">
    <form action="{{ route('admin.exam_questions.delete_last') }}" method="POST"  x-show="editMode">
        @csrf
        @method('DELETE')
        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
        <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Retirar Questão">
            <i class="fa-solid fa-minus-circle"></i>
        </button>
    </form>
    <form action="{{ route('admin.exam_questions.store') }}" method="POST" class="mx-2"  x-show="editMode">
        @csrf
        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
        <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar Questão">
            <i class="fa-solid fa-plus-circle"></i>
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
