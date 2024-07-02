@extends('adminLayout')

@section('main-content')
    <section class='admin-examinations-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard Concursos Públicos</h4>
            <a href="{{ route('admin.examinations.create') }}" class="btn btn-primary">Adicionar Concurso</a>
        </div>

        <x-filters.examinations-dashboard-filter :action="route('admin.examinations.index')" />

        <div class="d-flex justify-content-center mb-4">
            {!! $paginationLinks !!}
        </div>

        <x-dashboards.admin-examinations-dashboard :data="$examinations" :editRoute="$editRoute" :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const modalId = this.getAttribute('data-bs-target');
                const modalElement = document.querySelector(modalId);
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            });
        });
    });
</script>
@endpush
