@extends('adminLayout')

@section('main-content')
<section class='admin-examinations-page container mt-5'>
    <!-- Mensagens de sucesso e erro -->
    @if(session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif(session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Dashboard Concursos Públicos</h4>
        <div>
            <a href="{{ route('admin.examinations.create') }}" class="btn btn-primary me-2">Adicionar
                Concurso</a>
            <button id="bulkDeleteButton" class="btn btn-danger" disabled data-bs-toggle="modal"
                data-bs-target="#bulkDeleteConfirmationModal">
                Excluir Selecionados
            </button>
        </div>
    </div>

    <x-filters.examinations-dashboard-filter :action="route('admin.examinations.index')" />

    <div class="d-flex justify-content-center mb-4">
        {!! $paginationLinks !!}
    </div>

    <x-dashboards.admin-examinations-dashboard :data="$examinations" :editRoute="$editRoute" :deleteRoute="$deleteRoute"
        :paginationLinks="$paginationLinks" />
</section>

<x-popUps.bulk-delete-confirmation-modal :deleteRoute="$bulkDeleteRoute" />
@endsection
