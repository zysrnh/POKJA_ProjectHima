<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_acara')->default('POKJA HIMA IF');
            $table->string('tanggal_acara')->default('13 Oktober 2026');
            $table->string('jam_acara')->default('08:00 WIB - Selesai');
            $table->string('lokasi_acara')->default('Ruangan 105');
            $table->text('deskripsi_acara')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
