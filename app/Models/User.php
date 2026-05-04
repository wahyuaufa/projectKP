<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'no_whatsapp',
        'jenis_kelamin',
        'password',
        'otp_code',
        'otp_expires_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    //  public function getAuthIdentifierName(): string
    // {
    //     return 'no_whatsapp';
    // }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class);
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
{
    return $this->role === 'admin';
}

protected function castsOtp(): array
{
    return [
        'password' => 'hashed',
        'isVerified' => 'boolean',
        'otp_expires_at' => 'datetime',
    ];

}

public function isOtpValid(string $kode): bool
{
    return $this->otp_code === $kode
        && $this->otp_expires_at
        && now()->lt($this->otp_expires_at);
}

public function generateOtp(): string
{
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $this->update([
        'otp_code'       => $otp,
        'otp_expires_at' => now()->addSeconds(120), // 2 menit
    ]);
    return $otp;
}

}
