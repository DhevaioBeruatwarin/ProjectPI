<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Jogja Artsphere</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
        }

        /* Header */
        header {
            background-color: #231105;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #333;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-text {
            color: white;
            font-weight: bold;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .icon-btn {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            text-decoration: none;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Success Banner */
        .success-banner {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .success-icon {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .success-banner h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .success-banner p {
            font-size: 16px;
            opacity: 0.95;
        }

        /* Main Content */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 20px;
        }

        /* Order Info Card */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .card-body {
            padding: 20px;
        }

        /* Status Tracking */
        .status-tracking {
            margin-bottom: 30px;
        }

        .timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -28px;
            top: 0;
            width: 2px;
            height: 100%;
            background: #eee;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: -36px;
            top: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #eee;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .timeline-item.active .timeline-dot {
            background: #4CAF50;
        }

        .timeline-item.active::before {
            background: #4CAF50;
        }

        .timeline-content {
            padding-top: 0;
        }

        .timeline-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .timeline-item.active .timeline-title {
            color: #4CAF50;
        }

        .timeline-desc {
            font-size: 13px;
            color: #666;
        }

        .timeline-time {
            font-size: 12px;
            color: #999;
            margin-top: 3px;
        }

        /* Products */
        .product-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: #fafafa;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 1px solid #f0f0f0;
        }

        .product-item:last-child {
            margin-bottom: 0;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 15px;
        }

        .product-details {
            font-size: 13px;
            color: #666;
            margin-bottom: 3px;
        }

        .product-price {
            font-weight: 600;
            color: #ff6b35;
            margin-top: 8px;
        }

        /* Summary */
        .order-summary {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .summary-label {
            color: #666;
        }

        .summary-value {
            font-weight: 500;
            color: #333;
        }

        .summary-divider {
            height: 1px;
            background: #eee;
            margin: 15px 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 700;
            color: #ff6b35;
            padding-top: 15px;
        }

        .order-id-box {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            text-align: center;
        }

        .order-id-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .order-id-value {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .btn-group {
            margin-top: 20px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-bottom: 10px;
        }

        .btn-primary {
            background: #ff6b35;
            color: white;
        }

        .btn-primary:hover {
            background: #ff5722;
        }

        .btn-secondary {
            background: white;
            color: #666;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #f5f5f5;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .success-banner {
                padding: 20px;
            }

            .success-icon {
                font-size: 50px;
            }

            .success-banner h2 {
                font-size: 22px;
            }

            .product-item {
                flex-direction: column;
            }

            .product-image {
                width: 100%;
                height: 150px;
            }
        }
    </style>
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
            <a href="{{ route('pembeli.dashboard') }}" class="icon-btn">🏠</a>
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
                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                </a>
            @endif
        </div>
    </header>

    <!-- Success Banner -->
    <div class="success-banner">
        <div class="success-icon">✓</div>
        <h2>Pembayaran Berhasil!</h2>
        <p>Terima kasih atas pembelian Anda. Pesanan sedang diproses.</p>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="content-grid">
            <!-- Left Content -->
            <div>
                <!-- Status Tracking -->
                <div class="card status-tracking">
                    <div class="card-header">
                        <h3 class="card-title">📦 Status Pesanan</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item active">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Pembayaran Berhasil</div>
                                    <div class="timeline-desc">Pembayaran Anda telah dikonfirmasi</div>
                                    <div class="timeline-time">{{ now()->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                            <div class="timeline-item active">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Sedang Dikemas</div>
                                    <div class="timeline-desc">Pesanan Anda sedang disiapkan</div>
                                    <div class="timeline-time">Estimasi: 1-2 hari</div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Dikirim</div>
                                    <div class="timeline-desc">Pesanan dalam perjalanan</div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Selesai</div>
                                    <div class="timeline-desc">Pesanan telah sampai</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🎨 Produk yang Dibeli ({{ count($transaksi) }} item)</h3>
                    </div>
                    <div class="card-body">
                        @foreach($transaksi as $item)
                        <div class="product-item">
                            <img src="{{ asset('storage/karya_seni/' . $item->karya->gambar) }}" 
                                 alt="{{ $item->karya->judul }}"
                                 class="product-image">
                            <div class="product-info">
                                <div class="product-name">{{ $item->karya->judul }}</div>
                                <div class="product-details">Kategori: {{ $item->karya->kategori ?? 'Seni Rupa' }}</div>
                                <div class="product-details">Jumlah: {{ $item->jumlah }} pcs</div>
                                <div class="product-price">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div>
                <div class="order-summary">
                    <h3 class="summary-title">Ringkasan Pesanan</h3>
                    
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Biaya Admin</span>
                        <span class="summary-value">Rp 0</span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-total">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div class="order-id-box">
                        <div class="order-id-label">Order ID</div>
                        <div class="order-id-value">{{ $orderId }}</div>
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('pembeli.dashboard') }}" class="btn btn-primary">
                            Kembali ke Beranda
                        </a>
                        <button onclick="window.print()" class="btn btn-secondary">
                            📄 Cetak Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>