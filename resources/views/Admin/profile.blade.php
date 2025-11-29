<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #faf9f6;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            background-color: #2e2b28;
            min-height: 100vh;
            color: #fff;
            padding: 25px 15px;
        }
        .sidebar h5 {
            margin-bottom: 20px;
            color: #f8c471;
        }
        .sidebar ul {
            list-style: none;
            padding-left: 0;
        }
        .sidebar ul li {
            margin-bottom: 15px;
        }
        .sidebar ul li a,
        .sidebar ul li form button {
            color: #fff;
            text-decoration: none;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active,
        .sidebar ul li form button:hover {
            color: #f8c471;
            text-decoration: underline;
        }
        .main-content {
            padding: 40px;
            flex: 1;
        }
        .profile-photo img {
            border-radius: 50%;
            object-fit: cover;
        }
        .btn-upload {
            background-color: #f8c471;
            color: #000;
            padding: 6px 15px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 10px;
            cursor: pointer;
        }
        footer {
            background-color: #2e2b28;
            color: #ddd;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar col-md-3">
        <h5>Admin Dashboard</h5>
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.profil') }}" class="active">Profil</a></li>
            <li><a href="{{ route('admin.seniman.index') }}">Kelola Seniman</a></li>
            <li><a href="{{ route('admin.karya.index') }}">Kelola Karya</a></li>
            <li><a href="{{ route('admin.pembeli.index') }}">Kelola Pembeli</a></li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin logout?')">
                    @csrf
                    <button type="submit">Keluar</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
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
                    <img src="{{ asset('storage/foto_admin/' . $admin->foto) }}" width="200" height="200" alt="Foto Admin">
                @else
                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" width="200" height="200" alt="Default Avatar">
                @endif

                <form action="{{ route('admin.foto.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="foto" id="foto" accept="image/*" hidden onchange="this.form.submit()">
                    <label for="foto" class="btn-upload">Pilih Gambar</label>
                </form>
            </div>
        </div>
    </div>
</div>

<footer>
    © 2025 Jogja Artsphere Admin Panel
</footer>

</body>
</html>
