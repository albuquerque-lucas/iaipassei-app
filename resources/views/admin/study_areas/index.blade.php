@extends('adminLayout')

@section('main-content')
    <section class='admin-study-areas-page container mt-5'>
        <ul class="nav nav-tabs" id="studyAreasTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">
                    Dashboard
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button" role="tab" aria-controls="add" aria-selected="false">
                    Adicionar Área de Estudo
                </button>
            </li>
        </ul>
        <div class="tab-content" id="studyAreasTabContent">
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                    <h4>Dashboard Áreas de Estudo</h4>
                    <button id="bulkDeleteButton" class="btn btn-danger" disabled data-bs-toggle="modal" data-bs-target="#bulkDeleteConfirmationModal">
                        Excluir Selecionados
                    </button>
                </div>

                <x-filters.study-areas-dashboard-filter :action="route('admin.study_areas.index')" />

                <!-- Botões de navegação no topo -->
                <div class="d-flex justify-content-center mb-4">
                    {!! $paginationLinks !!}
                </div>

                <x-dashboards.admin-study-areas-dashboard :data="$studyAreas" :editRoute="$editRoute" :deleteRoute="$deleteRoute" :paginationLinks="$paginationLinks" />
            </div>
            <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="add-tab">
                <x-forms.create-study-area-form />
            </div>
        </div>
    </section>

    <x-popUps.bulk-delete-confirmation-modal :deleteRoute="$bulkDeleteRoute"/>
@endsection
