<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\KelasAdminController;
use App\Http\Controllers\Admin\KuesionerAdminController;
use App\Http\Controllers\Admin\PendaftaranAdminController;
use App\Http\Controllers\Admin\PengaturanAcaraController;
use App\Http\Controllers\Admin\PointAdminController;
use App\Http\Controllers\Admin\PresensiAdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MahasiswaKelompokController;
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

    // Stream Gambar Upload Langsung via Laravel (Kompatibel penuh dengan Subfolder cPanel tanpa perlu symlink)
    $servePublicUpload = function (string $folder) {
        return function (string $filename) use ($folder) {
            $safeName = basename($filename);
            $path = public_path($folder . '/' . $safeName);

            if (!is_file($path)) {
                abort(404);
            }

            return response()->file($path, [
                'Cache-Control' => 'public, max-age=86400',
            ]);
        };
    };

    Route::get('/avatar-file/{filename}', $servePublicUpload('uploads/avatars'))
        ->where('filename', '[A-Za-z0-9_\-\.]+')
        ->name('avatar.serve');

    Route::get('/uploads/avatars/{filename}', $servePublicUpload('uploads/avatars'))
        ->where('filename', '[A-Za-z0-9_\-\.]+');

    Route::get('/kelompok-file/{filename}', $servePublicUpload('uploads/kelompok'))
        ->where('filename', '[A-Za-z0-9_\-\.]+')
        ->name('kelompok.foto.serve');

    Route::get('/uploads/kelompok/{filename}', $servePublicUpload('uploads/kelompok'))
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
        Route::get('/dashboard/sync', [MahasiswaController::class, 'sync'])->name('dashboard.sync');
        Route::post('/profile', [MahasiswaController::class, 'updateProfile'])
            ->middleware('throttle:15,1')
            ->name('profile.update');
        Route::post('/kuesioner', [MahasiswaController::class, 'submitKuesioner'])
            ->middleware('throttle:20,1')
            ->name('kuesioner.submit');
        Route::post('/logout', [MahasiswaController::class, 'logout'])->name('logout');

        // Kelompok & Inbox Undangan Mahasiswa
        Route::post('/kelompok', [MahasiswaKelompokController::class, 'store'])->name('kelompok.store');
        Route::put('/kelompok', [MahasiswaKelompokController::class, 'update'])->name('kelompok.update');
        Route::get('/kelompok/join/{kode}', [MahasiswaKelompokController::class, 'joinByLink'])->name('kelompok.joinLink');
        Route::post('/kelompok/join', [MahasiswaKelompokController::class, 'joinByCode'])->name('kelompok.joinCode');
        Route::post('/kelompok/invite', [MahasiswaKelompokController::class, 'sendInvite'])->name('kelompok.invite');
        Route::delete('/kelompok/invitations/{invitationId}', [MahasiswaKelompokController::class, 'cancelInvite'])
            ->whereNumber('invitationId')
            ->name('kelompok.cancelInvite');
        Route::post('/kelompok/invitations/{invitationId}/respond', [MahasiswaKelompokController::class, 'respondInvite'])
            ->whereNumber('invitationId')
            ->name('kelompok.respondInvite');
        Route::delete('/kelompok/members/{memberId}', [MahasiswaKelompokController::class, 'removeMember'])
            ->whereNumber('memberId')
            ->name('kelompok.removeMember');
        Route::post('/kelompok/members/{memberId}/transfer', [MahasiswaKelompokController::class, 'transferKetua'])
            ->whereNumber('memberId')
            ->name('kelompok.transferKetua');
        Route::post('/kelompok/leave', [MahasiswaKelompokController::class, 'leaveOrDisband'])->name('kelompok.leave');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Authentication Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:30,1')
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
            Route::get('/poin/mahasiswa', [PointAdminController::class, 'mahasiswa'])->name('poin.mahasiswa');
            Route::get('/poin/leaderboard', [PointAdminController::class, 'leaderboard'])->name('poin.leaderboard');
            Route::get('/poin/leaderboard/sync', [PointAdminController::class, 'leaderboardSync'])->name('poin.leaderboard.sync');
            Route::get('/poin/aktivitas', [PointAdminController::class, 'aktivitas'])->name('poin.aktivitas');
            Route::post('/poin', [PointAdminController::class, 'store'])->name('poin.store');
            Route::get('/poin/{id}/riwayat', [PointAdminController::class, 'history'])->whereNumber('id')->name('poin.history');
            Route::delete('/poin/riwayat/{historyId}', [PointAdminController::class, 'destroyHistory'])->whereNumber('historyId')->name('poin.destroyHistory');

            // Kuesioner Dinamis & Rekap Jawaban Anggota
            Route::get('/kuesioner', [KuesionerAdminController::class, 'index'])->name('kuesioner.index');
            Route::get('/kuesioner/export', [KuesionerAdminController::class, 'exportCsv'])->name('kuesioner.export');
            Route::post('/kuesioner/pertanyaan', [KuesionerAdminController::class, 'storePertanyaan'])->name('kuesioner.pertanyaan.store');
            Route::put('/kuesioner/pertanyaan/{id}', [KuesionerAdminController::class, 'updatePertanyaan'])->whereNumber('id')->name('kuesioner.pertanyaan.update');
            Route::delete('/kuesioner/pertanyaan/{id}', [KuesionerAdminController::class, 'destroyPertanyaan'])->whereNumber('id')->name('kuesioner.pertanyaan.destroy');

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
