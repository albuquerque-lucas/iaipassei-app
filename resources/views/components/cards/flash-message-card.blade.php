@props(['type', 'message'])

@php
    $alertClass = $type === 'success' ? 'alert-success' : 'alert-danger';
@endphp

<div class="alert {{ $alertClass }} alert-dismissible fade show mt-4" role="alert">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
