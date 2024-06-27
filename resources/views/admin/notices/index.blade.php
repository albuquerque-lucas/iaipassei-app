@extends('adminLayout')

@section('main-content')
    <section class='admin-notices-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Dashboard de Editais</h3>
            <a href="{{ route('admin.notices.create') }}" class="btn btn-primary">Adicionar Edital</a>
        </div>

        <!-- Botões de navegação no topo -->
        <div class="d-flex justify-content-center mb-4">
            {!! $paginationLinks !!}
        </div>

        <x-dashboards.admin-dashboard :columns="$columns" :data="$notices" :editRoute="$editRoute" :deleteRoute="$deleteRoute" />
    </section>
@endsection
