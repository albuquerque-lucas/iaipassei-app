<div class="mt-4">
    <form action="{{ route('admin.study_areas.update', $studyArea->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nome da Área de Estudo</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $studyArea->name }}">
        </div>
        <div class="mb-3" id="searchSubjectsWrapper">
            <input type="text" id="searchSubjects" class="form-control mb-2" placeholder="Pesquisar Matérias">
            <button type="button" class="btn btn-secondary mb-2" id="clearSearchSubjects">Limpar Pesquisa</button>
        </div>
        <div class="mb-3">
            <label for="subjects" class="form-label">Matérias Associadas</label>
            <select id="subjects" name="subjects[]" class="form-control" multiple="multiple">
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>
