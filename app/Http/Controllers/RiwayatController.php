<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    

    public function index(Request $request)
    {
        $status = $request->get('status', 'semua');

        $query = Pemesanan::with(['jadwal.armada', 'jadwal.rute', 'kursis'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pemesanans = $query->paginate(10);

        return view('riwayat.index', compact('pemesanans', 'status'));
    }

    public function tiket(string $kode)
    {
        $pemesanan = Pemesanan::with(['jadwal.armada', 'jadwal.rute', 'kursis', 'user'])
            ->where('kode_pemesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('riwayat.tiket', compact('pemesanan'));
    }

    public function batalkan(string $kode)
    {
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode)
            ->where('user_id', Auth::id())
            ->where('status', 'akan_datang')
            ->firstOrFail();

        $pemesanan->update(['status' => 'dibatalkan']);

        return back()->with('success', 'Pemesanan berhasil dibatalkan.');
    }
}
