<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - Jogja Artsphere</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .payment-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .payment-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .order-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }

        .order-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .order-row:last-child {
            margin-bottom: 0;
            padding-top: 12px;
            border-top: 2px solid #ddd;
            font-weight: 600;
            font-size: 18px;
            color: #ff6b35;
        }

        .label {
            color: #666;
        }

        .value {
            color: #333;
            font-weight: 500;
        }

        .btn-pay {
            width: 100%;
            padding: 16px;
            background: #ff6b35;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-pay:hover {
            background: #ff5722;
        }

        .btn-pay:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .loading {
            display: none;
            margin-top: 20px;
        }

        .loading.active {
            display: block;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #ff6b35;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .info-text {
            color: #666;
            font-size: 13px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="payment-container">
        <div class="payment-icon">💳</div>
        <h2>Proses Pembayaran</h2>
        <p style="color: #666; margin-bottom: 20px;">Order ID: {{ $order_id }}</p>

        <div class="order-info">
            <div class="order-row">
                <span class="label">Jumlah Produk:</span>
                <span class="value">{{ count($items) }} item</span>
            </div>
            <div class="order-row">
                <span class="label">Total Pembayaran:</span>
                <span class="value total-price-large">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <button type="button" id="pay-button" class="btn-pay">
            Bayar Sekarang
        </button>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p style="margin-top: 10px; color: #666;">Memuat pembayaran...</p>
        </div>

        <p class="info-text">
            ✓ Pembayaran aman dengan Midtrans<br>
            ✓ Berbagai metode pembayaran tersedia
        </p>
    </div>

    <script>
        const payButton = document.getElementById('pay-button');
        const loading = document.getElementById('loading');
        const snapToken = '{{ $snap_token }}';
        const orderId = '{{ $order_id }}';

        payButton.addEventListener('click', function() {
            payButton.disabled = true;
            loading.classList.add('active');

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    // Redirect ke halaman sukses
                    window.location.href = '/pembeli/payment/success?order_id=' + orderId;
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
                    payButton.disabled = false;
                    loading.classList.remove('active');
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Terjadi kesalahan saat pembayaran. Silakan coba lagi.');
                    payButton.disabled = false;
                    loading.classList.remove('active');
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    alert('Anda menutup popup pembayaran sebelum menyelesaikan pembayaran');
                    payButton.disabled = false;
                    loading.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>