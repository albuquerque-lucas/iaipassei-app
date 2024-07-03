@extends('adminLayout')

@section('main-content')
    <section class='admin-subjects-page container mt-5'>
        <ul class="nav nav-tabs" id="subjectsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">
                    Dashboard
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button" role="tab" aria-controls="add" aria-selected="false">
                    Adicionar Matéria
                </button>
            </li>
        </ul>
        <div class="tab-content" id="subjectsTabContent">
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                <!-- Mensagens de sucesso e erro -->
                @if (session('success'))
                    <x-cards.flash-message-card type="success" :message="session('success')" />
                @elseif (session('error'))
                    <x-cards.flash-message-card type="error" :message="session('error')" />
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                    <h4>Dashboard Matérias</h4>
                    <button id="bulkDeleteButton" class="btn btn-danger" disabled data-bs-toggle="modal" data-bs-target="#bulkDeleteConfirmationModal">
                        Excluir Selecionados
                    </button>
                </div>

                <x-filters.subjects-dashboard-filter :action="route('admin.subjects.index')" />

                <!-- Botões de navegação no topo -->
                <div class="d-flex justify-content-center mb-4">
                    {!! $paginationLinks !!}
                </div>

                <x-dashboards.admin-subjects-dashboard :data="$subjects" :editRoute="$editRoute" :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
            </div>
            <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="add-tab">
                <x-forms.create-subject-form :educationLevels="$educationLevels" />
            </div>
        </div>
    </section>

    <x-popUps.bulk-delete-confirmation-modal :deleteRoute="$bulkDeleteRoute"/>
@endsection
