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
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->unsignedTinyInteger('pertemuan_aktif')->default(1)->after('deskripsi_acara');
            $table->boolean('is_scan_aktif')->default(true)->after('pertemuan_aktif');
            $table->string('token_salt', 32)->nullable()->after('is_scan_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->dropColumn(['pertemuan_aktif', 'is_scan_aktif', 'token_salt']);
        });
    }
};

