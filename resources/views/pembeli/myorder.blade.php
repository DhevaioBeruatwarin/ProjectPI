<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/myorder.css') }}">
    <title>Pesanan Saya - Jogja Artsphere</title>
   
</head>
<body>
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
           
            <h1>Pesanan Saya</h1>
            <p>Kelola dan lacak semua pesanan Anda</p>
        </div>
    </div>

    <div class="container">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="tab-btn active" data-filter="all">
                Semua Pesanan
            </button>
            <button class="tab-btn" data-filter="success">
                Berhasil
            </button>
            <button class="tab-btn" data-filter="pending">
                Pending
            </button>
            <button class="tab-btn" data-filter="failed">
                Gagal
            </button>
            
        </div>

        <!-- Orders List -->
        <div id="ordersList">
            @forelse($orders->groupBy('order_id') as $orderId => $transaksi)
                <div class="order-card" data-status="{{ $transaksi->first()->status }}">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div>
                            <div class="order-id">
                                <strong>Order ID:</strong> {{ $orderId }}
                            </div>
                            <div class="order-date">
                                 {{ $transaksi->first()->tanggal_jual->format('d M Y, H:i') }}
                            </div>
                        </div>
                        
                        <div>
                            @if($transaksi->first()->status == 'success')
                                <span class="status-badge status-success">Berhasil</span>
                            @elseif($transaksi->first()->status == 'pending')
                                <span class="status-badge status-pending">Pending</span>
                            @else
                                <span class="status-badge status-failed">Gagal</span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Items -->
                    <div class="product-items">
                        @foreach($transaksi as $item)
                            <div class="product-item">
                                <img 
                                    src="{{ asset('storage/karya_seni/' . $item->karya->gambar) }}" 
                                    alt="{{ $item->karya->judul }}"
                                    class="product-image"
                                    onerror="this.src='https://via.placeholder.com/80x80/667eea/ffffff?text=No+Image'"
                                />
                                
                                <div class="product-info">
                                    <div class="product-name">{{ $item->karya->judul ?? 'Produk Seni' }}</div>
                                    <div class="product-quantity">
                                        Jumlah: {{ $item->jumlah }} × Rp {{ number_format($item->karya->harga ?? 0, 0, ',', '.') }}
                                    </div>
                                    <div class="product-price">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="order-footer">
                        <div class="order-total">
                            Total: <strong>Rp {{ number_format($transaksi->sum('harga'), 0, ',', '.') }}</strong>
                        </div>
                        
                        <div class="order-actions">
                            <a href="{{ route('pembeli.payment.success', ['order_id' => $orderId]) }}" 
                               class="btn btn-outline btn-sm">
                                📄 Detail
                            </a>

                             <a href="{{ route('pembeli.dashboard') }}" class="back-btn">
                Kembali ke Beranda
            </a>
                            
                            @if($transaksi->first()->status == 'success')
                                <button class="btn btn-primary btn-sm" onclick="alert('Fitur review akan segera hadir!')">
                                    ⭐ Beri Ulasan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Belum Ada Pesanan</h3>
                    <p>Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                    <a href="{{ route('pembeli.dashboard') }}" class="btn btn-primary">
                        🛍️ Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const orderCards = document.querySelectorAll('.order-card');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;

                    // Filter orders
                    orderCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = 'block';
                        } else {
                            const status = card.dataset.status;
                            card.style.display = status === filter ? 'block' : 'none';
                        }
                    });

                    // Check if any orders visible
                    const visibleCards = Array.from(orderCards).filter(card => 
                        card.style.display !== 'none'
                    );

                    if (visibleCards.length === 0) {
                        const emptyMessage = document.createElement('div');
                        emptyMessage.className = 'empty-state';
                        emptyMessage.id = 'emptyFilterMessage';
                        emptyMessage.innerHTML = `
                            <div class="empty-icon">🔍</div>
                            <h3>Tidak Ada Pesanan</h3>
                            <p>Tidak ada pesanan dengan status ini.</p>
                        `;
                        
                        const existingEmpty = document.getElementById('emptyFilterMessage');
                        if (!existingEmpty) {
                            document.getElementById('ordersList').appendChild(emptyMessage);
                        }
                    } else {
                        const existingEmpty = document.getElementById('emptyFilterMessage');
                        if (existingEmpty) {
                            existingEmpty.remove();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>