<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8f8f8;
        }

        /* SIDEBAR */
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

        /* CONTENT */
        .content {
            padding: 40px;
            background: white;
            min-height: 100vh;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 3px 8px rgba(0,0,0,0.15);
        }
        .main-content {
    padding: 40px;
    background: #faf9f6;
    min-height: 100vh;
}

.page-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 25px;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.admin-table th {
    background: #f3f3f3;
    padding: 14px;
    font-weight: bold;
    border-bottom: 1px solid #ddd;
}

.admin-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.img-preview,
.karya-img,
.admin-table td img {
    width: 100vhpx !important;
    height: 80px !important;
    object-fit: cover;
    border-radius: 8px;
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

</head>
<body>

<div class="row g-0">
    
    <!-- SIDEBAR -->
    <div class="col-md-3 sidebar">
        <h5>Admin Dashboard</h5>
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.profil') }}">Profil</a></li>
            <li><a href="{{ route('admin.seniman.index') }}">Kelola Seniman</a></li>
            <li><a href="{{ route('admin.karya.index') }}">Kelola Karya</a></li>
            <li><a href="{{ route('admin.pembeli.index') }}">Kelola Pembeli</a></li>
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