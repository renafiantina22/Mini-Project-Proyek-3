<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Login') | Karhutla</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="guest-body">

    <main class="guest-content">
        @yield('content')
    </main>

</body>
</html>
