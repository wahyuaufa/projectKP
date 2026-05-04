<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ── Show login form ───────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    // ── Handle login ──────────────────────────────────────────

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'no_whatsapp' => ['required', 'string'],
            'password'    => ['required', 'string'],
        ]);

        $user = User::where('no_whatsapp', $credentials['no_whatsapp'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'no_whatsapp' => 'Nomor WhatsApp atau password salah.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()->intended(route('home'));
    }

    // ── Show register form ────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    // ── Handle register ───────────────────────────────────────

    // Tambah use di atas class

// ── Register: simpan user lalu kirim OTP ─────────────────
public function register(Request $request)
{
    $data = $request->validate([
        'nama_lengkap'  => ['required', 'string', 'max:255'],
        'jenis_kelamin' => ['required', 'in:laki-laki,perempuan'],
        'no_whatsapp'   => ['required', 'string', 'unique:users,no_whatsapp'],
        'password'      => ['required', 'string', 'min:6', 'confirmed'],
    ]);

    // Buat user dengan is_verified = false dulu
    $user = User::create([
        'nama_lengkap'  => $data['nama_lengkap'],
        'jenis_kelamin' => $data['jenis_kelamin'],
        'no_whatsapp'   => $data['no_whatsapp'],
        'role'          => 'customer',
        'is_verified'   => false,
        'password'      => Hash::make($data['password']),
    ]);

    // Generate OTP dan kirim via Fonnte
    $otp = $user->generateOtp();
    //$this->kirimOTP($user->no_whatsapp, $otp);

    // Simpan no_whatsapp di session untuk halaman verifikasi
    session(['verifikasi_no_wa' => $user->no_whatsapp]);

    return redirect()->route('auth.verifikasi')
        ->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda.');
}

// ── Tampilkan halaman verifikasi ─────────────────────────
public function showVerifikasi()
{
    $noWhatsapp = session('verifikasi_no_wa');
    if (! $noWhatsapp && auth()->check()) {
        $noWhatsapp = auth()->user()->no_whatsapp;
        session(['verifikasi_no_wa' => $noWhatsapp]);
    }

    if (! $noWhatsapp) return redirect()->route('register');

    $user = User::where('no_whatsapp', $noWhatsapp)->firstOrFail();
    if ($user->is_verified) return redirect()->route('home');

    // Jika OTP belum ada atau sudah expired → generate baru otomatis
    if (! $user->otp_expires_at || now()->gt($user->otp_expires_at)) {
        $otp = $user->generateOtp();

        // Kirim WA hanya di production
        if (app()->environment('production')) {
            $this->kirimOTP($user->no_whatsapp, $otp);
        }
    }

    // Hitung sisa detik OTP
    $diff = now()->diffInSeconds($user->otp_expires_at, false);
    $expireSeconds = $diff > 0 ? (int) floor($diff) : 0;

    return view('auth.verifikasi', compact('noWhatsapp', 'expireSeconds'));
}

// ── Submit kode OTP ──────────────────────────────────────
public function submitVerifikasi(Request $request)
{
    $request->validate(['otp' => 'required|string|size:6']);

    $noWhatsapp = session('verifikasi_no_wa');
    if (! $noWhatsapp) return redirect()->route('register');

    $user = User::where('no_whatsapp', $noWhatsapp)->firstOrFail();

    if (! $user->isOtpValid($request->otp)) {
        return back()->with('error', 'Kode OTP salah atau sudah kadaluarsa.');
    }

    // OTP valid → tandai verified, hapus OTP
    $user->update([
        'is_verified'    => true,
        'otp_code'       => null,
        'otp_expires_at' => null,
    ]);

    session()->forget('verifikasi_no_wa');
    Auth::login($user);

    return redirect()->route('home')
        ->with('success', 'Akun berhasil diverifikasi. Selamat datang!');
}

// ── Kirim ulang OTP ──────────────────────────────────────
public function resendOTP()
{
    $noWhatsapp = session('verifikasi_no_wa');
    if (! $noWhatsapp) return redirect()->route('register');

    $user = User::where('no_whatsapp', $noWhatsapp)->firstOrFail();
    $otp  = $user->generateOtp();
    $this->kirimOTP($user->no_whatsapp, $otp);

    return back()->with('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
}

// ── Private: kirim OTP via Fonnte ────────────────────────
private function kirimOTP(string $noWhatsapp, string $otp): void
{
    // Format nomor: 08xx → 628xx
    $no = preg_replace('/[^0-9]/', '', $noWhatsapp);
    if (str_starts_with($no, '0')) {
        $no = '62' . substr($no, 1);
    }

    Http::withHeaders([
        'Authorization' => config('services.fonnte.token'),
    ])->post('https://api.fonnte.com/send', [
        'target'  => $no,
        'message' => "🔐 *Kode Verifikasi GOTRAV*\n\n"
            . "Kode OTP Anda: *{$otp}*\n\n"
            . "Berlaku selama 2 menit.\n"
            . "Jangan berikan kode ini kepada siapapun.",
    ]);
}

    // ── Logout ────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
