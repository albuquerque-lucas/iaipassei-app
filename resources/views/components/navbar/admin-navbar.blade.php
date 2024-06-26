<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Iai Passei | Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <x-navbar.nav-item link="#" name="Home" active="true" />
                <x-navbar.nav-item link="#" name="Perfil" />
                <x-navbar.nav-dropdown name="Gerenciar">
                    <x-navbar.nav-dropdown-item link="#" name="Usuários" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.examinations.index') }}" name="Concursos" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.notices.index') }}" name="Editais" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.study_areas.index') }}" name="Áreas" />
                    <x-navbar.nav-dropdown-item link="{{ route('admin.subjects.index') }}" name="Matérias" />
                </x-navbar.nav-dropdown>
            </ul>
        </div>
    </div>
</nav>
