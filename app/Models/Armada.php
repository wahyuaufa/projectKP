<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Armada extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'tipe', 'jumlah_kursi', 'jumlah_supir',
        'has_ac', 'foto', 'harga_per_rute',
        'bagasi_gratis', 'kardus_gratis', 'biaya_bagasi_tambahan',
        'is_active', 'jadwal_admin_only',
    ];

    protected function casts(): array
    {
        return [
            'has_ac'            => 'boolean',
            'is_active'         => 'boolean',
            'jadwal_admin_only' => 'boolean',
        ];
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-armada.png');
    }

    /** Jadwal armada ini hanya bisa dibuat admin? */
    public function isAdminOnly(): bool
    {
        return $this->jadwal_admin_only === true;
    }
}
