<?php
// app/Http/Controllers/Admin/JadwalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\Driver;
use App\Models\Jadwal;
use App\Models\Rute;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        $jadwals = Jadwal::with(['armada', 'rute', 'driver', 'pemesanans.kursis'])
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_berangkat')
            ->get();

        $armadas = Armada::where('is_active', true)->get();
        $drivers = Driver::where('is_active', true)->orderBy('nama')->get();
        $rutes   = Rute::where('is_active', true)->get();

        return view('admin.jadwal.index', compact('jadwals', 'tanggal', 'armadas', 'drivers', 'rutes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'armada_id'      => 'required|exists:armadas,id',
            'rute_id'        => 'required|exists:rutes,id',
            'tanggal'        => 'required|date',
            'jam_berangkat'  => 'required|date_format:H:i',
            'harga'          => 'required|numeric|min:0',
            'driver_id'      => 'nullable|exists:drivers,id',
            'catatan_admin'  => 'nullable|string|max:500',
        ]);

        $data['jam_berangkat'] .= ':00';
        $data['is_active'] = true;

        // Cek duplikat
        $exists = Jadwal::where('armada_id', $data['armada_id'])
            ->where('rute_id', $data['rute_id'])
            ->whereDate('tanggal', $data['tanggal'])
            ->where('jam_berangkat', $data['jam_berangkat'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['jam_berangkat' => 'Jadwal dengan armada, rute, dan jam yang sama sudah ada.'])->withInput();
        }

        Jadwal::create($data);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $data = $request->validate([
            'driver_id'     => 'nullable|exists:drivers,id',
            'jam_berangkat' => 'required|date_format:H:i',
            'harga'         => 'required|numeric|min:0',
            'catatan_admin' => 'nullable|string|max:500',
            'is_active'     => 'boolean',
        ]);

        $data['jam_berangkat'] .= ':00';
        $jadwal->update($data);

        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        // Cek apakah ada pemesanan aktif
        $adaPemesanan = $jadwal->pemesanans()
            ->where('status', 'akan_datang')
            ->exists();

        if ($adaPemesanan) {
            return back()->withErrors(['delete' => 'Tidak bisa hapus jadwal yang masih ada pemesanan aktif.']);
        }

        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Buat jadwal massal untuk beberapa hari ke depan
     */
    public function buatMassal(Request $request)
    {
        $data = $request->validate([
            'armada_id'     => 'required|exists:armadas,id',
            'rute_id'       => 'required|exists:rutes,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_berangkat' => 'required|date_format:H:i',
            'harga'         => 'required|numeric|min:0',
        ]);

        $armada = Armada::findOrFail($data['armada_id']);
        $start  = Carbon::parse($data['tanggal_mulai']);
        $end    = Carbon::parse($data['tanggal_akhir']);
        $jam    = $data['jam_berangkat'] . ':00';
        $dibuat = 0;

        while ($start->lte($end)) {
            Jadwal::firstOrCreate(
                [
                    'armada_id'     => $data['armada_id'],
                    'rute_id'       => $data['rute_id'],
                    'tanggal'       => $start->format('Y-m-d'),
                    'jam_berangkat' => $jam,
                ],
                [
                    'harga'     => $data['harga'],
                    'is_active' => true,
                ]
            ) && $dibuat++;
            $start->addDay();
        }

        return back()->with('success', "Berhasil membuat {$dibuat} jadwal baru.");
    }
}
