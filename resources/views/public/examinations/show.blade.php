@extends('publicLayout')

@section('main-content')

<div class="m-height-5-rem container mt-5">
    @if(session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif(session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @elseif(session('info'))
        <x-cards.flash-message-card type="info" :message="session('info')" />
    @endif
</div>

<div class="container mt-4 d-flex justify-content-end">
    <a href="{{ route('public.examinations.index') }}" class="btn btn-indigo-900-hover edit-btn rounded-0">
        <i class="fa-solid fa-arrow-left me-1"></i>
        Concursos
    </a>
</div>

<div class="container mt-4 mb-5 m-height-100">

    <div class="card mb-4 position-relative rounded-0 shadow">
        <div class="card-body examination-card-body">
            <div class="card-title d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
                <h5 class="mb-2 mb-md-0 w-75 p-1">
                    {{ $examination->title }}
                </h5>
                <div class="w-100 w-md-25 d-flex justify-content-start justify-content-md-end align-items-center">
                    @auth
                        @if(auth()->user()->examinations->contains($examination->id))
                            <span class="badge rounded-pill me-3 badge-custom">
                                Inscrito
                                <i class="fa-solid fa-check ms-1"></i>
                            </span>
                        @endif
                    @endauth
                </div>
            </div>
            <p class="card-text w-100 d-flex justify-content-between align-items-center p-1 m-0">
                <strong>Instituição:</strong>
                {{ $examination->institution }}
            </p>
            <p class="card-text w-100 d-flex justify-content-between align-items-center p-1 m-0">
                <strong>Edital:</strong>
                @if($examination->latestNotice())
                    <a href="{{ asset('storage/' . $examination->latestNotice()->file_path) }}" target="_blank">
                        {{ $examination->latestNotice()->file_name }}
                    </a>
                @else
                    Indisponível
                @endif
            </p>
        </div>
    </div>

    <div class="card mb-4 rounded-0 shadow p-5">
        <div class="card-body">
            <ul class="list-group">
                @foreach($examination->exams as $exam)
                    <li class="list-group-item d-flex flex-column flex-md-row justify-content-between my-2 p-3 shadow border border-secondary-subtle">
                        <div class="w-100 w-md-75 mb-2 mb-md-0">
                            <h6 class="mb-1">{{ $exam->title }}</h6>
                            {{-- <p class="mb-1"><strong>Data:</strong> {{ $exam->date ? $exam->date->format('d/m/Y') : "Data não informada" }}</p> --}}
                            {{-- <p class="mb-1"><strong>Descrição:</strong> {{ $exam->description }}</p> --}}
                        </div>
                        <div class="d-flex flex-column align-items-start align-items-md-end w-100 w-md-25">
                            @can('canAccessExam', $exam)
                                <form id="unsubscribeExamForm-{{ $exam->id }}"
                                    action="{{ route('public.exams.unsubscribe', $exam->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-indigo-900 delete-btn btn-sm delete-exam-btn w-md-auto position-absolute top-0 end-0"
                                            data-exam-id="{{ $exam->id }}"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Retirar participação do ranking">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                                <a href="{{ route('public.exams.results', $exam->slug) }}"
                                class="btn btn-indigo-500 edit-btn btn-sm mt-md-5 w-8-rem w-md-auto rounded-0"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Painel da prova">
                                    <i class="fa-solid fa-book me-2"></i>
                                    Ranking
                                </a>
                            @else
                                <form action="{{ route('public.exams.subscribe', $exam->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-indigo-900-hover edit-btn btn-sm my-1 w-8-rem w-md-auto rounded-0"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Participar do ranking desta prova">
                                        <i class="fa-solid fa-plus-circle me-2"></i>
                                        Participar
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection
