<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/Seniman/karya/checkout.css') }}">
    <title>Konfirmasi Pembelian - Jogja Artsphere</title>

    <!-- Midtrans snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div>
            <div class="spinner"></div>
            <div>Memproses pembayaran...</div>
        </div>
    </div>

    <div class="container">
        <h2>Konfirmasi Pembelian</h2>

        <div class="checkout-layout">
            <!-- Left Column: Address & Products -->
            <div class="checkout-left">
                <!-- Address Section -->
                <div class="address-section">
                    <h4>Alamat Pengiriman</h4>
                    <div>{{ $pembeli->nama }} - {{ $pembeli->no_hp }}</div>
                    <div>{{ $pembeli->alamat }}</div>
                </div>

                <!-- Products Section -->
                <div class="product-section">
                    <h4>Produk ({{ count($produk) }} item)</h4>
                    @foreach($produk as $item)
                        <div class="product-item">
                            <img src="{{ asset('storage/karya_seni/' . $item->karya->gambar) }}" alt="{{ $item->karya->judul }}">
                            <div class="product-details">
                                <div class="product-name">{{ $item->karya->judul }}</div>
                                <div class="product-quantity">Jumlah: {{ $item->jumlah }} × Rp {{ number_format($item->karya->harga,0,',','.') }}</div>
                                <div class="product-price">Rp {{ number_format($item->karya->harga * $item->jumlah,0,',','.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Summary & Buttons -->
            <div class="checkout-right">
                <div class="summary-section">
                    <h4>Ringkasan Pesanan</h4>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($total,0,',','.') }}</span>
                    </div>
                    
                    <div class="summary-total">
                        <span class="summary-total-label">Total</span>
                        <span class="summary-total-value">Rp {{ number_format($total,0,',','.') }}</span>
                    </div>

                    <!-- Form -->
                    <form id="checkoutForm" method="POST" action="{{ route('pembeli.checkout.bayar') }}">
                        @csrf
                        @foreach($ids as $id)
                            <input type="hidden" name="ids[]" value="{{ $id }}" />
                        @endforeach

                        <div class="button-container">
                            <button type="submit" id="btnBayar" class="btn btn-primary">
                                Bayar Sekarang
                            </button>
                            <a href="{{ route('keranjang.index') }}" class="btn btn-secondary">
                                Kembali ke Keranjang
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const form = document.getElementById('checkoutForm');
        const btn = document.getElementById('btnBayar');
        const overlay = document.getElementById('loadingOverlay');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.addEventListener('submit', function(e){
            e.preventDefault();

            const ids = Array.from(form.querySelectorAll('input[name="ids[]"]')).map(i => i.value);

            if (!ids.length) {
                alert('Tidak ada produk yang dipilih');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Memproses...';
            overlay.classList.add('active');

            fetch("{{ route('pembeli.checkout.bayar') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(async res => {
                const contentType = res.headers.get('content-type') || '';
                let payload = null;
                if (contentType.includes('application/json')) payload = await res.json();
                else payload = { success:false, message: await res.text() };

                if (!res.ok) {
                    throw payload;
                }
                return payload;
            })
            .then(data => {
                if (!data.success) throw data;

                const snapToken = data.snap_token;
                const orderId = data.order_id;

                if (!snapToken) throw { message: 'Snap token tidak ditemukan dari server' };

                snap.pay(snapToken, {
                    onSuccess: function(result){
                        window.location.href = '/pembeli/payment/success?order_id=' + encodeURIComponent(orderId);
                    },
                    onPending: function(result){
                        alert('Pembayaran pending. Selesaikan pembayaran sesuai instruksi.');
                        window.location.href = '/pembeli/payment/success?order_id=' + encodeURIComponent(orderId);
                    },
                    onError: function(result){
                        alert('Terjadi kesalahan saat membuka metode pembayaran. Coba lagi.');
                        btn.disabled = false;
                        btn.textContent = 'Bayar Sekarang';
                        overlay.classList.remove('active');
                    },
                    onClose: function(){
                        btn.disabled = false;
                        btn.textContent = 'Bayar Sekarang';
                        overlay.classList.remove('active');
                        alert('Popup pembayaran ditutup. Silakan coba lagi jika ingin membayar.');
                    }
                });
            })
            .catch(err => {
                console.error('Payment error', err);
                const msg = (err && err.message) ? err.message : 'Gagal membuat pembayaran';
                alert(msg);
                btn.disabled = false;
                btn.textContent = 'Bayar Sekarang';
                overlay.classList.remove('active');
            });
        });
    })();
    </script>
</body>
</html>