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
        if (!Schema::hasTable('kuesioner_pertanyaans')) {
            Schema::create('kuesioner_pertanyaans', function (Blueprint $table) {
                $table->id();
                $table->text('pertanyaan');
                $table->string('tipe', 20)->default('teks'); // teks, paragraf, pilihan
                $table->json('opsi')->nullable();
                $table->boolean('wajib')->default(true);
                $table->integer('urutan')->default(0);
                $table->boolean('aktif')->default(true);
                $table->timestamps();

                $table->index(['aktif', 'urutan']);
            });
        }

        if (!Schema::hasTable('kuesioner_jawabans')) {
            Schema::create('kuesioner_jawabans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
                $table->foreignId('pertanyaan_id')->constrained('kuesioner_pertanyaans')->onDelete('cascade');
                $table->text('jawaban')->nullable();
                $table->timestamps();

                $table->unique(['pendaftaran_id', 'pertanyaan_id'], 'kuesioner_jawaban_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_jawabans');
        Schema::dropIfExists('kuesioner_pertanyaans');
    }
};

