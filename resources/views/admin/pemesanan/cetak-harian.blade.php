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

    :root {
      --navy: #1A3A5C;
      --navy-light: #f0f4f8;
      --border: #DEE2E6;
      --muted: #6C757D;
    }

    body { font-family: 'DM Sans', sans-serif; font-size: 13px; color: #1a2332; background: #e8edf3; }

    /* ── Nav bar ── */
    .nav-bar {
      background: var(--navy);
      padding: .75rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 12px rgba(0,0,0,.2);
    }

    .nav-brand {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 800;
      font-size: 1rem;
      color: #fff;
      display: flex;
      align-items: center;
      gap: .5rem;
      white-space: nowrap;
    }

    .nav-brand span { opacity: .6; font-weight: 500; font-size: .8rem; }

    .nav-date-nav {
      display: flex;
      align-items: center;
      gap: .5rem;
      background: rgba(255,255,255,.1);
      border-radius: 10px;
      padding: .35rem .6rem;
    }

    .nav-date-nav a, .nav-date-nav button {
      background: rgba(255,255,255,.15);
      border: none;
      color: #fff;
      border-radius: 7px;
      width: 32px; height: 32px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      text-decoration: none;
      font-size: .9rem;
      transition: background .15s;
    }

    .nav-date-nav a:hover, .nav-date-nav button:hover {
      background: rgba(255,255,255,.3);
      color: #fff;
    }

    .nav-date-input {
      background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.2);
      border-radius: 7px;
      color: #fff;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 700;
      font-size: .85rem;
      padding: .3rem .6rem;
      cursor: pointer;
      outline: none;
      width: 140px;
      text-align: center;
    }

    .nav-date-input::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .btn-nav {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      padding: .4rem 1rem;
      border-radius: 8px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 700;
      font-size: .82rem;
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: opacity .15s;
      white-space: nowrap;
    }

    .btn-nav:hover { opacity: .85; }
    .btn-nav-print { background: #fff; color: var(--navy); }
    .btn-nav-back  { background: rgba(255,255,255,.15); color: #fff; border: 1px solid rgba(255,255,255,.25); }

    /* ── Page ── */
    .print-page { max-width: 960px; margin: 1.5rem auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }

    .print-header { background: var(--navy); color: #fff; padding: 1.2rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .print-title  { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.1rem; }
    .print-sub    { font-size: .78rem; opacity: .7; margin-top: .2rem; }
    .print-body   { padding: 1.2rem 1.5rem; }

    .jadwal-block { border: 1.5px solid var(--border); border-radius: 10px; margin-bottom: 1.2rem; overflow: hidden; }
    .jadwal-head  { background: var(--navy-light); padding: .7rem 1rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); }
    .jadwal-time  { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.3rem; color: var(--navy); }

    table { width: 100%; border-collapse: collapse; }
    th    { background: #f8f9fb; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); padding: .55rem .8rem; border-bottom: 1px solid var(--border); }
    td    { padding: .6rem .8rem; font-size: .82rem; border-bottom: 1px solid var(--navy-light); vertical-align: top; }
    tr:last-child td { border-bottom: none; }

    .maps-link { color: var(--navy); font-size: .73rem; }

    /* ── Today badge ── */
    .badge-today {
      background: #dcfce7; color: #166534;
      font-size: .7rem; font-weight: 700;
      padding: 2px 8px; border-radius: 20px;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    @media print {
      body { background: #fff; }
      .nav-bar { display: none !important; }
      .print-page { box-shadow: none; border-radius: 0; margin: 0; max-width: 100%; }
    }

    @media (max-width: 600px) {
      .nav-brand span { display: none; }
      .nav-date-input { width: 120px; }
      .btn-nav-back { display: none; }
    }
  </style>
</head>
<body>

@php
  $tgl        = \Carbon\Carbon::parse($tanggal);
  $prevTanggal = $tgl->copy()->subDay()->format('Y-m-d');
  $nextTanggal = $tgl->copy()->addDay()->format('Y-m-d');
  $isToday    = $tgl->isToday();
@endphp

{{-- ── Sticky Nav Bar ── --}}
<nav class="nav-bar no-print">
  {{-- Brand --}}
  <div class="nav-brand">
    🚐 GoTrav <span>/ Cetak Harian</span>
  </div>

  {{-- Date navigation --}}
  <div class="nav-date-nav">
    <a href="{{ route('admin.pemesanan.cetak-harian', ['tanggal' => $prevTanggal]) }}" title="Hari sebelumnya">
      <i class="bi bi-chevron-left"></i>
    </a>
    <input type="date"
           class="nav-date-input"
           value="{{ $tanggal }}"
           onchange="window.location='{{ route('admin.pemesanan.cetak-harian') }}?tanggal='+this.value"
           title="Pilih tanggal">
    <a href="{{ route('admin.pemesanan.cetak-harian', ['tanggal' => $nextTanggal]) }}" title="Hari berikutnya">
      <i class="bi bi-chevron-right"></i>
    </a>
    @if(!$isToday)
    <a href="{{ route('admin.pemesanan.cetak-harian', ['tanggal' => today()->format('Y-m-d')]) }}"
       title="Hari ini" style="font-size:.7rem;width:auto;padding:0 8px;gap:3px;">
      <i class="bi bi-calendar-check"></i> Hari Ini
    </a>
    @endif
  </div>

  {{-- Actions --}}
  <div class="nav-actions">
    <a href="{{ route('admin.pemesanan.index') }}" class="btn-nav btn-nav-back">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <button class="btn-nav btn-nav-print" onclick="window.print()">
      <i class="bi bi-printer"></i> Cetak / PDF
    </button>
  </div>
</nav>

{{-- ── Print Page ── --}}
<div class="print-page">

  <div class="print-header">
    <div>
      <div class="print-title">🚐 PR GOTRAV Mitra Abadi</div>
      <div class="print-sub">Data Penjemputan Harian</div>
    </div>
    <div style="text-align:right">
      <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:1.1rem">
        {{ $tgl->translatedFormat('d F Y') }}
        @if($isToday) <span class="badge-today">Hari Ini</span> @endif
      </div>
      <div style="font-size:.75rem;opacity:.7">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
  </div>

  <div class="print-body">

    {{-- Ringkasan --}}
    <div style="display:flex;gap:1.5rem;padding:.8rem 1rem;background:var(--navy-light);border-radius:8px;margin-bottom:1.2rem">
      @php
        $totalJadwal    = $jadwals->count();
        $totalPemesanan = $jadwals->sum(fn($j) => $j->pemesanans->count());
        $totalPenumpang = $jadwals->sum(fn($j) => $j->pemesanans->sum('jumlah_penumpang'));
      @endphp
      <div><strong>{{ $totalJadwal }}</strong><div style="font-size:.72rem;color:var(--muted)">Jadwal</div></div>
      <div><strong>{{ $totalPemesanan }}</strong><div style="font-size:.72rem;color:var(--muted)">Pemesanan</div></div>
      <div><strong>{{ $totalPenumpang }}</strong><div style="font-size:.72rem;color:var(--muted)">Total Penumpang</div></div>
    </div>

    @forelse($jadwals as $jadwal)
    <div class="jadwal-block">
      <div class="jadwal-head">
        <div style="display:flex;align-items:center;gap:1rem">
          <span class="jadwal-time">{{ substr($jadwal->jam_berangkat,0,5) }}</span>
          <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem">{{ $jadwal->armada->nama }}</div>
            <div style="font-size:.75rem;color:var(--muted)">{{ $jadwal->rute->kota_asal }} → {{ $jadwal->rute->kota_tujuan }}</div>
          </div>
        </div>
        <div style="text-align:right">
          <div style="font-size:.8rem;color:var(--muted)">Driver</div>
          <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.88rem">
            {{ $jadwal->driver?->nama ?? '— Belum ditugaskan —' }}
          </div>
          @if($jadwal->driver)
          <div style="font-size:.73rem;color:var(--muted)">{{ $jadwal->driver->no_whatsapp }}</div>
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
            <td style="color:var(--muted);font-weight:700">{{ $i+1 }}</td>
            <td>
              <strong>{{ $p->user->nama_lengkap }}</strong><br>
              <span style="color:var(--muted)">{{ $p->user->no_whatsapp }}</span><br>
              <span style="color:var(--navy);font-weight:600">{{ $p->jumlah_penumpang }} orang</span>
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
              @if($gpsJ)<br><a href="{{ $gpsJ }}" class="maps-link">📍 Lihat Maps</a>@endif
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
              @if($gpsT)<br><a href="{{ $gpsT }}" class="maps-link">📍 Lihat Maps</a>@endif
            </td>
            <td>{{ $p->jumlah_bagasi ?: '-' }}</td>
            <td style="color:var(--muted)">{{ $p->catatan ?: '-' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <div style="padding:1rem;text-align:center;color:var(--muted);font-size:.83rem">Belum ada pemesanan</div>
      @endif

      @if($jadwal->catatan_admin)
      <div style="padding:.6rem 1rem;background:#fffbf0;font-size:.78rem;color:#7a5000;border-top:1px solid var(--border)">
        <i class="bi bi-sticky"></i> <strong>Catatan:</strong> {{ $jadwal->catatan_admin }}
      </div>
      @endif
    </div>
    @empty
    <div style="text-align:center;padding:3rem;color:var(--muted)">
      <i class="bi bi-calendar-x" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
      Tidak ada jadwal untuk tanggal ini.
    </div>
    @endforelse

  </div>
</div>

<div style="height:2rem"></div>
</body>
</html>