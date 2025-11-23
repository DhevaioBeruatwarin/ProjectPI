<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogja Artsphere - Belanja Kerajinan & Seni</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
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
        <form action="{{ route('dashboard.seniman.search') }}" method="GET" style="display:inline;">
    <input type="text" name="query" class="search-bar" placeholder="Cari karya..." value="{{ request('query') }}">
</form>

        <div class="header-right">
            <button class="icon-btn" id="camera-btn">📷</button>
            <a href="keranjang" class="icon-btn">🛒</a>
                   @if(\Illuminate\Support\Facades\Auth::guard('pembeli')->check())
    @php
        $pembeli = Auth::guard('pembeli')->user();
        $fotoPath = $pembeli->foto 
            ? asset('storage/foto_pembeli/' . $pembeli->foto)
            : asset('assets/defaultprofile.png'); 
    @endphp

    <a href="{{ route('pembeli.profil') }}" title="Profil">
        <img src="{{ $fotoPath }}" 
             alt="Foto Profil"
             class="profile-icon"
             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
    </a>
@endif
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">Yuk belanja di Jogja Artsphere</h1>
            <p class="hero-subtitle">Jelajahi koleksi terbaru dan favorit dari seniman Jogja. Pilih, klik, dan bawa pulang inspirasi!</p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('assets/logo.png') }}" alt="" class="logo1"> 
        </div>
    </div>

    <!-- Explore Products -->
    <div class="section-title">
        <span>Explore produk karya seni</span>
    </div>

    <div class="product-section">
        <div class="product-grid">
            @if($karyaSeni->isEmpty())
                <p style="text-align:center; width:100%; margin-top:20px; color:gray;">Belum ada karya seni tersedia.</p>
            @else
              

@foreach($karyaSeni as $item)
    <a href="{{ route('karya.detail', $item->kode_seni) }}" class="product-card" style="text-decoration: none; color: inherit;">
        <div class="product-image">
            @if($item->gambar)
                <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}" style="width:100%; height:200px; object-fit:cover; border-radius:10px;">
            @else
                <div style="width:100%; height:200px; background:#ddd; display:flex; align-items:center; justify-content:center; border-radius:10px;">No Image</div>
            @endif
            
            <!-- Stock Badge -->
            @if($item->stok <= 0)
                <div class="stock-badge stock-habis">Stok Habis</div>
            @elseif($item->stok <= 5)
                <div class="stock-badge stock-terbatas">Stok Terbatas</div>
            @endif
        </div>
        <div class="product-info">
            <div class="product-name">{{ $item->nama_karya }}</div>
            <div class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
            
            <!-- Stock Info -->
            <div class="product-meta">
                <div class="product-reviews">{{ $item->terjual ?? 0 }} terjual</div>
                <div class="product-stock {{ $item->stok <= 0 ? 'stock-out' : ($item->stok <= 5 ? 'stock-low' : 'stock-available') }}">
                    @if($item->stok <= 0)
                        <span class="stock-icon">❌</span> Habis
                    @else
                        <span class="stock-icon">📦</span> Stok: {{ $item->stok }}
                    @endif
                </div>
            </div>
        </div>
    </a>
@endforeach
            @endif
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
                <h3>Bantuan</h3>
                <div class="footer-links">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                    <a href="#">Customer Service</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p><strong>Jogja Artsphere</strong> - Satu Jika Untuk Koleksi Karya Lokal.</p>
        </div>
    </footer>
</body>
</html>