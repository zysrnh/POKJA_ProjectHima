<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\KelasAdminController;
use App\Http\Controllers\Admin\PendaftaranAdminController;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Pendaftaran Mahasiswa)
|--------------------------------------------------------------------------
*/
Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
Route::post('/daftar', [PendaftaranController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('pendaftaran.store');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
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
        Route::get('/pendaftar/{id}/edit', [PendaftaranAdminController::class, 'edit'])->name('pendaftar.edit');
        Route::put('/pendaftar/{id}', [PendaftaranAdminController::class, 'update'])->name('pendaftar.update');
        Route::delete('/pendaftar/{id}', [PendaftaranAdminController::class, 'destroy'])->name('pendaftar.destroy');

        // Kelola Kelas
        Route::resource('kelas', KelasAdminController::class)->except(['show']);

        // Kelola Akun Admin (CRUD Admin)
        Route::resource('users', AdminUserController::class)->except(['show']);
    });
});
