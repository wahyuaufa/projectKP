{{-- resources/views/admin/pemesanan/cetak-harian.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Data Penjemputan — {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=DM+Sans:wght@400;500&display=swap');
    body { font-family: 'DM Sans', sans-serif; font-size: 13px; color: #1a2332; background: #f0f4f8; }
    .print-page { max-width: 900px; margin: 1.5rem auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
    .print-header { background: #1A3A5C; color: #fff; padding: 1.2rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .print-title  { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.1rem; }
    .print-sub    { font-size: .78rem; opacity: .7; margin-top: .2rem; }
    .print-body   { padding: 1.2rem 1.5rem; }
    .jadwal-block { border: 1.5px solid #DEE2E6; border-radius: 10px; margin-bottom: 1.2rem; overflow: hidden; }
    .jadwal-head  { background: #f0f4f8; padding: .7rem 1rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #DEE2E6; }
    .jadwal-time  { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.3rem; color: #1A3A5C; }
    table         { width: 100%; border-collapse: collapse; }
    th            { background: #f8f9fb; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6C757D; padding: .55rem .8rem; border-bottom: 1px solid #DEE2E6; }
    td            { padding: .6rem .8rem; font-size: .82rem; border-bottom: 1px solid #f0f4f8; vertical-align: top; }
    tr:last-child td { border-bottom: none; }
    .no-print     { display: flex; gap: .5rem; justify-content: flex-end; padding: 1rem 1.5rem; border-top: 1px solid #DEE2E6; }
    .btn-print    { background: #1A3A5C; color: #fff; border: none; padding: .5rem 1.2rem; border-radius: 8px; font-size: .85rem; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-back     { background: #fff; color: #1A3A5C; border: 1.5px solid #DEE2E6; padding: .5rem 1.2rem; border-radius: 8px; font-size: .85rem; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem; }
    .maps-link    { color: #1A3A5C; font-size: .73rem; }
    @media print {
      body { background: #fff; }
      .print-page { box-shadow: none; border-radius: 0; margin: 0; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>
<div class="print-page">

  <div class="print-header">
    <div>
      <div class="print-title">🚐 PR GOTRAV Mitra Abadi</div>
      <div class="print-sub">Data Penjemputan Harian</div>
    </div>
    <div style="text-align:right">
      <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:1.1rem">
        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
      </div>
      <div style="font-size:.75rem;opacity:.7">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
  </div>

  <div class="print-body">

    {{-- Ringkasan --}}
    <div style="display:flex;gap:1.5rem;padding:.8rem 1rem;background:#f0f4f8;border-radius:8px;margin-bottom:1.2rem">
      @php
        $totalJadwal    = $jadwals->count();
        $totalPemesanan = $jadwals->sum(fn($j) => $j->pemesanans->count());
        $totalPenumpang = $jadwals->sum(fn($j) => $j->pemesanans->sum('jumlah_penumpang'));
      @endphp
      <div><strong>{{ $totalJadwal }}</strong><div style="font-size:.72rem;color:#6C757D">Jadwal</div></div>
      <div><strong>{{ $totalPemesanan }}</strong><div style="font-size:.72rem;color:#6C757D">Pemesanan</div></div>
      <div><strong>{{ $totalPenumpang }}</strong><div style="font-size:.72rem;color:#6C757D">Total Penumpang</div></div>
    </div>

    @forelse($jadwals as $jadwal)
    <div class="jadwal-block">
      <div class="jadwal-head">
        <div style="display:flex;align-items:center;gap:1rem">
          <span class="jadwal-time">{{ substr($jadwal->jam_berangkat,0,5) }}</span>
          <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem">{{ $jadwal->armada->nama }}</div>
            <div style="font-size:.75rem;color:#6C757D">{{ $jadwal->rute->kota_asal }} → {{ $jadwal->rute->kota_tujuan }}</div>
          </div>
        </div>
        <div style="text-align:right">
          <div style="font-size:.8rem;color:#6C757D">Driver</div>
          <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.88rem">
            {{ $jadwal->driver?->nama ?? '— Belum ditugaskan —' }}
          </div>
          @if($jadwal->driver)
          <div style="font-size:.73rem;color:#6C757D">{{ $jadwal->driver->no_whatsapp }}</div>
          @endif
        </div>
      </div>

      @if($jadwal->pemesanans->count())
      <table>
        <thead>
          <tr>
            <th style="width:30px">#</th>
            <th>Penumpang</th>
            <th>Kursi</th>
            <th>Titik Penjemputan</th>
            <th>Titik Tujuan</th>
            <th>Bagasi</th>
            <th>Catatan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($jadwal->pemesanans as $i => $p)
          <tr>
            <td style="color:#6C757D;font-weight:700">{{ $i+1 }}</td>
            <td>
              <strong>{{ $p->user->nama_lengkap }}</strong><br>
              <span style="color:#6C757D">{{ $p->user->no_whatsapp }}</span><br>
              <span style="color:#1A3A5C;font-weight:600">{{ $p->jumlah_penumpang }} orang · Kursi {{ $p->kursis->pluck('nomor_kursi')->join(', ') }}</span>
            </td>
            <td>{{ $p->kursis->pluck('nomor_kursi')->join(', ') }}</td>
            <td>
              @php
                $jemput = $p->titik_penjemputan;
                $gpsJ = null;
                if (preg_match('/\[GPS: ([\-\d\.]+),([\-\d\.]+)\]/', $jemput, $m)) {
                  $gpsJ = "https://maps.google.com/?q={$m[1]},{$m[2]}";
                  $jemput = preg_replace('/\s*\[GPS:[^\]]+\]/', '', $jemput);
                }
              @endphp
              {{ $jemput }}
              @if($gpsJ)
              <br><a href="{{ $gpsJ }}" class="maps-link">📍 Lihat Maps</a>
              @endif
            </td>
            <td>
              @php
                $tujuan = $p->titik_tujuan;
                $gpsT = null;
                if (preg_match('/\[GPS: ([\-\d\.]+),([\-\d\.]+)\]/', $tujuan, $m)) {
                  $gpsT = "https://maps.google.com/?q={$m[1]},{$m[2]}";
                  $tujuan = preg_replace('/\s*\[GPS:[^\]]+\]/', '', $tujuan);
                }
              @endphp
              {{ $tujuan }}
              @if($gpsT)
              <br><a href="{{ $gpsT }}" class="maps-link">📍 Lihat Maps</a>
              @endif
            </td>
            <td>{{ $p->jumlah_bagasi ?: '-' }}</td>
            <td style="color:#6C757D">{{ $p->catatan ?: '-' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <div style="padding:1rem;text-align:center;color:#6C757D;font-size:.83rem">Belum ada pemesanan</div>
      @endif

      @if($jadwal->catatan_admin)
      <div style="padding:.6rem 1rem;background:#fffbf0;font-size:.78rem;color:#7a5000;border-top:1px solid #DEE2E6">
        <i class="bi bi-sticky"></i> <strong>Catatan:</strong> {{ $jadwal->catatan_admin }}
      </div>
      @endif
    </div>
    @empty
    <div style="text-align:center;padding:3rem;color:#6C757D">Tidak ada jadwal untuk tanggal ini.</div>
    @endforelse
  </div>

  <div class="no-print">
    <a href="{{ route('admin.pemesanan.cetak-harian') }}?tanggal={{ $tanggal }}" class="btn-back">
      ← Ganti Tanggal
    </a>
    <button class="btn-print" onclick="window.print()">
      <i class="bi bi-printer"></i> Cetak / Simpan PDF
    </button>
  </div>
</div>
</body>
</html>
