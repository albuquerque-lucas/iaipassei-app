@extends('adminLayout')

@section('main-content')
    <section class='admin-users-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard | Usuários</h4>
            <div>
                {{-- <a href="{{ route('admin.users.create') }}" class="btn btn-dark edit-btn me-2 rounded-0 shadow-sm">
                    Adicionar Usuário
                </a> --}}
                <button id="bulkDeleteButton" class="btn btn-danger rounded-0 shadow-sm" disabled data-bs-toggle="modal" data-bs-target="#bulkDeleteConfirmationModal">
                    Excluir Selecionados
                </button>
            </div>
        </div>
        <x-cards.flash-message-container />
        <x-filters.users-dashboard-filter :action="route('admin.users.index')" />

        <div class="d-flex justify-content-center mb-4">
            {!! $paginationLinks !!}
        </div>

        <x-dashboards.admin-users-dashboard :data="$users" :editRoute="$editRoute" :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
    </section>

    <x-popUps.bulk-delete-confirmation-modal :deleteRoute="$bulkDeleteRoute"/>
@endsection
