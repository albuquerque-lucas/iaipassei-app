@extends('adminLayout')

@section('main-content')
    <section class='admin-examinations-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard de Concursos Públicos</h1>
            <a href="{{ route('admin.examinations.create') }}" class="btn btn-primary">Adicionar Concurso</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Nível Educacional</th>
                        <th>Instituição</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($examinations as $examination)
                        <tr>
                            <td>{{ $examination->id }}</td>
                            <td>{{ $examination->title }}</td>
                            <td>{{ $examination->educationLevel->name }}</td>
                            <td>{{ $examination->institution }}</td>
                            <td>{{ $examination->active ? 'Sim' : 'Não' }}</td>
                            <td>
                                <a href="{{ route('admin.examinations.edit', $examination->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('admin.examinations.destroy', $examination->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum concurso encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
