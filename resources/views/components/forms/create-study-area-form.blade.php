<div class="mt-4">
    <h4>Adicionar Área de Estudo</h4>
    <form action="{{ route('admin.study_areas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome da Área de Estudo</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
</div>
