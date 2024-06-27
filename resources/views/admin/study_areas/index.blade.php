@extends('adminLayout')

@section('main-content')
    <section class='admin-study-areas-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard Áreas de Estudo</h4>
            <a href="{{ route('admin.study_areas.create') }}" class="btn btn-primary">Adicionar Área de Estudo</a>
        </div>

        <x-filters.study-areas-dashboard-filter :action="route('admin.study_areas.index')" />

        <!-- Botões de navegação no topo -->
        <div class="d-flex justify-content-center mb-4">
            {!! $paginationLinks !!}
        </div>

        <x-dashboards.admin-study-areas-dashboard :data="$studyAreas" :editRoute="$editRoute" :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
    </section>
@endsection
