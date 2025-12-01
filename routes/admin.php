<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// ===================
// Login Admin
// ===================
Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');

// ===================
// Route Admin — butuh login
// ===================
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Profil admin
    Route::get('/profil', [AdminController::class, 'profil'])->name('profil');
    Route::post('/profil/update', [AdminController::class, 'updateProfil'])->name('updateProfil');
    Route::post('/profil/foto', [AdminController::class, 'updateFoto'])->name('foto.update');

    // Kelola Seniman
    Route::get('/seniman', [AdminController::class, 'kelolaSeniman'])->name('seniman.index');
    Route::delete('/seniman/{id}', [AdminController::class, 'hapusSeniman'])->name('seniman.delete');

    // Kelola Karya
    Route::get('/karya', [AdminController::class, 'kelolaKarya'])->name('karya.index');
    Route::delete('/karya/{kode_seni}', [AdminController::class, 'hapusKarya'])->name('karya.delete');

    // Kelola Pembeli
    Route::get('/pembeli', [AdminController::class, 'kelolaPembeli'])->name('pembeli.index');
    Route::delete('/pembeli/{id_pembeli}', [AdminController::class, 'hapusPembeli'])->name('pembeli.delete');

    Route::get('/monitoring/sistem', [AdminController::class, 'monitoringSistem'])
     ->name('monitoring.sistem');

    Route::get('/monitoring/keuangan', [AdminController::class, 'monitoringKeuangan'])
     ->name('monitoring.keuangan');

    // Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
