@extends('adminLayout')

@section('main-content')
<section class='page-height create-examinations-page container my-5'>
    <!-- Flash Messages -->
    <div class="m-height-5-rem mb-4">
        @if(session('success'))
            <x-cards.flash-message-card type="success" :message="session('success')" />
        @elseif(session('error'))
            <x-cards.flash-message-card type="error" :message="session('error')" />
        @elseif(session('info'))
            <x-cards.flash-message-card type="info" :message="session('info')" />
        @endif
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-tabs" id="createExaminationsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true">
                    Formulário
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="import-tab" data-bs-toggle="tab" data-bs-target="#import" type="button" role="tab" aria-controls="import" aria-selected="false">
                    Importar Arquivo
                </button>
            </li>
        </ul>
        <a href="{{ route('admin.examinations.index') }}" class="btn btn-dark rounded-0">voltar</a>
    </div>

    <div class="tab-content" id="createExaminationsTabContent">
        <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
            <div class="mt-4">
                <x-forms.create-examination-manual-form :educationLevels="$educationLevels" x-data="$data" :importedData="$importedData"/>
            </div>
        </div>
        <div class="tab-pane fade" id="import" role="tabpanel" aria-labelledby="import-tab">
            <div class="mt-4">
                <h4 class="my-5">Importar Arquivo</h4>
                <x-forms.create-examination-file-form />
            </div>
        </div>
    </div>
</section>
@endsection
