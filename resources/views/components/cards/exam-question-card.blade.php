@props(['question'])

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Questão {{ $question->question_number }}</h5>
            <div>
                <button class="btn btn-sm btn-dark edit-question-btn" data-bs-toggle="collapse" data-bs-target="#edit-question-{{ $question->id }}">
                    <i class='fa-solid fa-edit'></i>
                </button>
                <button class="btn btn-sm btn-danger delete-question-btn" data-bs-toggle="collapse" data-bs-target="#delete-question-{{ $question->id }}">
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
                    <div class='btn-container'>
                        <button class="btn btn-sm btn-dark edit-alternative-btn" data-bs-toggle="collapse" data-bs-target="#edit-alternative-{{ $alternative->id }}" aria-expanded="false" aria-controls="edit-alternative-{{ $alternative->id }}" onclick="toggleAlternativeText({{ $alternative->id }})">
                            <i class='fa-solid fa-edit'></i>
                        </button>
                    </div>

                    <div class="collapse w-100 mt-2" id="edit-alternative-{{ $alternative->id }}">
                        <x-forms.edit-alternative-form :alternative="$alternative"/>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

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
