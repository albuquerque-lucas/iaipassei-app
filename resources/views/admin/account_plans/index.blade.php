@extends('adminLayout')

@section('main-content')
<section class='admin-account-plans-page container mt-5'>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Dashboard | Planos de Conta</h4>
        <div>
            <a href="{{ route('admin.account_plans.create') }}" class="btn btn-dark edit-btn me-2 rounded-0">
                Adicionar Plano
                <i class="fa-solid fa-plus-circle ms-1"></i>
            </a>
                <button id="bulkDeleteButton" class="btn btn-danger" disabled data-bs-toggle="modal"
                data-bs-target="#bulkDeleteConfirmationModal">
                Excluir Selecionados
            </button>
        </div>
    </div>
    <x-cards.flash-message-container />

    <x-filters.account-plans-dashboard-filter :action="route('admin.account_plans.index')" />

    <div class="d-flex justify-content-center mb-4">
        {!! $paginationLinks !!}
    </div>

    <x-dashboards.admin-account-plans-dashboard :data="$accountPlans" :editRoute="$editRoute"
        :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
</section>

<x-popUps.bulk-delete-confirmation-modal :deleteRoute="$bulkDeleteRoute" />
@endsection
