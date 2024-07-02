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
