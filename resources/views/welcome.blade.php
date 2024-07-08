<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iai, Passei? - Em Construção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            background-color: black;
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .fade-in {
            opacity: 0;
            transition: opacity 2s ease-in-out;
        }
        .fade-in.show {
            opacity: 1;
        }
        h1, h5 {
            font-weight: 300;
        }

        h1 {
            font-size: 5rem;
            display: inline;
        }
    </style>
</head>

<body x-data="{ showFirst: false, showSecond: false }" x-init="setTimeout(() => showFirst = true, 500); setTimeout(() => showSecond = true, 2000)">
    <div class="text-center">
        <div class="fade-in" :class="{ 'show': showFirst }">
            <h1>Iai, </h1>
            <h1 x-show="showSecond" x-transition:enter="transition-opacity ease-in-out duration-2000" x-transition:leave="transition-opacity ease-in-out duration-2000">passei?</h1>
        </div>
        <h5>Site em desenvolvimento</h5>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
