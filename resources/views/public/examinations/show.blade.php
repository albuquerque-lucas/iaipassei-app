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
    <a href="{{ route('public.examinations.index') }}" class="btn btn-indigo-800-hover edit-btn rounded-0">
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

    <!-- Componente Livewire de Exames -->
    <div class="container">
        <livewire:public.exams-list :examination="$examination" />
    </div>
</div>

@endsection
