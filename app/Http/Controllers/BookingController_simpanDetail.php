<?php
// Ganti method simpanDetail() di BookingController.php dengan ini:

public function simpanDetail(Request $request)
{
    $data = $request->validate([
        // Asal
        'asal_province' => 'required|string',
        'asal_regency'  => 'required|string',
        'asal_district' => 'required|string',
        'asal_village'  => 'required|string',
        'asal_jalan'    => 'required|string|max:255',
        'asal_rt'       => 'nullable|string|max:5',
        'asal_rw'       => 'nullable|string|max:5',
        'asal_kodepos'  => 'nullable|string|max:5',
        'asal_patokan'  => 'nullable|string|max:255',
        'asal_lat'      => 'nullable|numeric',
        'asal_lng'      => 'nullable|numeric',

        // Tujuan
        'tujuan_province' => 'required|string',
        'tujuan_regency'  => 'required|string',
        'tujuan_district' => 'required|string',
        'tujuan_village'  => 'required|string',
        'tujuan_jalan'    => 'required|string|max:255',
        'tujuan_rt'       => 'nullable|string|max:5',
        'tujuan_rw'       => 'nullable|string|max:5',
        'tujuan_kodepos'  => 'nullable|string|max:5',
        'tujuan_patokan'  => 'nullable|string|max:255',
        'tujuan_lat'      => 'nullable|numeric',
        'tujuan_lng'      => 'nullable|numeric',

        // Lainnya
        'jumlah_bagasi' => 'nullable|integer|min:0',
        'catatan'       => 'nullable|string',
    ]);

    // Bangun string alamat lengkap untuk kolom titik_penjemputan & titik_tujuan
    $data['titik_penjemputan'] = self::buildAlamatString($request, 'asal');
    $data['titik_tujuan']      = self::buildAlamatString($request, 'tujuan');

    session(['booking.detail' => $data]);
    return redirect()->route('booking.konfirmasi');
}

// ── Helper: bangun string alamat ─────────────────────────────
private static function buildAlamatString(Request $request, string $prefix): string
{
    // Ambil nama teks dari database berdasarkan ID
    $province = \DB::table('provinces')->find($request->{$prefix . '_province'})?->name ?? $request->{$prefix . '_province'};
    $regency  = \DB::table('regencies')->find($request->{$prefix . '_regency'})?->name  ?? $request->{$prefix . '_regency'};
    $district = \DB::table('districts')->find($request->{$prefix . '_district'})?->name ?? $request->{$prefix . '_district'};
    $village  = \DB::table('villages')->find($request->{$prefix . '_village'})?->name   ?? $request->{$prefix . '_village'};

    $parts = array_filter([
        $request->{$prefix . '_jalan'},
        $request->{$prefix . '_rt'} ? 'RT ' . $request->{$prefix . '_rt'} : null,
        $request->{$prefix . '_rw'} ? 'RW ' . $request->{$prefix . '_rw'} : null,
        $village,
        'Kec. ' . $district,
        $regency,
        $province,
        $request->{$prefix . '_kodepos'},
    ]);

    $alamat = implode(', ', $parts);

    if ($request->{$prefix . '_patokan'}) {
        $alamat .= ' (Patokan: ' . $request->{$prefix . '_patokan'} . ')';
    }

    if ($request->{$prefix . '_lat'} && $request->{$prefix . '_lng'}) {
        $alamat .= ' [Maps: ' . $request->{$prefix . '_lat'} . ',' . $request->{$prefix . '_lng'} . ']';
    }

    return $alamat;
}
