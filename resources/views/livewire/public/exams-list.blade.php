<div>
    <div class="d-flex justify-content-center my-5">
        {{ $exams->links('pagination::bootstrap-4') }}
    </div>

    <div class="mb-4 d-flex align-items-center">
        <div class="d-flex align-items-centerbg-danger w-50 me-3">
            <input type="text" class="form-control rounded-0" placeholder="Buscar rankings..." wire:model.defer="tempSearch">
            <button class="w-35 btn btn-indigo-800-hover ms-2 rounded-0" type="button" wire:click="applySearch" wire:loading.attr="disabled" wire:target="applySearch">
                <span wire:loading.remove wire:target="applySearch">Pesquisar</span>
                <span wire:loading wire:target="applySearch">Pesquisando... <div class="spinner-border spinner-border-sm" role="status"></div></span>
            </button>
        </div>

        <div class="d-flex align-items-center justify-content-end w-50">
            <select class="form-select rounded-0 w-50" wire:model.defer="tempFilterStatus">
                <option value="all">Todos</option>
                <option value="enrolled">Inscrito</option>
                <option value="not_enrolled">Não Inscrito</option>
            </select>
            <button class="w-35 btn btn-indigo-800-hover ms-2 rounded-0" type="button" wire:click="applyFilter" wire:loading.attr="disabled" wire:target="applyFilter">
                <span wire:loading.remove wire:target="applyFilter">Filtrar</span>
                <span wire:loading wire:target="applyFilter">Filtrando... <div class="spinner-border spinner-border-sm" role="status"></div></span>
            </button>
        </div>
    </div>

    <div style="max-height: 80vh; overflow-y: scroll;" class="bg-light p-3">
        <ul class="list-group">
            @foreach($exams as $exam)
                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between my-2 p-3 shadow border border-secondary-subtle rounded-0">
                    <div class="w-100 w-md-75 mb-2 mb-md-0">
                        <p class="fw-lighter fs-5 m-0 exam-title-p">{{ $exam->title }}</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-md-end w-100 w-md-25">
                        @can('canAccessExam', $exam)
                            <a href="{{ route('public.exams.results', $exam->slug) }}" class="btn btn-indigo-500 edit-btn btn-sm w-8-rem rounded-0 me-1">
                                <i class="fa-solid fa-book me-2"></i>
                                Ranking
                            </a>
                            <form id="unsubscribeExamForm-{{ $exam->id }}" action="{{ route('public.exams.unsubscribe', $exam->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-indigo-800-hover delete-btn btn-sm delete-exam-btn w-md-auto rounded-0" data-exam-id="{{ $exam->id }}">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('public.exams.subscribe', $exam->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-indigo-800-hover edit-btn btn-sm my-1 w-8-rem w-md-auto rounded-0">
                                    <i class="fa-solid fa-plus-circle me-2"></i>
                                    Participar
                                </button>
                            </form>
                        @endcan
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $exams->links('pagination::bootstrap-4') }}
    </div>
</div>
