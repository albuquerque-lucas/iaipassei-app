<div class="table-responsive dashboard-table-container">
    <table class="table table-hover entity-dashboard">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Concurso</th>
                <th>Nome do Arquivo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td>{{ $item->examination->title }}</td>
                    <td>{{ $item->file_name }}</td>
                    <td>
                        <a href="{{ route('notices.download', $item->id) }}" class="btn btn-sm btn-dark" target="_blank">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="{{ asset('storage/' . $item->file_path) }}" class="btn btn-sm btn-dark edit-btn" target="_blank">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-dark edit-btn" data-bs-toggle="modal" data-bs-target="#editNoticeModal{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-dark delete-button delete-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <x-popUps.edit-notice-popUp :id="$item->id" :updateRoute="'admin.notices.update'" :notice="$item" />
                <x-popUps.confirm-delete-popUp :id="$item->id" :deleteRoute="$deleteRoute" />
            @empty
                <tr>
                    <td colspan="4" class="text-center">Nenhum edital encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
