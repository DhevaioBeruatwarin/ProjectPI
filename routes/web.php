<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardPembeliController;
use App\Http\Controllers\DashboardSenimanController;
use App\Http\Controllers\KaryaSeniController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\SenimanController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;

// LANDING PAGE
Route::get('/', function () {
    return view('landing');
})->name('landing');

// AUTH ROUTES
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =====================================================
// WEBHOOK MIDTRANS - HARUS DI LUAR MIDDLEWARE AUTH!
// =====================================================
Route::post('/payment-callback', [PaymentController::class, 'paymentCallback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('payment.callback');

// =====================================================
// TEST ROUTES - HAPUS SETELAH PRODUCTION!
// =====================================================
Route::get('/test-webhook-access', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Webhook endpoint is accessible',
        'timestamp' => now(),
        'app_url' => env('APP_URL')
    ]);
});

// DASHBOARD PEMBELI
Route::prefix('pembeli')
    ->middleware('auth:pembeli')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardPembeliController::class, 'index'])->name('pembeli.dashboard');
        Route::get('/dashboard/pembeli/search', [KaryaSeniController::class, 'search'])->name('dashboard.pembeli.search');

        // Profil
        Route::get('/profil', [PembeliController::class, 'profil'])->name('pembeli.profil');
        Route::get('/profil/edit', [PembeliController::class, 'edit'])->name('pembeli.profil.edit');
        Route::put('/profil/update', [PembeliController::class, 'update'])->name('pembeli.profil.update');
        Route::post('/profil/foto', [PembeliController::class, 'updateFoto'])->name('pembeli.profil.update_foto');

        // Keranjang
        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
        Route::post('/keranjang/tambah/{kode_seni}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
        Route::post('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
        Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
        Route::post('/keranjang/checkout', [PaymentController::class, 'prepareCheckout'])->name('keranjang.checkout');

        // Checkout & Payment
        Route::get('/checkout', [PaymentController::class, 'checkoutPreview'])->name('pembeli.checkout.preview');
        Route::post('/checkout/bayar', [PaymentController::class, 'bayar'])->name('pembeli.checkout.bayar');
        Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('pembeli.payment.success');

        // My Orders
        Route::get('/myorder', [PaymentController::class, 'myOrders'])->name('pembeli.myorder');

        // DETAIL KARYA UNTUK PEMBELI - PASTIKAN DI DALAM GRUP PEMBELI
        Route::get('/karya/{kode_seni}', [KaryaSeniController::class, 'showForPembeli'])->name('pembeli.karya.detail');

        // Chat dengan Seniman
        Route::get('/chat', [ChatController::class, 'pembeliIndex'])->name('pembeli.chat.index');
        Route::post('/chat/start', [ChatController::class, 'pembeliStart'])->name('pembeli.chat.start');
        Route::get('/chat/start/karya/{kode_seni}', [ChatController::class, 'pembeliStartFromKarya'])->name('pembeli.chat.start.from.karya');
        Route::get('/chat/{conversation}/messages', [ChatController::class, 'pembeliMessages'])->name('pembeli.chat.messages');
        Route::post('/chat/{conversation}/messages', [ChatController::class, 'pembeliSend'])->name('pembeli.chat.send');

        // Chat dengan Pembeli lain
        Route::get('/chat/pembeli', [ChatController::class, 'pembeliToPembeliIndex'])->name('pembeli.chat.pembeli.index');
        Route::post('/chat/pembeli/start', [ChatController::class, 'pembeliToPembeliStart'])->name('pembeli.chat.pembeli.start');
        Route::get('/chat/pembeli/{conversation}/messages', [ChatController::class, 'pembeliToPembeliMessages'])->name('pembeli.chat.pembeli.messages');
        Route::post('/chat/pembeli/{conversation}/messages', [ChatController::class, 'pembeliToPembeliSend'])->name('pembeli.chat.pembeli.send');

        // Di dalam grup Route::prefix('pembeli')->middleware('auth:pembeli')
    
        // Reviews Routes
        Route::post('/karya/{kode_seni}/review', [ReviewController::class, 'store'])->name('pembeli.review.store');
        Route::put('/review/{id_review}', [ReviewController::class, 'update'])->name('pembeli.review.update');
        Route::delete('/review/{id_review}', [ReviewController::class, 'destroy'])->name('pembeli.review.delete');
        Route::get('/karya/{kode_seni}/reviews', [ReviewController::class, 'getReviews'])->name('pembeli.review.get');
        Route::get('/karya/{kode_seni}/check-review', [ReviewController::class, 'checkUserReview'])->name('pembeli.review.check');

        // Logout
        Route::get('/logout', function () {
            Auth::guard('pembeli')->logout();
            return redirect()->route('login')->with('success', 'Berhasil logout!');
        })->name('pembeli.logout');
    });

// DASHBOARD SENIMAN
Route::prefix('seniman')
    ->middleware('auth:seniman')
    ->group(function () {
        Route::get('/dashboard', [DashboardSenimanController::class, 'index'])->name('seniman.dashboard');
        Route::get('/dashboard/pembeli/search', [KaryaSeniController::class, 'search'])->name('dashboard.seniman.search');

        Route::get('/profil', [SenimanController::class, 'profile'])->name('seniman.profil');
        Route::get('/profil/edit', [SenimanController::class, 'edit'])->name('seniman.edit.profil');
        Route::put('/profil/update', [SenimanController::class, 'update'])->name('seniman.profil.update');
        Route::post('/profil/foto', [SenimanController::class, 'updateFoto'])->name('seniman.profil.foto.update');

        Route::get('/karya', [SenimanController::class, 'karyaSaya'])->name('seniman.karya.index');
        Route::get('/karya/upload', [DashboardSenimanController::class, 'createKarya'])->name('seniman.karya.upload');
        Route::post('/karya/store', [DashboardSenimanController::class, 'storeKarya'])->name('seniman.karya.store');
        Route::get('/karya/edit/{kode_seni}', [DashboardSenimanController::class, 'editKarya'])->name('seniman.karya.edit');
        Route::put('/karya/update/{kode_seni}', [DashboardSenimanController::class, 'updateKarya'])->name('seniman.karya.update');
        Route::delete('/karya/delete/{kode_seni}', [DashboardSenimanController::class, 'destroyKarya'])->name('seniman.karya.delete');

        // DETAIL KARYA UNTUK SENIMAN - PASTIKAN DI DALAM GRUP SENIMAN
        Route::get('/karya/{kode_seni}', [KaryaSeniController::class, 'showForSeniman'])->name('seniman.karya.detail');

        Route::get('/logout', function () {
            Auth::guard('seniman')->logout();
            return redirect()->route('login')->with('success', 'Berhasil logout!');
        })->name('seniman.logout');

        // Chat
        Route::get('/chat', [ChatController::class, 'senimanIndex'])->name('seniman.chat.index');
        Route::post('/chat/start', [ChatController::class, 'senimanStart'])->name('seniman.chat.start');
        Route::get('/chat/{conversation}/messages', [ChatController::class, 'senimanMessages'])->name('seniman.chat.messages');
        Route::post('/chat/{conversation}/messages', [ChatController::class, 'senimanSend'])->name('seniman.chat.send');
    });

// HAPUS ROUTE CAMPURAN INI:
// Route::middleware(['auth:pembeli'])->group(function () {
//     Route::get('/karya/{kode_seni}', [KaryaSeniController::class, 'showForPembeli'])->name('pembeli.karya.detail');
// });
//
// Route::middleware(['auth:seniman'])->group(function () {
//     Route::get('/karya/{kode_seni}', [KaryaSeniController::class, 'showForSeniman'])->name('seniman.karya.detail');
// });

// DASHBOARD ADMIN
Route::prefix('admin')
    ->middleware('auth:admin')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('Admin.dashboard');
        })->name('admin.dashboard');
    });

// REDIRECT AFTER LOGIN
Route::get('/redirect-after-login', function () {
    if (Auth::guard('pembeli')->check()) {
        return redirect()->route('pembeli.dashboard');
    } elseif (Auth::guard('seniman')->check()) {
        return redirect()->route('seniman.dashboard');
    } elseif (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('landing');
    }
})->name('redirect.after.login');