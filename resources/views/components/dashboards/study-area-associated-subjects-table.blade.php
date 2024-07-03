<div class="mb-3">
    <form method="GET" action="{{ route('admin.study_areas.edit', $studyArea->id) }}">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar por Nome" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="order_by" class="form-control">
                    <option value="id" {{ request('order_by') == 'id' ? 'selected' : '' }}>Ordenar por ID</option>
                    <option value="title" {{ request('order_by') == 'title' ? 'selected' : '' }}>Ordenar por Nome</option>
                </select>
            </div>
            <div class="col-md-4">
                <select name="order" class="form-control">
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ordem Crescente</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Ordem Decrescente</option>
                </select>
            </div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>
</div>

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
            @forelse ($filteredSubjects as $subject)
                <tr>
                    <td>{{ $subject->id }}</td>
                    <td>{{ $subject->title }}</td>
                    <td>
                        <button type="button" class="btn btn-sm bg-dark text-white" data-bs-toggle="modal" data-bs-target="#confirmDeleteSubjectModal{{ $subject->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
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
