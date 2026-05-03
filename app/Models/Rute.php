<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rute extends Model
{
    use HasFactory;

    protected $fillable = [
        'kota_asal', 'kota_tujuan', 'arah', 'estimasi_durasi', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function getLabelAttribute(): string
    {
        return "{$this->kota_asal} → {$this->kota_tujuan}";
    }

    public function getArahLabelAttribute(): string
    {
        return match ($this->arah) {
            'barat_timur' => 'Barat → Timur',
            'timur_barat' => 'Timur → Barat',
            default       => $this->label,
        };
    }

    public static function cariByArah(string $arah): ?self
    {
        return static::where('arah', $arah)->where('is_active', true)->first();
    }

    public static function ensureRuteUtama(): void
    {
        static::firstOrCreate(
            ['arah' => 'barat_timur'],
            ['kota_asal' => 'Barat', 'kota_tujuan' => 'Timur', 'is_active' => true]
        );
        static::firstOrCreate(
            ['arah' => 'timur_barat'],
            ['kota_asal' => 'Timur', 'kota_tujuan' => 'Barat', 'is_active' => true]
        );
    }
}
