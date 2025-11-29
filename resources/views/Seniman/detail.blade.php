<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $karya->nama_karya }} - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/detail_karya.css') }}">
</head>
<body>
  
<header style="background-color:#231105; color:#fff; display:flex; align-items:center; justify-content:space-between; padding:10px 20px;">
    <div style="display:flex; align-items:center; gap:10px;">
        <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width:45px; height:45px;">
        <div style="font-weight:bold; font-size:18px;">JOGJA ARTSPHERE</div>
    </div>

    @php
        $seniman = Auth::guard('seniman')->user();
        $fotoPath = $seniman && $seniman->foto 
            ? asset('storage/foto_seniman/' . $seniman->foto)
            : asset('assets/defaultprofile.png');
    @endphp

    <a href="{{ route('seniman.profil') }}">
        <img src="{{ $fotoPath }}" alt="Profil" 
             style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
    </a>
</header>

<div class="pdp-container">
    <div class="pdp-left">
        @if($karya->gambar)
            <img src="{{ asset('storage/karya_seni/' . $karya->gambar) }}" alt="{{ $karya->nama_karya }}">
        @else
            <div class="no-image">No Image Available</div>
        @endif
    </div>

    <div class="pdp-right">
        <h1>{{ $karya->nama_karya }}</h1>
        <div class="price">Rp{{ number_format($karya->harga,0,',','.') }}</div>
        <p class="author">By {{ $karya->seniman->nama ?? 'Unknown' }}</p>
        <p class="description">{{ $karya->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

        <!-- Tidak ada tombol beli/keranjang -->
    </div>
</div>
 <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Jogja Artsphere</h3>
                <p>Jl. Malioboro No.123, Yogyakarta 55271</p>
                <p>Telp: (0274) 123-4567</p>
                <p>Email: info@jogja-artsphere.com</p>
            </div>
            <div class="footer-section">
                <h3>Bantuan</h3>
                <div class="footer-links">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                    <a href="#">Customer Service</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p><strong>Jogja Artsphere</strong> - Satu Jika Untuk Koleksi Karya Lokal.</p>
        </div>
    </footer>

    <script>
        function changeImage(thumb) {
            document.getElementById('mainImg').src = thumb.src;
        }
    </script>

</body>
</html>
