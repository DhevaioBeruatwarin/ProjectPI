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
        body {
            margin: 0;
            background: #f9fafb; /* PUTIH ABU TERANG */
        }

        /* ================= HEADER ================= */
        .header-admin {
            width: 100%;
            background: linear-gradient(120deg,#402314,#231105);
            border-bottom: 1px solid rgba(255,255,255,0.12);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 48px;
            height: 72px;
            box-sizing: border-box;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            width: 46px;
            height: 46px;
            background: #fff;
            border-radius: 50%;
            border: 2px solid #dab977;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 16px;
            color: #fff;
            letter-spacing: 2px;
        }

        .header-right img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #dab977;
            object-fit: cover;
        }

        /* ================= HERO (TENGAH PUTIH) ================= */
        .hero-banner {
            background: #000000ff; /* ⬅️ DIUBAH PUTIH */
            padding: 90px 20px 70px;
            text-align: center;
        }

        .hero-content {
            max-width: 720px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 42px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            letter-spacing: -1.5px;
            color: #ffffffff;
            text-transform: uppercase;
        }

        .hero-subtitle {
            margin-top: 18px;
            font-size: 18px;
            color: #ffffffff;
        }

        /* ================= BUTTON ================= */
        .hero-actions {
            margin-top: 36px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 14px 26px;
            border-radius: 12px;
            border: 2px solid #111;
            background: #111;
            color: #fff;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
        }

        .btn-outline {
            padding: 14px 26px;
            border-radius: 12px;
            border: 2px solid #111;
            background: #fff;
            color: #111;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
        }

        /* ================= SECTION ================= */
        .section-title {
            margin: 48px auto 28px;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 20px;
            color: #231105;
            text-transform: uppercase;
        }

        .product-section {
            background: #f9fafb; /* PUTIH TERANG */
            padding-bottom: 60px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(240px,1fr));
            gap: 30px;
            max-width: 900px;
            margin: 0 auto;
        }

        .product-card {
            border-radius: 18px;
            background: #fff;
            border: 1.5px solid #eee;
            text-align: center;
            transition: 0.25s;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 14px 32px rgba(0,0,0,0.12);
        }

        .product-image {
            height: 160px;
            margin: 18px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ================= FOOTER ================= */
        footer {
            background: #0a0a0a;
            color: #fff;
            padding: 60px 20px 30px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            gap: 40px;
            flex-wrap: wrap;
        }

        .footer-section h3 {
            color: #dab977;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .footer-section p, 
        .footer-section a {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            text-decoration: none;
        }

        .footer-bottom {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.5);
        }

        @media(max-width: 900px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
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

     <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Jogja Artsphere</h4>
                    <p>Platform seni modern & terkurasi.</p>
                </div>
                <div class="footer-col">
                    <h4>Kontak</h4>
                    <p>Jl. Malioboro 123</p>
                    <p>Yogyakarta</p>
                    <p>info@jogja-artsphere.com</p>
                </div>
                <div class="footer-col">
                    <h4>Bantuan</h4>
                    <a href="#">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© {{ date('Y') }} Jogja Artsphere. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>