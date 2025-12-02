<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Monitor Penjualan - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .monitor-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .page-header h1 {
            margin: 0;
            font-size: 2em;
        }

        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .value {
            font-size: 2em;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .stat-card .label {
            color: #999;
            font-size: 0.85em;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            color: #666;
            font-size: 0.9em;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95em;
        }

        .btn-filter {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn-filter:hover {
            background: #5568d3;
        }

        .btn-reset {
            background: #e0e0e0;
            color: #666;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }

        .btn-reset:hover {
            background: #d0d0d0;
        }

        .transaksi-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .table-header h2 {
            margin: 0;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #666;
            border-bottom: 2px solid #e9ecef;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
            display: inline-block;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-expired {
            background: #e2e3e5;
            color: #383d41;
        }

        .top-karya-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .top-karya-section h2 {
            margin: 0 0 20px 0;
            color: #333;
        }

        .top-karya-list {
            display: grid;
            gap: 15px;
        }

        .top-karya-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            gap: 15px;
        }

        .top-karya-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .top-karya-info {
            flex: 1;
        }

        .top-karya-info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .top-karya-info p {
            margin: 0;
            color: #666;
            font-size: 0.9em;
        }

        .top-karya-stats {
            text-align: right;
        }

        .top-karya-stats .value {
            font-size: 1.2em;
            font-weight: bold;
            color: #667eea;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #667eea;
        }

        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #5568d3;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state h3 {
            margin: 20px 0 10px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" 
                     alt="Jogja Artsphere Logo" 
                     style="width: 45px; height: 45px; object-fit: contain;">
            </div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <div class="header-right">
            @if(\Illuminate\Support\Facades\Auth::guard('seniman')->check())
                @php
                    $seniman = Auth::guard('seniman')->user();
                    $fotoPath = $seniman->foto 
                        ? asset('storage/foto_seniman/' . $seniman->foto)
                        : asset('assets/defaultprofile.png');
                @endphp
                <a href="{{ route('seniman.profil') }}" title="Profil">
                    <img src="{{ $fotoPath }}" 
                         alt="Foto Profil"
                         class="profile-icon"
                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                </a>
            @endif
        </div>
    </header>

    <div class="monitor-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>📊 Monitor Penjualan</h1>
            <p>Pantau dan analisis performa penjualan karya seni Anda</p>
        </div>

        <!-- Statistik Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Transaksi</h3>
                <div class="value">{{ number_format($statistik['total_transaksi'], 0, ',', '.') }}</div>
                <div class="label">Semua transaksi</div>
            </div>
            <div class="stat-card">
                <h3>Transaksi Sukses</h3>
                <div class="value" style="color: #28a745;">{{ number_format($statistik['transaksi_sukses'], 0, ',', '.') }}</div>
                <div class="label">Pembayaran berhasil</div>
            </div>
            <div class="stat-card">
                <h3>Total Pendapatan</h3>
                <div class="value" style="color: #667eea;">Rp {{ number_format($statistik['total_pendapatan'], 0, ',', '.') }}</div>
                <div class="label">Dari transaksi sukses</div>
            </div>
            <div class="stat-card">
                <h3>Item Terjual</h3>
                <div class="value" style="color: #ff6b6b;">{{ number_format($statistik['total_item_terjual'], 0, ',', '.') }}</div>
                <div class="label">Total unit terjual</div>
            </div>
            <div class="stat-card">
                <h3>Transaksi Pending</h3>
                <div class="value" style="color: #ffc107;">{{ number_format($statistik['transaksi_pending'], 0, ',', '.') }}</div>
                <div class="label">Menunggu pembayaran</div>
            </div>
        </div>

        <!-- Top 5 Karya Terlaris -->
        @if($topKarya->count() > 0)
        <div class="top-karya-section">
            <h2>🏆 Top 5 Karya Terlaris</h2>
            <div class="top-karya-list">
                @foreach($topKarya as $item)
                    @if($item['karya'])
                    <div class="top-karya-item">
                        @if($item['karya']->gambar)
                            <img src="{{ asset('storage/karya_seni/' . $item['karya']->gambar) }}" alt="{{ $item['karya']->nama_karya }}">
                        @else
                            <div style="width: 60px; height: 60px; background: #ddd; border-radius: 8px; display: flex; align-items: center; justify-content: center;">No Image</div>
                        @endif
                        <div class="top-karya-info">
                            <h4>{{ $item['karya']->nama_karya }}</h4>
                            <p>Rp {{ number_format($item['karya']->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="top-karya-stats">
                            <div class="value">{{ $item['total_terjual'] }} terjual</div>
                            <div style="color: #666; font-size: 0.9em;">Rp {{ number_format($item['total_pendapatan'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('seniman.monitor.penjualan') }}" class="filter-form">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Sukses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                </div>
                <div class="form-group">
                    <label>Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="form-group">
                    <label>Cari Karya</label>
                    <input type="text" name="search" placeholder="Nama karya..." value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                </div>
                <div class="form-group">
                    <a href="{{ route('seniman.monitor.penjualan') }}" class="btn-reset">🔄 Reset</a>
                </div>
            </form>
        </div>

        <!-- Daftar Transaksi -->
        <div class="transaksi-table">
            <div class="table-header">
                <h2>📋 Daftar Transaksi</h2>
            </div>
            @if($transaksi->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Order ID</th>
                        <th>Tanggal</th>
                        <th>Karya Seni</th>
                        <th>Pembeli</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Metode Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $item)
                    <tr>
                        <td>#{{ $item->no_transaksi }}</td>
                        <td>{{ $item->order_id }}</td>
                        <td>{{ $item->tanggal_jual->format('d M Y') }}</td>
                        <td>
                            @if($item->karya)
                                <strong>{{ $item->karya->nama_karya }}</strong>
                            @else
                                <span style="color: #999;">Karya dihapus</span>
                            @endif
                        </td>
                        <td>
                            @if($item->pembeli)
                                {{ $item->pembeli->nama }}
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td>{{ $item->jumlah }}</td>
                        <td><strong>Rp {{ number_format($item->harga, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($item->status == 'success')
                                <span class="status-badge status-success">Sukses</span>
                            @elseif($item->status == 'pending')
                                <span class="status-badge status-pending">Pending</span>
                            @elseif($item->status == 'failed')
                                <span class="status-badge status-failed">Gagal</span>
                            @elseif($item->status == 'expired')
                                <span class="status-badge status-expired">Expired</span>
                            @else
                                <span class="status-badge">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td>{{ $item->payment_type ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                {{ $transaksi->links() }}
            </div>
            @else
            <div class="empty-state">
                <h3>📭 Belum Ada Transaksi</h3>
                <p>Belum ada transaksi yang ditemukan dengan filter yang dipilih.</p>
            </div>
            @endif
        </div>

        <!-- Back Button -->
        <a href="{{ route('seniman.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>
    </div>
</body>
</html>

