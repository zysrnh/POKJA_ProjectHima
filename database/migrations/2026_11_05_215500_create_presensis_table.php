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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->unsignedTinyInteger('pertemuan')->comment('Pertemuan ke 1 s/d 7');
            $table->string('status')->default('hadir');
            $table->timestamp('waktu_hadir')->useCurrent();
            $table->timestamps();

            // Atomic unique constraint: 1 mahasiswa hanya bisa 1 kali hadir per pertemuan
            $table->unique(['pendaftaran_id', 'pertemuan'], 'unique_mhs_pertemuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
