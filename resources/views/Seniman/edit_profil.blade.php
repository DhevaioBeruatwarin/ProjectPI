<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Seniman - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/editprofile.css') }}">
</head>
<body>

<header class="seniman-header">
    <div class="header-left">
        <div class="logo"><img src="{{ asset('assets/logo.png') }}" class="logo"></div>
        <div class="logo-text">Jogja Artsphere</div>
    </div>
    <div class="header-right">
        <a href="{{ route('seniman.profil') }}" class="back-link">Kembali</a>
    </div>
</header>

<main class="profile-page">
    <section class="profile-card">
        <h2 class="profile-title">Edit Profil</h2>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('seniman.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="field-row">
                <label class="field-label">Nama Lengkap</label>
                <input type="text" name="nama" class="input-field" value="{{ old('nama', $seniman->nama) }}" disabled>
            </div>

            <div class="field-row">
                <label class="field-label">Email</label>
                <input type="email" name="email" class="input-field" value="{{ old('email', $seniman->email) }}" disabled>
            </div>

            <div class="field-row">
                <label class="field-label">Nomor Telepon</label>
                <input type="text" name="no_hp" class="input-field" value="{{ old('no_hp', $seniman->no_hp) }}">
            </div>

            <div class="field-row">
                <label class="field-label">Bidang Seni</label>
                <input type="text" name="bidang" class="input-field" value="{{ old('bidang', $seniman->bidang) }}" placeholder="Lukis, Patung, Batik, dll">
            </div>

            <div class="field-row">
                <label class="field-label">Bio</label>
                <textarea name="bio" class="input-field">{{ old('bio', $seniman->bio) }}</textarea>
            </div>

            <div class="field-row">
                <label class="field-label">Alamat</label>
                <textarea name="alamat" class="input-field">{{ old('alamat', $seniman->alamat) }}</textarea>
            </div>


            <div class="button-group">
                <a href="{{ route('seniman.profil') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </form>

    </section>
</main>

<script>
setTimeout( () => document.querySelectorAll('.alert').forEach( e => e.remove() ), 5000);
</script>
</body>
</html>