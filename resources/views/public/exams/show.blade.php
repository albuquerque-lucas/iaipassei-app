@extends('publicLayout')

@section('main-content')
<style>
    .form-check-input {
        background-color: #ddd;
    }
</style>


<section class="quiz-page container mt-5">
    <div class="w-100 d-flex justify-content-between align-items-start p-2">
        <h4 class="mb-4 text-start">{{ $exam->title }}</h4>
        <a href="{{ route('public.examinations.show', ['slug' => $exam->examination->slug]) }}" class="btn btn-indigo-500">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Voltar
        </a>
    </div>
    <div style="height:5rem" class="mb-4 w-100">
        @if (session('success'))
            <x-cards.flash-message-card type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-cards.flash-message-card type="error" :message="session('error')" />
        @endif

    </div>
    <div class="d-flex flex-column align-items-center mb-3">
        <div>
            {{ $questions->links('pagination::bootstrap-4') }}
        </div>
        <div class="mt-1 mb-3">
            Itens {{ $questions->firstItem() }} a {{ $questions->lastItem() }} de {{ $questions->total() }}
        </div>
    </div>

    <form method="POST" action="{{ route('public.exams.submit', ['exam' => $exam->slug]) }}">
        @csrf
        <input type="hidden" name="page" value="{{ $questions->currentPage() }}">

        <div class="row d-flex flex-column align-items-center">
            @foreach ($questions as $question)
                <div class="col-md-12 mb-4" style="width:80rem">
                    <div class="card">
                        <div class="card-body"
                            x-data="{
                                selected: {{ $markedAlternatives->has($question->id) ? $markedAlternatives->get($question->id) : 'null' }},
                                showEraser: false
                            }"
                            x-init="
                                showEraser = false;
                                if (selected !== null && selected != {{ $markedAlternatives->get($question->id) ?? 'null' }}) {
                                    showEraser = true;
                                }
                            ">
                            <div class="d-flex justify-content-between align-items-start" style="min-height:3rem">
                                <h5 class="card-title">Questão {{ $question->question_number }}</h5>
                                <p class="card-text">
                                    {{ $question->statement }}
                                </p>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-indigo-500 ms-3"
                                    x-show="showEraser"
                                    @click="selected = null; showEraser = false; $refs['question_{{ $question->id }}'].querySelector('input:checked').checked = false;">
                                    <i class="fa-solid fa-eraser"></i>
                                    Apagar
                                </button>
                            </div>
                            <ul class="list-group list-group-flush" x-ref="question_{{ $question->id }}">
                                @foreach ($question->alternatives as $alternative)
                                    <label for="alternative_{{ $alternative->id }}" class="list-group-item d-flex justify-content-between align-items-center mx-2">
                                        <div class="d-flex align-items-center">
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
                                                    @change="showEraser = true"
                                                    required
                                                    @if($markedAlternatives->has($question->id) && $markedAlternatives->get($question->id) == $alternative->id) checked @endif
                                                    >
                                                <label class="form-check-label" for="alternative_{{ $alternative->id }}">
                                                    {{ $alternative->text }}
                                                </label>
                                            </div>
                                        </div>
                                        @if($markedAlternatives->has($question->id) && $markedAlternatives->get($question->id) == $alternative->id)
                                            <span class="badge rounded-pill bg-success">
                                                <i class="fa-solid fa-check"></i>
                                            </span>
                                        @endif
                                    </label>
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

        <div class="d-flex justify-content-center mt-4 mb-5">
            <button type="submit" class="btn btn-indigo-500">Enviar Respostas</button>
        </div>
    </form>
</section>
@endsection