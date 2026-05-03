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

}
