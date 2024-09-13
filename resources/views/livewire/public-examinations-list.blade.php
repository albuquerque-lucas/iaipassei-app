<div>
    <div class="d-flex flex-column align-items-center mb-5">
        <div class="pagination-links m-height-5-rem d-flex align-items-center mb-4">
            {{ $examinations->links('pagination::bootstrap-4') }}
        </div>
        <div class="text-muted">
            Exibindo {{ $examinations->firstItem() }} a {{ $examinations->lastItem() }} de {{ $examinations->total() }} resultados
        </div>
    </div>

    <div class="row mb-4 justify-content-center">
        <div class="col-md-12">
            <div class="input-group">
                <input type="text" class="form-control rounded-0" placeholder="Buscar..." wire:model.defer="tempSearch">
                <button class="btn btn-indigo-900-hover rounded-0 ms-2 w-15" type="button" wire:click="applySearch" wire:loading.attr="disabled">
                    <span wire:loading.remove>Pesquisar</span>
                    <span wire:loading>Pescquisando... <div class="spinner-border spinner-border-sm" role="status"></div></span>
                </button>
            </div>

        </div>
    </div>

    <div class="row">
        @foreach($examinations as $examination)
            <div class="col-sm-12 col-md-6 mb-4">
                <div class="card shadow-sm h-100 rounded-0">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('public.examinations.show', ['slug' => $examination->slug]) }}" class="text-dark link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-25-hover">
                                {{ $examination->title }}
                            </a>
                        </h5>
                        <p class="card-text">
                            <strong>Instituição:</strong> {{ $examination->institution }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
