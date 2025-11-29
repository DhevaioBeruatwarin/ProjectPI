<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karya Saya - Jogja Artsphere</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Seniman/karya/index.css') }}">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-3">
            <h5>My Shop</h5>
            <ul>
                <li><a href="{{ route('seniman.profil') }}">Profil</a></li>
                <li><a href="{{ route('seniman.karya.index') }}" class="active">Karya Saya</a></li>
                <li><a href="{{ route('seniman.karya.upload') }}">Upload Karya</a></li>
                <li><a href="{{ route('seniman.chat.index') }}">Chat</a></li>
                <li><a href="{{ route('seniman.edit.profil') }}">Edit Profil</a></li>
                <li><a href="{{ route('seniman.logout') }}">Keluar</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-fill p-4">
            <div class="page-header">
                <h2>Karya Saya</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Gagal!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($karya->isEmpty())
                <div class="empty-state bg-white rounded shadow-sm">
                    <div style="font-size: 80px; margin-bottom: 20px;">🖼️</div>
                    <h4>Belum Ada Karya</h4>
                    <p class="mb-3">Anda belum mengupload karya seni</p>
                    <a href="{{ route('seniman.karya.upload') }}" class="btn btn-primary">Upload Karya Pertama</a>
                </div>
            @else
                <div class="row">
                    @foreach($karya as $item)
                        <div class="col-md-4 mb-4">
                            <div class="card karya-card shadow-sm h-100">
                                <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" 
                                     class="karya-img" 
                                     alt="{{ $item->nama_karya }}"
                                     onerror="this.src='https://via.placeholder.com/400x250?text=No+Image'">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $item->nama_karya }}</h5>
                                    <p class="card-text flex-grow-1">{{ Str::limit($item->deskripsi, 80) }}</p>
                                    <div class="price-tag">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('seniman.karya.edit', $item->kode_seni) }}" 
                                           class="btn btn-edit flex-fill">
                                            Edit
                                        </a>
                                        <form action="{{ route('seniman.karya.delete', $item->kode_seni) }}" 
                                              method="POST" 
                                              class="flex-fill"
                                              onsubmit="return confirm('Yakin ingin menghapus karya \"{{ $item->nama_karya }}\"?\n\nTindakan ini tidak dapat dibatalkan!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete w-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-3">
                    <h5>Jogja Artsphere</h5>
                    <p>Tentang Kami</p>
                    <p>Promo Hari Ini</p>
                    <p>Karya Asli Jogja</p>
                </div>
                <div class="col-md-3">
                    <h5>Bantuan</h5>
                    <p>Telepon: 0823-5314-0</p>
                    <p>Email: Customer.care@gmail.com</p>
                </div>
                <div class="col-md-3">
                    <h5>Metode Pembayaran</h5>
                    <p>BCA, BRI, Mandiri, Visa, Gopay, dll.</p>
                </div>
                <div class="col-md-3">
                    <h5>Ikuti Kami</h5>
                    <p>Instagram | Facebook | X</p>
                </div>
            </div>
            <hr style="background-color:#444;">
            <p class="mt-3">© 2025 Jogja Artsphere — Dukung Karya Lokal</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>