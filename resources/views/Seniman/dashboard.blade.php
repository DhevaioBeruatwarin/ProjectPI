<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Seniman - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    @include('components.back-button')
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-left">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
                <span class="brand">JOGJA ARTSPHERE</span>
            </div>

            <form action="{{ route('dashboard.seniman.search') }}" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Cari karya seni..." value="{{ request('query') }}">
            </form>

            <div class="header-right">
                @if(\Illuminate\Support\Facades\Auth::guard('seniman')->check())
                    @php
                        $seniman = Auth::guard('seniman')->user();
                        $fotoPath = $seniman->foto 
                            ? asset('storage/foto_seniman/' . $seniman->foto)
                            : asset('assets/defaultprofile.png');
                    @endphp
                    <a href="{{ route('seniman.profil') }}">
                        <img src="{{ $fotoPath }}" alt="Profile" class="avatar">
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Selamat datang,<br>{{ $seniman->nama }}</h1>
                <p class="hero-subtitle">Temukan karya seni dari seniman lain dan bagikan inspirasimu di Jogja Artsphere</p>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2>Karya Pilihan</h2>
            </div>

            @if($karyaSeni->isEmpty())
                <div class="empty-state">
                    <p>Belum ada karya seni tersedia</p>
                </div>
            @else
                <!-- Carousel Container -->
                <div class="carousel-wrapper">
                    <button class="carousel-btn prev" onclick="scrollCarousel(-1)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>

                    <div class="carousel" id="productCarousel">
                        @foreach($karyaSeni as $item)
                            <a href="{{ route('karya.detail', $item->kode_seni) }}" class="product-card {{ $item->stok <= 0 ? 'sold-out' : '' }}">
                                <div class="card-image">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}">
                                    @else
                                        <div class="no-image">No Image</div>
                                    @endif
                                    
                                    @if($item->stok <= 0)
                                        <span class="badge sold">Habis</span>
                                    @elseif($item->stok <= 5)
                                        <span class="badge limited">Terbatas</span>
                                    @endif
                                </div>

                                <div class="card-content">
                                    <h3>{{ $item->nama_karya }}</h3>
                                    <p class="artist">{{ $item->seniman->nama ?? 'Unknown' }}</p>
                                    <p class="price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    
                                    <div class="card-meta">
                                        @if($item->stok > 0)
                                            <span class="stock">{{ $item->stok }} unit</span>
                                        @else
                                            <span class="stock out">Stok Habis</span>
                                        @endif
                                        @if(isset($item->terjual) && $item->terjual > 0)
                                            <span class="sold">{{ $item->terjual }} terjual</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <button class="carousel-btn next" onclick="scrollCarousel(1)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                </div>

                <!-- Carousel Indicators -->
                <div class="carousel-indicators" id="indicators"></div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Jogja Artsphere</h4>
                    <p>Platform jual beli karya seni lokal Yogyakarta</p>
                </div>
                <div class="footer-col">
                    <h4>Kontak</h4>
                    <p>Jl. Malioboro No. 123</p>
                    <p>Yogyakarta 55271</p>
                    <p>Telp: (0274) 123-4567</p>
                    <p>Email: info@jogja-artsphere.com</p>
                </div>
                <div class="footer-col">
                    <h4>Bantuan</h4>
                    <a href="#">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                    <a href="#">Customer Service</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Jogja Artsphere. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        let currentSlide = 0;
        const carousel = document.getElementById('productCarousel');
        const cards = carousel.querySelectorAll('.product-card');
        const totalSlides = Math.ceil(cards.length / getCardsPerView());

        function getCardsPerView() {
            if (window.innerWidth <= 768) return 2;
            if (window.innerWidth <= 1024) return 3;
            return 4;
        }

        function scrollCarousel(direction) {
            const cardsPerView = getCardsPerView();
            const cardWidth = cards[0].offsetWidth;
            const gap = 24;
            const scrollAmount = (cardWidth + gap) * cardsPerView;

            currentSlide += direction;
            const maxSlide = Math.ceil(cards.length / cardsPerView) - 1;

            if (currentSlide < 0) currentSlide = 0;
            if (currentSlide > maxSlide) currentSlide = maxSlide;

            carousel.style.transform = `translateX(-${currentSlide * scrollAmount}px)`;
            updateIndicators();
        }

        function updateIndicators() {
            const indicators = document.getElementById('indicators');
            indicators.innerHTML = '';
            
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.className = 'indicator' + (i === currentSlide ? ' active' : '');
                dot.onclick = () => goToSlide(i);
                indicators.appendChild(dot);
            }
        }

        function goToSlide(index) {
            const cardsPerView = getCardsPerView();
            const cardWidth = cards[0].offsetWidth;
            const gap = 24;
            const scrollAmount = (cardWidth + gap) * cardsPerView;
            
            currentSlide = index;
            carousel.style.transform = `translateX(-${currentSlide * scrollAmount}px)`;
            updateIndicators();
        }

        // Initialize
        if (cards.length > 0) {
            updateIndicators();
        }

        // Responsive recalculation
        window.addEventListener('resize', () => {
            currentSlide = 0;
            carousel.style.transform = 'translateX(0)';
            updateIndicators();
        });
    </script>
</body>
</html>
