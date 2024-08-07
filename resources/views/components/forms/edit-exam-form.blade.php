@props(['exam'])

<form method="POST" action="{{ route('admin.exams.update', $exam->slug) }}">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ $exam->title }}" >
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">Data</label>
        <input type="date" class="form-control" id="date" name="date" value="{{ $exam->date ? $exam->date->format('Y-m-d') : '' }}" >
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea class="form-control" id="description" name="description" rows="3" >{{ $exam->description }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
</form>
