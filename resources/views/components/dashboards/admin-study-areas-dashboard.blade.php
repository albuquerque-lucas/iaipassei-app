<div class="table-responsive dashboard-table-container">
    <table class="table table-hover entity-dashboard">
        <thead class="table-dark">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><input type="checkbox" class="select-item" value="{{ $item->slug }}"></td>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td>{{ $item->name }}</td>
                    <td>
                        <a
                        href="{{ route($editRoute, $item->slug) }}"
                        class="btn btn-sm btn-dark edit-btn"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Editar"
                        >
                            <i class="fas fa-edit"></i>
                        </a>
                        <button
                        type="button"
                        class="btn btn-sm btn-dark delete-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmDeleteModal{{ $item->slug }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Excluir"
                        >
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Confirm Delete PopUp Component -->
                        <x-popUps.confirm-delete-popUp :slug="$item->slug" :deleteRoute="$deleteRoute" />
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
