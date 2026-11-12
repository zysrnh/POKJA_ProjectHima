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
        if (!Schema::hasTable('kelompoks')) {
            Schema::create('kelompoks', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kelompok', 100)->unique();
                $table->string('kode_invite', 24)->unique();
                $table->foreignId('ketua_id')->constrained('pendaftarans')->onDelete('cascade');
                $table->string('judul_proposal', 255)->nullable();
                $table->integer('nilai_proposal')->default(0);
                $table->string('status_proposal', 50)->nullable();
                $table->timestamp('revisi_terakhir_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kelompok_members')) {
            Schema::create('kelompok_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kelompok_id')->constrained('kelompoks')->onDelete('cascade');
                $table->foreignId('pendaftaran_id')->unique()->constrained('pendaftarans')->onDelete('cascade');
                $table->enum('role', ['ketua', 'anggota'])->default('anggota');
                $table->timestamp('joined_at')->nullable();
                $table->timestamps();

                $table->index(['kelompok_id', 'role']);
            });
        }

        if (!Schema::hasTable('kelompok_invitations')) {
            Schema::create('kelompok_invitations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kelompok_id')->constrained('kelompoks')->onDelete('cascade');
                $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
                $table->foreignId('invited_by')->constrained('pendaftarans')->onDelete('cascade');
                $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
                $table->timestamp('responded_at')->nullable();
                $table->timestamps();

                $table->index(['pendaftaran_id', 'status']);
                $table->index(['kelompok_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_invitations');
        Schema::dropIfExists('kelompok_members');
        Schema::dropIfExists('kelompoks');
    }
};
