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
                    <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td>{{ $item->name }}</td>
                    <td>
                        <a href="{{ route($editRoute, $item->id) }}" class="btn btn-sm btn-dark edit-btn">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-dark delete-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>

                        <!-- Confirm Delete PopUp Component -->
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
