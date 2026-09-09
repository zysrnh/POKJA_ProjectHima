<?php

use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
Route::post('/daftar', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
