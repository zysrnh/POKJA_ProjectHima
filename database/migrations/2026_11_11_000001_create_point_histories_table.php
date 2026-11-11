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
        if (!Schema::hasColumn('pendaftarans', 'total_point')) {
            Schema::table('pendaftarans', function (Blueprint $table) {
                $table->integer('total_point')->default(0)->after('status_kehadiran');
            });
        }

        if (!Schema::hasTable('point_histories')) {
            Schema::create('point_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
                $table->enum('tipe', ['tambah', 'kurang']);
                $table->string('kategori', 100);
                $table->integer('jumlah');
                $table->text('keterangan')->nullable();
                $table->string('created_by', 100)->nullable();
                $table->timestamps();

                $table->index(['pendaftaran_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_histories');

        if (Schema::hasColumn('pendaftarans', 'total_point')) {
            Schema::table('pendaftarans', function (Blueprint $table) {
                $table->dropColumn('total_point');
            });
        }
    }
};

