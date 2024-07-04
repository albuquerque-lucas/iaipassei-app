@props(['question'])

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Questão {{ $question->question_number }}</h5>
            <div>
                <button class="btn btn-sm btn-dark edit-question-btn" data-bs-toggle="collapse" data-bs-target="#edit-question-{{ $question->id }}">
                    <i class='fa-solid fa-edit'></i>
                </button>
            </div>
        </div>
        <p class="card-text">{{ $question->statement ?? 'Enunciado não informado' }}</p>

        <div class="collapse" id="edit-question-{{ $question->id }}">
            <form action="{{ route('admin.exam_questions.update', $question->id) }}" method="POST">
                @csrf
                @method('PATCH')
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
                        <form action="{{ route('admin.question_alternatives.update', $alternative->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
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
