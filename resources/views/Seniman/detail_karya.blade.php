<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $karya->nama_karya }} - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/detail_karya.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-left">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
                <span class="brand">JOGJA ARTSPHERE</span>
            </div>

            <form action="{{ route('dashboard.pembeli.search') }}" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Cari karya..." value="{{ request('query') }}">
            </form>

            <div class="header-right">
                <a href="{{ route('keranjang.index') }}" class="icon-link" title="Keranjang">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9 2L7.17 4H3a1 1 0 000 2h1l1.68 9.39A2 2 0 0011.56 17H18a2 2 0 001.97-1.61L21 8H7" stroke="currentColor" stroke-width="2"/>
                        <circle cx="9" cy="20" r="1" fill="currentColor"/>
                        <circle cx="18" cy="20" r="1" fill="currentColor"/>
                    </svg>
                </a>
                
                @php
                    $pembeli = Auth::guard('pembeli')->user();
                    $fotoPath = $pembeli && $pembeli->foto 
                        ? asset('storage/foto_pembeli/' . $pembeli->foto)
                        : asset('assets/defaultprofile.png');
                @endphp
                
                <a href="{{ route('pembeli.profil') }}" title="Profil">
                    <img src="{{ $fotoPath }}" alt="Profile" class="avatar">
                </a>
            </div>
        </div>
    </header>

    <!-- Product Detail -->
    <main class="product-detail">
        <div class="container">
            <div class="product-grid">
                <!-- Image Section -->
                <div class="product-image">
                    @if($karya->gambar)
                        <img src="{{ asset('storage/karya_seni/' . $karya->gambar) }}" alt="{{ $karya->nama_karya }}">
                    @else
                        <div class="no-image">No Image</div>
                    @endif
                </div>

                <!-- Info Section -->
                <div class="product-info">
                    <div class="product-header">
                        <h1 class="product-title">{{ $karya->nama_karya }}</h1>
                        <p class="product-artist">{{ $karya->seniman->nama ?? 'Unknown Artist' }}</p>
                        
                        <!-- Rating Summary -->
                        @if($averageRating)
                            <div class="rating-summary">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($averageRating))
                                            <span class="star filled">★</span>
                                        @elseif($i - 0.5 <= $averageRating)
                                            <span class="star half">★</span>
                                        @else
                                            <span class="star">★</span>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-value">{{ $averageRating }}/5</span>
                                <span class="rating-count">
                                    @if($karya->reviews && $karya->reviews->count() > 0)
                                        ({{ $karya->reviews->count() }} ulasan)
                                    @else
                                        (Belum ada ulasan)
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="product-price">Rp {{ number_format($karya->harga, 0, ',', '.') }}</div>

                    <!-- Stats Section untuk Pembeli -->
                    <div class="stats-section">
                        <h3 class="stats-title">Statistik Karya</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $karya->stok }}</div>
                                <div class="stat-label">Stok Tersedia</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $karya->terjual ?? 0 }}</div>
                                <div class="stat-label">Terjual</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">
                                    @if($averageRating)
                                        {{ $averageRating }}/5
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="stat-label">Rating</div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="stock-wrapper">
                        @if($karya->stok <= 0)
                            <div class="stock-badge out">
                                <span class="dot"></span>
                                Stok Habis
                            </div>
                        @elseif($karya->stok <= 5)
                            <div class="stock-badge low">
                                <span class="dot"></span>
                                {{ $karya->stok }} Tersisa
                            </div>
                        @else
                            <div class="stock-badge available">
                                <span class="dot"></span>
                                Tersedia
                            </div>
                        @endif

                        @if(isset($karya->terjual) && $karya->terjual > 0)
                            <span class="sold-badge">{{ $karya->terjual }} Terjual</span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="product-description">
                        <h3>Detail Produk</h3>
                        <p>{{ $karya->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                    </div>

                    <!-- Kode Seni (untuk informasi) -->
                    <div class="product-code">
                        <h3>Kode Karya</h3>
                        <p class="code-value">{{ $karya->kode_seni }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="product-actions">
                        @if($karya->stok > 0)
                            <button class="btn btn-primary" onclick="tambahKeranjang('{{ $karya->kode_seni }}', true)">
                                Beli Sekarang
                            </button>
                            <button class="btn btn-secondary" onclick="tambahKeranjang('{{ $karya->kode_seni }}', false)">
                                Tambah ke Keranjang
                            </button>
                            <a href="{{ route('pembeli.chat.start.from.karya', $karya->kode_seni) }}" class="btn btn-chat">
                                💬 Chat {{ $karya->seniman->nama ?? 'Seniman' }}
                            </a>
                        @else
                            <button class="btn btn-disabled" disabled>
                                Stok Habis
                            </button>
                            <a href="{{ route('pembeli.chat.start.from.karya', $karya->kode_seni) }}" class="btn btn-chat">
                                💬 Tanya Stok
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Sales Info Section (seperti di PDP Seniman) -->
    <section class="sales-section">
        <div class="container">
            <h2 class="section-title">Informasi Karya</h2>
            <div class="sales-grid">
                <div class="sales-card">
                    <div class="sales-icon">📦</div>
                    <div class="sales-content">
                        <h3>Ketersediaan</h3>
                        <p class="sales-value">{{ $karya->stok }} unit tersedia</p>
                        <p class="sales-desc">
                            @if($karya->stok <= 0)
                                <span style="color: #e74c3c;">Stok habis</span>
                            @elseif($karya->stok <= 5)
                                <span style="color: #f39c12;">Stok terbatas</span>
                            @else
                                <span style="color: #2ecc71;">Stok mencukupi</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="sales-card">
                    <div class="sales-icon">🔥</div>
                    <div class="sales-content">
                        <h3>Popularitas</h3>
                        <p class="sales-value">{{ $karya->terjual ?? 0 }} terjual</p>
                        <p class="sales-desc">
                            @if(($karya->terjual ?? 0) > 50)
                                <span style="color: #e74c3c;">Sangat laris</span>
                            @elseif(($karya->terjual ?? 0) > 10)
                                <span style="color: #f39c12;">Laris</span>
                            @else
                                <span style="color: #2ecc71;">Baru diluncurkan</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="sales-card">
                    <div class="sales-icon">⭐</div>
                    <div class="sales-content">
                        <h3>Rating</h3>
                        <p class="sales-value">
                            @if($averageRating)
                                {{ $averageRating }}/5
                            @else
                                Belum ada rating
                            @endif
                        </p>
                        <p class="sales-desc">
                            @if($karya->reviews && $karya->reviews->count() > 0)
                                {{ $karya->reviews->count() }} ulasan
                            @else
                                Belum ada ulasan
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section class="related-section">
        <div class="container">
            <h2 class="section-title">Karya Lainnya dari Seniman Ini</h2>
            
            @if($karyaSeniman->isEmpty())
                <p class="empty-text">Tidak ada karya lain tersedia</p>
            @else
                <div class="product-carousel">
                    @foreach($karyaSeniman as $item)
                        <a href="{{ route('pembeli.karya.detail', $item->kode_seni) }}" class="card {{ $item->stok <= 0 ? 'sold-out' : '' }}">
                            <div class="card-image">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}">
                                @else
                                    <div class="no-image">No Image</div>
                                @endif
                                
                                @if($item->stok <= 0)
                                    <span class="overlay-badge">Stok Habis</span>
                                @elseif($item->stok <= 5)
                                    <span class="overlay-badge warning">Terbatas</span>
                                @endif
                            </div>
                            
                            <div class="card-content">
                                <h3>{{ $item->nama_karya }}</h3>
                                <p class="card-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                <div class="card-meta">
                                    @if($item->stok > 0)
                                        <span class="meta-stock">{{ $item->stok }} tersedia</span>
                                    @else
                                        <span class="meta-stock out">Habis</span>
                                    @endif
                                    
                                    @if(isset($item->terjual) && $item->terjual > 0)
                                        <span class="meta-sold">{{ $item->terjual }} terjual</span>
                                    @endif
                                </div>

                                <!-- Rating untuk card -->
                                @if($item->reviews && $item->reviews->count() > 0)
                                    @php
                                        $itemRating = round($item->reviews->avg('nilai'), 1);
                                    @endphp
                                    <div class="card-rating">
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= floor($itemRating) ? 'filled' : '' }}">★</span>
                                            @endfor
                                        </div>
                                        <span class="rating-count">({{ $item->reviews->count() }})</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Ratings & Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <div class="reviews-header">
                <h2 class="section-title">Ulasan & Rating</h2>
                
                @if($averageRating)
                    <div class="overall-rating">
                        <div class="rating-score">
                            <span class="score">{{ $averageRating }}</span>
                            <span class="out-of">/5</span>
                        </div>
                        <div class="rating-details">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($averageRating))
                                        <span class="star filled">★</span>
                                    @elseif($i - 0.5 <= $averageRating)
                                        <span class="star half">★</span>
                                    @else
                                        <span class="star">★</span>
                                    @endif
                                @endfor
                            </div>
                            <p class="total-reviews">
                                Berdasarkan {{ $karya->reviews->count() }} ulasan
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            @if($karya->reviews && $karya->reviews->count() > 0)
                <div class="reviews-list">
                    @foreach($karya->reviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        @if($review->pembeli && $review->pembeli->foto)
                                            <img src="{{ asset('storage/foto_pembeli/' . $review->pembeli->foto) }}" alt="{{ $review->pembeli->nama }}">
                                        @else
                                            <div class="avatar-placeholder">{{ substr($review->pembeli->nama ?? 'U', 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div class="reviewer-details">
                                        <h4 class="reviewer-name">{{ $review->pembeli->nama ?? 'Pengguna' }}</h4>
                                        <div class="review-meta">
                                            <div class="review-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="star {{ $i <= $review->nilai ? 'filled' : '' }}">★</span>
                                                @endfor
                                            </div>
                                            <span class="review-date">
                                                {{ $review->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="review-content">
                                <p>{{ $review->komentar ?? 'Tidak ada komentar' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-reviews">
                    <div class="no-reviews-icon">⭐</div>
                    <h3>Belum ada ulasan</h3>
                    <p>Jadilah yang pertama memberikan ulasan untuk karya ini</p>
                </div>
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
                    <p>Jl. Malioboro No.123, Yogyakarta</p>
                    <p>Email: info@jogja-artsphere.com</p>
                    <p>Telp: (0274) 123-4567</p>
                </div>
                <div class="footer-col">
                    <h4>Bantuan</h4>
                    <a href="#">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                    <a href="#">FAQ</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Jogja Artsphere. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script>
        function tambahKeranjang(kodeSeni, langsungBeli) {
            fetch(`/pembeli/keranjang/tambah/${kodeSeni}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message);
                    if (langsungBeli) {
                        setTimeout(() => window.location.href = '/pembeli/keranjang', 800);
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showNotification('Terjadi kesalahan', 'error');
            });
        }

        function showNotification(message, type = 'success') {
            const notif = document.getElementById('notification');
            notif.textContent = message;
            notif.className = 'notification show';
            
            if (type === 'error') {
                notif.style.background = '#e74c3c';
            } else {
                notif.style.background = '#111';
            }
            
            setTimeout(() => {
                notif.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>