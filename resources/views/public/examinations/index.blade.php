@extends('publicLayout')

@section('main-content')
<div class="container my-5 d-flex flex-column align-items-center">
    <h3 class="mb-4">{{ $title }}</h3>

    <div class="d-flex justify-content-center mt-4">
        {!! $paginationLinks !!}
    </div>

    <div class="list-group w-75">
        @foreach($examinations as $examination)
        <div class="list-group-item d-flex align-items-center py-3">
            <div class="me-3">
                <!-- Placeholder for an icon or image if needed -->
                <i class="bi bi-journal" style="font-size: 2rem;"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-1">{{ $examination->title }}</h5>
                <p class="mb-1"><strong>Instituição:</strong> {{ $examination->institution }}</p>
                <p class="mb-1"><strong>Nível Educacional:</strong> {{ $examination->educationLevel->name }}</p>
            </div>
            <div>
                <a href="{{ route('public.examinations.show', ['slug' => $examination->slug]) }}" class="btn btn-teal-500 btn-sm">
                    Visualizar
                    <i class="fa-solid fa-eye ms-2"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {!! $paginationLinks !!}
    </div>
</div>
@endsection
