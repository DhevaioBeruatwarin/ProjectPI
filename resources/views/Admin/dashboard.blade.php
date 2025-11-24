<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    @include('components.back-button')
    <!-- Header -->
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" 
                     alt="Jogja Artsphere Logo" 
                     style="width: 45px; height: 45px; object-fit: contain;">
            </div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>

        <div class="header-right">
            @if(Auth::guard('admin')->check())
                @php
                    $admin = Auth::guard('admin')->user();
                    $fotoPath = $admin->foto 
                        ? asset('storage/foto_admin/' . $admin->foto)
                        : asset('assets/defaultprofile.png');
                @endphp

                <a href="{{ route('admin.profil') }}" title="Profil Admin">
                    <img src="{{ $fotoPath }}" 
                         alt="Foto Profil Admin"
                         class="profile-icon"
                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                </a>
            @endif
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">Selamat datang, {{ $admin->nama ?? 'Admin' }}</h1>
            <p class="hero-subtitle">Kelola seniman, karya seni, dan pengguna di Jogja Artsphere.</p>
        </div>
        <div class="hero-image"></div>
    </div>

    <!-- Section Admin Tools -->
    <div class="section-title">
        <span>Menu Manajemen Admin</span>
    </div>

    <div class="product-section">
        <div class="product-grid">

            <a href="{{ route('admin.seniman.index') }}" class="product-card" style="text-decoration:none; color:inherit;">
                <div class="product-image" style="background:#f6d365; height:200px; display:flex; align-items:center; justify-content:center; border-radius:10px;">
                    <strong>Kelola Seniman</strong>
                </div>
            </a>

            <a href="{{ route('admin.karya.index') }}" class="product-card" style="text-decoration:none; color:inherit;">
                <div class="product-image" style="background:#fda085; height:200px; display:flex; align-items:center; justify-content:center; border-radius:10px;">
                    <strong>Kelola Karya Seni</strong>
                </div>
            </a>

            <a href="{{ route('admin.pembeli.index') }}" class="product-card" style="text-decoration:none; color:inherit;">
                <div class="product-image" style="background:#84fab0; height:200px; display:flex; align-items:center; justify-content:center; border-radius:10px;">
                    <strong>Kelola Pembeli</strong>
                </div>
            </a>

        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Jogja Artsphere</h3>
                <p>Jl. Malioboro No. 123</p>
                <p>Yogyakarta 55271</p>
                <p>Telp: (0274) 123-4567</p>
                <p>Email: info@jogja-artsphere.com</p>
            </div>

            <div class="footer-section">
                <h3>Informasi</h3>
                <div class="footer-links">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Pusat Bantuan</a>
                    <a href="#">Kebijakan Privasi</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p><strong>Jogja Artsphere</strong> - Rumah bagi kreativitas lokal Yogyakarta.</p>
        </div>
    </footer>
</body>
</html>
