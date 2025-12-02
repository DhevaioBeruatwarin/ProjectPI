<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Profil Seniman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/Seniman/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .loading-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; }
        .loading-overlay.show { display: flex; }
        .spinner { border: 5px solid #f3f3f3; border-top: 5px solid #8B6F47; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .sidebar { width: 250px; background: white; padding: 20px 0; min-height: 100vh; box-shadow: 2px 0 5px rgba(0,0,0,0.08); position: sticky; top: 0; }
        .sidebar h5 { padding: 0 20px 15px 20px; color: #333; font-weight: 600; font-size: 16px; border-bottom: 2px solid #8B6F47; }
        .sidebar ul { list-style: none; padding: 10px 0; }
        .sidebar a { display: block; padding: 12px 20px; color: #555; text-decoration: none; transition: all 0.3s ease; border-left: 3px solid transparent; font-size: 13px; }
        .sidebar a:hover, .sidebar a.active { background-color: #f9f9f9; color: #8B6F47; border-left-color: #8B6F47; padding-left: 17px; }
        .main-content { padding: 30px; flex: 1; overflow-y: auto; display: flex; flex-direction: column; }
        .content-wrapper { flex: 1; }
        .profile-header-card { background: white; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .profile-header { display: flex; gap: 25px; align-items: flex-start; }
        .profile-photo-section { flex-shrink: 0; text-align: center; }
        .profile-photo img { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid #8B6F47; box-shadow: 0 2px 10px rgba(139, 111, 71, 0.15); }
        .btn-upload { display: inline-block; margin-top: 10px; padding: 8px 16px; background: #8B6F47; color: white; border-radius: 6px; font-size: 12px; transition: all 0.3s ease; text-decoration: none; cursor: pointer; }
        .btn-upload:hover { background: #6d5535; transform: translateY(-2px); }
        .profile-info { flex: 1; }
        .profile-info h2 { color: #333; font-weight: 700; margin-bottom: 5px; font-size: 24px; }
        .profile-status { color: #666; font-size: 13px; margin-bottom: 18px; }
        .profile-details { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px; }
        .detail-item { background: #fafafa; padding: 12px; border-radius: 6px; border-left: 3px solid #8B6F47; }
        .detail-label { color: #999; font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 4px; letter-spacing: 0.5px; }
        .detail-value { color: #333; font-size: 13px; font-weight: 500; }
        .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .action-btn { padding: 10px 18px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }
        .action-btn-primary { background: #8B6F47; color: white; }
        .action-btn-primary:hover { background: #6d5535; transform: translateY(-2px); }
        .action-btn-secondary { background: #e8e8e8; color: #333; }
        .action-btn-secondary:hover { background: #d8d8d8; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s ease; border-top: 3px solid #8B6F47; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(139, 111, 71, 0.1); }
        .stat-icon { font-size: 24px; color: #8B6F47; margin-bottom: 10px; }
        .stat-number { font-size: 28px; font-weight: 700; color: #333; margin-bottom: 5px; }
        .stat-label { font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .content-section { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .section-title { color: #333; font-weight: 700; font-size: 16px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #8B6F47; display: inline-block; }
        .bio-section { background: #fafafa; padding: 15px; border-radius: 6px; border-left: 3px solid #8B6F47; }
        .bio-label { font-weight: 600; color: #333; margin-bottom: 8px; font-size: 13px; }
        .bio-text { color: #555; font-size: 13px; line-height: 1.5; }
        .empty-state { text-align: center; padding: 30px 20px; color: #999; }
        .empty-state-icon { font-size: 40px; margin-bottom: 10px; color: #ddd; }
        .alert { border-radius: 8px; margin-bottom: 20px; border: none; font-size: 13px; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
        .table { font-size: 13px; margin-bottom: 0; }
        .table thead { background: #f9f9f9; }
        .table th { color: #666; font-weight: 600; padding: 12px; border: none; }
        .table td { padding: 12px; vertical-align: middle; border-color: #f0f0f0; }
        .table tbody tr:hover { background: #fafafa; }
        .quick-links-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .quick-link-card { padding: 20px; border-radius: 10px; color: white; text-align: center; text-decoration: none !important; transition: transform 0.3s ease; font-weight: 600; cursor: pointer; }
        .quick-link-card:hover { transform: translateY(-3px); }
        .quick-link-card i { font-size: 28px; display: block; margin-bottom: 10px; }
        .card-brown { background: linear-gradient(135deg, #8B6F47 0%, #6d5535 100%); }
        .card-brown:hover { color: white; text-decoration: none; }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center text-white"><div class="spinner mx-auto mb-3"></div><p>Mengupload foto...</p></div>
    </div>

    <div class="d-flex" style="min-height: 100vh; flex-direction: column;">
        <div style="display: flex; flex: 1;">
            <div class="sidebar">
                <h5><i class="fas fa-store me-2"></i>My Shop</h5>
                <ul>
                    <li><a href="{{ route('seniman.profil') }}" class="active"><i class="fas fa-user me-2"></i>Profil</a></li>
                    <li><a href="{{ route('seniman.karya.index') }}"><i class="fas fa-palette me-2"></i>Karya Saya</a></li>
                    <li><a href="{{ route('seniman.karya.upload') }}"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Karya</a></li>
                    <li><a href="{{ route('seniman.chat.index') }}"><i class="fas fa-comments me-2"></i>Chat</a></li>
                    <li><a href="{{ route('seniman.edit.profil') }}"><i class="fas fa-edit me-2"></i>Edit Profil</a></li>
                    <li><a href="{{ route('seniman.logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>

            <div class="main-content">
                <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="profile-header-card">
                    <div class="profile-header">
                        <div class="profile-photo-section">
                            <div class="profile-photo">
                                @php $fotoPath = null; $defaultImage = asset('assets/defaultprofile.png'); if ($seniman->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists('foto_seniman/' . $seniman->foto)) { $fotoPath = asset('storage/foto_seniman/' . $seniman->foto); } @endphp
                                <img src="{{ $fotoPath ? $fotoPath . '?v=' . time() : $defaultImage }}" alt="Foto Seniman" id="profileImage" onerror="this.src='{{ $defaultImage }}'; this.onerror=null;">
                            </div>
                            <form action="{{ route('seniman.profil.foto.update') }}" method="POST" enctype="multipart/form-data" id="fotoForm">
                                @csrf
                                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg,image/gif" hidden>
                                <label for="foto" class="btn-upload"><i class="fas fa-camera me-1"></i>Ubah Foto</label>
                            </form>
                        </div>
                        <div class="profile-info">
                            <h2>{{ $seniman->nama }}</h2>
                            <div class="profile-status"><i class="fas fa-check-circle me-1" style="color: #10b981;"></i><span>Seniman aktif</span></div>
                            <div class="profile-details">
                                <div class="detail-item"><div class="detail-label"><i class="fas fa-envelope me-1"></i>Email</div><div class="detail-value">{{ $seniman->email }}</div></div>
                                <div class="detail-item"><div class="detail-label"><i class="fas fa-phone me-1"></i>Telepon</div><div class="detail-value">{{ $seniman->no_hp ?? '-' }}</div></div>
                                <div class="detail-item"><div class="detail-label"><i class="fas fa-palette me-1"></i>Bidang Seni</div><div class="detail-value">{{ $seniman->bidang ?? 'Tidak diatur' }}</div></div>
                                <div class="detail-item"><div class="detail-label"><i class="fas fa-calendar-alt me-1"></i>Bergabung</div><div class="detail-value">{{ \Carbon\Carbon::parse($seniman->created_at)->format('d M Y') }}</div></div>
                            </div>
                            <div class="action-buttons">
                                <a href="{{ route('seniman.edit.profil') }}" class="action-btn action-btn-primary"><i class="fas fa-edit"></i>Edit Profil</a>
                                <a href="{{ route('seniman.karya.index') }}" class="action-btn action-btn-secondary"><i class="fas fa-palette"></i>Lihat Karya</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-palette"></i></div><div class="stat-number">{{ $seniman->karya()->count() ?? 0 }}</div><div class="stat-label">Total Karya</div></div>
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-shopping-bag"></i></div><div class="stat-number">0</div><div class="stat-label">Terjual</div></div>
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-comments"></i></div><div class="stat-number">0</div><div class="stat-label">Percakapan</div></div>
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-star"></i></div><div class="stat-number">5.0</div><div class="stat-label">Rating Rata-rata</div></div>
                </div>

                <div class="content-section">
                    <h3 class="section-title"><i class="fas fa-pen-fancy me-2"></i>Tentang Saya</h3>
                    <div class="bio-section"><div class="bio-label">Bio Profil</div><div class="bio-text">{{ $seniman->bio ?? 'Belum ada bio. Tambahkan bio untuk menarik lebih banyak pembeli!' }}</div></div>
                </div>

                <div class="content-section">
                    <h3 class="section-title"><i class="fas fa-palette me-2"></i>Karya Terbaru</h3>
                    @if($seniman->karya()->latest()->take(5)->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light"><tr><th>Nama Karya</th><th>Harga</th><th>Stok</th><th>Tanggal Upload</th></tr></thead>
                                <tbody>
                                    @foreach($seniman->karya()->latest()->take(5)->get() as $karya)
                                    <tr><td>{{ $karya->nama_karya }}</td><td><strong>Rp {{ number_format($karya->harga, 0, ',', '.') }}</strong></td><td>{{ $karya->stok }}</td><td>{{ \Carbon\Carbon::parse($karya->created_at)->format('d M Y') }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state"><div class="empty-state-icon"><i class="fas fa-inbox"></i></div><p>Belum ada karya. Mulai upload karya seni terbaik Anda!</p></div>
                    @endif
                </div>

                <div class="content-section">
                    <h3 class="section-title"><i class="fas fa-link me-2"></i>Akses Cepat</h3>
                    <div class="quick-links-grid">
                        <a href="{{ route('seniman.karya.upload') }}" class="quick-link-card card-brown"><i class="fas fa-cloud-upload-alt"></i>Upload Karya</a>
                        <a href="{{ route('seniman.chat.index') }}" class="quick-link-card card-brown"><i class="fas fa-comments"></i>Chat Pembeli</a>
                    </div>
                </div>
                </div>

                <footer class="footer" style="background: #1a1a1a; color: #fff; padding: 40px 0; margin-top: 60px; border-top: 1px solid #333; width: 100%;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3 mb-4"><h5 style="color: #8B6F47; font-weight: 700; margin-bottom: 15px;">Jogja Artsphere</h5><ul style="list-style: none; padding: 0;"><li><a href="#" style="color: #aaa; text-decoration: none; font-size: 13px;">Tentang Kami</a></li><li><a href="#" style="color: #aaa; text-decoration: none; font-size: 13px;">Promo Hari Ini</a></li><li><a href="#" style="color: #aaa; text-decoration: none; font-size: 13px;">Karya Asli Jogja</a></li></ul></div>
                            <div class="col-md-3 mb-4"><h5 style="color: #8B6F47; font-weight: 700; margin-bottom: 15px;">Bantuan</h5><ul style="list-style: none; padding: 0;"><li style="font-size: 13px; margin-bottom: 8px; color: #aaa;"><i class="fas fa-phone me-2"></i>0823-5314-0</li><li style="font-size: 13px; color: #aaa;"><i class="fas fa-envelope me-2"></i>Customer.care@gmail.com</li></ul></div>
                            <div class="col-md-3 mb-4"><h5 style="color: #8B6F47; font-weight: 700; margin-bottom: 15px;">Metode Pembayaran</h5><p style="font-size: 13px; color: #aaa;">BCA, BRI, Mandiri, Visa, Gopay, dll.</p></div>
                            <div class="col-md-3 mb-4"><h5 style="color: #8B6F47; font-weight: 700; margin-bottom: 15px;">Ikuti Kami</h5><div style="font-size: 18px;"><a href="#" style="color: #aaa; margin-right: 10px; text-decoration: none;"><i class="fab fa-instagram"></i></a><a href="#" style="color: #aaa; margin-right: 10px; text-decoration: none;"><i class="fab fa-facebook"></i></a><a href="#" style="color: #aaa; text-decoration: none;"><i class="fab fa-twitter"></i></a></div></div>
                        </div>
                        <hr style="background-color: #444; margin: 30px 0;">
                        <p style="text-align: center; color: #888; font-size: 12px; margin: 0;">© 2025 Jogja Artsphere — Dukung Karya Lokal</p>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2048000) { alert('Ukuran foto maksimal 2MB'); e.target.value = ''; return; }
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) { alert('Format foto harus JPEG, PNG, JPG, atau GIF'); e.target.value = ''; return; }
                document.getElementById('loadingOverlay').classList.add('show');
                document.getElementById('fotoForm').submit();
            }
        });
        setTimeout(function() { const alerts = document.querySelectorAll('.alert'); alerts.forEach(alert => { new bootstrap.Alert(alert).close(); }); }, 5000);
        window.onpageshow = function(event) { if (event.persisted) { window.location.reload(); } };
    </script>
</body>
</html>