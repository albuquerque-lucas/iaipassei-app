@extends('publicLayout')

@section('main-content')

<section class="quiz-page container mt-5">
    <div class="quiz-header">
        <h4 class="mb-4">{{ $exam->title }}</h4>
        <a href="{{ route('public.examinations.show', ['slug' => $exam->examination->slug]) }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="alert mt-5 not-enrolled-alert">
        Você precisa estar inscrito no concurso para visualizar o simulado.
    </div>
</section>
@endsection
