<?php
// database/migrations/2024_01_01_000010_create_wilayah_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->char('id', 2)->primary();
            $table->string('name', 100);
        });

        Schema::create('regencies', function (Blueprint $table) {
            $table->char('id', 5)->primary();
            $table->char('province_id', 2);
            $table->string('name', 100);
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->char('id', 8)->primary();
            $table->char('regency_id', 5);
            $table->string('name', 100);
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');
        });

        Schema::create('villages', function (Blueprint $table) {
            $table->char('id', 13)->primary();
            $table->char('district_id', 8);
            $table->string('name', 100);
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('regencies');
        Schema::dropIfExists('provinces');
    }
};
