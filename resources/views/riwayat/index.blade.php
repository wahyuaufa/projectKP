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
              <a href="{{ route('riwayat.tiket', $p->kode_pemesanan) }}"
                 class="btn btn-sm btn-outline-secondary rounded-2"
                 title="Lihat Tiket">
                <i class="bi bi-eye"></i>
              </a>
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
@endsection
