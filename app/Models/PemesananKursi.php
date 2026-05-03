<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananKursi extends Model
{
    protected $fillable = ['pemesanan_id', 'nomor_kursi'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }
}
