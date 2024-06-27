<div class="table-responsive">
    <table class="table table-hover">
        <thead class='admin-dashboard-head table-dark'>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column['label'] }}</th>
                @endforeach
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    @foreach ($columns as $column)
                        <td>
                            @if ($column['field'] === 'id')
                                <strong>{{ $item->{$column['field']} ?? 'Não informado' }}</strong>
                            @else
                                {{ $item->{$column['field']} ?? 'Não informado' }}
                            @endif
                        </td>
                    @endforeach
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
                    <td colspan="{{ count($columns) + 1 }}" class="text-center">Nenhum registro encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
