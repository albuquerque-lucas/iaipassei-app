@extends('adminLayout')

@section('main-content')
    <section class='admin-study-areas-page container mt-5'>
        <ul class="nav nav-tabs" id="studyAreasTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="show-tab" data-bs-toggle="tab" data-bs-target="#show" type="button" role="tab" aria-controls="show" aria-selected="true">
                    Visualizar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab" aria-controls="edit" aria-selected="false">
                    Editar
                </button>
            </li>
        </ul>
        <div class="tab-content" id="studyAreasTabContent">
            <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
                <div class="mt-4">
                    <h4>Área de Estudo: {{ $studyArea->name }}</h4>
                    <hr>
                    <h5>Concursos Associados</h5>
                    <ul>
                        @forelse ($studyArea->examinations as $examination)
                            <li>{{ $examination->title }}</li>
                        @empty
                            <li>Nenhum concurso associado.</li>
                        @endforelse
                    </ul>
                    <h5>Matérias Associadas</h5>
                    <ul>
                        @forelse ($studyArea->subjects as $subject)
                            <li>{{ $subject->title }}</li>
                        @empty
                            <li>Nenhuma matéria associada.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                <div class="mt-4">
                    <form action="{{ route('admin.study_areas.update', $studyArea->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome da Área de Estudo</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $studyArea->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="examinations" class="form-label">Concursos Associados</label>
                            <select multiple class="form-control" id="examinations" name="examinations[]">
                                @foreach($allExaminations as $examination)
                                    <option value="{{ $examination->id }}" {{ in_array($examination->id, $studyArea->examinations->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $examination->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subjects" class="form-label">Matérias Associadas</label>
                            <select multiple class="form-control" id="subjects" name="subjects[]">
                                @foreach($allSubjects as $subject)
                                    <option value="{{ $subject->id }}" {{ in_array($subject->id, $studyArea->subjects->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $subject->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
