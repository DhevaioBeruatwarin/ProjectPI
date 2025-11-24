<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja - Jogja Artsphere</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">

       <style>
        /* Toast Notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            min-width: 300px;
        }
        
        .toast.error {
            background: #f44336;
        }
        
        .toast-icon {
            font-size: 24px;
        }
        
        .toast-content {
            flex: 1;
        }
        
        .toast-title {
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .toast-message {
            font-size: 14px;
            opacity: 0.95;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s;
        }
        
        .toast-close:hover {
            background: rgba(255,255,255,0.2);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        
        .toast.hiding {
            animation: slideOut 0.3s ease-out forwards;
        }
        
        /* Button Loading State */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }
        
        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
        
        /* Cart Icon Badge */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
        }
        
        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
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
        <form action="{{ route('dashboard.seniman.search') }}" method="GET" style="display:inline;">
            <input type="text" name="query" class="search-bar" placeholder="Cari karya..." value="{{ request('query') }}">
        </form>

        <div class="header-right">
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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="{{ route('pembeli.dashboard') }}">Beranda</a> 
            <span>/</span> 
            <span class="active">Keranjang Belanja</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container main-content">
        @if(count($keranjang) > 0)
            <div class="cart-layout">
                <!-- Left Side - Cart Items -->
                <div class="cart-items-section">
                    <!-- Cart Header -->
                    <div class="cart-header-box">
                        <div class="select-all">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            <label for="selectAll">Pilih Semua ({{ count($keranjang) }} Produk)</label>
                        </div>
                        <div class="header-labels">
                            <span>Harga Satuan</span>
                            <span>Jumlah</span>
                            <span>Total Harga</span>
                            <span>Aksi</span>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    @foreach($keranjang as $item)
                    <div class="cart-item-card" data-item-id="{{ $item->id_keranjang }}">
                        <div class="item-main">
                            <input type="checkbox" 
                                   class="item-checkbox" 
                                   data-id="{{ $item->id_keranjang }}" 
                                   data-price="{{ $item->karya->harga }}"
                                   data-qty="{{ $item->jumlah }}"
                                   onchange="updateTotal()">
                            
                            <img src="{{ asset('storage/karya_seni/'.$item->karya->gambar) }}" 
                                 alt="{{ $item->karya->judul }}" 
                                 class="item-image">
                            
                            <div class="item-info">
                                <h3 class="item-title">{{ $item->karya->judul }}</h3>
                                <p class="item-category">Kategori: {{ $item->karya->kategori ?? 'Seni Rupa' }}</p>
                                <div class="item-tags">
                                    <span class="tag">Original</span>
                                    @if($item->karya->stok < 5)
                                        <span class="tag limited">Stok Terbatas</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="item-details-row">
                            <div class="item-price">
                                <span class="price-label">Rp {{ number_format($item->karya->harga, 0, ',', '.') }}</span>
                            </div>

                            <div class="item-quantity">
                                <button class="qty-btn" onclick="updateQty({{ $item->id_keranjang }}, -1)">−</button>
                                <input type="text" value="{{ $item->jumlah }}" readonly class="qty-input" id="qty-{{ $item->id_keranjang }}">
                                <button class="qty-btn" onclick="updateQty({{ $item->id_keranjang }}, 1)">+</button>
                            </div>

                            <div class="item-total">
                                <span class="total-price" id="total-{{ $item->id_keranjang }}">Rp {{ number_format($item->karya->harga * $item->jumlah, 0, ',', '.') }}</span>
                            </div>

                            <div class="item-actions">
                                <form action="{{ route('keranjang.hapus', $item->id_keranjang) }}" method="POST" 
                                      onsubmit="return confirm('Hapus produk ini?')" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Bulk Actions -->
                    <div class="bulk-actions">
                        <div class="bulk-right">
                            <a href="{{ route('pembeli.dashboard') }}" class="btn-continue-shopping">Lanjut Belanja</a>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Summary -->
                <div class="cart-summary-section">
                    <div class="summary-card">
                        <h3 class="summary-title">Ringkasan Belanja</h3>
                        
                        <div class="summary-row">
                            <span>Total Harga (<span id="selectedCount">0</span> Produk)</span>
                            <span id="subtotal">Rp 0</span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-total">
                            <span>Total</span>
                            <div class="total-price-large">
                                <span id="grandTotal">Rp 0</span>
                            </div>
                        </div>

                        <button class="btn-checkout" onclick="proceedCheckout()">
                            Beli (<span id="checkoutCount">0</span>)
                        </button>

                        <div class="summary-benefits">
                            <div class="benefit-item">
                                ✓ 100% Original
                            </div>
                            <div class="benefit-item">
                                ✓ Garansi Keaslian
                            </div>
                            <div class="benefit-item">
                                ✓ Pembayaran Aman
                            </div>
                        </div>
                    </div>

                    <!-- Promo Card -->
                    <div class="promo-card">
                        <div class="promo-icon">🎁</div>
                        <div class="promo-content">
                            <h4>Voucher Tersedia</h4>
                            <p>Dapatkan diskon hingga 50%</p>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- Empty Cart -->
            <div class="empty-cart">
                <div class="empty-icon">🛒</div>
                <h2>Wah, keranjang belanjamu kosong</h2>
                <p>Yuk, isi dengan karya seni pilihan</p>
                <a href="{{ route('pembeli.dashboard') }}" class="btn-empty-shop">Mulai Belanja</a>
            </div>
        @endif
    </div>

    <script>
        let selectedItems = new Set();

      function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll').checked;
            
            // Clear selected items jika uncheck
            if (!selectAll) {
                selectedItems.clear();
            }
            
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.checked = selectAll;
                if (selectAll) {
                    selectedItems.add(checkbox.dataset.id);
                }
            });
            
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            let count = 0;
            
            document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                const price = parseFloat(checkbox.dataset.price);
                const qty = parseInt(checkbox.dataset.qty);
                total += price * qty;
                count++;
                selectedItems.add(checkbox.dataset.id);
            });

            document.querySelectorAll('.item-checkbox:not(:checked)').forEach(checkbox => {
                selectedItems.delete(checkbox.dataset.id);
            });
            
            document.getElementById('subtotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('selectedCount').textContent = count;
            document.getElementById('checkoutCount').textContent = count;
        }

        function updateQty(id, change) {
            const qtyInput = document.getElementById(`qty-${id}`);
            const currentQty = parseInt(qtyInput.value);
            const newQty = currentQty + change;
            
            if (newQty < 1) {
                alert('Jumlah minimal adalah 1');
                return;
            }

            // Disable buttons sementara untuk mencegah double click
            const buttons = document.querySelectorAll(`[onclick*="updateQty(${id}"]`);
            buttons.forEach(btn => btn.disabled = true);

            fetch(`/pembeli/keranjang/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ jumlah: newQty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update quantity input
                    qtyInput.value = newQty;
                    
                    // Update total harga untuk item ini
                    const totalElement = document.getElementById(`total-${id}`);
                    totalElement.textContent = 'Rp ' + data.new_subtotal.toLocaleString('id-ID');
                    
                    // Update data-qty di checkbox
                    const checkbox = document.querySelector(`.item-checkbox[data-id="${id}"]`);
                    if (checkbox) {
                        checkbox.dataset.qty = newQty;
                    }
                    
                    // Update ringkasan jika item ter-check
                    updateTotal();
                } else {
                    alert(data.message || 'Gagal mengubah jumlah');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            })
            .finally(() => {
                // Enable buttons kembali
                buttons.forEach(btn => btn.disabled = false);
            });
        }

        function deleteSelected() {
            if (selectedItems.size === 0) {
                alert('Pilih produk yang ingin dihapus');
                return;
            }
            
            if (confirm(`Hapus ${selectedItems.size} produk dari keranjang?`)) {
                fetch('/pembeli/keranjang/hapus-bulk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: Array.from(selectedItems) })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Produk berhasil dihapus');
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal menghapus produk');
                });
            }
        }

     function proceedCheckout() {
    if (selectedItems.size === 0) {
        alert("Pilih dulu barang yang mau dibeli");
        return;
    }

    const ids = Array.from(selectedItems);

    fetch("{{ route('keranjang.checkout') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ items: ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert("Gagal melanjutkan checkout");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan. Coba lagi.");
    });
}

    </script>
</body>
</html>