<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'armada_id',
        'rute_id',
        'driver_id',       // ← tambahan
        'arah',            // ← tambahan — wajib ada agar firstOrCreate & create bisa simpan arah
        'tanggal',
        'jam_berangkat',
        'harga',
        'is_active',
        'catatan_admin',   // ← tambahan
    ];

    protected function casts(): array
    {
        return [
            'tanggal'   => 'date',
            'harga'     => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────────────────────

    public function armada()
    {
        return $this->belongsTo(Armada::class);
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class);
    }

    // ── Kursi yang sudah terpesan (hanya status aktif) ────────

    public function getKursiTerpesanAttribute(): array
    {
        return PemesananKursi::whereHas('pemesanan', function ($q) {
            $q->where('jadwal_id', $this->id)
              ->where('status', 'akan_datang'); // hanya yang aktif
        })->pluck('nomor_kursi')->toArray();
    }
}