@extends('publicLayout')

@section('main-content')
<style>
    .question-input {
        width: 3rem;
        text-align: center;
    }
</style>

<section class="quiz-page container mt-5">
    <div class="w-100 d-flex justify-content-between align-items-start p-2">
        <h4 class="mb-4 text-start">{{ $exam->title }}</h4>
        <a href="{{ route('public.examinations.show', ['slug' => $exam->examination->slug]) }}" class="btn btn-indigo-500 rounded-0">
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

    <form method="POST" action="{{ route('public.exams.submit', ['exam' => $exam->slug]) }}">
        @csrf

        <div class="row">
            @foreach ($questions as $question)
                <div class="col-md-2 mb-4">
                    <div class="card rounded-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center" style="min-height:3rem">
                                <h6 class="card-title fw-bold">{{ $question->question_number }} .</h6>
                            </div>
                            <p class="card-text">
                                {{ $question->statement }}
                            </p>
                            <input
                                type="text"
                                class="form-control question-input"
                                name="question_{{ $question->id }}"
                                value="{{ $markedAlternatives->has($question->id) ? $markedAlternatives->get($question->id)->letter : '' }}"
                                required>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4 mb-5">
            <button type="submit" class="btn btn-indigo-500 rounded-0">Enviar Respostas</button>
        </div>
    </form>
</section>
@endsection
