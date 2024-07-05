@props(['id', 'updateRoute', 'notice'])

<div class="modal fade" id="editNoticeModal{{ $id }}" tabindex="-1" aria-labelledby="editNoticeModalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title" id="editNoticeModalLabel{{ $id }}">Editar Edital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($updateRoute, $id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="examination_id_{{ $id }}" class="form-label">Concurso</label>
                        <select class="form-select" id="examination_id_{{ $id }}" name="examination_id">
                            <option value="{{ $notice->examination->id }}" selected>{{ $notice->examination->title }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file_{{ $id }}" class="form-label">Arquivo (PDF)</label>
                        <input type="file" class="form-control" id="file_{{ $id }}" name="file" accept="application/pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
