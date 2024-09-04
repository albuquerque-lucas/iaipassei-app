@extends('adminLayout')

@section('main-content')
<section class='page-height container my-5' style="min-height:120vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Dashboard Concursos Públicos</h4>
        <div>
            <a href="{{ route('admin.examinations.create') }}" class="btn btn-dark edit-btn me-2 rounded-0 shadow">
                Adicionar
                <i class="fa-solid fa-plus-circle ms-1"></i>
            </a>
                <button id="bulkDeleteButton" class="btn btn-danger rounded-0 shadow" disabled data-bs-toggle="modal"
                data-bs-target="#bulkDeleteConfirmationModal">
                Excluir Selecionados
                <i class="fa-solid fa-xmark ms-1"></i>
            </button>
        </div>
    </div>

    @if(session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif(session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @endif

    <x-filters.examinations-dashboard-filter :action="route('admin.examinations.index')" />

    <div class="d-flex justify-content-center mb-4">
        {!! $paginationLinks !!}
    </div>

    <x-dashboards.admin-examinations-dashboard :data="$examinations" :editRoute="$editRoute" :deleteRoute="$deleteRoute"
        :paginationLinks="$paginationLinks" />
</section>

<x-popUps.bulk-delete-confirmation-modal :deleteRoute="$bulkDeleteRoute" />
@endsection
