<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\KelasAdminController;
use App\Http\Controllers\Admin\PendaftaranAdminController;
use App\Http\Controllers\Admin\PengaturanAcaraController;
use App\Http\Controllers\Admin\PointAdminController;
use App\Http\Controllers\Admin\PresensiAdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PresensiMahasiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global Anti-DDoS Rate Limiter Group (Max 120 Req / Menit per IP)
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:120,1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Routes (Pendaftaran Mahasiswa & Scanner Absensi HP)
    |--------------------------------------------------------------------------
    */
    Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/sukses', [PendaftaranController::class, 'sukses'])->name('pendaftaran.sukses');
    Route::post('/daftar', [PendaftaranController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('pendaftaran.store');

    // Stream Avatar Image Directly via Laravel (Kompatibel penuh dengan Subfolder cPanel tanpa perlu symlink)
    $serveAvatar = function (string $filename) {
        $safeName = basename($filename);
        $path = public_path('uploads/avatars/' . $safeName);

        if (!is_file($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    };

    Route::get('/avatar-file/{filename}', $serveAvatar)
        ->where('filename', '[A-Za-z0-9_\-\.]+')
        ->name('avatar.serve');

    Route::get('/uploads/avatars/{filename}', $serveAvatar)
        ->where('filename', '[A-Za-z0-9_\-\.]+');

    // Scanner Absensi Mahasiswa di HP
    Route::get('/absen', [PresensiMahasiswaController::class, 'showAbsen'])->name('presensi.absen');
    Route::post('/absen/scan', [PresensiMahasiswaController::class, 'submitScan'])
        ->middleware('throttle:45,1')
        ->name('presensi.scan');
    Route::post('/absen/cek-nim', [PresensiMahasiswaController::class, 'cekNim'])
        ->middleware('throttle:30,1')
        ->name('presensi.cekNim');

    /*
    |--------------------------------------------------------------------------
    | Portal Mahasiswa (Login NIM, Aktivasi, Lupa Password & Dashboard)
    |--------------------------------------------------------------------------
    */
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/login', [MahasiswaController::class, 'showLogin'])->name('login');
        Route::post('/login', [MahasiswaController::class, 'login'])
            ->middleware('throttle:10,1')
            ->name('login.post');

        Route::post('/lupa-password', [MahasiswaController::class, 'sendResetLink'])
            ->middleware('throttle:3,1')
            ->name('lupaPassword.post');
        Route::get('/reset-password/{id}', [MahasiswaController::class, 'showResetPassword'])
            ->whereNumber('id')
            ->name('showResetPassword');
        Route::post('/reset-password/{id}', [MahasiswaController::class, 'updatePassword'])
            ->whereNumber('id')
            ->middleware('throttle:10,1')
            ->name('updatePassword');

        Route::get('/aktivasi', [MahasiswaController::class, 'aktivasiDirect'])->name('aktivasi');
        Route::post('/verifikasi-qr', [MahasiswaController::class, 'verifikasiQr'])
            ->middleware('throttle:10,1')
            ->name('verifikasiQr');
        Route::get('/set-password', [MahasiswaController::class, 'showSetPassword'])->name('setPassword');
        Route::post('/set-password', [MahasiswaController::class, 'savePassword'])
            ->middleware('throttle:10,1')
            ->name('savePassword');

        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
        Route::post('/profile', [MahasiswaController::class, 'updateProfile'])
            ->middleware('throttle:15,1')
            ->name('profile.update');
        Route::post('/logout', [MahasiswaController::class, 'logout'])->name('logout');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Authentication Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.post');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        /*
        |--------------------------------------------------------------------------
        | Protected Admin Routes
        |--------------------------------------------------------------------------
        */
        Route::middleware('auth')->group(function () {
            // Data Pendaftar
            Route::get('/', [PendaftaranAdminController::class, 'index'])->name('dashboard');
            Route::get('/pendaftar/export', [PendaftaranAdminController::class, 'exportCsv'])->name('pendaftar.export');
            Route::post('/pendaftar/sync-spreadsheet', [PendaftaranAdminController::class, 'syncSpreadsheet'])->name('pendaftar.sync');
            Route::post('/pendaftar/bulk-delete', [PendaftaranAdminController::class, 'bulkDestroy'])->name('pendaftar.bulk-destroy');
            Route::get('/pendaftar/{id}/edit', [PendaftaranAdminController::class, 'edit'])->whereNumber('id')->name('pendaftar.edit');
            Route::put('/pendaftar/{id}', [PendaftaranAdminController::class, 'update'])->whereNumber('id')->name('pendaftar.update');
            Route::patch('/pendaftar/{id}/toggle-kehadiran', [PendaftaranAdminController::class, 'toggleKehadiran'])->whereNumber('id')->name('pendaftar.toggleKehadiran');
            Route::delete('/pendaftar/{id}', [PendaftaranAdminController::class, 'destroy'])->whereNumber('id')->name('pendaftar.destroy');

            // Presensi 7 Pertemuan & Layar Proyektor Kelas
            Route::get('/presensi', [PresensiAdminController::class, 'index'])->name('presensi.index');
            Route::get('/presensi/layar', [PresensiAdminController::class, 'layarProyektor'])->name('presensi.layar');
            Route::get('/presensi/token', [PresensiAdminController::class, 'getProyektorToken'])->name('presensi.token');
            Route::get('/presensi/recent-scans', [PresensiAdminController::class, 'getRecentScans'])->name('presensi.recent-scans');
            Route::post('/presensi/sesi', [PresensiAdminController::class, 'updateSesi'])->name('presensi.sesi');
            Route::patch('/presensi/{id}/toggle', [PresensiAdminController::class, 'toggleManual'])->whereNumber('id')->name('presensi.toggle');
            Route::post('/presensi/sync-spreadsheet', [PresensiAdminController::class, 'syncSpreadsheet'])->name('presensi.sync');
            Route::get('/presensi/export', [PresensiAdminController::class, 'exportExcel'])->name('presensi.export');

            // Kelola Point Keaktifan Mahasiswa (PointKu Mode)
            Route::get('/poin', [PointAdminController::class, 'index'])->name('poin.index');
            Route::get('/poin/leaderboard', [PointAdminController::class, 'leaderboard'])->name('poin.leaderboard');
            Route::get('/poin/aktivitas', [PointAdminController::class, 'aktivitas'])->name('poin.aktivitas');
            Route::post('/poin', [PointAdminController::class, 'store'])->name('poin.store');
            Route::get('/poin/{id}/riwayat', [PointAdminController::class, 'history'])->whereNumber('id')->name('poin.history');
            Route::delete('/poin/riwayat/{historyId}', [PointAdminController::class, 'destroyHistory'])->whereNumber('historyId')->name('poin.destroyHistory');

            // Kelola Kelas
            Route::resource('kelas', KelasAdminController::class)->except(['show']);

            // Kelola Akun Admin (CRUD Admin)
            Route::resource('users', AdminUserController::class)->except(['show']);

            // Pengaturan Informasi Acara
            Route::get('/pengaturan', [PengaturanAcaraController::class, 'index'])->name('pengaturan.index');
            Route::put('/pengaturan', [PengaturanAcaraController::class, 'update'])->name('pengaturan.update');
        });
    });

});
