@extends('layouts.app')

@section('title', 'Edit Karya')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Seniman/karya/create.css') }}">

<div class="upload-container">
    <div class="upload-header">
        <h2>Edit Karya Seni</h2>
        <p>Perbarui detail karya Anda di sini</p>
    </div>

    <form action="{{ route('seniman.karya.update', $karya->kode_seni) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Judul Karya</label>
            <input type="text" name="judul" class="form-input" value="{{ $karya->nama_karya }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-input form-textarea" required>{{ $karya->deskripsi }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Harga</label>
            <div class="currency-prefix">
                <input type="text" id="harga" name="harga" class="form-input" value="{{ number_format($karya->harga, 0, ',', '.') }}" required>
            </div>
            <div class="input-hint">Masukkan harga dalam Rupiah</div>
        </div>

        <div class="form-group">
            <label class="form-label">Gambar (Opsional)</label>
            <div class="file-input-wrapper">
                <label class="file-input-label">
                    <div style="font-size: 32px; margin-bottom: 10px;">🖼️</div>
                    <div style="color: #333; font-weight: 500;">Klik untuk ubah gambar</div>
                    <div style="color: #999; font-size: 13px; margin-top: 5px;">JPG, PNG, atau JPEG</div>
                    <input type="file" name="gambar" class="file-input" accept="image/jpeg,image/png,image/jpg">
                </label>
                <div class="file-name" id="fileName"></div>
            </div>

            <div class="preview-wrapper mt-3" style="text-align:center;">
                <img src="{{ asset('storage/karya_seni/' . $karya->gambar) }}" alt="Preview Karya" width="200" style="border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                <p style="color:#666; font-size:13px; margin-top:5px;">Gambar saat ini</p>
            </div>
        </div>
        <div class="form-group">
    <label class="form-label">Stok</label>
    <input type="number" name="stok" class="form-input" value="{{ old('stok', $karya->stok) }}" min="0" required>
    <div class="input-hint">Jumlah karya yang tersedia untuk dijual</div>
</div>

        <button type="submit" class="btn-submit">Simpan Perubahan</button>
    </form>
</div>

<script>
    const hargaInput = document.getElementById('harga');
    hargaInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = value ? new Intl.NumberFormat('id-ID').format(value) : '';
    });

    document.querySelector('form').addEventListener('submit', function() {
        hargaInput.value = hargaInput.value.replace(/\D/g, '');
    });

    // Tampilkan nama file
    document.querySelector('.file-input').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || '';
        document.getElementById('fileName').textContent = fileName ? `📁 ${fileName}` : '';
    });
</script>
@endsection
