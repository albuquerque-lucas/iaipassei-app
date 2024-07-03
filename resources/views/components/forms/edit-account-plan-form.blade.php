@props(['accountPlan'])

<form action="{{ route('admin.account_plans.update', $accountPlan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $accountPlan->name }}" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ $accountPlan->description }}</textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Preço</label>
        <input type="number" class="form-control" id="price" name="price" value="{{ $accountPlan->price }}">
    </div>
    <div class="mb-3">
        <label for="duration_days" class="form-label">Duração (dias)</label>
        <input type="number" class="form-control" id="duration_days" name="duration_days" value="{{ $accountPlan->duration_days }}">
    </div>
    <div class="mb-3">
        <label for="access_level" class="form-label">Nível de Acesso</label>
        <input type="text" class="form-control" id="access_level" name="access_level" value="{{ $accountPlan->access_level }}" required>
    </div>
    <div class="mb-3">
        <label for="is_public" class="form-label">É Público?</label>
        <select class="form-control" id="is_public" name="is_public" required>
            <option value="1" {{ $accountPlan->is_public ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ !$accountPlan->is_public ? 'selected' : '' }}>Não</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
</form>
