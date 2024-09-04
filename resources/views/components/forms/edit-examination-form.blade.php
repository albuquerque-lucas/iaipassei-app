<form method="POST" action="{{ route('admin.examinations.update', $examination->slug) }}" class="examination-edit-form">
    @csrf
    @method('PUT')

    <!-- Título -->
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control rounded-0" id="title" name="title" value="{{ $examination->title }}" required>
    </div>

    <!-- Instituição -->
    <div class="mb-3">
        <label for="institution" class="form-label">Instituição</label>
        <input type="text" class="form-control rounded-0" id="institution" name="institution" value="{{ $examination->institution }}" required>
    </div>

    <!-- Áreas de Estudo -->
    <div class="mb-3">
        <label for="study_areas" class="form-label">Áreas de Estudo</label>
        <select class="form-select rounded-0" id="study_areas" name="study_areas[]" multiple>
            @foreach($allStudyAreas as $area)
                <option value="{{ $area->id }}" {{ $examination->studyAreas->contains($area->id) ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Botão de salvar -->
    <div class="d-flex justify-content-start mb-3">
        <button type="submit" class="btn btn-dark w-25 rounded-0 shadow-sm">
            Salvar Alterações
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studyAreasSelect = document.getElementById('study_areas');

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

        updateOptions(allStudyAreas, selectedStudyAreas);
    });
</script>
