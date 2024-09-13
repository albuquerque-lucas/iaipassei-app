@extends('publicLayout')

@section('main-content')
<div class="container my-5 page-height">
    <h3 class="mb-4">{{ $title }}</h3>

    <livewire:public-examinations-list />
</div>
@endsection
