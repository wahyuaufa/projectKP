<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'no_whatsapp',
        'no_kendaraan',
        'jenis_kendaraan',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function waLink(string $pesan = ''): string
    {
        $no = preg_replace('/[^0-9]/', '', $this->no_whatsapp);
        if (str_starts_with($no, '0')) {
            $no = '62' . substr($no, 1);
        }
        return 'https://wa.me/' . $no . '?text=' . urlencode($pesan);
    }
}