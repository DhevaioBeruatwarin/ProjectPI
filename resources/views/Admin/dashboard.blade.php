<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Jogja Artsphere</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
    /* Minimal admin-only tweaks to harmonize with user dashboard */
    .header-admin {
        width: 100%;
        background: #231105;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 48px;
        height: 72px;
        box-sizing: border-box;
        box-shadow: 0 2px 16px 0 rgba(35,17,5,0.08);
    }
    .header-admin .header-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .header-admin .logo {
        width: 46px;
        height: 46px;
        background: #fff;
        border-radius: 50%;
        border: 2px solid #dab977;
        display: flex;
    }
    .header-admin .brand {
        font-weight: 900;
        font-size: 16px;
        letter-spacing: 2px;
        color: #fff;
        text-transform: uppercase;
        margin-left: 10px;
        font-family: 'Montserrat', 'Arial', sans-serif;
    }
    .header-admin .header-right img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #dab977;
        object-fit: cover;
        margin-left: 20px;
        box-shadow: 0 1px 6px rgba(200, 142, 33, 0.08);
    }
    .hero-banner {
        background: linear-gradient(120deg,#402314 55%,#231105 100%);
        padding: 62px 0 48px;
    }
    .hero-banner .hero-content {
        max-width: 650px;
        margin: 0 auto;
        color: #fff;
    }
    .hero-banner .hero-title {
        font-size: 38px;
        font-family: 'Montserrat', Arial Black, Arial, sans-serif;
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -2.2px;
        text-transform: uppercase;
    }
    .hero-banner .hero-subtitle {
        font-size: 18px;
        font-family: 'Montserrat', sans-serif;
        margin-top: 14px;
        color: rgba(255,255,255,0.85);
        font-weight: 500;
    }
    .section-title {
        margin: 48px auto 22px auto;
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        font-size: 20px;
        color: #231105;
        text-align: center;
        letter-spacing: 1.3px;
        text-transform: uppercase;
    }
    .product-section {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }
    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(240px, 1fr));
        gap: 30px;
        width: 100%;
        max-width: 900px;
        margin-bottom: 40px;
    }
    .product-card {
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 2px 14px rgba(35, 17, 5, 0.06), 0 1.5px 7px rgba(218, 165, 32, 0.04);
        border: 1.5px solid #faf4ec;
        transition: box-shadow 0.21s, transform 0.19s, border-color 0.18s;
        text-align: center;
        min-height: 120px;
    }
    .product-card:hover {
        transform: translateY(-8px) scale(1.025);
        box-shadow: 0 12px 32px #dab97736, 0 1.5px 8px #dab9771d;
        border-color: #dab977;
    }
    .product-image {
        height: 160px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 13px !important;
        margin: 18px 8px !important;
        font-size: 18px !important;
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 900 !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
    }
    /* Footer harmonize */
    footer {
        background: #231105;
        color: #fff;
        padding: 65px 0 24px;
        margin-top: 80px;
    }
    .footer-content {
        display: flex;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
        gap: 44px;
        flex-wrap: wrap;
        padding-bottom: 28px;
    }
    .footer-section h3 {
        color: #dab977;
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        font-size: 17px;
        text-transform: uppercase;
        margin-bottom: 9px;
        letter-spacing: 1.2px;
    }
    .footer-section p, .footer-section a {
        font-size: 14px;
        color: rgba(255,255,255,0.88);
        font-family: 'Montserrat', sans-serif;
        font-weight: 500;
        line-height: 1.7;
        text-decoration: none;
    }
    .footer-section a:hover { color: #dab977; }
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,0.10);
        padding-top: 20px;
        text-align: center;
        font-size: 13px;
        color: rgba(255,255,255,0.58);
        font-family: 'Montserrat', sans-serif;
        font-weight: 500;
        letter-spacing: 1.1px;
    }
    @media (max-width:1024px) {
        .header-admin { padding: 0 20px; }
        .product-grid { grid-template-columns: 1fr; gap: 22px; }
        .footer-content { flex-direction: column; gap: 16px; }
    }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-admin">
        <div class="header-left">
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" 
                     alt="Jogja Artsphere Logo" 
                     style="width: 42px; height: 42px; object-fit: contain; border-radius: 50%;">
            </div>
            <span class="brand">JOGJA ARTSPHERE</span>
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
                         class="profile-icon">
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
                <div class="product-image" style="background:#f6d365;">
                    <strong>Kelola Seniman</strong>
                </div>
            </a>

            <a href="{{ route('admin.karya.index') }}" class="product-card" style="text-decoration:none; color:inherit;">
                <div class="product-image" style="background:#fda085;">
                    <strong>Kelola Karya Seni</strong>
                </div>
            </a>

            <a href="{{ route('admin.pembeli.index') }}" class="product-card" style="text-decoration:none; color:inherit;">
                <div class="product-image" style="background:#84fab0;">
                    <strong>Kelola Pembeli</strong>
                </div>
            </a>

            <a href="{{ route('admin.monitoring.sistem') }}" class="product-card" style="text-decoration:none; color:inherit;">
                <div class="product-image" style="background:#60a5fa;">
                    <strong>Monitoring Sistem</strong>
                </div>
            </a>

            <a href="{{ route('admin.monitoring.keuangan') }}" class="product-card" style="text-decoration:none; color:inherit;">
                <div class="product-image" style="background:#a78bfa;">
                    <strong>Monitoring Keuangan</strong>
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