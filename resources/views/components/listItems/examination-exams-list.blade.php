@props(['exams'])

<div class="table-responsive scrollable-list p-4 shadow border border-dark-subtle border-5 rounded-0">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Título</th>
                <th scope="col" class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exams as $exam)
            <tr>
                <td>{{ $exam->id }}</td>
                <td>{{ $exam->title }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.exams.edit', $exam->slug) }}" class="btn btn-dark edit-btn btn-sm me-2 rounded-0">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('admin.exams.destroy', $exam->slug) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-dark delete-btn btn-sm rounded-0">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
