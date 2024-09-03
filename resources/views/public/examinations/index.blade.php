@extends('publicLayout')

@section('main-content')
<div class="container my-5 page-height">
    <h3 class="mb-4">{{ $title }}</h3>

    <div class="d-flex justify-content-center my-4">
        {!! $paginationLinks !!}
    </div>

    <div class="row">
        @foreach($examinations as $examination)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 rounded-0">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('public.examinations.show', ['slug' => $examination->slug]) }}" class="text-dark link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-25-hover">
                                {{ $examination->title }}
                            </a>
                        </h5>
                        <p class="card-text"><strong>Instituição:</strong> {{ $examination->institution }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {!! $paginationLinks !!}
    </div>
</div>
@endsection
