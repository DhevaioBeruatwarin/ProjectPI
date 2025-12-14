<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Pesanan Saya - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/myorder.css') }}">
    <style>
        /* Review Modal Styles */
        .review-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .review-modal {
            background: white;
            border-radius: 12px;
            padding: 32px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .review-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .review-modal-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .review-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #757575;
        }

        .review-product-info {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding: 16px;
            background: #f8f8f8;
            border-radius: 8px;
        }

        .review-product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .review-product-details h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .review-product-details p {
            margin: 0;
            color: #757575;
            font-size: 14px;
        }

        .review-rating {
            margin-bottom: 24px;
        }

        .review-rating h4 {
            margin: 0 0 12px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .review-stars {
            display: flex;
            gap: 8px;
        }

        .review-star {
            font-size: 32px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .review-star:hover,
        .review-star.active {
            color: #ffc107;
        }

        .review-comment {
            margin-bottom: 24px;
        }

        .review-comment label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 16px;
        }

        .review-comment textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
        }

        .review-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #111;
            color: white;
        }

        .btn-primary:hover {
            background: #000;
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #111;
        }

        .btn-secondary:hover {
            background: #e5e5e5;
        }

        .review-success {
            text-align: center;
            padding: 40px 20px;
        }

        .review-success-icon {
            font-size: 64px;
            margin-bottom: 16px;
            color: #2ecc71;
        }

        .review-success h3 {
            margin: 0 0 8px 0;
            font-size: 20px;
        }

        .review-success p {
            color: #757575;
            margin-bottom: 24px;
        }

        .review-existing {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .review-existing h4 {
            margin: 0 0 12px 0;
            font-size: 16px;
        }

        .review-existing .stars {
            margin-bottom: 12px;
        }

        .review-existing .star {
            color: #ffc107;
            font-size: 18px;
        }

        .review-existing .comment {
            color: #555;
            font-size: 14px;
            line-height: 1.6;
        }

        .review-existing-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .review-edit-btn, .review-delete-btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            font-size: 13px;
            cursor: pointer;
        }

        .review-edit-btn {
            background: #3498db;
            color: white;
        }

        .review-delete-btn {
            background: #e74c3c;
            color: white;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 9999;
            display: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .notification.success {
            background: #2ecc71;
        }

        .notification.error {
            background: #e74c3c;
        }

        .notification.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>Pesanan Saya</h1>
            <p>Kelola dan lacak semua pesanan Anda</p>
        </div>
    </div>

    <div class="container">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="tab-btn active" data-filter="all">
                Semua Pesanan
            </button>
            <button class="tab-btn" data-filter="success">
                Berhasil
            </button>
            <button class="tab-btn" data-filter="pending">
                Pending
            </button>
            <button class="tab-btn" data-filter="failed">
                Gagal
            </button>
        </div>

        <!-- Orders List -->
        <div id="ordersList">
            @forelse($orders->groupBy('order_id') as $orderId => $transaksi)
                <div class="order-card" data-status="{{ $transaksi->first()->status }}">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div>
                            <div class="order-id">
                                <strong>Order ID:</strong> {{ $orderId }}
                            </div>
                            <div class="order-date">
                                 {{ $transaksi->first()->tanggal_jual->format('d M Y, H:i') }}
                            </div>
                        </div>
                        
                        <div>
                            @if($transaksi->first()->status == 'success')
                                <span class="status-badge status-success">Berhasil</span>
                            @elseif($transaksi->first()->status == 'pending')
                                <span class="status-badge status-pending">Pending</span>
                            @else
                                <span class="status-badge status-failed">Gagal</span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Items -->
                    <div class="product-items">
                        @foreach($transaksi as $item)
                            <div class="product-item">
                                <img 
                                    src="{{ asset('storage/karya_seni/' . $item->karya->gambar) }}" 
                                    alt="{{ $item->karya->judul }}"
                                    class="product-image"
                                    onerror="this.src='https://via.placeholder.com/80x80/667eea/ffffff?text=No+Image'"
                                />
                                
                                <div class="product-info">
                                    <div class="product-name">{{ $item->karya->nama_karya ?? 'Produk Seni' }}</div>
                                    <div class="product-quantity">
                                        Jumlah: {{ $item->jumlah }} × Rp {{ number_format($item->karya->harga ?? 0, 0, ',', '.') }}
                                    </div>
                                    <div class="product-price">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Review Button (for success orders) -->
                                @if($transaksi->first()->status == 'success')
                                    <div class="review-action">
                                        @php
                                            $kode_seni = $item->karya->kode_seni;
                                            $existingReview = $item->karya->reviews->firstWhere('id_user', Auth::guard('pembeli')->user()->id_pembeli);
                                        @endphp
                                        
                                        @if($existingReview)
                                            <button class="btn-review" onclick="viewExistingReview('{{ $kode_seni }}', {{ $existingReview->id_review }})">
                                                <span class="review-star-filled">★</span> Lihat Ulasan Anda
                                            </button>
                                        @else
                                            <button class="btn-review" onclick="openReviewModal('{{ $kode_seni }}', '{{ $item->karya->nama_karya }}', '{{ asset('storage/karya_seni/' . $item->karya->gambar) }}')">
                                                ⭐ Beri Ulasan
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="order-footer">
                        <div class="order-total">
                            Total: <strong>Rp {{ number_format($transaksi->sum('harga'), 0, ',', '.') }}</strong>
                        </div>
                        
                        <div class="order-actions">
                            <a href="{{ route('pembeli.payment.success', ['order_id' => $orderId]) }}" 
                               class="btn btn-outline btn-sm">
                                📄 Detail
                            </a>
                            
                            <a href="{{ route('pembeli.dashboard') }}" class="back-btn">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Belum Ada Pesanan</h3>
                    <p>Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                    <a href="{{ route('pembeli.dashboard') }}" class="btn btn-primary">
                        🛍️ Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Review Modal -->
    <div class="review-modal-overlay" id="reviewModal">
        <div class="review-modal">
            <div class="review-modal-header">
                <h2 id="modalTitle">Beri Ulasan</h2>
                <button class="review-modal-close" onclick="closeReviewModal()">&times;</button>
            </div>

            <!-- Product Info -->
            <div class="review-product-info">
                <img id="modalProductImage" src="" alt="Product" class="review-product-image">
                <div class="review-product-details">
                    <h4 id="modalProductName"></h4>
                    <p id="modalProductCode"></p>
                </div>
            </div>

            <!-- Review Form -->
            <div id="reviewFormContainer">
                <div class="review-rating">
                    <h4>Rating</h4>
                    <div class="review-stars" id="ratingStars">
                        <span class="review-star" data-value="1">★</span>
                        <span class="review-star" data-value="2">★</span>
                        <span class="review-star" data-value="3">★</span>
                        <span class="review-star" data-value="4">★</span>
                        <span class="review-star" data-value="5">★</span>
                    </div>
                    <input type="hidden" id="ratingValue" value="0">
                </div>

                <div class="review-comment">
                    <label for="reviewComment">Komentar</label>
                    <textarea id="reviewComment" placeholder="Bagikan pengalaman Anda tentang karya ini..."></textarea>
                </div>

                <div class="review-actions">
                    <button class="btn btn-primary" onclick="submitReview()" id="submitReviewBtn">Kirim Ulasan</button>
                    <button class="btn btn-secondary" onclick="closeReviewModal()">Batal</button>
                </div>
            </div>

            <!-- Success Message -->
            <div id="reviewSuccess" style="display: none;">
                <div class="review-success">
                    <div class="review-success-icon">✓</div>
                    <h3>Ulasan Terkirim!</h3>
                    <p>Terima kasih telah memberikan ulasan. Ulasan Anda membantu seniman dan pembeli lain.</p>
                    <button class="btn btn-primary" onclick="closeReviewModal()">Tutup</button>
                </div>
            </div>

            <!-- Existing Review -->
            <div id="existingReviewContainer" style="display: none;">
                <div class="review-existing">
                    <h4>Ulasan Anda</h4>
                    <div class="stars" id="existingReviewStars"></div>
                    <p class="comment" id="existingReviewComment"></p>
                    <p class="review-date" id="existingReviewDate"></p>
                    <div class="review-existing-actions">
                        <button class="review-edit-btn" onclick="editReview()">✏️ Edit</button>
                        <button class="review-delete-btn" onclick="deleteReview()">🗑️ Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Review Modal (separate for editing) -->
    <div class="review-modal-overlay" id="editReviewModal" style="display: none;">
        <div class="review-modal">
            <div class="review-modal-header">
                <h2>Edit Ulasan</h2>
                <button class="review-modal-close" onclick="closeEditReviewModal()">&times;</button>
            </div>

            <div class="review-rating">
                <h4>Rating</h4>
                <div class="review-stars" id="editRatingStars">
                    <span class="review-star" data-value="1">★</span>
                    <span class="review-star" data-value="2">★</span>
                    <span class="review-star" data-value="3">★</span>
                    <span class="review-star" data-value="4">★</span>
                    <span class="review-star" data-value="5">★</span>
                </div>
                <input type="hidden" id="editRatingValue" value="0">
            </div>

            <div class="review-comment">
                <label for="editReviewComment">Komentar</label>
                <textarea id="editReviewComment" placeholder="Bagikan pengalaman Anda tentang karya ini..."></textarea>
            </div>

            <div class="review-actions">
                <button class="btn btn-primary" onclick="updateReview()" id="updateReviewBtn">Update Ulasan</button>
                <button class="btn btn-secondary" onclick="closeEditReviewModal()">Batal</button>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script>
        let currentKodeSeni = '';
        let currentReviewId = null;
        let currentRating = 0;

        // Open review modal
        function openReviewModal(kode_seni, productName, productImage) {
            currentKodeSeni = kode_seni;
            currentReviewId = null;
            
            // Set modal content
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductCode').textContent = 'Kode: ' + kode_seni;
            document.getElementById('modalProductImage').src = productImage;
            
            // Reset form
            resetReviewForm();
            
            // Show form, hide success and existing review
            document.getElementById('reviewFormContainer').style.display = 'block';
            document.getElementById('reviewSuccess').style.display = 'none';
            document.getElementById('existingReviewContainer').style.display = 'none';
            
            // Show modal
            document.getElementById('reviewModal').style.display = 'flex';
        }

        // View existing review
        function viewExistingReview(kode_seni, reviewId) {
            currentKodeSeni = kode_seni;
            currentReviewId = reviewId;
            
            // Fetch review data
            fetch(`/pembeli/karya/${kode_seni}/check-review`)
                .then(res => res.json())
                .then(response => {
                    if (response.has_reviewed && response.review) {
                        const review = response.review;
                        
                        // Set product info
                        document.getElementById('modalProductName').textContent = 'Karya Seni';
                        document.getElementById('modalProductCode').textContent = 'Kode: ' + kode_seni;
                        
                        // Display existing review
                        document.getElementById('existingReviewStars').innerHTML = '★'.repeat(review.nilai);
                        document.getElementById('existingReviewComment').textContent = review.komentar;
                        document.getElementById('existingReviewDate').textContent = 'Dikirim pada: ' + new Date(review.created_at).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                        
                        // Hide form, show existing review
                        document.getElementById('reviewFormContainer').style.display = 'none';
                        document.getElementById('reviewSuccess').style.display = 'none';
                        document.getElementById('existingReviewContainer').style.display = 'block';
                        
                        // Show modal
                        document.getElementById('reviewModal').style.display = 'flex';
                    }
                });
        }

        // Star rating interaction
        document.addEventListener('DOMContentLoaded', function() {
            // For new review
            const stars = document.querySelectorAll('#ratingStars .review-star');
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.dataset.value);
                    setRating(stars, value);
                    document.getElementById('ratingValue').value = value;
                    currentRating = value;
                });
                
                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.dataset.value);
                    highlightStars(stars, value);
                });
            });

            document.getElementById('ratingStars').addEventListener('mouseleave', function() {
                highlightStars(stars, currentRating);
            });

            // For edit review
            const editStars = document.querySelectorAll('#editRatingStars .review-star');
            editStars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.dataset.value);
                    setRating(editStars, value);
                    document.getElementById('editRatingValue').value = value;
                    currentRating = value;
                });
                
                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.dataset.value);
                    highlightStars(editStars, value);
                });
            });

            document.getElementById('editRatingStars').addEventListener('mouseleave', function() {
                highlightStars(editStars, currentRating);
            });
        });

        function setRating(stars, value) {
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                if (starValue <= value) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        function highlightStars(stars, value) {
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                if (starValue <= value) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#ddd';
                }
            });
        }

        // Submit review
        function submitReview() {
            const rating = document.getElementById('ratingValue').value;
            const comment = document.getElementById('reviewComment').value.trim();
            
            if (!rating || rating == 0) {
                showNotification('Pilih rating terlebih dahulu!', 'error');
                return;
            }
            
            if (!comment) {
                showNotification('Tuliskan komentar Anda!', 'error');
                return;
            }

            const submitBtn = document.getElementById('submitReviewBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';

            fetch(`/pembeli/karya/${currentKodeSeni}/review`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    rating: rating,
                    komentar: comment
                })
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    // Show success message
                    document.getElementById('reviewFormContainer').style.display = 'none';
                    document.getElementById('reviewSuccess').style.display = 'block';
                    
                    // Update button in the order list
                    updateReviewButtonInList(currentKodeSeni, response.review?.id_review);
                    
                    showNotification('Ulasan berhasil dikirim!', 'success');
                } else {
                    showNotification(response.message || 'Gagal mengirim ulasan', 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kirim Ulasan';
                }
            })
            .catch(err => {
                console.error(err);
                showNotification('Terjadi kesalahan', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kirim Ulasan';
            });
        }

        // Edit review
        function editReview() {
            closeReviewModal();
            
            // Fetch review data for editing
            fetch(`/pembeli/karya/${currentKodeSeni}/check-review`)
                .then(res => res.json())
                .then(response => {
                    if (response.has_reviewed && response.review) {
                        const review = response.review;
                        currentReviewId = review.id_review;
                        currentRating = review.nilai;
                        
                        // Set edit form values
                        setRating(document.querySelectorAll('#editRatingStars .review-star'), review.nilai);
                        document.getElementById('editRatingValue').value = review.nilai;
                        document.getElementById('editReviewComment').value = review.komentar;
                        
                        // Show edit modal
                        document.getElementById('editReviewModal').style.display = 'flex';
                    }
                });
        }

        // Update review
        function updateReview() {
            const rating = document.getElementById('editRatingValue').value;
            const comment = document.getElementById('editReviewComment').value.trim();
            
            if (!rating || rating == 0) {
                showNotification('Pilih rating terlebih dahulu!', 'error');
                return;
            }
            
            if (!comment) {
                showNotification('Tuliskan komentar Anda!', 'error');
                return;
            }

            const updateBtn = document.getElementById('updateReviewBtn');
            updateBtn.disabled = true;
            updateBtn.textContent = 'Mengupdate...';

            fetch(`/pembeli/review/${currentReviewId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    rating: rating,
                    komentar: comment
                })
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    showNotification('Ulasan berhasil diperbarui!', 'success');
                    closeEditReviewModal();
                    // Reload page to update review display
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(response.message || 'Gagal mengupdate ulasan', 'error');
                    updateBtn.disabled = false;
                    updateBtn.textContent = 'Update Ulasan';
                }
            })
            .catch(err => {
                console.error(err);
                showNotification('Terjadi kesalahan', 'error');
                updateBtn.disabled = false;
                updateBtn.textContent = 'Update Ulasan';
            });
        }

        // Delete review
        function deleteReview() {
            if (!confirm('Apakah Anda yakin ingin menghapus ulasan ini?')) {
                return;
            }

            fetch(`/pembeli/review/${currentReviewId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    showNotification('Ulasan berhasil dihapus!', 'success');
                    closeReviewModal();
                    // Update button in the order list
                    updateReviewButtonInList(currentKodeSeni, null);
                } else {
                    showNotification(response.message || 'Gagal menghapus ulasan', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showNotification('Terjadi kesalahan', 'error');
            });
        }

        // Close modals
        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
            resetReviewForm();
        }

        function closeEditReviewModal() {
            document.getElementById('editReviewModal').style.display = 'none';
        }

        function resetReviewForm() {
            document.getElementById('ratingValue').value = '0';
            document.getElementById('reviewComment').value = '';
            currentRating = 0;
            
            const stars = document.querySelectorAll('#ratingStars .review-star');
            stars.forEach(star => {
                star.classList.remove('active');
                star.style.color = '#ddd';
            });
        }

        // Update review button in order list
        function updateReviewButtonInList(kode_seni, reviewId) {
            const reviewButtons = document.querySelectorAll(`.review-action button[onclick*="${kode_seni}"]`);
            reviewButtons.forEach(button => {
                if (reviewId) {
                    button.innerHTML = '<span class="review-star-filled">★</span> Lihat Ulasan Anda';
                    button.setAttribute('onclick', `viewExistingReview('${kode_seni}', ${reviewId})`);
                } else {
                    button.innerHTML = '⭐ Beri Ulasan';
                    button.setAttribute('onclick', `openReviewModal('${kode_seni}', '...', '...')`);
                }
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const orderCards = document.querySelectorAll('.order-card');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    orderCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = 'block';
                        } else {
                            const status = card.dataset.status;
                            card.style.display = status === filter ? 'block' : 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>