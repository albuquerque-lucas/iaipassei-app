@extends('adminLayout')

@section('main-content')
    <section class='admin-study-areas-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('admin.study_areas.index') }}" class="btn btn-secondary">Voltar</a>
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
        </div>
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
                    <x-dashboards.study-area-associated-subjects-table :studyArea="$studyArea" :filteredSubjects="$filteredSubjects" />
                </div>
            </div>
            <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                <x-forms.edit-study-area-form :studyArea="$studyArea" :allSubjects="$allSubjects" />
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

            const choices = new Choices(subjectsSelect, {
                removeItemButton: true,
                shouldSort: false,
                placeholder: true,
                placeholderValue: 'Pesquisar Matérias'
            });

            function updateOptions(items, selectedItems) {
                const choicesInstance = choices;
                choicesInstance.clearStore();
                items.forEach(item => {
                    choicesInstance.setChoices([{
                        value: item.id,
                        label: item.title,
                        selected: selectedItems.includes(item.id),
                        disabled: false
                    }], 'value', 'label', false);
                });
            }

            searchSubjectsInput.addEventListener('input', function() {
                const searchTerm = searchSubjectsInput.value.toLowerCase();
                let filteredSubjects = allSubjects.filter(subject => subject.title.toLowerCase().includes(searchTerm));
                updateOptions(filteredSubjects, selectedSubjects);
            });

            clearSearchSubjectsButton.addEventListener('click', function() {
                searchSubjectsInput.value = '';
                updateOptions(allSubjects, selectedSubjects);
            });

            updateOptions(allSubjects, selectedSubjects);
        });
    </script>
@endsection
