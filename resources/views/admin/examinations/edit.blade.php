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

                <div class="mt-5">
                    <h5>Lista de Provas</h5>
                    <ul class="list-group">
                        @foreach($examination->exams as $exam)
                            <li class="list-group-item d-flex justify-content-between align-items-center w-50 my-1">
                                <span>{{ $exam->title }}</span>
                                <div>
                                    <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-primary btn-sm me-2"><i class="fa-solid fa-eye"></i></a>
                                    <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
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
@endsection
