@extends('layouts.app')

@section('title', 'Edit Profil - Jogja Artsphere')

@section('content')
<style>
/* ===== RESET & BASE ===== */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    background: #f5f5f5;
    color: #111;
    line-height: 1.6;
}

/* ===== HEADER ===== */
.pembeli-header {
    background: #fff;
    border-bottom: 1px solid #e5e5e5;
    padding: 20px 48px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-left { display: flex; align-items: center; gap: 16px; }

.logo { width: 48px; height: 48px; border: solid black 1px; border-radius: 50%; }

.logo-text {
    font-size: 18px; font-weight: 800; letter-spacing: 0.5px;
    color: #111; text-transform: uppercase;
}

.header-right { display: flex; align-items: center; }

.back-link {
    color: #111; text-decoration: none; font-weight: 600; font-size: 15px;
    padding: 10px 24px; border: 1px solid #e5e5e5; border-radius: 30px;
    transition: all 0.2s ease; text-transform: uppercase; letter-spacing: 0.5px;
}

.back-link:hover { background: #111; color: #fff; border-color: #111; }

.profile-page { max-width: 800px; margin: 60px auto; padding: 0 20px; }

.profile-card { background: #fff; padding: 60px 80px; }

.profile-title {
    font-size: 42px; font-weight: 800; letter-spacing: -0.5px;
    color: #111; margin-bottom: 48px; line-height: 1.2;
}

/* ===== ALERT ===== */
.alert {
    padding: 16px 24px; margin-bottom: 32px; border-radius: 0;
    font-size: 14px; font-weight: 500;
}

.alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }

.alert-danger { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

.alert ul { margin: 0; padding: 0; list-style: none; }
.alert li { padding: 4px 0; }
.alert li::before { content: "• "; font-weight: bold; }

/* ===== FORMS ===== */
.field-row { margin-bottom: 32px; }

.field-label {
    display: block; font-size: 14px; font-weight: 600; color: #111;
    margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;
}

.input-field {
    width: 100%; padding: 16px 20px; border: 1px solid #e5e5e5; font-size: 16px;
    background: #fff; transition: all 0.2s ease;
}

.input-field:focus { outline: none; border-color: #111; }
.input-field:hover { border-color: #8d8d8d; }
textarea.input-field { resize: vertical; min-height: 100px; line-height: 1.6; }

.helper-text { margin-top: 8px; font-size: 13px; color: #757575; }

/* ===== BUTTON GROUP ===== */
.button-group { display: flex; gap: 16px; margin-top: 48px; padding-top: 32px; border-top: 1px solid #e5e5e5; }

.btn {
    flex: 1; padding: 18px 24px; border: 1px solid #111; border-radius: 30px;
    font-size: 16px; font-weight: 600; cursor: pointer; text-align: center;
    text-transform: uppercase; letter-spacing: 1px;
}

.btn-primary { background: #111; color: #fff; }
.btn-primary:hover { background: #000; transform: scale(1.01); }
.btn-outline:hover { background: #111; color: #fff; transform: scale(1.01); }
</style>

<header class="pembeli-header">
    <div class="header-left">
        <div class="logo"><img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo"></div>
        <div class="logo-text">Jogja Artsphere</div>
    </div>
    <div class="header-right">
        <a href="{{ route('pembeli.profil') }}" class="back-link">Kembali</a>
    </div>
</header>

<main class="profile-page">
    <section class="profile-card">
        <h2 class="profile-title">Edit Profil</h2>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('pembeli.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="field-row">
                <label class="field-label">Nama Lengkap</label>
                <input type="text" name="nama" class="input-field" value="{{ old('nama', $pembeli->nama) }}" disabled>
            </div>

            <div class="field-row">
                <label class="field-label">Email</label>
                <input type="email" name="email" class="input-field" value="{{ old('email', $pembeli->email) }}" disabled>
            </div>

            <div class="field-row">
                <label class="field-label">Nomor Telepon</label>
                <input type="text" name="no_hp" class="input-field" value="{{ old('no_hp', $pembeli->no_hp) }}">
            </div>

            <div class="field-row">
                <label class="field-label">Alamat Lengkap</label>
                <textarea name="alamat" class="input-field">{{ old('alamat', $pembeli->alamat) }}</textarea>
            </div>

            <div class="button-group">
                <a href="{{ route('pembeli.profil') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>

    </section>
</main>

<script>
setTimeout(() => { document.querySelectorAll('.alert').forEach(el => el.remove()) }, 5000);
</script>
@endsection