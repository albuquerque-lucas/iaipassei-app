@props(['examinationSlug'])

<div class="modal fade" id="addNoticeModal" tabindex="-1" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoticeModalLabel">Adicionar Edital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.notices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="examination_slug" value="{{ $examinationSlug }}">
                    <div class="mb-3">
                        <label for="file" class="form-label">Arquivo (PDF)</label>
                        <input type="file" class="form-control" id="file" name="file" accept="application/pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar Edital</button>
                </div>
            </form>
        </div>
    </div>
</div>
