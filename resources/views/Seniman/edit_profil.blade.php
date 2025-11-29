<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Seniman</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/editprofile.css') }}">
</head>
<body>
    @include('components.back-button')
    <header class="seniman-header">
        <div class="header-left">
            <div class="logo"></div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <div class="header-right">
            <a href="{{ route('seniman.profil') }}" class="back-link">Kembali ke Profil</a>
        </div>
    </header>

    <main class="profile-page">
        <section class="profile-card" style="margin: 0 auto; max-width: 760px;">
            <h2 class="profile-title">Edit Profil Seniman</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seniman.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field-row">
                    <label class="field-label">Nama Toko/Username</label>
                    <input type="text"
                           name="nama"
                           class="input-field"
                           value="{{ old('nama', $seniman->nama) }}"
                           required>
                </div>

                <div class="field-row">
                    <label class="field-label">Email</label>
                    <input type="email"
                           name="email"
                           class="input-field"
                           value="{{ old('email', $seniman->email) }}"
                           required>
                </div>

                <div class="field-row">
                    <label class="field-label">No. Telepon</label>
                    <input type="text"
                           name="no_hp"
                           class="input-field"
                           value="{{ old('no_hp', $seniman->no_hp) }}"
                           placeholder="Contoh: 081234567890">
                </div>

                <div class="field-row">
                    <label class="field-label">Bidang Seni</label>
                    <input type="text"
                           name="bidang"
                           class="input-field"
                           value="{{ old('bidang', $seniman->bidang) }}"
                           placeholder="Contoh: Lukis cat minyak, Batik, dll">
                </div>

                <div class="field-row">
                    <label class="field-label">Bio Singkat</label>
                    <textarea name="bio"
                              class="input-field"
                              rows="3"
                              placeholder="Ceritakan tentang karya dan perjalanan seni Anda">{{ old('bio', $seniman->bio) }}</textarea>
                </div>

                <div class="field-row">
                    <label class="field-label">Alamat Workshop</label>
                    <textarea name="alamat"
                              class="input-field"
                              rows="3"
                              placeholder="Alamat lengkap untuk pengiriman atau kunjungan">{{ old('alamat', $seniman->alamat) }}</textarea>
                </div>

                <div class="field-row">
                    <label class="field-label">Foto Profil</label>
                    <input type="file" name="foto" class="input-field" accept="image/*">
                    <small class="helper-text">Format jpg/png, maksimal 2MB.</small>
                </div>

                <div class="button-group">
                    <a href="{{ route('seniman.profil') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>