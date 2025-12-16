<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $karya->nama_karya }} - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/detail_karya.css') }}">
</head>

<style>
    /* Response untuk pembeli */
.seller-response.buyer-view {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-top: 15px;
    border-left: 4px solid #3498db;
}

.seller-response.buyer-view .seller-info {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.seller-response.buyer-view .seller-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    overflow: hidden;
}

.seller-response.buyer-view .seller-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.seller-response.buyer-view .seller-avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #3498db;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
}

.seller-response.buyer-view .seller-details {
    display: flex;
    flex-direction: column;
}

.seller-response.buyer-view .seller-details strong {
    font-size: 13px;
    color: #333;
}

.seller-response.buyer-view .response-date {
    font-size: 11px;
    color: #666;
}

.seller-response.buyer-view .response-content {
    color: #333;
    font-size: 14px;
    line-height: 1.5;
    padding-left: 10px;
}

.responses-count {
    background: #e8f4fd;
    color: #3498db;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 13px;
    margin-left: 5px;
}
</style>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-left">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
                <span class="brand">JOGJA ARTSPHERE</span>
            </div>

            <form action="{{ route('dashboard.pembeli.search') }}" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Cari karya..." value="{{ request('query') }}">
            </form>

            <div class="header-right">
                <a href="{{ route('keranjang.index') }}" class="icon-link" title="Keranjang">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9 2L7.17 4H3a1 1 0 000 2h1l1.68 9.39A2 2 0 0011.56 17H18a2 2 0 001.97-1.61L21 8H7" stroke="currentColor" stroke-width="2"/>
                        <circle cx="9" cy="20" r="1" fill="currentColor"/>
                        <circle cx="18" cy="20" r="1" fill="currentColor"/>
                    </svg>
                </a>
                
                @php
                    $pembeli = Auth::guard('pembeli')->user();
                    $fotoPath = $pembeli && $pembeli->foto 
                        ? asset('storage/foto_pembeli/' . $pembeli->foto)
                        : asset('assets/defaultprofile.png');
                @endphp
                
                <a href="{{ route('pembeli.profil') }}" title="Profil">
                    <img src="{{ $fotoPath }}" alt="Profile" class="avatar">
                </a>
            </div>
        </div>
    </header>

    <!-- Product Detail -->
    <main class="product-detail">
        <div class="container">
            <div class="product-grid">
                <!-- Image Section -->
                <div class="product-image">
                    @if($karya->gambar)
                        <img src="{{ asset('storage/karya_seni/' . $karya->gambar) }}" alt="{{ $karya->nama_karya }}">
                    @else
                        <div class="no-image">No Image</div>
                    @endif
                </div>

                <!-- Info Section -->
                <div class="product-info">
                    <div class="product-header">
                        <h1 class="product-title">{{ $karya->nama_karya }}</h1>
                        <p class="product-artist">{{ $karya->seniman->nama ?? 'Unknown Artist' }}</p>
                        
                        <!-- Rating Summary -->
                        @if($averageRating)
                            <div class="rating-summary">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($averageRating))
                                            <span class="star filled">★</span>
                                        @elseif($i - 0.5 <= $averageRating)
                                            <span class="star half">★</span>
                                        @else
                                            <span class="star">★</span>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-value">{{ $averageRating }}/5</span>
                                <span class="rating-count">
                                    @if($karya->reviews && $karya->reviews->count() > 0)
                                        ({{ $karya->reviews->count() }} ulasan)
                                    @else
                                        (Belum ada ulasan)
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="product-price">Rp {{ number_format($karya->harga, 0, ',', '.') }}</div>

                    <!-- Stats Section untuk Pembeli -->
                    <div class="stats-section">
                        <h3 class="stats-title">Statistik Karya</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $karya->stok }}</div>
                                <div class="stat-label">Stok Tersedia</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $karya->terjual ?? 0 }}</div>
                                <div class="stat-label">Terjual</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">
                                    @if($averageRating)
                                        {{ $averageRating }}/5
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="stat-label">Rating</div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="stock-wrapper">
                        @if($karya->stok <= 0)
                            <div class="stock-badge out">
                                <span class="dot"></span>
                                Stok Habis
                            </div>
                        @elseif($karya->stok <= 5)
                            <div class="stock-badge low">
                                <span class="dot"></span>
                                {{ $karya->stok }} Tersisa
                            </div>
                        @else
                            <div class="stock-badge available">
                                <span class="dot"></span>
                                Tersedia
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="product-description">
                        <h3>Detail Produk</h3>
                        <p>{{ $karya->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="product-actions">
                        @if($karya->stok > 0)
                            <button class="btn btn-primary" onclick="tambahKeranjang('{{ $karya->kode_seni }}', true)">
                                Beli Sekarang
                            </button>
                            <button class="btn btn-secondary" onclick="tambahKeranjang('{{ $karya->kode_seni }}', false)">
                                Tambah ke Keranjang
                            </button>
                            <a href="{{ route('pembeli.chat.start.from.karya', $karya->kode_seni) }}" class="btn btn-chat">
                                💬 Chat {{ $karya->seniman->nama ?? 'Seniman' }}
                            </a>
                        @else
                            <button class="btn btn-disabled" disabled>
                                Stok Habis
                            </button>
                            <a href="{{ route('pembeli.chat.start.from.karya', $karya->kode_seni) }}" class="btn btn-chat">
                                💬 Tanya Stok
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

<!-- Bantuan & Support Section -->
<section class="support-section">
    <div class="container">
        <h2 class="section-title">Bantuan & Support</h2>
        <div class="support-grid">
            <div class="support-card">
                <div class="support-icon">📞</div>
                <div class="support-content">
                    <h3>Customer Service</h3>
                    <p class="support-value">24/7 Support</p>
                    <p class="support-desc">Tim kami siap membantu Anda kapan saja</p>
                    <a href="https://wa.me/6281234567890" class="support-link" target="_blank">
                        Chat WhatsApp
                    </a>
                </div>
            </div>
            <div class="support-card">
                <div class="support-icon">🛠️</div>
                <div class="support-content">
                    <h3>Panduan Perawatan</h3>
                    <p class="support-value">Tips Merawat Karya</p>
                    <p class="support-desc">Jaga keindahan karya seni Anda tetap awet</p>
                    <button class="support-link" onclick="showCareGuide()">
                        Baca Panduan
                    </button>
                </div>
            </div>
            <div class="support-card">
                <div class="support-icon">❓</div>
                <div class="support-content">
                    <h3>FAQ</h3>
                    <p class="support-value">Pertanyaan Umum</p>
                    <p class="support-desc">Temukan jawaban untuk pertanyaan umum</p>
                    <button class="support-link" onclick="showFAQ()">
                        Lihat FAQ
                    </button>
                </div>
            </div>
        </div>

        <!-- FAQ Accordion -->
        <div class="faq-accordion" id="faqAccordion" style="display: none;">
            <h3 class="faq-title">Pertanyaan yang Sering Diajukan</h3>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    Bagaimana cara merawat karya seni ini?
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <p>• Hindari paparan sinar matahari langsung<br>
                       • Bersihkan dengan kain lembut dan kering<br>
                       • Jauhkan dari area lembab<br>
                       • Simpan pada suhu ruangan</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    Apakah karya ini datang dengan sertifikat keaslian?
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <p>Ya, setiap karya dilengkapi dengan sertifikat keaslian yang ditandatangani oleh seniman.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    Berapa lama waktu pengiriman?
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <p>• Yogyakarta: 1-2 hari<br>
                       • Jabodetabek: 3-4 hari<br>
                       • Kota besar lainnya: 4-7 hari<br>
                       • Daerah terpencil: 7-14 hari</p>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Related Products -->
    <section class="related-section">
        <div class="container">
            <h2 class="section-title">Karya Lainnya dari Seniman Ini</h2>
            
            @if($karyaSeniman->isEmpty())
                <p class="empty-text">Tidak ada karya lain tersedia</p>
            @else
                <div class="product-carousel">
                    @foreach($karyaSeniman as $item)
                        <a href="{{ route('pembeli.karya.detail', $item->kode_seni) }}" class="card {{ $item->stok <= 0 ? 'sold-out' : '' }}">
                            <div class="card-image">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}">
                                @else
                                    <div class="no-image">No Image</div>
                                @endif
                                
                                @if($item->stok <= 0)
                                    <span class="overlay-badge">Stok Habis</span>
                                @elseif($item->stok <= 5)
                                    <span class="overlay-badge warning">Terbatas</span>
                                @endif
                            </div>
                            
                            <div class="card-content">
                                <h3>{{ $item->nama_karya }}</h3>
                                <p class="card-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                <div class="card-meta">
                                    @if($item->stok > 0)
                                        <span class="meta-stock">{{ $item->stok }} tersedia</span>
                                    @else
                                        <span class="meta-stock out">Habis</span>
                                    @endif
                                    
                                    @if(isset($item->terjual) && $item->terjual > 0)
                                        <span class="meta-sold">{{ $item->terjual }} terjual</span>
                                    @endif
                                </div>

                                <!-- Rating untuk card -->
                                @if($item->reviews && $item->reviews->count() > 0)
                                    @php
                                        $itemRating = round($item->reviews->avg('nilai'), 1);
                                    @endphp
                                    <div class="card-rating">
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= floor($itemRating) ? 'filled' : '' }}">★</span>
                                            @endfor
                                        </div>
                                        <span class="rating-count">({{ $item->reviews->count() }})</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

<!-- Ratings & Reviews Section -->
<section class="reviews-section">
    <div class="container">
        <div class="reviews-header">
            <h2 class="section-title">Ulasan & Rating</h2>
            
            @if($averageRating)
                <div class="overall-rating">
                    <div class="rating-score">
                        <span class="score">{{ $averageRating }}</span>
                        <span class="out-of">/5</span>
                    </div>
                    <div class="rating-details">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <span class="star filled">★</span>
                                @elseif($i - 0.5 <= $averageRating)
                                    <span class="star half">★</span>
                                @else
                                    <span class="star">★</span>
                                @endif
                            @endfor
                        </div>
                        <p class="total-reviews">
                            Berdasarkan {{ $karya->reviews->count() }} ulasan
                            @if($karya->reviews->sum('responses_count') > 0)
                                <span class="responses-count">
                                    • {{ $karya->reviews->sum('responses_count') }} tanggapan seniman
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </div>

        @if($karya->reviews && $karya->reviews->count() > 0)
            <div class="reviews-list">
                @foreach($karya->reviews as $review)
                    @php
                        // Load responses jika tabel ada
                        $hasResponses = false;
                        $responses = collect();
                        if (\Schema::hasTable('review_responses')) {
                            $review->load(['responses' => function($query) {
                                $query->whereNull('deleted_at')->latest();
                            }, 'responses.seniman']);
                            $hasResponses = $review->responses->isNotEmpty();
                            $responses = $review->responses;
                        }
                    @endphp
                    
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    @if($review->pembeli && $review->pembeli->foto)
                                        <img src="{{ asset('storage/foto_pembeli/' . $review->pembeli->foto) }}" alt="{{ $review->pembeli->nama }}">
                                    @else
                                        <div class="avatar-placeholder">{{ substr($review->pembeli->nama ?? 'U', 0, 1) }}</div>
                                    @endif
                                </div>
                                <div class="reviewer-details">
                                    <h4 class="reviewer-name">{{ $review->pembeli->nama ?? 'Pengguna' }}</h4>
                                    <div class="review-meta">
                                        <div class="review-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= $review->nilai ? 'filled' : '' }}">★</span>
                                            @endfor
                                        </div>
                                        <span class="review-date">
                                            {{ $review->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="review-content">
                            <p>{{ $review->komentar ?? 'Tidak ada komentar' }}</p>
                        </div>

                        {{-- Tanggapan seniman (untuk pembeli) --}}
                        @if($hasResponses)
                            @foreach($responses as $response)
                                <div class="seller-response buyer-view">
                                    <div class="response-header">
                                        <div class="seller-info">
                                            <div class="seller-avatar">
                                                @if($response->seniman && $response->seniman->foto)
                                                    <img src="{{ asset('storage/foto_seniman/' . $response->seniman->foto) }}" 
                                                         alt="{{ $response->seniman->nama }}">
                                                @else
                                                    <div class="seller-avatar-placeholder">S</div>
                                                @endif
                                            </div>
                                            <div class="seller-details">
                                                <strong>Balasan Seniman</strong>
                                                <span class="response-date">
                                                    {{ $response->created_at->format('d M Y, H:i') }}
                                                    @if($response->created_at != $response->updated_at)
                                                        • (Diedit)
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="response-content">
                                        <p>{{ $response->tanggapan }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-reviews">
                <div class="no-reviews-icon">⭐</div>
                <h3>Belum ada ulasan</h3>
                <p>Jadilah yang pertama memberikan ulasan untuk karya ini</p>
            </div>
        @endif
    </div>
</section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Jogja Artsphere</h4>
                    <p>Platform jual beli karya seni lokal Yogyakarta</p>
                </div>
                <div class="footer-col">
                    <h4>Kontak</h4>
                    <p>Jl. Malioboro No.123, Yogyakarta</p>
                    <p>Email: info@jogja-artsphere.com</p>
                    <p>Telp: (0274) 123-4567</p>
                </div>
                <div class="footer-col">
                    <h4>Bantuan</h4>
                    <a href="#">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                    <a href="#">FAQ</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Jogja Artsphere. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script>
        function tambahKeranjang(kodeSeni, langsungBeli) {
            fetch(`/pembeli/keranjang/tambah/${kodeSeni}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message);
                    if (langsungBeli) {
                        setTimeout(() => window.location.href = '/pembeli/keranjang', 800);
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showNotification('Terjadi kesalahan', 'error');
            });
        }

        function showNotification(message, type = 'success') {
            const notif = document.getElementById('notification');
            notif.textContent = message;
            notif.className = 'notification show';
            
            if (type === 'error') {
                notif.style.background = '#e74c3c';
            } else {
                notif.style.background = '#111';
            }
            
            setTimeout(() => {
                notif.classList.remove('show');
            }, 3000);
        }


        // Fungsi untuk FAQ Accordion
function toggleFAQ(button) {
    const faqItem = button.parentElement;
    const answer = button.nextElementSibling;
    const icon = button.querySelector('.faq-icon');
    
    // Toggle active class
    faqItem.classList.toggle('active');
    answer.classList.toggle('active');
    
    // Change icon
    if (faqItem.classList.contains('active')) {
        icon.textContent = '−';
    } else {
        icon.textContent = '+';
    }
}

// Fungsi untuk menampilkan FAQ
function showFAQ() {
    const faqAccordion = document.getElementById('faqAccordion');
    faqAccordion.style.display = faqAccordion.style.display === 'none' ? 'block' : 'none';
}

// Fungsi untuk menampilkan panduan perawatan
function showCareGuide() {
    alert('Panduan Perawatan:\n\n1. Hindari sinar matahari langsung\n2. Bersihkan dengan kain lembut\n3. Jauhkan dari kelembaban\n4. Simpan di suhu ruangan\n5. Hindari bahan kimia');
}

// Review System JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.getElementById('reviewForm');
    const reviewCheck = document.getElementById('reviewCheck');
    const existingReview = document.getElementById('existingReview');
    const cancelEditBtn = document.getElementById('cancelEdit');
    const editReviewBtn = document.getElementById('editReviewBtn');
    const deleteReviewBtn = document.getElementById('deleteReviewBtn');
    const userRatingStars = document.getElementById('userRatingStars');
    const userReviewText = document.getElementById('userReviewText');
    
    const kodeSeni = "{{ $karya->kode_seni }}";
    
    // Check if user has already reviewed
    checkUserReview();
    
    // Star rating interaction
    const starsInput = document.querySelectorAll('.stars-input input');
    const starsLabels = document.querySelectorAll('.stars-input label');
    
    starsLabels.forEach(label => {
        label.addEventListener('mouseover', function() {
            const value = this.getAttribute('for').replace('star', '');
            highlightStars(value);
        });
        
        label.addEventListener('mouseout', function() {
            const checked = document.querySelector('.stars-input input:checked');
            if (checked) {
                highlightStars(checked.value);
            } else {
                resetStars();
            }
        });
    });
    
    starsInput.forEach(input => {
        input.addEventListener('change', function() {
            highlightStars(this.value);
        });
    });
    
    // Submit review form
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch(`/pembeli/karya/${kodeSeni}/review`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    showNotification('Review berhasil ditambahkan!');
                    resetReviewForm();
                    checkUserReview();
                    // Refresh reviews section
                    loadReviews();
                } else {
                    showNotification(response.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showNotification('Terjadi kesalahan', 'error');
            });
        });
    }
    
    // Edit review
    if (editReviewBtn) {
        editReviewBtn.addEventListener('click', function() {
            fetch(`/pembeli/karya/${kodeSeni}/check-review`)
                .then(res => res.json())
                .then(response => {
                    if (response.has_reviewed && response.review) {
                        const review = response.review;
                        
                        // Fill form with existing review
                        document.querySelector(`#star${review.nilai}`).checked = true;
                        document.getElementById('komentar').value = review.komentar;
                        highlightStars(review.nilai);
                        
                        // Show form, hide existing review
                        reviewForm.style.display = 'block';
                        existingReview.style.display = 'none';
                        cancelEditBtn.style.display = 'inline-block';
                        
                        // Change form to update mode
                        reviewForm.dataset.mode = 'update';
                        reviewForm.dataset.reviewId = review.id_review;
                        
                        const submitBtn = reviewForm.querySelector('button[type="submit"]');
                        submitBtn.textContent = 'Update Review';
                    }
                });
        });
    }
    
    // Cancel edit
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            resetReviewForm();
            existingReview.style.display = 'block';
            reviewForm.style.display = 'block';
            this.style.display = 'none';
        });
    }
    
    // Delete review
    if (deleteReviewBtn) {
        deleteReviewBtn.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus review ini?')) {
                fetch(`/pembeli/karya/${kodeSeni}/check-review`)
                    .then(res => res.json())
                    .then(response => {
                        if (response.has_reviewed && response.review) {
                            const reviewId = response.review.id_review;
                            
                            fetch(`/pembeli/review/${reviewId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(result => {
                                if (result.success) {
                                    showNotification('Review berhasil dihapus!');
                                    resetReviewForm();
                                    checkUserReview();
                                    loadReviews();
                                }
                            });
                        }
                    });
            }
        });
    }
    
    // Helper functions
    function checkUserReview() {
        fetch(`/pembeli/karya/${kodeSeni}/check-review`)
            .then(res => res.json())
            .then(response => {
                if (response.has_reviewed && response.review) {
                    // User has reviewed
                    const review = response.review;
                    
                    // Display existing review
                    userRatingStars.innerHTML = '★'.repeat(review.nilai);
                    userReviewText.textContent = review.komentar;
                    
                    existingReview.style.display = 'block';
                    reviewForm.style.display = 'none';
                } else {
                    // User hasn't reviewed
                    existingReview.style.display = 'none';
                    reviewForm.style.display = 'block';
                }
            });
    }
    
    function highlightStars(value) {
        starsLabels.forEach(label => {
            const starValue = label.getAttribute('for').replace('star', '');
            if (starValue <= value) {
                label.style.color = '#ffc107';
            } else {
                label.style.color = '#ddd';
            }
        });
    }
    
    function resetStars() {
        starsLabels.forEach(label => {
            label.style.color = '#ddd';
        });
    }
    
    function resetReviewForm() {
        reviewForm.reset();
        resetStars();
        
        if (reviewForm.dataset.mode === 'update') {
            delete reviewForm.dataset.mode;
            delete reviewForm.dataset.reviewId;
            
            const submitBtn = reviewForm.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Kirim Ulasan';
        }
        
        cancelEditBtn.style.display = 'none';
    }
    
    function loadReviews() {
        // Reload the reviews section
        fetch(`/pembeli/karya/${kodeSeni}/reviews`)
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    // Update reviews list
                    updateReviewsList(response.reviews);
                    
                    // Update average rating
                    if (response.average_rating) {
                        updateAverageRating(response.average_rating);
                    }
                }
            });
    }
    
    function updateReviewsList(reviews) {
        // Implement update logic for reviews list
        console.log('Reviews updated:', reviews);
    }
    
    function updateAverageRating(rating) {
        // Update average rating display
        const ratingElements = document.querySelectorAll('.average-rating-value');
        ratingElements.forEach(el => {
            el.textContent = rating.toFixed(1);
        });
    }
    
    function showNotification(message, type = 'success') {
        // Use existing notification system
        const notif = document.getElementById('notification');
        if (notif) {
            notif.textContent = message;
            notif.className = 'notification show';
            
            if (type === 'error') {
                notif.style.background = '#e74c3c';
            } else {
                notif.style.background = '#111';
            }
            
            setTimeout(() => {
                notif.classList.remove('show');
            }, 3000);
        } else {
            alert(message);
        }
    }
});

    </script>
</body>
</html>