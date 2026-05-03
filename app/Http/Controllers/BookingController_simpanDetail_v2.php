<?php
// Ganti method simpanDetail() dan tambahkan helper buildAlamat()
// di dalam class BookingController

public function simpanDetail(Request $request)
{
    $request->validate([
        // Asal — wajib
        'asal_province_id'   => 'required|string',
        'asal_regency_id'    => 'required|string',
        'asal_district_id'   => 'required|string',
        'asal_village_id'    => 'required|string',
        'asal_jalan'         => 'required|string|max:255',
        // Asal — opsional
        'asal_rt'            => 'nullable|string|max:5',
        'asal_rw'            => 'nullable|string|max:5',
        'asal_kodepos'       => 'nullable|digits:5',
        'asal_patokan'       => 'nullable|string|max:255',
        'asal_lat'           => 'nullable|numeric|between:-90,90',
        'asal_lng'           => 'nullable|numeric|between:-180,180',

        // Tujuan — wajib
        'tujuan_province_id' => 'required|string',
        'tujuan_regency_id'  => 'required|string',
        'tujuan_district_id' => 'required|string',
        'tujuan_village_id'  => 'required|string',
        'tujuan_jalan'       => 'required|string|max:255',
        // Tujuan — opsional
        'tujuan_rt'          => 'nullable|string|max:5',
        'tujuan_rw'          => 'nullable|string|max:5',
        'tujuan_kodepos'     => 'nullable|digits:5',
        'tujuan_patokan'     => 'nullable|string|max:255',
        'tujuan_lat'         => 'nullable|numeric|between:-90,90',
        'tujuan_lng'         => 'nullable|numeric|between:-180,180',

        // Lainnya
        'jumlah_bagasi'      => 'nullable|integer|min:0|max:99',
        'catatan'            => 'nullable|string|max:500',
    ]);

    $data = $request->all();

    // Bangun string alamat lengkap dari nama wilayah
    $data['titik_penjemputan'] = $this->buildAlamat($request, 'asal');
    $data['titik_tujuan']      = $this->buildAlamat($request, 'tujuan');

    session(['booking.detail' => $data]);

    return redirect()->route('booking.konfirmasi');
}

// ── Helper: susun string alamat lengkap ──────────────────────
private function buildAlamat(Request $request, string $p): string
{
    // Nama wilayah sudah dikirim dari hidden input (terisi via JS)
    $province = $request->input("{$p}_province_name")
        ?? \DB::table('provinces')->find($request->input("{$p}_province_id"))?->name ?? '-';
    $regency  = $request->input("{$p}_regency_name")
        ?? \DB::table('regencies')->find($request->input("{$p}_regency_id"))?->name  ?? '-';
    $district = $request->input("{$p}_district_name")
        ?? \DB::table('districts')->find($request->input("{$p}_district_id"))?->name ?? '-';
    $village  = $request->input("{$p}_village_name")
        ?? \DB::table('villages')->find($request->input("{$p}_village_id"))?->name   ?? '-';

    $rt      = $request->input("{$p}_rt");
    $rw      = $request->input("{$p}_rw");
    $kodepos = $request->input("{$p}_kodepos");
    $patokan = $request->input("{$p}_patokan");
    $lat     = $request->input("{$p}_lat");
    $lng     = $request->input("{$p}_lng");

    // Susun bagian-bagian alamat
    $parts = array_filter([
        $request->input("{$p}_jalan"),
        ($rt && $rw) ? "RT {$rt}/RW {$rw}" : ($rt ? "RT {$rt}" : null),
        "Kel. {$village}",
        "Kec. {$district}",
        $regency,
        $province,
        $kodepos,
    ]);

    $alamat = implode(', ', $parts);

    if ($patokan) {
        $alamat .= " (Patokan: {$patokan})";
    }

    if ($lat && $lng) {
        $alamat .= " [GPS: {$lat},{$lng}]";
    }

    return $alamat;
}
