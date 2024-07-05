@extends('adminLayout')

@section('main-content')
    <section class='admin-notices-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard Editais</h4>
            <a href="{{ route('admin.notices.create') }}" class="btn btn-primary">Adicionar Edital</a>
        </div>

        @if (session('success'))
            <x-cards.flash-message-card type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-cards.flash-message-card type="error" :message="session('error')" />
        @endif

        <div class="d-flex justify-content-center mb-4">
            {!! $paginationLinks !!}
        </div>

        <x-dashboards.admin-notices-dashboard :data="$notices" :editRoute="$editRoute" :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
    </section>
@endsection
