<?php
// app/Http/Controllers/Admin/PemesananController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $status  = $request->get('status', 'semua');

        $query = Pemesanan::with(['user', 'jadwal.armada', 'jadwal.rute', 'jadwal.driver', 'kursis'])
            ->whereHas('jadwal', fn($q) => $q->whereDate('tanggal', $tanggal))
            ->latest();

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pemesanans = $query->get();

        // Kelompokkan per jadwal
        $perJadwal = $pemesanans->groupBy('jadwal_id');

        $drivers = Driver::where('is_active', true)->orderBy('nama')->get();

        return view('admin.pemesanan.index', compact('pemesanans', 'perJadwal', 'tanggal', 'status', 'drivers'));
    }

    /**
     * Tampilkan detail satu pemesanan
     */
    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load(['user', 'jadwal.armada', 'jadwal.rute', 'jadwal.driver', 'kursis']);
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    /**
     * Update status pemesanan
     */
    public function updateStatus(Request $request, Pemesanan $pemesanan)
    {
        $request->validate(['status' => 'required|in:akan_datang,selesai,dibatalkan']);
        $pemesanan->update(['status' => $request->status]);
        return back()->with('success', 'Status pemesanan diperbarui.');
    }

    /**
     * Generate teks ringkasan penjemputan untuk dikirim ke driver via WhatsApp
     */
    public function shareDriver(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        $jadwal = Jadwal::with([
            'armada', 'rute', 'driver',
            'pemesanans' => fn($q) => $q->where('status', 'akan_datang')
                                        ->with(['user', 'kursis']),
        ])->findOrFail($request->jadwal_id);

        $driver = $request->driver_id
            ? Driver::findOrFail($request->driver_id)
            : $jadwal->driver;

        // Build pesan WhatsApp
        $pesan = $this->buildPesanDriver($jadwal, $driver);

        // Jika request AJAX → return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'pesan'   => $pesan,
                'wa_link' => $driver ? $driver->waLink($pesan) : null,
            ]);
        }

        return back()->with('pesan_driver', $pesan);
    }

    /**
     * Halaman cetak / ekspor data penjemputan harian
     */
    public function cetakHarian(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        $jadwals = Jadwal::with([
            'armada', 'rute', 'driver',
            'pemesanans' => fn($q) => $q->where('status', 'akan_datang')->with(['user', 'kursis']),
        ])
        ->whereDate('tanggal', $tanggal)
        ->where('is_active', true)
        ->orderBy('jam_berangkat')
        ->get();

        return view('admin.pemesanan.cetak-harian', compact('jadwals', 'tanggal'));
    }

    // ── Private Helper ────────────────────────────────────────

    private function buildPesanDriver(Jadwal $jadwal, ?Driver $driver): string
    {
        $tgl     = Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y');
        $jam     = substr($jadwal->jam_berangkat, 0, 5);
        $armada  = $jadwal->armada->nama;
        $rute    = $jadwal->rute->kota_asal . ' → ' . $jadwal->rute->kota_tujuan;
        $pemesanans = $jadwal->pemesanans;

        $salam = $driver ? "Halo Kak *{$driver->nama}*," : "Halo,";

        $pesan  = "🚐 *GOTRAV — INFO PENJEMPUTAN*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "{$salam}\n";
        $pesan .= "Berikut data penjemputan untuk:\n\n";
        $pesan .= "📅 *Tanggal:* {$tgl}\n";
        $pesan .= "⏰ *Jam:* {$jam} WIB\n";
        $pesan .= "🚗 *Armada:* {$armada}\n";
        $pesan .= "🗺️ *Rute:* {$rute}\n";
        $pesan .= "👥 *Total Penumpang:* {$pemesanans->sum('jumlah_penumpang')} orang\n\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "📋 *DAFTAR PENJEMPUTAN:*\n\n";

        foreach ($pemesanans as $i => $p) {
            $no    = $i + 1;
            $kursi = $p->kursis->pluck('nomor_kursi')->join(', ');
            $nama  = $p->user->nama_lengkap;
            $wa    = $p->user->no_whatsapp;

            // Bersihkan teks dari tag GPS lama di kolom titik_*
            $jemput = preg_replace('/\s*\[GPS:[^\]]+\]/', '', $p->titik_penjemputan);
            $tujuan = preg_replace('/\s*\[GPS:[^\]]+\]/', '', $p->titik_tujuan);

            $pesan .= "*{$no}. {$nama}*\n";
            $pesan .= "   💺 Kursi: {$kursi}\n";
            $pesan .= "   ⏰ Jam Jemput: {$p->jam_penjemputan_format} WIB\n";
            $pesan .= "   📞 WA: {$wa}\n";
            $pesan .= "   🟢 Jemput: {$jemput}\n";

            // Link Maps penjemputan
            if ($p->maps_jemput) {
                $pesan .= "   📍 Lokasi Jemput: {$p->maps_jemput}\n";
            }

            $pesan .= "   🔴 Tujuan: {$tujuan}\n";

            // Link Maps tujuan
            if ($p->maps_tujuan) {
                $pesan .= "   📍 Lokasi Tujuan: {$p->maps_tujuan}\n";
            }

            // Link Directions jika keduanya ada
            if ($p->maps_directions) {
                $pesan .= "   🗺️ Rute Navigasi: {$p->maps_directions}\n";
            }

            if ($p->jumlah_bagasi > 0) {
                $pesan .= "   🧳 Bagasi: {$p->jumlah_bagasi} item\n";
            }
            if ($p->catatan) {
                $pesan .= "   📝 Catatan: {$p->catatan}\n";
            }

            $pesan .= "\n";
        }

        $pesan .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "_Pesan ini dikirim otomatis oleh sistem GOTRAV_";

        return $pesan;
    }
}
