<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | IaiPassei</title>
    @vite('resources/scss/app.scss')
    @vite('resources/js/app.js')
</head>

<body class='body'>
    <header>
        <x-navbar.admin-navbar />
    </header>
    <main>
        @yield('main-content')
    </main>
    <footer>

    </footer>
    @vite('resources/js/app.js')
</body>

</html>
