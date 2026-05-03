<?php
// database/migrations/2024_01_01_000030_update_jadwal_system.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tandai armada yang jadwalnya hanya bisa dibuat admin
        Schema::table('armadas', function (Blueprint $table) {
            $table->boolean('jadwal_admin_only')
                  ->default(false)
                  ->after('is_active')
                  ->comment('true = hanya admin yang bisa buat jadwal (contoh: Hiace)');
        });

        // Tambah kolom jam_penjemputan di pemesanans
        // Setiap pemesanan punya jam penjemputannya sendiri
        // meski dalam 1 jadwal yang sama
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->time('jam_penjemputan')
                  ->nullable()
                  ->after('tanggal_pesan')
                  ->comment('Jam penjemputan spesifik per pemesanan');
        });
    }

    public function down(): void
    {
        Schema::table('armadas', function (Blueprint $table) {
            $table->dropColumn('jadwal_admin_only');
        });
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropColumn('jam_penjemputan');
        });
    }
};
