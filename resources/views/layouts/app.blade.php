<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jogja Artsphere')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    @include('components.back-button')
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width:45px; height:45px;">
            </div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <div class="header-right">
            @php
                $seniman = Auth::guard('seniman')->user();
                $fotoPath = $seniman && $seniman->foto
                    ? asset('storage/foto_seniman/' . $seniman->foto)
                    : asset('assets/defaultprofile.png');
            @endphp
            <a href="{{ route('seniman.profil') }}" title="Profil">
                <img src="{{ $fotoPath }}" class="profile-icon" style="width:40px; height:40px; border-radius:50%;">
            </a>
        </div>
    </header>

    <main style="min-height:70vh;">
        @yield('content')
    </main>

    <footer>
        <p style="text-align:center;">© {{ date('Y') }} Jogja Artsphere</p>
    </footer>
</body>
</html>
