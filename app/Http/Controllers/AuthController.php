<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:laki-laki,perempuan'],
            'no_whatsapp'  => ['required', 'string', 'unique:users,no_whatsapp'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'nama_lengkap'  => $data['nama_lengkap'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'no_whatsapp'   => $data['no_whatsapp'],
            'password'      => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat. Selamat datang!');
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
