<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #fafafa;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ===== SIDEBAR (MATCHING PEMBELI STYLE) ===== */

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e5e5e5;
            padding: 30px 25px;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar h5 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 35px;
        }

        .sidebar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 18px;
        }

        .sidebar ul li a,
        .sidebar ul li form button {
            display: block;
            width: 100%;
            text-align: left;
            text-decoration: none;
            color: #333;
            font-size: 15px;
            font-weight: 500;
            background: none;
            border: none;
            padding: 5px 0;
        }

        .sidebar ul li a.active {
            color: #7a5af5;
        }

        .sidebar ul li a:hover,
        .sidebar ul li form button:hover {
            color: #7a5af5;
        }

        /* ===== CONTENT ===== */

        .content {
            margin-left: 260px; /* Biar rata seperti pembeli */
            padding: 40px 50px;
            background: #fafafa;
            min-height: 100vh;
        }

        .page-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.06);
        }

        .page-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            background: #f3f3f3;
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-weight: 600;
        }

        .admin-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .admin-table img {
            width: 90px;
            height: 80px;
            object-fit: cover;
            border-radius: 7px;
            border: 1px solid #ccc;
        }

        .btn-delete {
            background: #d9534f;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
        }

        .btn-delete:hover {
            background: #c9302c;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/Seniman/profile.css') }}">

</head>

<body>
<div class="row g-0">
        
    <!-- SIDEBAR -->
    <div class="col-md-3 sidebar">
        <h5>Profil Admin</h3>
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.profil') }}">Profil</a></li>
            <li><a href="{{ route('admin.seniman.index') }}">Kelola Seniman</a></li>
            <li><a href="{{ route('admin.karya.index') }}">Kelola Karya</a></li>
            <li><a href="{{ route('admin.pembeli.index') }}">Kelola Pembeli</a></li>
            <li><a href="{{ route('admin.monitoring.sistem') }}">Monitoring Sistem</a></li>
            <li><a href="{{ route('admin.monitoring.keuangan') }}">Monitoring Keuangan</a></li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit">Keluar</button>
                </form>
            </li>
        </ul>
    </div>

    
    <!-- CONTENT -->
    <div class="col-md-9 content">
        @yield('content')
    </div>

    
</div>

</body>

</html>

