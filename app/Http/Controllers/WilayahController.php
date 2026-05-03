<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function provinces()
    {
        return response()->json(DB::table('provinces')->orderBy('name')->get());
    }

    public function regencies(string $provinceId)
    {
        return response()->json(
            DB::table('regencies')->where('province_id', $provinceId)->orderBy('name')->get()
        );
    }

    public function districts(string $regencyId)
    {
        return response()->json(
            DB::table('districts')->where('regency_id', $regencyId)->orderBy('name')->get()
        );
    }

    public function villages(string $districtId)
    {
        return response()->json(
            DB::table('villages')->where('district_id', $districtId)->orderBy('name')->get()
        );
    }

    /**
     * Cek ketersediaan jadwal untuk armada pada tanggal & arah tertentu.
     * Query param: armada_id, tanggal, arah (barat_timur|timur_barat)
     */
    public function cekJadwal(Request $request)
    {
        $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'tanggal'   => 'required|date',
            'arah'      => 'nullable|in:barat_timur,timur_barat',
        ]);

        $query = Jadwal::with(['pemesanans.kursis', 'armada'])
            ->where('armada_id', $request->armada_id)
            ->whereDate('tanggal', $request->tanggal)
            ->where('is_active', true);

        // Filter per arah jika dikirim
        if ($request->filled('arah')) {
            $query->where('arah', $request->arah);
        }

        $jadwals = $query->get();

        if ($jadwals->isEmpty()) {
            return response()->json([
                'tersedia'      => false,
                'jumlah'        => 0,
                'kursi_tersisa' => 0,
                'total_kursi'   => 0,
            ]);
        }

        $totalKursi    = $jadwals->first()->armada->jumlah_kursi;
        $kursiTerpesan = $jadwals
            ->flatMap(fn($j) => $j->pemesanans)
            ->flatMap(fn($p) => $p->kursis)
            ->pluck('nomor_kursi')
            ->unique()
            ->count();

        return response()->json([
            'tersedia'      => true,
            'jumlah'        => $jadwals->count(),
            'kursi_tersisa' => max(0, $totalKursi - $kursiTerpesan),
            'total_kursi'   => $totalKursi,
        ]);
    }
}
