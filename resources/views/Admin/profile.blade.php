@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')

<div class="main-content flex-fill">
    <h3 class="mb-4">Profil Admin</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-start">
        
        <div class="col-md-8">
            <div class="mb-3"><strong>Nama:</strong> {{ $admin->nama_admin ?? $admin->nama }}</div>
            <div class="mb-3"><strong>Email:</strong> {{ $admin->email }}</div>
            <div class="mb-3"><strong>No. Telepon:</strong> {{ $admin->no_hp ?? '-' }}</div>
            <div class="mb-3"><strong>Jabatan:</strong> {{ $admin->jabatan ?? 'Administrator' }}</div>
            <div class="mb-3"><strong>Alamat:</strong> {{ $admin->alamat ?? '-' }}</div>
        </div>

        <div class="profile-photo col-md-4 text-center">
            @if($admin->foto)
                <img src="{{ asset('storage/foto_admin/' . $admin->foto) }}" width="200" height="200" alt="Foto Admin" style="border-radius:50%;object-fit:cover;">
            @else
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" width="200" height="200" alt="Default Avatar" style="border-radius:50%;">
            @endif

            <form action="{{ route('admin.foto.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="foto" id="foto" accept="image/*" hidden onchange="this.form.submit()">
                <label for="foto" class="btn btn-warning mt-3">Pilih Gambar</label>
            </form>
        </div>
    </div>
</div>

@endsection
