@props(['name'])

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $name }}
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        {{ $slot }}
    </ul>
</li>
