<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    @vite('resources/scss/app.scss')
</head>

<body class='body bg-secondary-subtle'>
    <header>
        <x-navbar.public-navbar :slug="$slug ?? ''"/>
    </header>
    <main>
        @yield('main-content')
    </main>
    <footer class="bg-dark text-light p-3 d-flex align-items-center justify-content-center">
        <div class="footer-inner container d-flex align-items-center justify-content-center">
            Todos os direitos reservados
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    @vite('resources/js/app.js')
</body>

</html>
