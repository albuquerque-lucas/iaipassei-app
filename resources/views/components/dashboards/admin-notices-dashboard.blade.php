<div class="table-responsive dashboard-table-container">
    <table class="table table-hover entity-dashboard">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Concurso</th>
                <th class="text-center">Nome do Arquivo</th>
                <th class="text-end pe-4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td>{{ $item->examination->title }}</td>
                    <td class="text-center">{{ $item->file_name }}</td>
                    <td class="text-end pe-4">
                        <a
                        href="{{ route('notices.download', $item->id) }}"
                        class="btn btn-sm btn-dark rounded-0"
                        target="_blank"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Download"
                        >
                            <i class="fas fa-download"></i>
                        </a>
                        <a
                        href="{{ asset('storage/' . $item->file_path) }}"
                        class="btn btn-sm btn-dark edit-btn rounded-0"
                        target="_blank"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Visualizar Edital"
                        >
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <button
                        type="button"
                        class="btn btn-sm btn-dark edit-btn rounded-0"
                        data-bs-toggle="modal"
                        data-bs-target="#editNoticeModal{{ $item->id }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Alterar Edital"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        <button
                        type="button"
                        class="btn btn-sm btn-dark delete-btn rounded-0"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmDeleteModal{{ $item->id }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Excluir Edital"
                        >
                            <i class="fas fa-trash"></i>
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
