<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KdashboardController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\KlaporanController;
use App\Http\Controllers\AspirasiBaruController;
use App\Http\Controllers\ArsipController;

Route::get('/', [PengaduanController::class, 'formPengaduan'])
    ->name('home');

Route::post('/pengaduan/store', [PengaduanController::class, 'store'])
    ->name('pengaduan.store')
    ->middleware('auth');

Route::get('/history', [RiwayatController::class, 'index'])
    ->name('history.index');
    
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth','role:admin'])
    ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/pengguna', UserController::class)
        ->only(['index','store','update','destroy']);

    Route::resource('/kategori', KategoriController::class)
        ->only(['index','store','update','destroy']);

    Route::get('/kategori/search', [KategoriController::class, 'search'])->name('kategori.search');
    
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [DashboardController::class, 'update'])->name('profile.update');

    // SISWA
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/download-template', [SiswaController::class, 'downloadTemplate'])
        ->name('siswa.download-template');
    Route::post('/siswa/create-account', [SiswaController::class, 'createAccount'])
        ->name('siswa.create-account');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{nisn}', [SiswaController::class, 'update'])
        ->whereNumber('nisn')
        ->name('siswa.update');
    Route::delete('/siswa/{nisn}', [SiswaController::class, 'destroy'])
        ->whereNumber('nisn')
        ->name('siswa.destroy');

    // PERBAIKAN: ASPIRASI MASUK - Pindahkan ini ke dalam grup yang benar
    Route::prefix('aspirasi-masuk')->name('aspirasi.masuk.')->group(function () {
        Route::get('/', [AspirasiBaruController::class, 'index'])->name('index');
        Route::get('/unread-count', [AspirasiBaruController::class, 'getCount'])->name('unread-count');
        Route::get('/{id}/detail', [AspirasiBaruController::class, 'getDetail'])->name('detail');
        Route::post('/{id}/terima', [AspirasiBaruController::class, 'terima'])->name('terima');
        Route::post('/{id}/tolak', [AspirasiBaruController::class, 'tolak'])->name('tolak');
    });

    // ARSIP DITOLAK (Menggunakan ArsipController)
    Route::get('/arsip-ditolak', [ArsipController::class, 'index'])
        ->name('arsip.index');
    Route::get('/arsip-ditolak/{id}', [ArsipController::class, 'show'])
        ->name('arsip.show');

    // ASPIRASI MENUNGGU
    Route::get('/aspirasi', [AspirasiController::class, 'index'])
        ->name('aspirasi.index');
    Route::put('/aspirasi/{id}', [AspirasiController::class, 'update'])
        ->name('aspirasi.update');
    
    // Store feedback untuk aspirasi baru
    Route::post('/pengaduan/{pengaduan}/feedback', [AspirasiController::class, 'storeWithFeedback'])
        ->name('pengaduan.feedback.store');

    // FEEDBACK / ASPIRASI PROSES
    Route::get('/feedback', [FeedbackController::class, 'index'])
        ->name('feedback.index');
    Route::put('/feedback/{id}', [FeedbackController::class, 'update'])
        ->name('feedback.update');
    Route::post('/feedback/{id}/reply', [FeedbackController::class, 'reply'])
        ->name('feedback.reply');
    Route::get('/feedback/{id}/feedbacks', [FeedbackController::class, 'getFeedbacks'])
        ->name('feedback.getFeedbacks');

    // HISTORY / ASPIRASI SELESAI
    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history.index');
    Route::get('/history/{aspirasi}', [HistoryController::class, 'show'])
        ->name('history.show');

    // LAPORAN
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan');
    Route::get('/laporan/preview', [LaporanController::class, 'preview'])
        ->name('laporan.preview');
    Route::get('/laporan/preview-single', [LaporanController::class, 'previewSingle'])
        ->name('laporan.preview-single');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])
        ->name('laporan.export-pdf');
    Route::get('/laporan/preview-selected', [LaporanController::class, 'previewSelected'])
        ->name('laporan.preview-selected');
});

Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->name('kepsek.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [KdashboardController::class, 'dashboard'])
        ->name('dashboard');
    
    // Laporan
    Route::get('/laporan', [KlaporanController::class, 'index'])
        ->name('laporan.index');
    Route::get('/laporan/preview', [KlaporanController::class, 'preview'])
        ->name('laporan.preview');
    Route::get('/laporan/export-pdf', [KlaporanController::class, 'exportPdf'])
        ->name('laporan.export-pdf');
    Route::get('/laporan/preview-single', [KlaporanController::class, 'previewSingle'])
        ->name('laporan.preview-single');
    Route::get('/laporan/preview-selected', [KlaporanController::class, 'previewSelected'])
        ->name('laporan.preview-selected');
    
    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])
        ->name('profile');
    Route::put('/profile/update', [DashboardController::class, 'update'])
        ->name('profile.update');
    
    // API for statistics
    Route::get('/statistics', [KdashboardController::class, 'getStatistics'])
        ->name('statistics');
});