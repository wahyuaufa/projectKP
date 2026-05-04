<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class IsVerified
{
public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && ! auth()->user()->is_verified) {
            // Simpan no_wa ke session agar bisa langsung ke halaman verifikasi
            session(['verifikasi_no_wa' => auth()->user()->no_whatsapp]);
 
            return redirect()->route('home')
                ->with('warning', 'Silakan verifikasi nomor WhatsApp Anda terlebih dahulu sebelum melakukan booking.');
        }
 
        return $next($request);
    }

}