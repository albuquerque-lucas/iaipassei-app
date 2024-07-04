@props(['question', 'editMode'])

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Questão {{ $question->question_number }}</h5>
            <div x-show="editMode">
                <button class="btn btn-sm btn-dark edit-btn edit-question-btn" data-bs-toggle="collapse" data-bs-target="#edit-question-{{ $question->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Questão">
                    <i class='fa-solid fa-edit'></i>
                </button>
                <form action="{{ route('admin.question_alternatives.store') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="exam_question_id" value="{{ $question->id }}">
                    <button type="submit" class="btn btn-sm btn-dark edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar Alternativa">
                        <i class="fa-solid fa-plus-circle"></i>
                    </button>
                </form>
                <button
                class="btn btn-sm btn-secondary delete-question-btn"
                data-bs-toggle="modal"
                data-bs-target="#confirmDeleteModal{{ $question->id }}"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="Excluir Questão">
                    <i class='fa-solid fa-trash'></i>
                </button>
            </div>
        </div>
        <p class="card-text">{{ $question->statement ?? 'Enunciado não informado' }}</p>

        <div class="collapse" id="edit-question-{{ $question->id }}">
            <x-forms.edit-question-form :question="$question"/>
        </div>

        <ul class="list-group mt-3">
            @foreach ($question->alternatives as $alternative)
                <x-listItems.alternative-item :alternative="$alternative" x-show="editMode"/>
            @endforeach
        </ul>
    </div>
</div>

<x-popUps.confirm-delete-popUp :id="$question->id" :deleteRoute="'admin.exam_questions.destroy'"/>

<style>
    .collapse.show + .edit-question-btn,
    .collapse.show + .edit-alternative-btn {
        display: none;
    }
    .edit-btn:hover {
        background-color: #007bff !important;
        border-color: #007bff !important;
        box-shadow: 1px 1px 3px #333;
    }
</style>
