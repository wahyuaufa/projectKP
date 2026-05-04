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

        $jadwals = Jadwal::with(['armada', 'rute', 'driver', 'pemesanans.kursis', 'pemesanans.user'])
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_berangkat')
            ->get();

        // Auto-sync jam_berangkat dari jam penjemputan paling awal
        // Berlaku untuk jadwal Innova yang jam-nya masih 00:00:00
        foreach ($jadwals as $jadwal) {
            /** @var Jadwal $jadwal */
            $pemesananAktif = $jadwal->pemesanans->where('status', 'akan_datang');

            if ($jadwal->jam_berangkat === '00:00:00' && $pemesananAktif->count()) {
                $jamAwal = $pemesananAktif->min('jam_penjemputan');
                if ($jamAwal) {
                    $jadwal->update(['jam_berangkat' => $jamAwal]);
                    $jadwal->jam_berangkat = $jamAwal;
                }
            }
        }

        // Re-sort setelah update jam
        $jadwals = $jadwals->sortBy('jam_berangkat')->values();

        $armadas = Armada::where('is_active', true)->get();
        $drivers = Driver::where('is_active', true)->orderBy('nama')->get();
        $rutes = Rute::where('is_active', true)->get();

        return view('admin.jadwal.index', compact('jadwals', 'tanggal', 'armadas', 'drivers', 'rutes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'arah' => 'required|in:barat_timur,timur_barat',
            'tanggal' => 'required|date',
            'jam_berangkat' => 'required|date_format:H:i',
            'harga' => 'required|numeric|min:0',
            'driver_id' => 'nullable|exists:drivers,id',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        // Ambil rute otomatis dari arah yang dipilih
        $rute = Rute::where('arah', $data['arah'])
            ->where('is_active', true)
            ->first();

        if (! $rute) {
            return back()->withErrors(['arah' => 'Rute untuk arah ini belum tersedia di database.'])->withInput();
        }

        $data['rute_id'] = $rute->id;
        $data['jam_berangkat'] .= ':00';
        $data['is_active'] = true;

        // Cek duplikat
        $exists = Jadwal::where('armada_id', $data['armada_id'])
            ->where('arah', $data['arah'])
            ->whereDate('tanggal', $data['tanggal'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['tanggal' => 'Jadwal untuk armada, arah, dan tanggal yang sama sudah ada.'])
                ->withInput();
        }

        Jadwal::create($data);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $data = $request->validate([
            'driver_id' => 'nullable|exists:drivers,id',
            'jam_berangkat' => 'nullable|date_format:H:i',
            'harga' => 'required|numeric|min:0',
            'catatan_admin' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Jika jam kosong (fleksibel), set ke 00:00:00
        if (! empty($data['jam_berangkat'])) {
            $data['jam_berangkat'] .= ':00';
        } else {
            $data['jam_berangkat'] = '00:00:00';
        }

        $jadwal->update($data);

        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $adaPemesanan = $jadwal->pemesanans()
            ->where('status', 'akan_datang')
            ->exists();

        if ($adaPemesanan) {
            return back()->withErrors([
                'delete' => 'Tidak bisa hapus jadwal yang masih ada pemesanan aktif.',
            ]);
        }

        $jadwal->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function buatMassal(Request $request)
    {
        $data = $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'arah' => 'required|in:barat_timur,timur_barat',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_berangkat' => 'required|date_format:H:i',
            'harga' => 'required|numeric|min:0',
        ]);

        $rute = Rute::where('arah', $data['arah'])
            ->where('is_active', true)
            ->first();

        if (! $rute) {
            return back()->withErrors(['arah' => 'Rute untuk arah ini belum tersedia.'])->withInput();
        }

        $start = Carbon::parse($data['tanggal_mulai']);
        $end = Carbon::parse($data['tanggal_akhir']);
        $jam = $data['jam_berangkat'].':00';
        $dibuat = 0;

        while ($start->lte($end)) {
            $jadwal = Jadwal::firstOrCreate(
                [
                    'armada_id' => $data['armada_id'],
                    'rute_id' => $rute->id,
                    'arah' => $data['arah'],
                    'tanggal' => $start->format('Y-m-d'),
                ],
                [
                    'jam_berangkat' => $jam,
                    'harga' => $data['harga'],
                    'is_active' => true,
                ]
            );

            if ($jadwal->wasRecentlyCreated) {
                $dibuat++;
            }
            $start->addDay();
        }

        return back()->with('success', "Berhasil membuat {$dibuat} jadwal baru.");
    }
}
