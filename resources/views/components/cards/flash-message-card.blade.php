@props(['type', 'message'])

@php
    $alertClass = $type === 'success' ? 'flash-success' : 'flash-danger';
@endphp

<div class="alert {{ $alertClass }} alert-dismissible fade show text-start" role="alert">
    @if ($type === 'success')
        <i class="fa-regular fa-circle-check me-3"></i>
    @else
        <i class="fa-solid fa-exclamation me-3"></i>
    @endif
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
