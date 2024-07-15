<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid container">
        <a class="navbar-brand" href="#">Iai Passei</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto">
                <x-navbar.nav-item link="#" name="Início" active="true" />
                <x-navbar.nav-item link="#" name="Sobre" />
                <x-navbar.nav-item link="#" name="Contato" />
                <x-navbar.nav-item link="#" name="Serviços" />
            </ul>
            @auth
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li>
                                <form method="POST" action="#">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.login.index') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>
