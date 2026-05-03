<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\Driver;
use App\Models\Armada;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Statistik hari ini
        $jadwalHariIni     = Jadwal::whereDate('tanggal', $today)->count();
        $pemesananHariIni  = Pemesanan::whereDate('created_at', $today)->count();
        $penumpangHariIni  = Pemesanan::whereDate('created_at', $today)
                                ->where('status', '!=', 'dibatalkan')
                                ->sum('jumlah_penumpang');
        $pendapatanHariIni = Pemesanan::whereDate('created_at', $today)
                                ->where('status', '!=', 'dibatalkan')
                                ->sum('total_harga');

        // Jadwal mendatang (7 hari ke depan)
        $jadwalMendatang = Jadwal::with(['armada', 'rute', 'driver', 'pemesanans'])
            ->whereDate('tanggal', '>=', $today)
            ->whereDate('tanggal', '<=', $today->copy()->addDays(7))
            ->where('is_active', true)
            ->orderBy('tanggal')
            ->orderBy('jam_berangkat')
            ->get();

        // Pemesanan terbaru
        $pemesananTerbaru = Pemesanan::with(['user', 'jadwal.armada', 'jadwal.rute'])
            ->latest()
            ->take(8)
            ->get();

        // Statistik bulanan (30 hari terakhir)
        $grafikData = Pemesanan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total, SUM(jumlah_penumpang) as penumpang')
            ->where('status', '!=', 'dibatalkan')
            ->whereDate('created_at', '>=', now()->subDays(29))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        // Isi hari yang kosong
        $labels = [];
        $totals = [];
        $penumpangs = [];
        for ($i = 29; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $labels[]     = now()->subDays($i)->format('d/m');
            $totals[]     = $grafikData[$tgl]->total ?? 0;
            $penumpangs[] = $grafikData[$tgl]->penumpang ?? 0;
        }

        $totalDriver  = Driver::where('is_active', true)->count();
        $totalArmada  = Armada::where('is_active', true)->count();

        return view('admin.dashboard.index', compact(
            'jadwalHariIni', 'pemesananHariIni', 'penumpangHariIni', 'pendapatanHariIni',
            'jadwalMendatang', 'pemesananTerbaru',
            'labels', 'totals', 'penumpangs',
            'totalDriver', 'totalArmada'
        ));
    }
}
