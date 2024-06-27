@extends('adminLayout')

@section('main-content')
    <section class='admin-subjects-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard Matérias</h4>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">Adicionar Matéria</a>
        </div>

        <x-filters.subjects-dashboard-filter :action="route('admin.subjects.index')" />

        <!-- Botões de navegação no topo -->
        <div class="d-flex justify-content-center mb-4">
            {!! $paginationLinks !!}
        </div>

        <x-dashboards.admin-subjects-dashboard :data="$subjects" :editRoute="$editRoute" :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
    </section>
@endsection
