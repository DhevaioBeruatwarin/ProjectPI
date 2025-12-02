<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Profil Pembeli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Seniman/profile.css') }}">
    <style>
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .loading-overlay.show { display: flex; }
        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body> 
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center text-white">
            <div class="spinner mx-auto mb-3"></div>
            <p>Mengupload foto...</p>
        </div>
    </div>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-3">
            <h5>My Profile</h5>
            <ul>
                <li><a href="{{ route('pembeli.profil') }}" class="active">Profile</a></li>
                <li><a href="{{ route('pembeli.myorder') }}">My Order</a></li>
                <li><a href="{{ route('pembeli.chat.index') }}">Chat dengan Seniman</a></li>
                {{-- <li><a href="{{ route('pembeli.chat.pembeli.index') }}">Chat dengan Pembeli</a></li> --}}
                <li><a href="{{ route('pembeli.profil.edit') }}">Edit Profile</a></li>
                <li><a href="{{ route('pembeli.logout') }}">Logout</a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="main-content flex-fill">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-start">
                <div class="col-md-8">
                    <h3 class="mb-4">Profil Saya</h3>
                    <div class="mb-3"><strong>Nama:</strong> {{ $pembeli->nama }}</div>
                    <div class="mb-3"><strong>Email:</strong> {{ $pembeli->email }}</div>
                    <div class="mb-3"><strong>No. Telepon:</strong> {{ $pembeli->no_hp ?? '-' }}</div>
                    <div class="mb-3"><strong>Alamat:</strong> {{ $pembeli->alamat ?? '-' }}</div>
                    <div class="mb-3"><strong>Bio:</strong> {{ $pembeli->bio ?? 'Belum ada bio' }}</div>
                </div>

                <div class="profile-photo col-md-4">
                    @php
                        use Illuminate\Support\Facades\Storage;
                        $fotoPath = null;
                        $defaultImage = asset('assets/defaultprofile.png');
                        
                        if ($pembeli->foto) {
                            // Cek apakah file ada di storage
                            if (Storage::disk('public')->exists('foto_pembeli/' . $pembeli->foto)) {
                                $fotoPath = asset('storage/foto_pembeli/' . $pembeli->foto);
                            }
                        }
                    @endphp
                    <img src="{{ $fotoPath ? $fotoPath . '?v=' . time() : $defaultImage }}" 
                         alt="Foto Pembeli" 
                         id="profileImage"
                         onerror="this.src='{{ $defaultImage }}'; this.onerror=null;"
                         style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                    <form action="{{ route('pembeli.profil.update_foto') }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          id="fotoForm">
                        @csrf
                        <input type="file" 
                               name="foto" 
                               id="foto" 
                               accept="image/jpeg,image/png,image/jpg,image/gif" 
                               hidden>
                        <label for="foto" class="btn-upload" style="cursor: pointer;">Pilih Gambar</label>
                    </form>
                </div>
            </div>
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
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                if (file.size > 2048000) {
                    alert('Ukuran foto maksimal 2MB');
                    e.target.value = '';
                    return;
                }
                
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format foto harus JPEG, PNG, JPG, atau GIF');
                    e.target.value = '';
                    return;
                }
                
                document.getElementById('loadingOverlay').classList.add('show');
                document.getElementById('fotoForm').submit();
            }
        });

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    </script>
</body>
</html>