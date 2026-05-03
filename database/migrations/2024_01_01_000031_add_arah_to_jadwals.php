<?php
// database/migrations/2024_01_01_000031_add_arah_to_jadwals.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom arah ke jadwals untuk membedakan
        // barat→timur vs timur→barat dalam satu hari
        Schema::table('jadwals', function (Blueprint $table) {
            $table->enum('arah', ['barat_timur', 'timur_barat'])
                  ->nullable()
                  ->after('rute_id')
                  ->comment('Arah perjalanan hari itu');
        });

        // Tambah kolom arah ke rutes juga untuk konsistensi
        Schema::table('rutes', function (Blueprint $table) {
            $table->enum('arah', ['barat_timur', 'timur_barat', 'lainnya'])
                  ->default('lainnya')
                  ->after('kota_tujuan');
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn('arah');
        });
        Schema::table('rutes', function (Blueprint $table) {
            $table->dropColumn('arah');
        });
    }
};
