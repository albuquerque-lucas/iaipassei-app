@extends('adminLayout')

@section('main-content')
<section class='edit-exam-page container mt-5' x-data="{
    editMode: localStorage.getItem('questionEditMode') === 'true',
    toggleEditMode() {
        this.editMode = !this.editMode;
        localStorage.setItem('questionEditMode', this.editMode);
    }
}">
    <x-tabs.tabs :backRoute="route('admin.examinations.edit', $exam->examination->id)" />

    @if (session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif (session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @endif

    <div class="tab-content" id="editExamTabContent">
        <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
            <x-cards.exam-info-card :exam="$exam" :numQuestions="$numQuestions" />

            <button class="btn btn-dark mb-3" @click="toggleEditMode">
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
        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="mt-4">
                <h4>Editar Prova</h4>
                <x-forms.edit-exam-form :exam="$exam" />
            </div>
        </div>
    </div>
</section>
@endsection
