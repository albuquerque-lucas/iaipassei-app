<div class="table-responsive dashboard-table-container">
    <table class="table table-hover shadow">
        <thead class="table-dark">
            <tr>
                <th style="width: 5%;"><input type="checkbox" id="selectAll"></th>
                <th style="width: 5%;">ID</th>
                <th style="width: 35%;">Título</th>
                <th style="width: 25%;">Instituição</th>
                <th style="width: 15%;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td class="text-wrap">{{ $item->title }}</td>
                    <td>{{ $item->institution }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a
                            href="{{ route($editRoute, $item->slug) }}"
                            class="btn btn-sm btn-dark edit-btn rounded-0"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Editar Concurso"
                            >
                                <i class="fas fa-edit"></i>
                            </a>
                            <button
                            type="button"
                            class="btn btn-sm btn-dark delete-btn rounded-0"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDeleteModal{{ $item->slug }}"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Excluir Concurso"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <x-popUps.confirm-delete-popUp :slug="$item->slug" :deleteRoute="$deleteRoute" />
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum concurso encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
