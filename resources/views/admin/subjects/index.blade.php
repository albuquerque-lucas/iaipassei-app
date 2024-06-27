@extends('adminLayout')

@section('main-content')
    <section class='admin-subjects-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Dashboard de Matérias</h3>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">Adicionar Matéria</a>
        </div>

        <!-- Botões de navegação no topo -->
        <div class="d-flex justify-content-center mb-4">
            {!! $paginationLinks !!}
        </div>

        <x-dashboards.admin-dashboard :columns="$columns" :data="$subjects" :editRoute="$editRoute" :deleteRoute="$deleteRoute" />
    </section>
@endsection
