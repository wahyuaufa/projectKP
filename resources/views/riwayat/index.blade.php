{{-- resources/views/riwayat/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Pemesanan')
@section('content')
<div class="container py-5" style="min-height:80vh">
  <h2 class="section-title mb-1" data-aos="fade-up">Riwayat Pemesanan</h2>
  <p class="text-muted mb-4" data-aos="fade-up">Berikut adalah riwayat pemesanan Anda</p>

  {{-- Tab Filter --}}
  <ul class="nav nav-tabs-gotrav nav mb-4" data-aos="fade-up">
    @foreach(['semua' => 'Semua', 'akan_datang' => 'Akan Datang', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $key => $label)
    <li class="nav-item">
      <a class="nav-link {{ $status === $key ? 'active' : '' }}"
         href="{{ route('riwayat.index', ['status' => $key]) }}">
        {{ $label }}
      </a>
    </li>
    @endforeach
  </ul>

  <div class="card-gotrav" data-aos="fade-up">
    @if($pemesanans->count())
    <div class="table-responsive">
      <table class="table riwayat-table mb-0">
        <thead>
          <tr>
            <th>No. Pesanan</th>
            <th>Tanggal</th>
            <th>Rute</th>
            <th>Armada</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pemesanans as $p)
          <tr>
            <td><strong style="color:var(--primary)">{{ $p->kode_pemesanan }}</strong></td>
            <td>
              {{ $p->jadwal->tanggal->format('d M Y') }}<br>
              <small class="text-muted">{{ $p->jadwal->jam_berangkat }}</small>
            </td>
            <td>
              <div style="font-size:.85rem;">{{ $p->jadwal->rute->kota_asal }}</div>
              <div style="font-size:.8rem;color:var(--muted);">→ {{ $p->jadwal->rute->kota_tujuan }}</div>
            </td>
            <td>
              {{ $p->jadwal->armada->nama }}<br>
              <small class="text-muted">{{ $p->jadwal->armada->jumlah_kursi }} Seat</small>
            </td>
            <td>
              <span class="badge-status {{ $p->status_badge }}">{{ $p->status_label }}</span>
            </td>
            <td>
              {{-- Lihat Tiket --}}
              <a href="{{ route('riwayat.tiket', $p->kode_pemesanan) }}"
                 class="btn btn-sm btn-outline-secondary rounded-2"
                 title="Lihat Tiket">
                <i class="bi bi-eye"></i>
              </a>

              {{-- Batalkan (hanya untuk akan_datang) --}}
              @if($p->status === 'akan_datang')
              <form method="POST" action="{{ route('riwayat.batalkan', $p->kode_pemesanan) }}"
                    class="d-inline"
                    onsubmit="return confirm('Yakin batalkan pesanan ini?')">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Batalkan">
                  <i class="bi bi-x-circle"></i>
                </button>
              </form>
              @endif

              {{-- Download Faktur: selalu tampil, disabled jika belum selesai --}}
              @if($p->status === 'selesai')
                <a href="{{ route('riwayat.faktur', $p->kode_pemesanan) }}"
                   class="btn btn-sm btn-outline-success rounded-2"
                   title="Download Faktur"
                   target="_blank">
                  <i class="bi bi-download"></i>
                </a>
              @else
                <button type="button"
                        class="btn btn-sm btn-outline-secondary rounded-2 btn-faktur-disabled"
                        title="Faktur belum tersedia"
                        data-status="{{ $p->status_label }}">
                  <i class="bi bi-download"></i>
                </button>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $pemesanans->links() }}</div>
    @else
    <div class="p-5 text-center">
      <i class="bi bi-inbox fs-1 d-block mb-3" style="color:var(--border)"></i>
      <div style="font-family:var(--font-heading);font-weight:700;color:var(--muted)">Belum ada pemesanan</div>
      <a href="{{ route('booking.armada') }}" class="btn-gotrav btn mt-3">Booking Sekarang</a>
    </div>
    @endif
  </div>
</div>

{{-- ── Toast Notification ── --}}
<div id="faktur-toast" style="
  position:fixed; bottom:28px; right:28px; z-index:9999;
  display:none; align-items:center; gap:12px;
  background:#1e293b; color:#f1f5f9;
  padding:14px 18px; border-radius:12px;
  box-shadow:0 8px 32px rgba(0,0,0,.2);
  font-size:.875rem; font-weight:500;
  min-width:280px; max-width:360px;
">
  <span style="
    display:inline-flex; align-items:center; justify-content:center;
    width:34px; height:34px; border-radius:8px;
    background:rgba(251,191,36,.15); flex-shrink:0;
  ">
    <i class="bi bi-lock-fill" style="color:#fbbf24; font-size:1rem;"></i>
  </span>
  <div style="flex:1; min-width:0;">
    <div style="font-weight:700; margin-bottom:2px;">Faktur belum tersedia</div>
    <div id="faktur-toast-msg" style="color:#94a3b8; font-size:.8rem; line-height:1.4;"></div>
  </div>
  <button onclick="closeToast()" style="
    background:none; border:none; color:#64748b;
    cursor:pointer; padding:2px; line-height:1; flex-shrink:0;
  "><i class="bi bi-x-lg"></i></button>
</div>

<style>
  @keyframes gt-slide-up {
    from { opacity:0; transform:translateY(10px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .btn-faktur-disabled {
    opacity:.45;
    cursor:not-allowed !important;
  }
</style>

<script>
  var _toastTimer;

  document.querySelectorAll('.btn-faktur-disabled').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var label = this.dataset.status || 'belum selesai';
      showFakturToast(
        'Faktur hanya tersedia jika status pesanan <strong>Selesai</strong>. ' +
        'Status saat ini: <strong>' + label + '</strong>.'
      );
    });
  });

  function showFakturToast(msg) {
    var toast = document.getElementById('faktur-toast');
    document.getElementById('faktur-toast-msg').innerHTML = msg;
    toast.style.display = 'flex';
    toast.style.animation = 'none';
    void toast.offsetWidth; // reflow — paksa animasi restart
    toast.style.animation = 'gt-slide-up .25s ease forwards';
    clearTimeout(_toastTimer);
    _toastTimer = setTimeout(closeToast, 4500);
  }

  function closeToast() {
    document.getElementById('faktur-toast').style.display = 'none';
  }
</script>
@endsection