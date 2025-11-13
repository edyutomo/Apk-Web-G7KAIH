<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KebiasaanController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\GuruController;
use Illuminate\Support\Facades\Route;

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes untuk kebiasaan
    Route::post('/kebiasaan', [KebiasaanController::class, 'store'])->name('kebiasaan.store');
    Route::get('/api/kebiasaan/chart', [KebiasaanController::class, 'getChartData'])->name('api.kebiasaan.chart');

    // ==============================================
    // ROUTES UNTUK GURU - MANAGEMENT MURID
    // ==============================================
    Route::prefix('murid')->group(function () {
        // Route untuk upload CSV murid
        Route::get('/upload', [MuridController::class, 'upload'])->name('murid.upload');
        Route::post('/preview-upload', [MuridController::class, 'previewUpload'])->name('murid.preview-upload');
        Route::post('/process-upload', [MuridController::class, 'processUpload'])->name('murid.process-upload');
        
        // Route untuk tambah manual murid
        Route::get('/create', [MuridController::class, 'create'])->name('murid.create');
        Route::post('/', [MuridController::class, 'store'])->name('murid.store');
        
        // Route untuk lihat detail murid
        Route::get('/{id}', [MuridController::class, 'show'])->name('murid.show');
        Route::get('/api/{id}/chart', [MuridController::class, 'getChartData'])->name('api.murid.chart');
    });

    // ==============================================
    // ROUTES UNTUK PENGAWAS - RESET PASSWORD (TERPISAH)
    // ==============================================
    Route::prefix('pengawas')->group(function () {
        // Route untuk reset password murid - HANYA PENGAWAS
        Route::get('/reset-password', [MuridController::class, 'showResetPasswordForm'])->name('pengawas.reset-password');
        Route::post('/search-murid', [MuridController::class, 'searchMurid'])->name('pengawas.search-murid');
        Route::post('/generate-password', [MuridController::class, 'generateNewPassword'])->name('pengawas.generate-password');
        Route::post('/reset-password/{id}', [MuridController::class, 'resetPasswordAction'])->name('pengawas.reset-password');
    });

    // Reset Password Guru
    Route::get('/reset-password-guru', [GuruController::class, 'showResetPasswordGuruForm'])->name('pengawas.reset-password-guru');
    Route::post('/search-guru', [GuruController::class, 'searchGuru'])->name('pengawas.search-guru');
    Route::post('/generate-password-guru', [GuruController::class, 'generateNewPasswordGuru'])->name('pengawas.generate-password-guru');
    Route::post('/reset-password-guru/{id}', [GuruController::class, 'resetPasswordGuruAction'])->name('pengawas.reset-password-guru');
    // Route untuk custom password guru
Route::post('/pengawas/reset-password-guru/{id}', [GuruController::class, 'resetPasswordGuruCustom'])->name('pengawas.reset-password-guru-custom');

    // ==============================================
    // ROUTES UNTUK PENGAWAS - MANAGEMENT GURU
    // ==============================================
    Route::prefix('guru')->group(function () {
        Route::get('/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/', [GuruController::class, 'store'])->name('guru.store');
        Route::get('/{id}', [GuruController::class, 'show'])->name('guru.show');
    });
});

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});