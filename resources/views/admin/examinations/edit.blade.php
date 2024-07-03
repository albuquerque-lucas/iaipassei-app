@extends('adminLayout')

@section('main-content')
<section class='edit-examinations-page container mt-5'>
    <x-tabs.tabs :backRoute="route('admin.examinations.index')" />

    @if (session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif (session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @endif

    <div class="tab-content" id="editExaminationsTabContent">
        <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
            <div class="mt-4">
                <h4>Visualizar Concurso</h4>
                <p><strong>Título:</strong> {{ $examination->title }}</p>
                <p><strong>Instituição:</strong> {{ $examination->institution }}</p>
                <p><strong>Nível Educacional:</strong> {{ $examination->educationLevel->name }}</p>
                <p><strong>Quantidade de Provas:</strong> {{ $numExams }}</p>
                <p><strong>Quantidade de Questões por Prova:</strong> {{ $numQuestionsPerExam }}</p>
                <p><strong>Quantidade de Alternativas por Questão:</strong> {{ $numAlternativesPerQuestion }}</p>

                <div class="d-flex align-items-center">
                    <label for="select_exam" class="form-label me-2 my-5">Selecionar Prova:</label>
                    <select class="form-select me-2 w-25" id="select_exam">
                        @foreach($examination->exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                        @endforeach
                    </select>
                    <a id="view_exam_btn" href="#" class="btn btn-primary">Visualizar</a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="mt-4">
                <h4>Editar Concurso</h4>
                <x-forms.edit-examination-form
                    :examination="$examination"
                    :educationLevels="$educationLevels"
                />

                <h4 class="mt-5">Criar Nova Prova</h4>
                <x-forms.create-exam-form :examination="$examination" />
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectExam = document.getElementById('select_exam');
        const viewExamBtn = document.getElementById('view_exam_btn');

        viewExamBtn.addEventListener('click', function () {
            const selectedExamId = selectExam.value;
            const url = `{{ url('admin/exams/edit') }}/${selectedExamId}`;
            window.location.href = url;
        });
    });
</script>
@endsection
