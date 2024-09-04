<form method="POST" action="{{ route('admin.examinations.update', $examination->slug) }}" class="examination-edit-form">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="education_level_id" class="form-label">Nível Educacional</label>
        <select class="form-select rounded-0" id="education_level_id" name="education_level_id" required>
            @foreach($educationLevels as $level)
                <option value="{{ $level->id }}" {{ $examination->education_level_id == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control rounded-0" id="title" name="title" value="{{ $examination->title }}" required>
    </div>
    <div class="mb-3">
        <label for="institution" class="form-label">Instituição</label>
        <input type="text" class="form-control rounded-0" id="institution" name="institution" value="{{ $examination->institution }}" required>
    </div>
    <div class="mb-3">
        <label for="study_areas" class="form-label">Áreas de Estudo</label>
        <select class="form-select rounded-0" id="study_areas" name="study_areas[]" multiple>
            @foreach($allStudyAreas as $area)
                <option value="{{ $area->id }}" {{ $examination->studyAreas->contains($area->id) ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
            @endforeach
        </select>
        {{-- <input type="text" id="searchStudyAreas" placeholder="Pesquisar Áreas de Estudo" class="mt-2 form-control">
        <button type="button" id="clearSearchStudyAreas" class="btn btn-secondary mt-2">Limpar Pesquisa</button> --}}
    </div>
    <button type="submit" class="btn btn-dark w-25 rounded-0 shadow-sm">
        Salvar Alterações
    </button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchStudyAreasInput = document.getElementById('searchStudyAreas');
        const studyAreasSelect = document.getElementById('study_areas');
        const clearSearchStudyAreasButton = document.getElementById('clearSearchStudyAreas');

        let allStudyAreas = @json($allStudyAreas);
        let selectedStudyAreas = @json($examination->studyAreas->pluck('id'));

        const choices = new Choices(studyAreasSelect, {
            removeItemButton: true,
            shouldSort: false,
            placeholder: true,
            placeholderValue: 'Pesquisar Áreas de Estudo'
        });

        function updateOptions(items, selectedItems) {
            const choicesInstance = choices;
            choicesInstance.clearStore();
            items.forEach(item => {
                choicesInstance.setChoices([{
                    value: item.id,
                    label: item.name,
                    selected: selectedItems.includes(item.id),
                    disabled: false
                }], 'value', 'label', false);
            });
        }

        searchStudyAreasInput.addEventListener('input', function() {
            const searchTerm = searchStudyAreasInput.value.toLowerCase();
            let filteredStudyAreas = allStudyAreas.filter(area => area.name.toLowerCase().includes(searchTerm));
            updateOptions(filteredStudyAreas, selectedStudyAreas);
        });

        clearSearchStudyAreasButton.addEventListener('click', function() {
            searchStudyAreasInput.value = '';
            updateOptions(allStudyAreas, selectedStudyAreas);
        });

        updateOptions(allStudyAreas, selectedStudyAreas);
    });
</script>
