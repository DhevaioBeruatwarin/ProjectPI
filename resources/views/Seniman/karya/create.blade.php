@extends('layouts.app')

@section('title', 'Upload Karya Baru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Seniman/karya/create.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="upload-container">
    <div class="upload-header">
        <h2>Upload Karya</h2>
        <p>Bagikan karya seni terbaik Anda kepada dunia</p>
    </div>

    <form action="{{ route('seniman.karya.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Judul Karya -->
        <div class="form-group">
            <label class="form-label">Judul Karya</label>
            <input type="text" 
                   name="judul" 
                   class="form-input" 
                   placeholder="Masukkan judul karya Anda" 
                   value="{{ old('judul') }}"
                   required>
            @error('judul')
                <div class="input-hint" style="color: #d32f2f;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" 
                      class="form-input form-textarea" 
                      placeholder="Ceritakan inspirasi dan detail karya Anda..."
                      required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="input-hint" style="color: #d32f2f;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Harga -->
        <div class="form-group">
            <label class="form-label">Harga</label>
            <div class="currency-prefix">
                <input type="text" 
                       id="harga" 
                       name="harga" 
                       class="form-input" 
                       placeholder="0"
                       value="{{ old('harga') }}"
                       required>
            </div>
            <div class="input-hint">Masukkan harga dalam Rupiah</div>
            @error('harga')
                <div class="input-hint" style="color: #d32f2f;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Stok -->
        <div class="form-group">
            <label class="form-label">Stok</label>
            <input type="number" 
                   name="stok" 
                   class="form-input" 
                   placeholder="Jumlah tersedia" 
                   min="0" 
                   value="{{ old('stok', 1) }}"
                   required>
            <div class="input-hint">Jumlah karya yang tersedia untuk dijual</div>
            @error('stok')
                <div class="input-hint" style="color: #d32f2f;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Gambar -->
        <div class="form-group">
            <label class="form-label">Gambar Karya</label>
            <div class="file-input-wrapper">
                <label class="file-input-label">
                    <div>🖼️</div>
                    <div>Pilih Gambar</div>
                    <div>JPG, PNG, atau JPEG (Max 2MB)</div>
                    <input type="file" 
                           name="gambar" 
                           class="file-input" 
                           accept="image/jpeg,image/png,image/jpg" 
                           required>
                </label>
                <div class="file-name" id="fileName"></div>
            </div>
            @error('gambar')
                <div class="input-hint" style="color: #d32f2f; text-align: center; margin-top: 12px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-submit">Upload Karya</button>
    </form>
</div>

<script>
    // Format harga dengan separator ribuan
    const hargaInput = document.getElementById('harga');
    
    hargaInput.addEventListener('input', function(e) {
        // Ambil hanya angka
        let value = this.value.replace(/\D/g, '');
        
        // Jika kosong, biarkan kosong
        if (value === '') {
            this.value = '';
            return;
        }
        
        // Format dengan separator ribuan
        let formatted = new Intl.NumberFormat('id-ID').format(value);
        this.value = formatted;
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

    // Display file name dengan validasi
    document.querySelector('.file-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileNameDisplay = document.getElementById('fileName');
        
        if (file) {
            // Validasi ukuran file (max 2MB)
            if (file.size > 2048000) {
                alert('Ukuran file maksimal 2MB');
                this.value = '';
                fileNameDisplay.textContent = '';
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file harus JPG, PNG, atau JPEG');
                this.value = '';
                fileNameDisplay.textContent = '';
                return;
            }
            
            fileNameDisplay.textContent = `📁 ${file.name}`;
            fileNameDisplay.style.color = '#111';
            fileNameDisplay.style.fontWeight = '500';
        } else {
            fileNameDisplay.textContent = '';
        }
    });

    // Auto focus on first input
    window.addEventListener('load', function() {
        document.querySelector('input[name="judul"]').focus();
    });
</script>
@endsection