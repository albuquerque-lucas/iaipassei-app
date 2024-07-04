@props(['question'])

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Questão {{ $question->question_number }}</h5>
            <div x-show="editMode">
                <button class="btn btn-sm btn-dark edit-question-btn" data-bs-toggle="collapse" data-bs-target="#edit-question-{{ $question->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Questão">
                    <i class='fa-solid fa-edit'></i>
                </button>
                <form action="{{ route('admin.question_alternatives.store') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="exam_question_id" value="{{ $question->id }}">
                    <button type="submit" class="btn btn-sm btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar Alternativa">
                        <i class="fa-solid fa-plus-circle"></i>
                    </button>
                </form>
                <button class="btn btn-sm btn-danger delete-question-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $question->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir Questão">
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
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="alternative-text" id="alternative-text-{{ $alternative->id }}">{{ $alternative->letter }}. {{ $alternative->text }}</span>
                    <div class='btn-container' x-show="editMode">
                        <button class="btn btn-sm btn-dark edit-alternative-btn mx-1" data-bs-toggle="collapse" data-bs-target="#edit-alternative-{{ $alternative->id }}" aria-expanded="false" aria-controls="edit-alternative-{{ $alternative->id }}" onclick="toggleAlternativeText({{ $alternative->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Alternativa">
                            <i class='fa-solid fa-edit'></i>
                        </button>
                        <form action="{{ route('admin.question_alternatives.destroy', $alternative->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir Alternativa">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>

                    <div class="collapse w-100 mt-2" id="edit-alternative-{{ $alternative->id }}">
                        <x-forms.edit-alternative-form :alternative="$alternative"/>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<x-popUps.confirm-delete-popUp :id="$question->id" deleteRoute="admin.exam_questions.destroy"/>

<script>
    function toggleAlternativeText(id) {
        const alternativeText = document.getElementById('alternative-text-' + id);
        if (alternativeText.style.display === 'none') {
            alternativeText.style.display = 'block';
        } else {
            alternativeText.style.display = 'none';
        }
    }
</script>

<style>
    .list-group-item {
        position: relative;
    }
    .btn-container {
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        height: 2rem;
        right: 1%;
        top: 10%;
    }
    .collapse.show + .edit-question-btn,
    .collapse.show + .edit-alternative-btn {
        display: none;
    }
</style>
