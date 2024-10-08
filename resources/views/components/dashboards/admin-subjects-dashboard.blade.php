<div class="table-responsive dashboard-table-container">
    <table class="table table-hover entity-dashboard">
        <thead class="table-dark">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Nome</th>
                <th class="text-end pe-3">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td>{{ $item->title }}</td>
                    <td class="text-end pe-3">
                        <a
                        href="{{ route($editRoute, $item->id) }}"
                        class="btn btn-sm btn-dark edit-btn rounded-0"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Editar"
                        >
                            <i class="fas fa-edit"></i>
                        </a>
                        <button
                        type="button"
                        class="btn btn-sm btn-dark delete-btn rounded-0"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmDeleteModal{{ $item->id }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Excluir"
                        >
                            <i class="fas fa-trash"></i>
                        </button>

                        <x-popUps.confirm-delete-popUp :id="$item->id" :deleteRoute="$deleteRoute" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Nenhuma área de estudo encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
