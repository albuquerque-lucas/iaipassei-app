@extends('adminLayout')

@section('main-content')
    <section class='admin-subjects-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard de Matérias</h1>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">Adicionar Matéria</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        {{-- <th>Área de Estudo</th> --}}
                        <th>Nível Educacional</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjects as $subject)
                        <tr>
                            <td>{{ $subject->id }}</td>
                            <td>{{ $subject->title }}</td>
                            {{-- <td>{{ $subject->studyArea->name }}</td> --}}
                            <td>{{ $subject->educationLevel->name ?? 'Não informado' }}</td>
                            <td>
                                <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhuma matéria encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $subjects->links() }}
        </div>
    </section>
@endsection
