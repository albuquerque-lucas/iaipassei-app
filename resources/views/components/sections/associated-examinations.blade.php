<div class="mt-4">
    <h4 class="ps-1 ms-3 mb-3">Concursos Associados</h4>
    <div class="list-group">
        @forelse($examinations as $examination)
            <div class="list-group-item d-flex justify-content-between align-items-center rounded-0">
                <div>
                    <h5>{{ $examination->title }}</h5>
                    <p><strong>Instituição:</strong> {{ $examination->institution }}</p>
                    <p><strong>Nível Educacional:</strong> {{ $examination->educationLevel->name }}</p>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <a href="{{ route('public.examinations.show', $examination->slug) }}" class="btn btn-primary btn-sm mb-2 rounded-0 w-8-rem">
                        Visualizar
                        <i class="fa-solid fa-book ms-2"></i>
                    </a>
                    <form action="{{ route('examinations.unsubscribe', $examination->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-dark delete-btn btn-sm rounded-0 w-8-rem">
                            Cancelar
                            <i class="fa-solid fa-ban ms-2"></i>
                        </button>
                    </form>
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
