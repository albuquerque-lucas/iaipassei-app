@extends('adminLayout')

@section('main-content')
<section class='page-height container mt-5 pb-5'>
    <x-tabs.tabs :backRoute="route('admin.examinations.index')" />

    @if (session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif (session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @endif

    <div class="tab-content" id="editExaminationsTabContent">
        <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
            <x-sections.exam-info-display
                :examination="$examination"
                :numExams="$numExams"
                :numQuestionsPerExam="$numQuestionsPerExam"
                :numAlternativesPerQuestion="$numAlternativesPerQuestion"
                :studyAreas="$examination->studyAreas"
            />
        </div>
        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="mt-4">
                <h4>Editar Concurso</h4>
                <x-forms.edit-examination-form
                    :examination="$examination"
                    :educationLevels="$educationLevels"
                    :allStudyAreas="$allStudyAreas"
                />

                <h4 class="mt-5">Criar Nova Prova</h4>
                <x-forms.create-exam-form :examination="$examination" />
            </div>
        </div>
    </div>
</section>
@endsection
