@props(['link', 'name'])

<li class="nav-item navbar-item mx-1">
    <a class="nav-link {{ request()->url() == $link ? 'active' : '' }}" href="{{ $link }}">{{ $name }}</a>
</li>
