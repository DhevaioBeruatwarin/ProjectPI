@extends('layouts.app')

@section('title', 'Upload Karya Baru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Seniman/karya/create.css') }}">

<div class="upload-container">
    <div class="upload-header">
        <h2>Upload Karya Baru</h2>
        <p>Bagikan karya seni Anda kepada dunia</p>
    </div>

    <form action="{{ route('seniman.karya.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Judul Karya</label>
            <input type="text" name="judul" class="form-input" placeholder="Masukkan judul karya..." required>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-input form-textarea" placeholder="Ceritakan tentang karya Anda..." required></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Harga</label>
            <div class="currency-prefix">
                <input type="text" id="harga" name="harga" class="form-input" placeholder="0" required>
            </div>
            <div class="input-hint">Masukkan harga dalam Rupiah</div>
        </div>

        <div class="form-group">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-input" placeholder="Masukkan jumlah stok..." min="0" value="1" required>
            <div class="input-hint">Jumlah karya yang tersedia untuk dijual</div>
        </div>

        <div class="form-group">
            <label class="form-label">Gambar Karya</label>
            <div class="file-input-wrapper">
                <label class="file-input-label">
                    <div style="font-size: 32px; margin-bottom: 10px;">🖼️</div>
                    <div style="color: #333; font-weight: 500;">Klik untuk pilih gambar</div>
                    <div style="color: #999; font-size: 13px; margin-top: 5px;">JPG, PNG, atau JPEG</div>
                    <input type="file" name="gambar" class="file-input" accept="image/jpeg,image/png,image/jpg" required>
                </label>
                <div class="file-name" id="fileName"></div>
            </div>
        </div>

        <button type="submit" class="btn-submit">Upload Karya</button>
    </form>
</div>

<script>
    // Format harga dengan benar
    const hargaInput = document.getElementById('harga');
    let lastValue = '';

    hargaInput.addEventListener('input', function(e) {
        // Ambil hanya angka
        let value = this.value.replace(/\D/g, '');
        
        // Jika kosong, biarkan kosong
        if (value === '') {
            this.value = '';
            lastValue = '';
            return;
        }
        
        // Format dengan separator ribuan
        let formatted = new Intl.NumberFormat('id-ID').format(value);
        
        this.value = formatted;
        lastValue = formatted;
    });

    // Handle backspace dengan benar
    hargaInput.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' || e.key === 'Delete') {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.slice(0, -1);
                this.value = value ? new Intl.NumberFormat('id-ID').format(value) : '';
                e.preventDefault();
            }
        }
    });

    // Sebelum submit, ubah ke angka murni
    document.querySelector('form').addEventListener('submit', function(e) {
        let rawValue = hargaInput.value.replace(/\D/g, '');
        hargaInput.value = rawValue;
    });

    // Display file name
    document.querySelector('.file-input').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || '';
        document.getElementById('fileName').textContent = fileName ? `📁 ${fileName}` : '';
    });
</script>
@endsection