@extends('adminLayout')

@section('main-content')
    <section class='admin-notices-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard de Editais</h1>
            <a href="{{ route('admin.notices.create') }}" class="btn btn-primary">Adicionar Edital</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Concurso</th>
                        <th>Nome do Arquivo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notices as $notice)
                        <tr>
                            <td>{{ $notice->id }}</td>
                            <td>{{ $notice->examination->title }}</td>
                            <td>{{ $notice->file_name }}</td>
                            <td>
                                <a href="{{ route('admin.notices.edit', $notice->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('admin.notices.destroy', $notice->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum edital encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
