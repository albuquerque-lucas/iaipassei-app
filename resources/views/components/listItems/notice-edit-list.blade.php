@props(['notices'])

<ul class="list-group">
    @foreach($notices as $notice)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $notice->file_name }}</span>
            <div>
                <a href="{{ route('notices.download', $notice->id) }}" class="btn btn-dark btn-sm edit-btn rounded-0">
                    <i class="fa-solid fa-download"></i>
                </a>
                <button type="button" class="btn btn-dark edit-btn btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#editNoticeModal{{ $notice->id }}">
                    <i class="fa-solid fa-edit"></i>
                </button>
                <button type="button" class="btn btn-dark delete-btn btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $notice->id }}">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </li>
        <x-popUps.edit-notice-popUp :id="$notice->id" updateRoute="admin.notices.update" :notice="$notice" />
        <x-popUps.confirm-delete-popUp :id="$notice->id" deleteRoute="admin.notices.destroy" />
    @endforeach
</ul>
