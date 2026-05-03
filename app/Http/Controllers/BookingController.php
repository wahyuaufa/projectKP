<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\PemesananKursi;
use App\Models\Rute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // ══════════════════════════════════════════════════════════
    // STEP 1 — Pilih Armada
    // ══════════════════════════════════════════════════════════

    public function pilihArmada()
    {
        $armadas = Armada::where('is_active', true)->get();
        return view('booking.pilih-armada', compact('armadas'));
    }

    public function simpanArmada(Request $request)
    {
        $request->validate(['armada_id' => 'required|exists:armadas,id']);
        session(['booking.armada_id' => $request->armada_id]);
        return redirect()->route('booking.rute-jadwal');
    }

    // ══════════════════════════════════════════════════════════
    // STEP 2 — Pilih Arah & Tanggal & Jam
    // Jadwal BELUM dibuat di sini — hanya validasi & simpan session
    // ══════════════════════════════════════════════════════════

    public function ruteJadwal()
    {
        $armadaId = session('booking.armada_id');
        if (! $armadaId) return redirect()->route('booking.armada');

        $armada = Armada::findOrFail($armadaId);
        Rute::ensureRuteUtama();

        return view('booking.rute-jadwal', compact('armada'));
    }

    public function simpanRuteJadwal(Request $request)
    {
        $request->validate([
            'arah'            => 'required|in:barat_timur,timur_barat',
            'tanggal'         => 'required|date|after_or_equal:today',
            'jam_penjemputan' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $jam = Carbon::createFromFormat('H:i', $value);
                    $min = Carbon::createFromFormat('H:i', '17:00');
                    $max = Carbon::createFromFormat('H:i', '22:00');
                    if ($jam->lt($min) || $jam->gt($max)) {
                        $fail('Jam penjemputan hanya tersedia antara 17.00 sampai 22.00 WIB.');
                    }
                },
            ],
        ]);

        $armadaId = session('booking.armada_id');
        $armada   = Armada::findOrFail($armadaId);
        $tanggal  = $request->tanggal;
        $arah     = $request->arah;

        // Pastikan rute ada
        $rute = Rute::cariByArah($arah);
        if (! $rute) {
            Rute::ensureRuteUtama();
            $rute = Rute::cariByArah($arah);
        }

        // ── HIACE: validasi jadwal sudah dibuat admin ─────────
        if ($armada->isAdminOnly()) {
            $jadwalAda = Jadwal::where('armada_id', $armadaId)
                ->whereDate('tanggal', $tanggal)
                ->where('arah', $arah)
                ->where('is_active', true)
                ->exists();

            if (! $jadwalAda) {
                $arahLabel = $arah === 'barat_timur' ? 'Barat → Timur' : 'Timur → Barat';
                return back()->withInput()->withErrors([
                    'tanggal' => "Belum ada jadwal {$armada->nama} arah {$arahLabel} "
                        . "untuk tanggal " . Carbon::parse($tanggal)->translatedFormat('d F Y')
                        . ". Pilih tanggal lain atau hubungi admin.",
                ]);
            }
        }

        // ── Simpan ke session — jadwal belum dibuat ───────────
        // Untuk Innova: jadwal dibuat nanti di prosesPemesanan()
        // Untuk Hiace:  jadwal_id diisi nanti juga di prosesPemesanan()
        session([
            'booking.armada_id'       => $armadaId,
            'booking.rute_id'         => $rute->id,
            'booking.arah'            => $arah,
            'booking.tanggal'         => $tanggal,
            'booking.jam_penjemputan' => $request->jam_penjemputan,
        ]);

        return redirect()->route('booking.kursi');
    }

    // ══════════════════════════════════════════════════════════
    // STEP 2b — Pilih Kursi
    // Tampilkan kursi terpesan dari jadwal yang sudah ada (jika ada)
    // Jika belum ada jadwal, semua kursi kosong
    // ══════════════════════════════════════════════════════════

    public function pilihKursi()
    {
        $armadaId = session('booking.armada_id');
        $arah     = session('booking.arah');
        $tanggal  = session('booking.tanggal');

        if (! $armadaId || ! $arah || ! $tanggal) {
            return redirect()->route('booking.rute-jadwal');
        }

        $armada = Armada::findOrFail($armadaId);

        // Cari jadwal yang sudah ada untuk tampilkan kursi terpesan
        $jadwalAda = Jadwal::where('armada_id', $armadaId)
            ->whereDate('tanggal', $tanggal)
            ->where('arah', $arah)
            ->where('is_active', true)
            ->first();

        // Kursi terpesan dari jadwal yang sudah ada
        // Jika belum ada jadwal → semua kursi masih kosong
        $kursiTerpesan = $jadwalAda ? $jadwalAda->kursi_terpesan : [];

        return view('booking.pilih-kursi', compact('armada', 'kursiTerpesan'));
    }

    public function simpanKursi(Request $request)
    {
        $request->validate([
            'kursi'   => 'required|array|min:1',
            'kursi.*' => 'integer|min:1',
        ]);
        session(['booking.kursi' => $request->kursi]);
        return redirect()->route('booking.detail');
    }

    // ══════════════════════════════════════════════════════════
    // STEP 3 — Detail Pemesanan
    // ══════════════════════════════════════════════════════════

    public function detailPemesanan()
    {
        $armadaId = session('booking.armada_id');
        $arah     = session('booking.arah');

        if (! $armadaId || ! $arah) return redirect()->route('booking.armada');

        $armada         = Armada::findOrFail($armadaId);
        $kursi          = session('booking.kursi', []);
        $jamPenjemputan = session('booking.jam_penjemputan');
        $arahRute       = $arah === 'barat_timur' ? 'Barat → Timur' : 'Timur → Barat';

        // Ambil jadwal jika sudah ada (untuk sidebar info harga dll)
        $jadwalAda = Jadwal::with('armada', 'rute')
            ->where('armada_id', $armadaId)
            ->whereDate('tanggal', session('booking.tanggal'))
            ->where('arah', $arah)
            ->where('is_active', true)
            ->first();

        // Harga dari jadwal yang ada, atau fallback ke harga armada
        $hargaPerOrang = $jadwalAda ? $jadwalAda->harga : $armada->harga_per_rute;

        return view('booking.detail', compact('armada', 'kursi', 'jamPenjemputan', 'arah', 'arahRute', 'hargaPerOrang'
        ));
    }

    public function simpanDetail(Request $request)
    {
        $request->validate([
            'asal_province_id'   => 'required|string',
            'asal_regency_id'    => 'required|string',
            'asal_district_id'   => 'required|string',
            'asal_village_id'    => 'required|string',
            'asal_jalan'         => 'required|string|max:255',
            'asal_rt'            => 'nullable|string|max:5',
            'asal_rw'            => 'nullable|string|max:5',
            'asal_kodepos'       => 'nullable|digits:5',
            'asal_patokan'       => 'nullable|string|max:255',
            'asal_lat'           => 'nullable|numeric|between:-90,90',
            'asal_lng'           => 'nullable|numeric|between:-180,180',

            'tujuan_province_id' => 'required|string',
            'tujuan_regency_id'  => 'required|string',
            'tujuan_district_id' => 'required|string',
            'tujuan_village_id'  => 'required|string',
            'tujuan_jalan'       => 'required|string|max:255',
            'tujuan_rt'          => 'nullable|string|max:5',
            'tujuan_rw'          => 'nullable|string|max:5',
            'tujuan_kodepos'     => 'nullable|digits:5',
            'tujuan_patokan'     => 'nullable|string|max:255',
            'tujuan_lat'         => 'nullable|numeric|between:-90,90',
            'tujuan_lng'         => 'nullable|numeric|between:-180,180',

            'jam_penjemputan'    => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $jam = Carbon::createFromFormat('H:i', $value);
                    $min = Carbon::createFromFormat('H:i', '17:00');
                    $max = Carbon::createFromFormat('H:i', '22:00');
                    if ($jam->lt($min) || $jam->gt($max)) {
                        $fail('Jam penjemputan hanya tersedia antara 17.00 sampai 22.00 WIB.');
                    }
                },
            ],
            'jumlah_bagasi'      => 'nullable|numeric|min:0|max:99',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $data                      = $request->all();
        $data['jumlah_bagasi']     = (int) ($data['jumlah_bagasi'] ?? 0);
        $data['titik_penjemputan'] = $this->buildAlamatLengkap($request, 'asal');
        $data['titik_tujuan']      = $this->buildAlamatLengkap($request, 'tujuan');

        session(['booking.detail' => $data]);
        return redirect()->route('booking.konfirmasi');
    }

    // ══════════════════════════════════════════════════════════
    // STEP 4 — Konfirmasi
    // ══════════════════════════════════════════════════════════

    public function konfirmasi()
    {
        $armadaId = session('booking.armada_id');
        $arah     = session('booking.arah');
        $detail   = session('booking.detail', []);
        $kursi    = session('booking.kursi', []);

        if (! $armadaId || ! $arah) return redirect()->route('booking.armada');

        $armada = Armada::findOrFail($armadaId);

        // Ambil harga dari jadwal yang sudah ada jika ada,
        // fallback ke harga armada
        $jadwalAda = Jadwal::where('armada_id', $armadaId)
            ->whereDate('tanggal', session('booking.tanggal'))
            ->where('arah', $arah)
            ->where('is_active', true)
            ->first();

        $hargaPerOrang       = $jadwalAda ? $jadwalAda->harga : $armada->harga_per_rute;
        $jumlahPenumpang     = count($kursi);
        $bagasiGratis        = $armada->bagasi_gratis * $jumlahPenumpang;
        $bagasiTambahan      = max(0, ($detail['jumlah_bagasi'] ?? 0) - $bagasiGratis);
        $biayaBagasiTambahan = $bagasiTambahan * $armada->biaya_bagasi_tambahan;
        $totalHarga          = ($hargaPerOrang * $jumlahPenumpang) + $biayaBagasiTambahan;
        $jamPenjemputan      = $detail['jam_penjemputan'] ?? session('booking.jam_penjemputan');
        $arahRute            = $arah === 'barat_timur' ? 'Barat → Timur' : 'Timur → Barat';
        $tanggal             = session('booking.tanggal');

        return view('booking.konfirmasi', compact(
            'armada', 'kursi', 'detail', 'jumlahPenumpang',
            'biayaBagasiTambahan', 'totalHarga', 'jamPenjemputan',
            'arah', 'arahRute', 'tanggal', 'hargaPerOrang'
        ));
    }

    // ══════════════════════════════════════════════════════════
    // PROSES — Jadwal dibuat DI SINI, dalam transaksi
    // Hanya terjadi saat user benar-benar konfirmasi pemesanan
    // ══════════════════════════════════════════════════════════

    public function prosesPemesanan(Request $request)
    {
        $armadaId = session('booking.armada_id');
        $ruteId   = session('booking.rute_id');
        $arah     = session('booking.arah');
        $tanggal  = session('booking.tanggal');
        $kursi    = session('booking.kursi', []);
        $detail   = session('booking.detail', []);

        $armada = Armada::findOrFail($armadaId);

        $jumlahPenumpang     = count($kursi);
        $bagasiGratis        = $armada->bagasi_gratis * $jumlahPenumpang;
        $bagasiTambahan      = max(0, ($detail['jumlah_bagasi'] ?? 0) - $bagasiGratis);
        $biayaBagasiTambahan = $bagasiTambahan * $armada->biaya_bagasi_tambahan;

        // $jamRaw         = $detail['jam_penjemputan'] ?? session('booking.jam_penjemputan') ?? '00:00';
        // $jamPenjemputan = strlen($jamRaw) === 5 ? $jamRaw . ':00' : $jamRaw;

        $jamPenjemputan = session('booking.jam_penjemputan');

// Jika session utama kosong, baru ambil dari detail form
if (!$jamPenjemputan) {
    $jamPenjemputan = $detail['jam_penjemputan'] ?? '17:00'; // Default ke batas bawah jika error
}

// Pastikan format H:i:s untuk database
if (strlen($jamPenjemputan) === 5) {
    $jamPenjemputan .= ':00';
}

        $kode = null;

        DB::transaction(function () use (
            $armada, $armadaId, $ruteId, $arah, $tanggal,
            $kursi, $detail, $jumlahPenumpang,
            $biayaBagasiTambahan, $jamPenjemputan, &$kode
        ) {
            // ── Cari atau buat jadwal di dalam transaksi ──────
            if ($armada->isAdminOnly()) {
                // Hiace: jadwal harus sudah ada dari admin
                $jadwal = Jadwal::where('armada_id', $armadaId)
                    ->whereDate('tanggal', $tanggal)
                    ->where('arah', $arah)
                    ->where('is_active', true)
                    ->firstOrFail();
            } else {
                // Innova: firstOrCreate — max 1 jadwal per arah per hari
                $jadwal = Jadwal::firstOrCreate(
                    [
                        'armada_id' => $armadaId,
                        'rute_id'   => $ruteId,
                        'arah'      => $arah,
                        'tanggal'   => $tanggal,
                    ],
                    [
                        'jam_berangkat' => '00:00:00',
                        'harga'         => $armada->harga_per_rute,
                        'is_active'     => true,
                    ]
                );
            }

            $totalHarga = ($jadwal->harga * $jumlahPenumpang) + $biayaBagasiTambahan;
            $kode       = Pemesanan::generateKode();

            $pemesanan = Pemesanan::create([
                'kode_pemesanan'        => $kode,
                'user_id'               => Auth::id(),
                'jadwal_id'             => $jadwal->id,
                'tanggal_pesan'         => now(),
                'jam_penjemputan'       => $jamPenjemputan,
                'titik_penjemputan'     => $detail['titik_penjemputan'],
                'titik_tujuan'          => $detail['titik_tujuan'],
                'jumlah_penumpang'      => $jumlahPenumpang,
                'jumlah_bagasi'         => $detail['jumlah_bagasi'] ?? 0,
                'biaya_bagasi_tambahan' => $biayaBagasiTambahan,
                'total_harga'           => $totalHarga,
                'status'                => 'akan_datang',
                'catatan'               => $detail['catatan'] ?? null,
            ]);

            foreach ($kursi as $nomorKursi) {
                PemesananKursi::create([
                    'pemesanan_id' => $pemesanan->id,
                    'nomor_kursi'  => $nomorKursi,
                ]);
            }
        });

        // Bersihkan semua session booking
        session()->forget([
            'booking.armada_id', 'booking.rute_id',
            'booking.arah', 'booking.tanggal',
            'booking.jam_penjemputan', 'booking.kursi', 'booking.detail',
        ]);

        return redirect()->route('riwayat.tiket', $kode);
    }

    // ══════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════════════════════════

    private function buildAlamatLengkap(Request $request, string $p): string
    {
        $province = $request->input("{$p}_province_name")
            ?: (DB::table('provinces')->find($request->input("{$p}_province_id"))?->name ?? '');
        $regency  = $request->input("{$p}_regency_name")
            ?: (DB::table('regencies')->find($request->input("{$p}_regency_id"))?->name ?? '');
        $district = $request->input("{$p}_district_name")
            ?: (DB::table('districts')->find($request->input("{$p}_district_id"))?->name ?? '');
        $village  = $request->input("{$p}_village_name")
            ?: (DB::table('villages')->find($request->input("{$p}_village_id"))?->name ?? '');

        $rt      = $request->input("{$p}_rt");
        $rw      = $request->input("{$p}_rw");
        $jalan   = $request->input("{$p}_jalan");
        $kodepos = $request->input("{$p}_kodepos");
        $patokan = $request->input("{$p}_patokan");
        $lat     = $request->input("{$p}_lat");
        $lng     = $request->input("{$p}_lng");

        $parts = array_filter([
            $jalan,
            $rt && $rw ? "RT {$rt}/RW {$rw}" : ($rt ? "RT {$rt}" : null),
            $village  ? "Kel. {$village}"  : null,
            $district ? "Kec. {$district}" : null,
            $regency,
            $province,
            $kodepos,
        ]);

        $alamat = implode(', ', $parts);
        if ($patokan) $alamat .= " (Patokan: {$patokan})";
        if ($lat && $lng) $alamat .= " [GPS: {$lat},{$lng}]";

        return $alamat;
    }
}