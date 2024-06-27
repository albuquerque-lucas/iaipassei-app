@extends('adminLayout')

@section('main-content')
    <section class='admin-examinations-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Dashboard de Concursos Públicos</h3>
            <a href="{{ route('admin.examinations.create') }}" class="btn btn-primary">Adicionar Concurso</a>
        </div>

        <!-- Botões de navegação no topo -->
        <div class="d-flex justify-content-center mb-4">
            {!! $paginationLinks !!}
        </div>

        <x-dashboards.admin-examinations-dashboard :data="$examinations" :editRoute="$editRoute" :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
    </section>
@endsection
