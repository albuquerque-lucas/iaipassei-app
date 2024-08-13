@extends('publicLayout')

@section('main-content')
<style>
    .form-check-input {
        background-color: #ddd;
    }
</style>
<section class="quiz-page container mt-5">
    <h4 class="mb-4">{{ $exam->title }}</h4>
    <div class="d-flex flex-column align-items-center mt-5 mb-3">
        <div>
            {{ $questions->links('pagination::bootstrap-4') }}
        </div>
        <div class="mt-1 mb-3">
            Itens {{ $questions->firstItem() }} a {{ $questions->lastItem() }} de {{ $questions->total() }}
        </div>
    </div>

    <form method="POST">
        @csrf
        <div class="row">
            @foreach ($questions as $question)
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body" x-data="{
                            selected: (document.querySelector('input[name=question_{{ $question->id }}]:checked') ? document.querySelector('input[name=question_{{ $question->id }}]:checked').value : null)
                        }">
                            <div class="d-flex justify-content-between" style="height:6rem">
                                <h5 class="card-title">Questão {{ $question->question_number }}</h5>
                                <p class="card-text">
                                    {{ $question->statement }}
                                </p>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary ms-3"
                                    style="max-height: 2.5rem"
                                    x-show="selected !== null"
                                    @click="selected = null; $refs['question_{{ $question->id }}'].querySelector('input:checked').checked = false;">
                                    Desmarcar
                                </button>
                            </div>
                            <ul class="list-group list-group-flush" x-ref="question_{{ $question->id }}">
                                @foreach ($question->alternatives as $alternative)
                                    <li class="list-group-item d-flex mx-2">
                                        <span class="mx-2 fw-bold">
                                            {{ $alternative->letter }} -
                                        </span>
                                        <div class="form-check ms-3">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="question_{{ $question->id }}"
                                                id="alternative_{{ $alternative->id }}"
                                                value="{{ $alternative->id }}"
                                                x-model="selected"
                                                required
                                                >
                                            <label class="form-check-label" for="alternative_{{ $alternative->id }}">
                                                {{ $alternative->text }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            {{ $questions->links('pagination::bootstrap-4') }}
        </div>
        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-primary">Enviar Respostas</button>
        </div>
    </form>
</section>
@endsection
