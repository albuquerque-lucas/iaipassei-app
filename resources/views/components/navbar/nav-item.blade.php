@props(['link', 'name', 'active' => false])

<li class="nav-item">
    <a class="nav-link {{ $active ? 'active' : '' }}" href="{{ $link }}">{{ $name }}</a>
</li>
