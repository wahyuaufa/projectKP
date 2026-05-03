<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel drivers
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('no_whatsapp', 20)->unique();
            $table->string('no_kendaraan', 20)->nullable();
            $table->string('jenis_kendaraan', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tambah kolom ke jadwals
        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreignId('driver_id')
                  ->nullable()
                  ->after('rute_id')
                  ->constrained('drivers')
                  ->nullOnDelete();
            $table->text('catatan_admin')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['driver_id', 'catatan_admin']);
        });
        Schema::dropIfExists('drivers');
    }
};