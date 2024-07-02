<div class="table-responsive dashboard-table-container">
    <table class="table table-hover entity-dashboard">
        <thead class="table-dark">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Plano de Conta</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ optional($item->accountPlan)->name ?? 'Não informado' }}</td>
                    <td>
                        <a href="{{ route($editRoute, $item->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger delete-button" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                @include('components.popUps.confirm-delete-popUp', ['id' => $item->id, 'deleteRoute' => $deleteRoute])
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
