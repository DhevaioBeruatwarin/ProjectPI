<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pembeli | Jogja Artsphere</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <!-- NAVBAR -->
    <header class="header">
        <div class="container nav-container">
            <div class="header-left">
                <a href="{{ url('/') }}" class="logo-link">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
                </a>
                <span class="brand">JOGJA ARTSPHERE</span>
            </div>

            <form action="{{ route('dashboard.pembeli.search') }}" method="GET" class="search-form" role="search" aria-label="Search karya seni">
                <input type="search" name="query" placeholder="Cari karya seni..." value="{{ request('query') }}" aria-label="Cari karya seni">
            </form>

            <div class="header-right">
                <a href="{{ route('keranjang.index') }}" class="icon-link" title="Keranjang">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 2L7.17 4H3a1 1 0 000 2h1l1.68 9.39A2 2 0 0011.56 17H18a2 2 0 001.97-1.61L21 8H7" stroke="currentColor" stroke-width="2"/>
                        <circle cx="9" cy="20" r="1" fill="currentColor"/>
                        <circle cx="18" cy="20" r="1" fill="currentColor"/>
                    </svg>
                </a>

                @if(\Illuminate\Support\Facades\Auth::guard('pembeli')->check())
                    @php
                        $pembeli = Auth::guard('pembeli')->user();
                        $fotoPath = $pembeli->foto ? asset('storage/foto_pembeli/' . $pembeli->foto) : asset('assets/defaultprofile.png');
                    @endphp
                    <a href="{{ route('pembeli.profil') }}" class="profile-link" title="Profil">
                        <img src="{{ $fotoPath }}" alt="Foto Profil" class="avatar">
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- HERO BANNER (horizontal) -->
    <section class="hero-banner" style="background-image: url('{{ asset('assets/hero_banner.jpg') }}');">
        <div class="hero-overlay-layer" aria-hidden="true"></div>

        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Eksplorasi Karya Seni</h1>
                <p class="hero-subtitle">Koleksi kurasi dari seniman Yogyakarta — minimalis, modern, dan elegan.</p>
                <div class="hero-ctas">
                    <a href="#" class="btn-ghost">BROWSE COLLECTION</a>
                    <a href="#" class="btn-outline">Pelajari</a>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUCTS -->
    <main class="products-section">
        <div class="container">
            <div class="section-header">
                <h2>Karya Pilihan</h2>
            </div>

            @if($karyaSeni->isEmpty())
                <div class="empty-state">
                    <p>Belum ada karya seni tersedia</p>
                </div>
            @else
                <div class="carousel-wrapper">
                    <button class="carousel-btn prev" onclick="scrollCarousel(-1)" aria-label="Previous">
                        ‹
                    </button>
                    
                    <div class="carousel" id="productCarousel" role="list">
                        @foreach($karyaSeni as $item)
                           <a href="{{ route('pembeli.karya.detail', $item->kode_seni) }}" class="product-card" style="text-decoration: none; color: inherit;">
                                <div class="card-image">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}">
                                    @else
                                        <div class="no-image">No Image</div>
                                    @endif

                                    @if($item->stok <= 0)
                                        <span class="badge sold">Sold Out</span>
                                    @elseif($item->stok <= 5)
                                        <span class="badge limited">Low Stock</span>
                                    @endif
                                </div>
                                

                                <div class="card-content">
                                    <h3 class="card-title">{{ $item->nama_karya }}</h3>
                                    <p class="artist">{{ $item->seniman->nama ?? 'Seniman' }}</p>
                                    <p class="price">Rp{{ number_format($item->harga, 0, ',', '.') }}</p>

                                    <div class="card-meta">
                                        <span class="stock{{ $item->stok <= 0 ? ' out' : '' }}">
                                            {{ $item->stok > 0 ? $item->stok . ' tersedia' : 'Stok Habis' }}
                                        </span>
                                        @if(isset($item->terjual) && $item->terjual > 0)
                                            <span class="sold-count">{{ $item->terjual }} terjual</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <button class="carousel-btn next" onclick="scrollCarousel(1)" aria-label="Next">
                        ›
                    </button>
                </div>

                <div class="carousel-indicators" id="indicators" aria-hidden="true"></div>
            @endif
        </div>
    </main>

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

    <!-- Carousel controller (external) -->
    <script src="{{ asset('js/carousel-fix.js') }}"></script>
</body>
</html>