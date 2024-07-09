@props(['exams'])

<ul class="list-group">
    @foreach($exams as $exam)
        <li class="list-group-item d-flex justify-content-between align-items-center w-50 my-1">
            <span>{{ $exam->title }}</span>
            <div>
                <a href="{{ route('admin.exams.edit', $exam->slug) }}" class="btn btn-dark edit-btn btn-sm me-2">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <form action="{{ route('admin.exams.destroy', $exam->slug) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-dark delete-btn btn-sm">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </li>
    @endforeach
</ul>
