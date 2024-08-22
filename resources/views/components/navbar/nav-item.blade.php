@props(['link', 'name'])

<li class="nav-item">
    <a class="nav-link {{ request()->url() == $link ? 'active' : '' }}" href="{{ $link }}">{{ $name }}</a>
</li>
