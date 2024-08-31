@extends('adminLayout')

@section('main-content')
    <section class='admin-subjects-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">voltar</a>
            <ul class="nav nav-tabs" id="subjectsTab" role="tablist">
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
        </div>
        <div class="tab-content" id="subjectsTabContent">
            <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
                <div class="mt-4">
                    <h4>Matéria: {{ $subject->title }}</h4>
                    <hr>
                    <h5>Nível Educacional</h5>
                    <p>{{ $subject->educationLevel->name ?? 'Não informado' }}</p>
                </div>
            </div>
            <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                <div class="mt-4">
                    <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Nome da Matéria</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $subject->title }}">
                        </div>
                        <div class="mb-3">
                            <label for="education_level_id" class="form-label">Nível Educacional</label>
                            <select id="education_level_id" name="education_level_id" class="form-control">
                                @foreach($educationLevels as $educationLevel)
                                    <option value="{{ $educationLevel->id }}" {{ $subject->education_level_id == $educationLevel->id ? 'selected' : '' }}>
                                        {{ $educationLevel->name }}
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
