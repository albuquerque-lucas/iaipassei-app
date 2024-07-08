<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid container">
        <a class="navbar-brand" href="{{ route('admin.examinations.index') }}">Iai Passei | Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto">
                <x-navbar.nav-item link="{{ route('admin.examinations.index') }}" name="Home" active="true" />
                <x-navbar.nav-dropdown name="Gerenciar">
                    <x-navbar.nav-dropdown-item link="{{ route('admin.users.index') }}" name="Usuários" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.examinations.index') }}" name="Concursos" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.notices.index') }}" name="Editais" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.subjects.index') }}" name="Matérias" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.study_areas.index') }}" name="Áreas" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.account_plans.index') }}" name="Planos" />
                </x-navbar.nav-dropdown>
            </ul>
            @auth
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.profile.index', Auth::user()->slug) }}">Perfil</a></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>
