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
                <p><strong>Quantidade de Provas:</strong> {{ $examination->exams->count() }}</p>
                <p><strong>Quantidade de Questões por Prova:</strong> {{ $examination->exams->first() ? $examination->exams->first()->examQuestions->count() : 'N/A' }}</p>
                <p><strong>Quantidade de Alternativas por Questão:</strong> {{ $examination->exams->first() && $examination->exams->first()->examQuestions->first() ? $examination->exams->first()->examQuestions->first()->alternatives->count() : 'N/A' }}</p>
            </div>
        </div>
        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="mt-4">
                <h4>Editar Concurso</h4>
                <form method="POST" action="{{ route('admin.examinations.update', $examination->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="education_level_id" class="form-label">Nível Educacional</label>
                        <select class="form-select" id="education_level_id" name="education_level_id" required>
                            @foreach($educationLevels as $level)
                                <option value="{{ $level->id }}" {{ $examination->education_level_id == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $examination->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control" id="institution" name="institution" value="{{ $examination->institution }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="num_exams" class="form-label">Número de Provas</label>
                        <input type="number" class="form-control" id="num_exams" name="num_exams" value="{{ $examination->exams->count() }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="num_questions_per_exam" class="form-label">Número de Questões por Prova</label>
                        <input type="number" class="form-control" id="num_questions_per_exam" name="num_questions_per_exam" value="{{ $examination->exams->first() ? $examination->exams->first()->examQuestions->count() : 0 }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="num_alternatives_per_question" class="form-label">Número de Alternativas por Questão</label>
                        <input type="number" class="form-control" id="num_alternatives_per_question" name="num_alternatives_per_question" value="{{ $examination->exams->first() && $examination->exams->first()->examQuestions->first() ? $examination->exams->first()->examQuestions->first()->alternatives->count() : 0 }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
