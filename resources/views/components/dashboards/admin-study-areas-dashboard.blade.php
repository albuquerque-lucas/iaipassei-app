<!-- resources/views/components/dashboards/admin-study-areas-dashboard.blade.php -->

<div class="table-responsive dashboard-table-container">
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><strong>{{ $item->id }}</strong></td>
                    <td>{{ $item->name }}</td>
                    <td>
                        <a href="{{ route($editRoute, $item->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route($deleteRoute, $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhuma área de estudo encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Botões de navegação no fundo -->
{{-- <div class="d-flex justify-content-center mt-4">
    {!! $paginationLinks !!}
</div> --}}
