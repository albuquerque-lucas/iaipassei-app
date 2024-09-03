@extends('publicLayout')

@section('main-content')

<div class="flash-message-container container mt-5">
    @if(session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif(session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @elseif(session('info'))
        <x-cards.flash-message-card type="info" :message="session('info')" />
    @endif
</div>

<div class="container mt-4 d-flex justify-content-end">
    <a href="{{ route('public.examinations.index') }}" class="btn btn-dark edit-btn rounded-0">
        <i class="fa-solid fa-arrow-left me-1"></i>

    </a>
</div>

<div class="container mt-4 mb-5 m-height-100">

    <div class="card mb-4 position-relative rounded-0 shadow">
        <div class="card-body examination-card-body">
            <div class="card-title d-flex align-items-center justify-content-between">
                <h5>
                    {{ $examination->title }}
                </h5>
                <div class="w-25 d-flex align-items-center justify-content-end">
                    @auth
                        @if(auth()->user()->examinations->contains($examination->id))
                            <span class="badge rounded-pill mb-2 me-2 badge-custom">
                                Listado
                                <i class="fa-solid fa-check ms-1"></i>
                            </span>
                            <form action="{{ route('examinations.unsubscribe', $examination->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button id="unsubscribeBtn" class="btn btn-dark delete-btn btn-sm cancel-btn rounded-0 shadow-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Retirar da minha lista de concursos">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('examinations.subscribe', $examination->id) }}" method="POST">
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
            <p class="card-text"><strong>Instituição:</strong> {{ $examination->institution }}</p>
            <p class="card-text"><strong>Escolaridade:</strong> {{ $examination->educationLevel->name }}</p>
        </div>
    </div>

    <div class="card mb-4 rounded-0 shadow p-5">
        <div class="card-body">
            <h5 class="card-title fw-bold">Lista de provas</h5>
            <p class="card-text">Quantidade: {{ $examination->exams->count() }}</p>

            <ul class="list-group">
                @foreach($examination->exams as $exam)
                <li class="list-group-item d-flex justify-content-between rounded-0 my-2 p-3 shadow-sm border border-secondary-subtle">
                    <div>
                        <h6 class="mb-1">{{ $exam->title }}</h6>
                        <p class="mb-1"><strong>Data:</strong> {{ $exam->date ? $exam->date->format('d/m/Y') : "Data não informada" }}</p>
                        <p class="mb-1"><strong>Descrição:</strong> {{ $exam->description }}</p>
                    </div>
                    <div class="d-flex flex-column align-items-end w-25">
                        @can('canAccessExam', $exam)
                            <a
                            href="{{ route('public.exams.results', $exam->slug) }}"
                            class="btn btn-indigo-500 edit-btn btn-sm my-1 w-8-rem rounded-0"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Painel da prova"
                            >
                                <i class="fa-solid fa-book me-2"></i>
                                Ranking
                            </a>
                            <form
                            id="unsubscribeExamForm-{{ $exam->id }}"
                            action="{{ route('public.exams.unsubscribe', $exam->id) }}"
                            method="POST"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                type="button"
                                class="btn btn-danger btn-sm delete-exam-btn my-1 w-8-rem rounded-0"
                                data-exam-id="{{ $exam->id }}"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Retirar participação do ranking"
                                >
                                    <i class="fa-solid fa-circle-minus me-2"></i>
                                    participar
                                </button>
                            </form>

                        @else
                            <form action="{{ route('public.exams.subscribe', $exam->id) }}" method="POST">
                                @csrf
                                <button
                                type="submit"
                                class="btn btn-dark edit-btn btn-sm my-1 w-8-rem rounded-0"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Participar do ranking desta prova"
                                >
                                    <i class="fa-solid fa-plus-circle me-2"></i>
                                    participar
                                </button>
                            </form>
                        @endcan
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="container mt-4 d-flex justify-content-end">
        <a href="{{ route('public.examinations.index') }}" class="btn btn-dark edit-btn rounded-0">
            <i class="fa-solid fa-arrow-left me-1"></i>

        </a>
    </div>
</div>
@endsection
