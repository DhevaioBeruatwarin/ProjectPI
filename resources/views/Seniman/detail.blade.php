<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $karya->nama_karya }} - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/detail.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-left">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
                <span class="brand">JOGJA ARTSPHERE</span>
            </div>

            <form action="{{ route('dashboard.seniman.search') }}" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Cari karya..." value="{{ request('query') }}">
            </form>

            <div class="header-right">
                @php
                    $seniman = Auth::guard('seniman')->user();
                    $fotoPath = $seniman && $seniman->foto 
                        ? asset('storage/foto_seniman/' . $seniman->foto)
                        : asset('assets/defaultprofile.png');
                @endphp
                
                <a href="{{ route('seniman.profil') }}" title="Profil">
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
                        <p class="product-artist">Karya Saya</p>
                    </div>

                    <div class="product-price">Rp {{ number_format($karya->harga, 0, ',', '.') }}</div>

                    <!-- Stats Section -->
                    <div class="stats-section">
                        <h3 class="stats-title">Statistik Karya</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $karya->stok }}</div>
                                <div class="stat-label">Stok</div>
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
                        <h3>Detail Karya</h3>
                        <p>{{ $karya->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                    </div>

                    <!-- Kode Seni -->
                    <div class="product-code">
                        <h3>Kode Karya</h3>
                        <p class="code-value">{{ $karya->kode_seni }}</p>
                    </div>

                    <!-- Admin Actions -->
                    <div class="admin-actions">
                        <a href="{{ route('seniman.karya.edit', $karya->kode_seni) }}" class="btn btn-edit">
                            Edit Karya
                        </a>
                        <form action="{{ route('seniman.karya.delete', $karya->kode_seni) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Hapus karya ini?')">
                                Hapus Karya
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Sales Info -->
    <section class="sales-section">
        <div class="container">
            <h2 class="section-title">Informasi Penjualan</h2>
            <div class="sales-grid">
                <div class="sales-card">
                    <div class="sales-icon">💰</div>
                    <div class="sales-content">
                        <h3>Total Pendapatan</h3>
                        <p class="sales-value">Rp {{ number_format(($karya->terjual ?? 0) * $karya->harga, 0, ',', '.') }}</p>
                        <p class="sales-desc">Dari {{ $karya->terjual ?? 0 }} penjualan</p>
                    </div>
                </div>
                <div class="sales-card">
                    <div class="sales-icon">📊</div>
                    <div class="sales-content">
                        <h3>Ketersediaan</h3>
                        <p class="sales-value">{{ $karya->stok }} unit</p>
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
</body>
</html>