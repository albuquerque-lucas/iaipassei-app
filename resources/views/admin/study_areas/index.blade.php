@extends('adminLayout')

@section('main-content')
    <section class='admin-study-areas-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard de Áreas de Estudo</h1>
            <a href="{{ route('admin.study_areas.create') }}" class="btn btn-primary">Adicionar Área de Estudo</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        {{-- <th>Quantidade de Matérias</th> --}}
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($studyAreas as $studyArea)
                        <tr>
                            <td>{{ $studyArea->id }}</td>
                            <td>{{ $studyArea->name }}</td>
                            {{-- <td>{{ $studyArea->subjects_count }}</td> --}}
                            <td>
                                <a href="{{ route('admin.study_areas.edit', $studyArea->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('admin.study_areas.destroy', $studyArea->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Nenhuma área de estudo encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $studyAreas->links() }}
        </div>
    </section>
@endsection
