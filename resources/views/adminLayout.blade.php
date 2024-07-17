<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | IaiPassei</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    @vite('resources/scss/app.scss')
    @vite('resources/js/app.js')
</head>

<body class='body bg-secondary-subtle'>
    <header>
        @auth
            @if(Auth::user()->accountPlan->access_level > 7)
                <x-navbar.admin-navbar />
            @endif
        @endauth
    </header>
    <main>
        @yield('main-content')
    </main>
    <footer>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    @vite('resources/js/app.js')
</body>

</html>
