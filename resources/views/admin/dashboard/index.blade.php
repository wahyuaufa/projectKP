{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Stat Cards ─────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
  @php $stats = [
    ['Jadwal Hari Ini',    $jadwalHariIni,     'bi-calendar-check',  '#1A3A5C', 'rgba(26,58,92,.1)'],
    ['Pemesanan Hari Ini', $pemesananHariIni,  'bi-receipt',          '#c0392b', 'rgba(192,57,43,.1)'],
    ['Penumpang Hari Ini', $penumpangHariIni,  'bi-people-fill',      '#1e7e34', 'rgba(30,126,52,.1)'],
    ['Pendapatan Hari Ini','Rp '.number_format($pendapatanHariIni,0,',','.'), 'bi-cash-stack', '#b07000', 'rgba(176,112,0,.1)'],
  ]; @endphp

  @foreach($stats as $s)
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:{{ $s[4] }};color:{{ $s[3] }}">
        <i class="bi {{ $s[2] }}"></i>
      </div>
      <div>
        <div class="stat-label">{{ $s[0] }}</div>
        <div class="stat-value">{{ $s[1] }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="row g-3 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(26,58,92,.08);color:var(--primary)">
        <i class="bi bi-person-badge"></i>
      </div>
      <div>
        <div class="stat-label">Driver Aktif</div>
        <div class="stat-value">{{ $totalDriver }}</div>
        <a href="{{ route('admin.driver.index') }}" style="font-size:.75rem;color:var(--primary)">Kelola driver →</a>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(244,160,32,.1);color:#b07000">
        <i class="bi bi-truck"></i>
      </div>
      <div>
        <div class="stat-label">Armada Aktif</div>
        <div class="stat-value">{{ $totalArmada }}</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">

  {{-- ── Grafik ──────────────────────────────────────────── --}}
  <div class="col-lg-8">
    <div class="acard">
      <div class="acard-header">
        <span><i class="bi bi-bar-chart-line me-2" style="color:var(--primary)"></i>Pemesanan 30 Hari Terakhir</span>
      </div>
      <div class="acard-body" style="padding:1rem">
        <canvas id="grafikPemesanan" height="100"></canvas>
      </div>
    </div>
  </div>

  {{-- ── Jadwal Mendatang ────────────────────────────────── --}}
  <div class="col-lg-4">
    <div class="acard h-100">
      <div class="acard-header">
        <span><i class="bi bi-clock me-2" style="color:var(--primary)"></i>Jadwal 7 Hari ke Depan</span>
        <a href="{{ route('admin.jadwal.index') }}" class="abtn abtn-outline abtn-sm">Kelola</a>
      </div>
      <div style="max-height:340px;overflow-y:auto">
        @forelse($jadwalMendatang as $j)
        <div style="padding:.8rem 1.3rem;border-bottom:1px solid var(--border)">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div style="font-family:var(--font-head);font-weight:700;font-size:.88rem">
                {{ \Carbon\Carbon::parse($j->tanggal)->format('d M') }}
                · {{ substr($j->jam_berangkat,0,5) }}
              </div>
              <div style="font-size:.78rem;color:var(--muted);margin-top:.1rem">
                {{ $j->armada->nama }} · {{ $j->pemesanans->count() }} pemesan
              </div>
            </div>
            @if($j->driver)
            <span class="abadge abadge-assign" style="font-size:.7rem">
              <i class="bi bi-person-check"></i>{{ Str::limit($j->driver->nama,10) }}
            </span>
            @else
            <span class="abadge" style="background:rgba(220,53,69,.1);color:var(--danger);font-size:.7rem">
              <i class="bi bi-person-x"></i>Belum ada
            </span>
            @endif
          </div>
        </div>
        @empty
        <div class="p-4 text-center" style="color:var(--muted);font-size:.85rem">
          <i class="bi bi-calendar-x d-block mb-2" style="font-size:1.5rem"></i>
          Tidak ada jadwal dalam 7 hari ke depan
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>

{{-- ── Pemesanan Terbaru ────────────────────────────────── --}}
<div class="acard">
  <div class="acard-header">
    <span><i class="bi bi-receipt me-2" style="color:var(--primary)"></i>Pemesanan Terbaru</span>
    <a href="{{ route('admin.pemesanan.index') }}" class="abtn abtn-outline abtn-sm">Lihat Semua</a>
  </div>
  <div style="overflow-x:auto">
    <table class="atable">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Pelanggan</th>
          <th>Jadwal</th>
          <th>Penumpang</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pemesananTerbaru as $p)
        <tr>
          <td><strong style="color:var(--primary);font-size:.85rem">{{ $p->kode_pemesanan }}</strong></td>
          <td>
            <div style="font-weight:600;font-size:.88rem">{{ $p->user->nama_lengkap }}</div>
            <div style="font-size:.75rem;color:var(--muted)">{{ $p->user->no_whatsapp }}</div>
          </td>
          <td style="font-size:.82rem">
            {{ \Carbon\Carbon::parse($p->jadwal->tanggal)->format('d M Y') }}<br>
            <span style="color:var(--muted)">{{ $p->jadwal->armada->nama }}</span>
          </td>
          <td><span style="font-weight:700">{{ $p->jumlah_penumpang }}</span> orang</td>
          <td>
            @php $badge = match($p->status) {
              'akan_datang' => 'abadge-akan',
              'selesai'     => 'abadge-selesai',
              'dibatalkan'  => 'abadge-batal',
              default       => ''
            }; @endphp
            <span class="abadge {{ $badge }}">{{ $p->status_label }}</span>
          </td>
          <td>
            <a href="{{ route('admin.pemesanan.show', $p->id) }}" class="abtn abtn-outline abtn-sm">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center" style="color:var(--muted);padding:2rem">Belum ada pemesanan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('grafikPemesanan').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: {!! json_encode($labels) !!},
    datasets: [
      {
        label: 'Pemesanan',
        data: {!! json_encode($totals) !!},
        backgroundColor: 'rgba(26,58,92,.75)',
        borderRadius: 6,
        borderSkipped: false,
      },
      {
        label: 'Penumpang',
        data: {!! json_encode($penumpangs) !!},
        backgroundColor: 'rgba(244,160,32,.6)',
        borderRadius: 6,
        borderSkipped: false,
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top', labels: { font: { family: 'Plus Jakarta Sans', size: 12 } } },
    },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 10 } } },
      y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } } }
    }
  }
});
</script>
@endpush
