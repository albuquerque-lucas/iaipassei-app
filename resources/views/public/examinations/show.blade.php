@extends('publicLayout')

@section('main-content')
<div class="container my-5">
    <h1 class="mb-4">{{ $examination->title }}</h1>

    @if(session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif(session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @elseif(session('info'))
        <x-cards.flash-message-card type="info" :message="session('info')" />
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $examination->title }}</h5>
            <p class="card-text"><strong>Instituição:</strong> {{ $examination->institution }}</p>
            <p class="card-text"><strong>Nível Educacional:</strong> {{ $examination->educationLevel->name }}</p>
            @auth
                <form action="{{ route('examinations.subscribe', $examination->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success mt-3">Inscrever-se</button>
                </form>
            @endauth
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Provas</h5>
            <p class="card-text"><strong>Quantidade de Provas:</strong> {{ $examination->exams->count() }}</p>

            <ul class="list-group">
                @foreach($examination->exams as $exam)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">{{ $exam->title }}</h6>
                        <p class="mb-1"><strong>Data:</strong> {{ $exam->date->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Descrição:</strong> {{ $exam->description }}</p>
                    </div>
                    <div>
                        <a href="#" class="btn btn-primary">Ver detalhes</a>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('public.examinations.index') }}" class="btn btn-secondary">Voltar para Concursos</a>
    </div>
</div>
@endsection
