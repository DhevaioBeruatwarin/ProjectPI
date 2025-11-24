<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
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
            <a href="{{ route('pembeli.profil') }}" class="back-link">Kembali ke Profil</a>
        </div>
    </header>

    <main class="profile-page">
        <section class="profile-card" style="margin: 0 auto; max-width: 700px;">
            <h2 class="profile-title">Edit Profil</h2>

            @if(session('success'))
                <p style="color: green; text-align: center;">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
                <div style="color: red; margin-bottom: 15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pembeli.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field-row">
                    <label class="field-label">Username</label>
                    <input type="text" name="nama" class="input-field" value="{{ old('nama', $pembeli->nama) }}" required>
                </div>

                <div class="field-row">
                    <label class="field-label">Email</label>
                    <input type="email" name="email" class="input-field" value="{{ old('email', $pembeli->email) }}" required>
                </div>

                <div class="field-row">
                    <label class="field-label">No. Telepon</label>
                    <input type="text" name="no_hp" class="input-field" value="{{ old('no_hp', $pembeli->no_hp) }}">
                </div>

                <div class="field-row">
                    <label class="field-label">Bio Singkat</label>
                    <textarea name="bio" class="input-field" rows="3">{{ old('bio', $pembeli->bio ?? '') }}</textarea>
                </div>

                <div class="field-row">
                    <label class="field-label">Alamat</label>
                    <textarea name="alamat" class="input-field" rows="2">{{ old('alamat', $pembeli->alamat ?? '') }}</textarea>
                </div>

                <div class="field-row">
                    <label class="field-label">Foto Profil</label>
                    <input type="file" name="foto" class="input-field" accept="image/*">
                    <small class="helper-text">Maksimal 2MB. Kosongkan jika tidak ingin mengganti.</small>
                </div>

                <div style="text-align: center; margin-top: 25px;">
                    <button type="submit" class="btn-upload">Simpan Perubahan</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>