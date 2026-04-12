<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProfilController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\Warga\WargaDashboardController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\PesanController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Fallback logout via GET (untuk menghindari 419)
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout.get');

// ===== ADMIN ROUTES =====
Route::middleware(['auth', 'admin.only'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/warga', [WargaController::class, 'index'])->name('warga');
    Route::post('/warga', [WargaController::class, 'store'])->name('warga.store');
    Route::put('/warga/{warga}', [WargaController::class, 'update'])->name('warga.update');
    Route::delete('/warga/{warga}', [WargaController::class, 'destroy'])->name('warga.destroy');
    Route::get('/iuran', [IuranController::class, 'index'])->name('iuran');
    Route::post('/iuran/generate', [IuranController::class, 'generate'])->name('iuran.generate');
    Route::patch('/iuran/{iuran}/status', [IuranController::class, 'updateStatus'])->name('iuran.status');
    Route::delete('/iuran/{iuran}', [IuranController::class, 'destroy'])->name('iuran.destroy');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran/{pembayaran}/setujui', [PembayaranController::class, 'setujui'])->name('pembayaran.setujui');
    Route::post('/pembayaran/{pembayaran}/tolak', [PembayaranController::class, 'tolak'])->name('pembayaran.tolak');
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::put('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::delete('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/pembayaran/poll', [PembayaranController::class, 'poll'])->name('pembayaran.poll');
    Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas');
    Route::delete('/aktivitas/hapus-semua', [AktivitasController::class, 'hapusSemua'])->name('aktivitas.hapus');
    Route::get('/profil', [AdminProfilController::class, 'show'])->name('profil');
    Route::put('/profil', [AdminProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [AdminProfilController::class, 'updatePassword'])->name('profil.password');
    // Upload foto profil & banner
    Route::post('/profil/upload-avatar', [AdminProfilController::class, 'uploadAvatar'])->name('profil.avatar');
    Route::post('/profil/upload-banner', [AdminProfilController::class, 'uploadBanner'])->name('profil.banner');
    // Backup database
    Route::get('/backup', [BackupController::class, 'index'])->name('backup');
    Route::post('/backup', [BackupController::class, 'store'])->name('backup.store');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    // System Maintenance
    Route::post('/system/fix-storage', [\App\Http\Controllers\Admin\SystemController::class, 'fixStorage'])->name('system.fix-storage');
    Route::get('/system/check-storage', [\App\Http\Controllers\Admin\SystemController::class, 'checkStorage'])->name('system.check-storage');
    // Chat: admin inbox
    Route::get('/chat', [PesanController::class, 'adminIndex'])->name('chat');
    Route::post('/chat/{user}', [PesanController::class, 'store'])->name('chat.store');
    Route::get('/chat/poll', [PesanController::class, 'poll'])->name('chat.poll');
});

// ===== WARGA ROUTES =====
Route::middleware(['auth'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/dashboard', [WargaDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/iuran', [WargaDashboardController::class, 'iuran'])->name('iuran');
    Route::get('/riwayat', [WargaDashboardController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/poll', [WargaDashboardController::class, 'pollRiwayat'])->name('riwayat.poll');
    Route::get('/profil', [WargaDashboardController::class, 'profil'])->name('profil');
    Route::put('/profil', [WargaDashboardController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/password', [WargaDashboardController::class, 'updatePassword'])->name('profil.password');
    // Upload foto profil & banner
    Route::post('/profil/upload-avatar', [WargaDashboardController::class, 'uploadAvatar'])->name('profil.avatar');
    Route::post('/profil/upload-banner', [WargaDashboardController::class, 'uploadBanner'])->name('profil.banner');
    // Chat: warga hubungi admin
    Route::get('/chat', [PesanController::class, 'wargaIndex'])->name('chat');
    Route::post('/chat', [PesanController::class, 'store'])->name('chat.store');
    Route::get('/chat/poll', [PesanController::class, 'poll'])->name('chat.poll');
});

// Redirect /dashboard berdasarkan role
Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('warga.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifikasi
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::get('/dropdown', [NotifikasiController::class, 'dropdown'])->name('dropdown');
        Route::post('/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('baca-semua');
        Route::post('/{notifikasi}/baca', [NotifikasiController::class, 'baca'])->name('baca');
        Route::delete('/{notifikasi}/hapus', [NotifikasiController::class, 'hapus'])->name('hapus');
    });

    // Warga: bayar iuran
    Route::get('/bayar', [PembayaranController::class, 'form'])->name('bayar');
    Route::post('/bayar', [PembayaranController::class, 'store'])->name('bayar.store');
    Route::get('/kwitansi/{pembayaran}', [PembayaranController::class, 'kwitansi'])->name('kwitansi');

    // Admin: kirim notifikasi
    Route::post('/admin/notifikasi/pengingat', [NotifikasiController::class, 'kirimPengingat'])->name('admin.notifikasi.pengingat');
    Route::post('/admin/notifikasi/konfirmasi', [NotifikasiController::class, 'kirimKonfirmasi'])->name('admin.notifikasi.konfirmasi');
});

// Google OAuth (Socialite)
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// Firebase Auth
Route::post('/auth/firebase/callback', [App\Http\Controllers\Auth\FirebaseController::class, 'callback'])->name('auth.firebase');

// Verifikasi nomor telepon via Firebase OTP (user harus login)
Route::post('/auth/verify-phone', [App\Http\Controllers\Auth\FirebaseController::class, 'verifyPhone'])
    ->name('auth.verify-phone')
    ->middleware('auth');

// API: badge pesan belum dibaca
Route::get('/chat/unread-count', [PesanController::class, 'unreadCount'])
    ->name('chat.unread')
    ->middleware('auth');

require __DIR__.'/auth.php';
