@extends('adminLayout')

@section('main-content')
    <section class='admin-examinations-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard de Concursos Públicos</h1>
            <a href="{{ route('admin.examinations.create') }}" class="btn btn-primary">Adicionar Concurso</a>
        </div>

        <!-- Botões de navegação no topo -->
        <div class="d-flex justify-content-center mb-4">
            {{ $examinations->links('pagination::bootstrap-4') }}
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Instituição</th>
                        <th>Nível Educacional</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($examinations as $examination)
                        <tr>
                            <td>{{ $examination->id }}</td>
                            <td>{{ $examination->title }}</td>
                            <td>{{ $examination->institution }}</td>
                            <td>{{ optional($examination->educationLevel)->name ?? 'Não informado' }}</td>
                            <td>
                                <a href="{{ route('admin.examinations.edit', $examination->id) }}" class="btn btn-sm btn-primary">Editar</a>
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

        <!-- Botões de navegação no fundo -->
        {{-- <div class="d-flex justify-content-center mt-4">
            {{ $examinations->links('pagination::bootstrap-4') }}
        </div> --}}
    </section>
@endsection
