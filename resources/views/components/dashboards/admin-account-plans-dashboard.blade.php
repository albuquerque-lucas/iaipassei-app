<div class="table-responsive dashboard-table-container">
    <table class="table table-hover entity-dashboard">
        <thead class="table-dark">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Duração (Dias)</th>
                <th>Nível de Acesso</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->duration_days }}</td>
                    <td>{{ $item->access_level ?? 'Não informado' }}</td>
                    <td>
                        <a href="{{ route($editRoute, $item->id) }}" class="btn btn-sm btn-dark edit-btn">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-dark delete-button delete-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <x-popUps.confirm-delete-popUp :id="$item->id" :deleteRoute="$deleteRoute" />
            @empty
                <tr>
                    <td colspan="8" class="text-center">Nenhum plano encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
