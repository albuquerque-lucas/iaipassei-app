@extends('publicLayout')

@section('main-content')

<div class="container my-5">
    <h3 class="mb-4">{{ $examination->title }}</h3>

    <div class="flash-message-container">
        @if(session('success'))
            <x-cards.flash-message-card type="success" :message="session('success')" />
        @elseif(session('error'))
            <x-cards.flash-message-card type="error" :message="session('error')" />
        @elseif(session('info'))
            <x-cards.flash-message-card type="info" :message="session('info')" />
        @endif
    </div>

    <div class="card mb-4 position-relative">
        <div class="card-body examination-card-body">
            <h5 class="card-title">{{ $examination->title }}</h5>
            <p class="card-text"><strong>Instituição:</strong> {{ $examination->institution }}</p>
            <p class="card-text"><strong>Nível Educacional:</strong> {{ $examination->educationLevel->name }}</p>
            @auth
                @if(auth()->user()->examinations->contains($examination->id))
                    <span class="badge position-absolute top-0 end-0 m-3 p-2 rounded-pill badge-custom">
                        Inscrito
                        <i class="fa-solid fa-check ms-1"></i>
                    </span>
                    <form action="{{ route('examinations.unsubscribe', $examination->id) }}" method="POST" class="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary delete-btn btn-sm">
                            Retirar Inscrição
                            <i class="fa-solid fa-ban ms-1"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('examinations.subscribe', $examination->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Inscrever</button>
                    </form>
                @endif
            @endauth
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Provas</h5>
            <p class="card-text"><strong>Quantidade de Provas:</strong> {{ $examination->exams->count() }}</p>

            <ul class="list-group">
                @foreach($examination->exams as $exam)
                <li class="list-group-item d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">{{ $exam->title }}</h6>
                        <p class="mb-1"><strong>Data:</strong> {{ $exam->date->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Descrição:</strong> {{ $exam->description }}</p>
                    </div>
                    <div class="d-flex flex-column align-items-end w-25">
                        <a href="{{ route('public.exams.show', $exam->slug) }}" class="btn btn-primary btn-sm my-1 w-50">
                            Simulado
                            <i class="fa-solid fa-file-signature ms-1"></i>
                        </a>
                        @can('canAccessExam', $exam)
                            @if($exam->resultStatus === 'final')
                                <a href="#" class="btn btn-secondary btn-sm my-1 w-50">
                                    Resultado Final
                                </a>
                            @elseif($exam->resultStatus === 'partial')
                                <a href="#" class="btn btn-secondary btn-sm my-1 w-50">
                                    Resultados Parciais
                                </a>
                            @else
                                <a href="#" class="btn btn-secondary btn-sm my-1 w-50">
                                    Resultado
                                </a>
                            @endif
                        @endcan
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
