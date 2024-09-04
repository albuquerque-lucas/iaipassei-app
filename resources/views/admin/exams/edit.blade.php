@extends('adminLayout')

@section('main-content')
<section class='page-height edit-exam-page container my-5 pb-5' x-data="{
    editMode: localStorage.getItem('questionEditMode') === 'true',
    toggleEditMode() {
        this.editMode = !this.editMode;
        localStorage.setItem('questionEditMode', this.editMode);
    }
}">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-tabs" id="studyAreasTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="show-tab" data-bs-toggle="tab" data-bs-target="#show" type="button" role="tab" aria-controls="show" aria-selected="true">
                    Visualizar
                </button>
            </li>
        </ul>
        <a href="{{ route('admin.examinations.edit', $exam->examination->slug) }}" class="btn btn-dark rounded-0 shadow-sm">voltar</a>
    </div>

    @if (session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif (session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @endif

    <div class="tab-content" id="editExamTabContent">

        <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
            <x-cards.exam-info-card
            :exam="$exam"
            :numQuestions="$numQuestions"
            :educationLevels="$allEducationLevels"
            />

            <button class="btn btn-dark mb-3 rounded-0 edit-btn" @click="toggleEditMode">
                <span x-show="!editMode">Editar Questões</span>
                <span x-show="editMode">Fechar Edição</span>
            </button>
            <x-bars.question-control-bar :exam="$exam" x-show="editMode" />

            <h4 class="mt-2 py-2">Questões</h4>
            @foreach ($examQuestions as $question)
                <x-cards.exam-question-card :question="$question" x-show="editMode" />
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $examQuestions->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection
