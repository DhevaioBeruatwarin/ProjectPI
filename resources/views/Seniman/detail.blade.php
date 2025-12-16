<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $karya->nama_karya }} - Dashboard Seniman | Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/detail.css') }}">
    <style>
        /* Menyesuaikan dengan styling detail_karya.css */
        .product-detail .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-bottom: 60px;
        }

        .product-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .no-image {
            width: 100%;
            height: 500px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: #666;
            font-size: 18px;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .product-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .product-title {
            font-size: 32px;
            font-weight: 700;
            color: #111;
            margin: 0 0 8px 0;
        }

        .product-artist {
            font-size: 16px;
            color: #666;
            margin: 0;
        }

        .product-price {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        /* Stats Section */
        .stats-section {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
        }

        .stats-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 0 0 20px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #111;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
        }

        /* Stock & Status */
        .stock-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 0;
        }

        .stock-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .stock-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .stock-badge.available {
            background: #f0f9f0;
            color: #2ecc71;
        }

        .stock-badge.available .dot {
            background: #2ecc71;
        }

        .stock-badge.low {
            background: #fff8e1;
            color: #f39c12;
        }

        .stock-badge.low .dot {
            background: #f39c12;
        }

        .stock-badge.out {
            background: #fee;
            color: #e74c3c;
        }

        .stock-badge.out .dot {
            background: #e74c3c;
        }

        .sold-badge {
            background: #111;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Description & Code */
        .product-description,
        .product-code {
            padding: 20px 0;
            border-top: 1px solid #eee;
        }

        .product-description h3,
        .product-code h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 0 0 12px 0;
        }

        .product-description p {
            color: #555;
            line-height: 1.6;
            margin: 0;
        }

        .code-value {
            font-family: monospace;
            background: #f5f5f5;
            padding: 10px 15px;
            border-radius: 6px;
            color: #333;
            font-size: 16px;
            margin: 0;
        }

        /* Admin Actions */
        .admin-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-edit {
            background: #111;
            color: white;
        }

        .btn-edit:hover {
            background: #000;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #fee;
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: white;
            transform: translateY(-2px);
        }

        /* Sales Section */
        .sales-section {
            background: #f9f9f9;
            padding: 60px 0;
            margin: 40px 0;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            text-align: center;
            margin: 0 0 40px 0;
        }

        .sales-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .sales-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .sales-card:hover {
            transform: translateY(-5px);
        }

        .sales-icon {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .sales-content h3 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin: 0 0 10px 0;
        }

        .sales-value {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            margin: 0 0 5px 0;
        }

        .sales-desc {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        /* Reviews Section */
        .reviews-section {
            padding: 60px 0;
            background: white;
        }

        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .overall-rating {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .rating-score {
            font-size: 32px;
            font-weight: 700;
            color: #111;
        }

        .rating-details {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .stars {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #ddd;
            font-size: 20px;
        }

        .star.filled {
            color: #ffc107;
        }

        .star.half {
            color: #ffc107;
            position: relative;
        }

        .star.half:after {
            content: '★';
            position: absolute;
            left: 0;
            width: 50%;
            overflow: hidden;
            color: #ddd;
        }

        .total-reviews {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        /* Review Cards */
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .review-card {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid #eee;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .review-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
        }

        .reviewer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: #111;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 600;
        }

        .review-stars {
            display: flex;
            gap: 2px;
        }

        .review-content {
            color: #333;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Reply Form */
        .seller-reply {
            background: #f0f9f0;
            border-left: 4px solid #2ecc71;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .seller-reply strong {
            font-size: 14px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .seller-reply p {
            color: #555;
            margin: 0;
            line-height: 1.5;
        }

        .reply-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .reply-form textarea {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
            font-size: 14px;
        }

        .reply-form textarea:focus {
            outline: none;
            border-color: #111;
        }

        .btn-primary.btn-sm {
            align-self: flex-end;
            padding: 10px 20px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn-primary.btn-sm:hover {
            background: #000;
        }

        /* No Reviews */
        .no-reviews {
            text-align: center;
            padding: 60px 20px;
        }

        .no-reviews-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .no-reviews h3 {
            font-size: 24px;
            color: #333;
            margin: 0 0 10px 0;
        }

        .no-reviews p {
            color: #666;
            margin: 0;
        }

        /* Delete Form */
        .delete-form {
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .sales-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .reviews-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .stats-grid,
            .sales-grid {
                grid-template-columns: 1fr;
            }

            .admin-actions {
                flex-direction: column;
            }
        }
    </style>
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
                            ✏️ Edit Karya
                        </a>
                        <form action="{{ route('seniman.karya.delete', $karya->kode_seni) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Hapus karya ini?')">
                                🗑️ Hapus Karya
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
                            @if($karya->reviews->sum('responses_count') > 0)
                                <span class="responses-count">
                                    • {{ $karya->reviews->sum('responses_count') }} tanggapan
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <div class="alert-icon">✅</div>
                <div class="alert-content">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <div class="alert-icon">❌</div>
                <div class="alert-content">
                    <strong>Gagal!</strong> {{ session('error') }}
                </div>
            </div>
        @endif

        @if($karya->reviews && $karya->reviews->count() > 0)
            <div class="reviews-list">
                @foreach($karya->reviews as $review)
                    @php
                        // Load responses jika tabel ada
                        $hasResponses = false;
                        $responses = collect();
                        if (\Schema::hasTable('review_responses')) {
                            $review->load(['responses' => function($query) {
                                $query->whereNull('deleted_at')->latest();
                            }, 'responses.seniman']);
                            $hasResponses = $review->responses->isNotEmpty();
                            $responses = $review->responses;
                        }
                    @endphp
                    
                    <div class="review-card" id="review-{{ $review->id_review }}">
                        <div class="review-header">
                            <div class="review-author">
                                <div class="reviewer-avatar">
                                    @if($review->pembeli && $review->pembeli->foto)
                                        <img src="{{ asset('storage/foto_pembeli/' . $review->pembeli->foto) }}" 
                                             alt="{{ $review->pembeli->nama }}">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ substr($review->pembeli->nama ?? 'U', 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="reviewer-info">
                                    <strong class="reviewer-name">{{ $review->pembeli->nama ?? 'Pengguna' }}</strong>
                                    <span class="review-date">{{ $review->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->nilai ? 'filled' : '' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        
                        <div class="review-content">
                            <p>{{ $review->komentar ?? 'Tidak ada komentar' }}</p>
                        </div>

                        <!-- Seller Response -->
                        <div class="seller-response-section">
                            @if($hasResponses)
                                @foreach($responses as $response)
                                    <div class="seller-response">
                                        <div class="response-header">
                                            <div class="seller-info">
                                                <div class="seller-avatar">
                                                    @if($response->seniman && $response->seniman->foto)
                                                        <img src="{{ asset('storage/foto_seniman/' . $response->seniman->foto) }}" 
                                                             alt="{{ $response->seniman->nama }}">
                                                    @else
                                                        <div class="seller-avatar-placeholder">S</div>
                                                    @endif
                                                </div>
                                                <div class="seller-details">
                                                    <strong>Anda (Seniman)</strong>
                                                    <span class="response-date">
                                                        {{ $response->created_at->format('d M Y, H:i') }}
                                                        @if($response->created_at != $response->updated_at)
                                                            • (Diedit)
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- Tombol edit kecil di pojok kanan atas -->
                                            <button type="button"
                                                    class="btn-edit-small edit-response-toggle"
                                                    data-review-id="{{ $review->id_review }}">
                                                ✏️ Edit
                                            </button>
                                        </div>
                                        <div class="response-content">
                                            <p>{{ $response->tanggapan }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Response Form -->
                            <div class="response-form-wrapper {{ $hasResponses ? 'is-hidden' : '' }}"
                                 id="response-form-wrapper-{{ $review->id_review }}">
                                <form action="{{ route('seniman.review.reply', $review->id_review) }}" 
                                      method="POST" 
                                      class="response-form"
                                      id="response-form-{{ $review->id_review }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="tanggapan-{{ $review->id_review }}" class="form-label">
                                            @if($hasResponses)
                                                Edit Tanggapan Anda:
                                            @else
                                                Tanggapi ulasan ini:
                                            @endif
                                        </label>
                                        <textarea
                                            id="tanggapan-{{ $review->id_review }}"
                                            name="tanggapan"
                                            rows="3"
                                            placeholder="Tulis tanggapan Anda untuk {{ $review->pembeli->nama ?? 'pembeli' }}..."
                                            class="form-control"
                                            required
                                        >{{ old('tanggapan', $responses->first()->tanggapan ?? '') }}</textarea>
                                        <div class="form-footer">
                                            <span class="char-count">0/2000 karakter</span>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    @if($hasResponses)
                                                        ✏️ Update Tanggapan
                                                    @else
                                                        💬 Kirim Tanggapan
                                                    @endif
                                                </button>
                                                @if($hasResponses)
                                                    <button type="button"
                                                            class="btn btn-secondary btn-sm cancel-edit"
                                                            data-review-id="{{ $review->id_review }}">
                                                        Batal
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-reviews">
                <div class="no-reviews-icon">⭐</div>
                <h3>Belum ada ulasan</h3>
                <p>Belum ada pembeli yang memberikan ulasan untuk karya ini</p>
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

    <script>
// Character counter + validation + toggle edit form
document.addEventListener('DOMContentLoaded', function() {
    // Character counter
    const textareas = document.querySelectorAll('.form-control');
    textareas.forEach(textarea => {
        const charCount = textarea.parentElement.querySelector('.char-count');
        if (charCount) {
            updateCharCount(textarea, charCount);
            
            textarea.addEventListener('input', function() {
                updateCharCount(this, charCount);
            });
        }
    });

    // Form validation
    const forms = document.querySelectorAll('.response-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const textarea = this.querySelector('textarea');
            if (!textarea.value.trim()) {
                e.preventDefault();
                showToast('Silakan isi tanggapan terlebih dahulu', 'error');
                textarea.focus();
                return;
            }
            
            if (textarea.value.length > 2000) {
                e.preventDefault();
                showToast('Tanggapan maksimal 2000 karakter', 'error');
                textarea.focus();
                return;
            }
        });
    });

    // Toggle form when clicking "Edit" button
    const editButtons = document.querySelectorAll('.edit-response-toggle');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-review-id');
            const wrapper = document.getElementById(`response-form-wrapper-${reviewId}`);
            const textarea = document.getElementById(`tanggapan-${reviewId}`);

            if (wrapper) {
                wrapper.classList.remove('is-hidden');
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            if (textarea) {
                textarea.focus();
            }
        });
    });

    // Cancel edit button
    const cancelButtons = document.querySelectorAll('.cancel-edit');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-review-id');
            const wrapper = document.getElementById(`response-form-wrapper-${reviewId}`);
            
            if (wrapper) {
                wrapper.classList.add('is-hidden');
            }
        });
    });

    // Auto-scroll to form if there's an error
    @if($errors->any())
        const firstError = document.querySelector('.alert-error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    @endif
});

function updateCharCount(textarea, charCountElement) {
    const length = textarea.value.length;
    charCountElement.textContent = `${length}/2000 karakter`;
    
    if (length > 1800) {
        charCountElement.style.color = '#e74c3c';
        charCountElement.style.fontWeight = '600';
    } else if (length > 1500) {
        charCountElement.style.color = '#f39c12';
    } else {
        charCountElement.style.color = '#888';
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-icon">${type === 'error' ? '❌' : 'ℹ️'}</span>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
    </script>
</body>
</html>