<div class="mt-4">
    <h4 class="ps-1 ms-3 mb-3">Concursos Associados</h4>
    <div class="list-group">
        @forelse($examinations as $examination)
            <div class="list-group-item d-flex justify-content-between align-items-center rounded-0 shadow">
                <div>
                    <h5>{{ $examination->title }}</h5>
                    <p><strong>Instituição:</strong> {{ $examination->institution }}</p>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <form action="{{ route('examinations.unsubscribe', $examination->id) }}" method="POST" class="position-absolute top-0 end-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-dark delete-btn btn-sm rounded-0">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                    <a href="{{ route('public.examinations.show', $examination->slug) }}" class="btn btn-dark edit-btn btn-sm mb-2 rounded-0 w-8-rem mt-5">
                        Ranking
                        <i class="fa-solid fa-book ms-2"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="list-group-item text-center">
                <p class="text-muted mb-0">Você ainda não está inscrito em nenhum concurso.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $examinations->links() }}
    </div>
</div>
