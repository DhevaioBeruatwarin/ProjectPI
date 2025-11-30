<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jogja Artsphere')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

    <main style="min-height:70vh;">
        @yield('content')
    </main>

    <footer>
        <p style="text-align:center;">© {{ date('Y') }} Jogja Artsphere</p>
    </footer>
</body>
</html>
