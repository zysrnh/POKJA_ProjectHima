<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexExists(string $table, string $indexName): bool
    {
        $rows = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($rows) > 0;
    }

    /**
     * Menambahkan dukungan kuesioner per pertemuan pada tabel kuesioner_pertanyaans dan kuesioner_jawabans.
     */
    public function up(): void
    {
        if (Schema::hasTable('kuesioner_pertanyaans') && !Schema::hasColumn('kuesioner_pertanyaans', 'pertemuan')) {
            Schema::table('kuesioner_pertanyaans', function (Blueprint $table) {
                $table->unsignedSmallInteger('pertemuan')->nullable()->after('aktif')->index();
            });
        }

        if (Schema::hasTable('kuesioner_jawabans')) {
            if (!Schema::hasColumn('kuesioner_jawabans', 'pertemuan')) {
                Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                    $table->unsignedSmallInteger('pertemuan')->default(1)->after('pertanyaan_id')->index();
                });
            }

            if (!$this->indexExists('kuesioner_jawabans', 'kuesioner_jawabans_pendaftaran_idx')) {
                Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                    $table->index('pendaftaran_id', 'kuesioner_jawabans_pendaftaran_idx');
                });
            }

            if ($this->indexExists('kuesioner_jawabans', 'kuesioner_jawaban_unique')) {
                Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                    $table->dropUnique('kuesioner_jawaban_unique');
                });
            }

            if (!$this->indexExists('kuesioner_jawabans', 'kj_pendaftaran_pertanyaan_pertemuan_uq')) {
                Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                    $table->unique(['pendaftaran_id', 'pertanyaan_id', 'pertemuan'], 'kj_pendaftaran_pertanyaan_pertemuan_uq');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kuesioner_jawabans')) {
            if ($this->indexExists('kuesioner_jawabans', 'kj_pendaftaran_pertanyaan_pertemuan_uq')) {
                Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                    $table->dropUnique('kj_pendaftaran_pertanyaan_pertemuan_uq');
                });
            }
            if (!$this->indexExists('kuesioner_jawabans', 'kuesioner_jawaban_unique')) {
                Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                    $table->unique(['pendaftaran_id', 'pertanyaan_id'], 'kuesioner_jawaban_unique');
                });
            }
            if ($this->indexExists('kuesioner_jawabans', 'kuesioner_jawabans_pendaftaran_idx')) {
                Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                    $table->dropIndex('kuesioner_jawabans_pendaftaran_idx');
                });
            }
            if (Schema::hasColumn('kuesioner_jawabans', 'pertemuan')) {
                Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                    $table->dropColumn('pertemuan');
                });
            }
        }

        if (Schema::hasTable('kuesioner_pertanyaans') && Schema::hasColumn('kuesioner_pertanyaans', 'pertemuan')) {
            Schema::table('kuesioner_pertanyaans', function (Blueprint $table) {
                $table->dropColumn('pertemuan');
            });
        }
    }
};
