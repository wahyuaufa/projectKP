<?php
// database/migrations/2024_01_01_000032_add_koordinat_to_pemesanans.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            // Koordinat titik penjemputan
            $table->decimal('lat_penjemputan', 10, 7)->nullable()->after('titik_penjemputan');
            $table->decimal('lng_penjemputan', 10, 7)->nullable()->after('lat_penjemputan');

            // Koordinat titik tujuan
            $table->decimal('lat_tujuan', 10, 7)->nullable()->after('titik_tujuan');
            $table->decimal('lng_tujuan', 10, 7)->nullable()->after('lat_tujuan');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropColumn([
                'lat_penjemputan', 'lng_penjemputan',
                'lat_tujuan', 'lng_tujuan',
            ]);
        });
    }
};
