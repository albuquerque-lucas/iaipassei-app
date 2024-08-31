@extends('publicLayout')

@section('main-content')

<div class="flash-message-container container mt-4 mb-2">
    @if(session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif(session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @elseif(session('info'))
        <x-cards.flash-message-card type="info" :message="session('info')" />
    @endif
</div>

<div class="container mt-4 m-height-100">
    <h3 class="mb-4">{{ $examination->title }}</h3>

    <div class="card mb-4 position-relative rounded-0 shadow">
        <div class="card-body examination-card-body">
            <h5 class="card-title">{{ $examination->title }}</h5>
            <p class="card-text"><strong>Instituição:</strong> {{ $examination->institution }}</p>
            <p class="card-text"><strong>Escolaridade:</strong> {{ $examination->educationLevel->name }}</p>
            @auth
                @if(auth()->user()->examinations->contains($examination->id))
                    <span class="badge rounded-pill mb-2 badge-custom">
                        Listado
                        <i class="fa-solid fa-check ms-1"></i>
                    </span>
                    <form action="{{ route('examinations.unsubscribe', $examination->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button id="unsubscribeBtn" class="btn btn-dark delete-btn btn-sm rounded-0">
                            <i class="fa-solid fa-ban me-1"></i>
                            Remover
                        </button>
                    </form>
                @else
                    <form action="{{ route('examinations.subscribe', $examination->id) }}" method="POST" class="mt-5">
                        @csrf
                        <button type="submit" class="btn btn-teal-500 btn-sm rounded-0">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Listar
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>

    <div class="card mb-4 rounded-0 shadow">
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
                        @can('canAccessExam', $exam)
                            <a href="{{ route('public.exams.show', $exam->slug) }}" class="btn btn-indigo-500 btn-sm my-1 w-8-rem rounded-0">
                                Gabarito
                                <i class="fa-solid fa-file-signature ms-1"></i>
                            </a>

                            @if($exam->resultStatus === 'final')
                                <a href="{{ route('public.exams.results', $exam->slug) }}" class="btn btn-dark btn-sm my-1 w-8-rem rounded-0">
                                    Resultado Final
                                </a>
                            @elseif($exam->resultStatus === 'partial')
                                <a href="{{ route('public.exams.results', $exam->slug) }}" class="btn btn-dark btn-sm my-1 w-8-rem rounded-0">
                                    Resultado Parcial
                                </a>
                            @else
                                <a href="{{ route('public.exams.results', $exam->slug) }}" class="btn btn-dark btn-sm my-1 w-8-rem rounded-0">
                                    Resultado
                                </a>
                            @endif

                            <form id="unsubscribeExamForm-{{ $exam->id }}" action="{{ route('public.exams.unsubscribe', $exam->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-dark btn-sm delete-exam-btn my-1 w-8-rem rounded-0" data-exam-id="{{ $exam->id }}">
                                    Retirar Inscrição
                                </button>
                            </form>

                        @else
                            <form action="{{ route('public.exams.subscribe', $exam->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-teal-500 btn-sm my-1 w-8-rem rounded-0">
                                    <i class="fa-solid fa-plus-circle me-2"></i>
                                    Inscrever
                                </button>
                            </form>
                        @endcan
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('public.examinations.index') }}" class="btn btn-indigo-500 rounded-0">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Concursos
        </a>
    </div>
</div>
@endsection
