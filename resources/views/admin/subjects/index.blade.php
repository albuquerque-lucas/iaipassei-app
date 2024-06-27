@extends('adminLayout')

@section('main-content')
    <section class='admin-subjects-page container mt-5'>
        <div class="d-flex justify-content-between align-items-center mb-4'>
            <h3>Dashboard de Matérias</h3>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">Adicionar Matéria</a>
        </div>

        <!-- Botões de navegação no topo -->
        <div class="d-flex justify-content-center mb-4">
            {{ $subjects->links('pagination::bootstrap-4') }}
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
                            <td>{{ optional($subject->educationLevel)->name ?? 'Não informado' }}</td>
                            <td>
                                <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
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
                            <td colspan="4" class="text-center">Nenhuma matéria encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Botões de navegação no fundo -->
        {{-- <div class="d-flex justify-content-center mt-4">
            {{ $subjects->links('pagination::bootstrap-4') }}
        </div> --}}
    </section>
@endsection
