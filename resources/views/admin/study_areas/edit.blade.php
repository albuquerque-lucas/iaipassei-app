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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome da Matéria</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($studyArea->subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->id }}</td>
                                        <td>{{ $subject->title }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm bg-dark text-white" data-bs-toggle="modal" data-bs-target="#confirmDeleteSubjectModal{{ $subject->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Confirm Delete Modal -->
                                    <div class="modal fade" id="confirmDeleteSubjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="confirmDeleteSubjectModalLabel{{ $subject->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmDeleteSubjectModalLabel{{ $subject->id }}">Confirmar Exclusão</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Tem certeza que deseja excluir a matéria "{{ $subject->title }}"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('admin.study_areas.remove_subject', [$studyArea->id, $subject->id]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Nenhuma matéria associada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                            <label for="searchSubjects" class="form-label">Pesquisar Matérias</label>
                            <input type="text" id="searchSubjects" class="form-control mb-2" placeholder="Pesquisar Matérias">
                            <button type="button" class="btn btn-secondary mb-2" id="clearSearchSubjects">Limpar Pesquisa</button>
                        </div>
                        <div class="mb-3">
                            <label for="subjects" class="form-label">Matérias Associadas</label>
                            <select multiple class="form-control" id="subjects" name="subjects[]">
                                <!-- Options will be rendered here based on search -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchSubjectsInput = document.getElementById('searchSubjects');
            const subjectsSelect = document.getElementById('subjects');
            const clearSearchSubjectsButton = document.getElementById('clearSearchSubjects');

            let allSubjects = @json($allSubjects);
            let selectedSubjects = @json($studyArea->subjects->pluck('id'));

            function updateOptions(selectElement, items, selectedItems) {
                selectElement.innerHTML = items.map(item => `
                    <option value="${item.id}" ${selectedItems.includes(item.id) ? 'selected' : ''}>
                        ${item.title}
                    </option>
                `).join('');
            }

            searchSubjectsInput.addEventListener('input', function() {
                const searchTerm = searchSubjectsInput.value.toLowerCase();
                let filteredSubjects = allSubjects.filter(subject => subject.title.toLowerCase().includes(searchTerm));
                updateOptions(subjectsSelect, filteredSubjects, selectedSubjects);
            });

            clearSearchSubjectsButton.addEventListener('click', function() {
                searchSubjectsInput.value = '';
                updateOptions(subjectsSelect, [], selectedSubjects);
            });

            updateOptions(subjectsSelect, [], selectedSubjects);
        });
    </script>
@endsection
