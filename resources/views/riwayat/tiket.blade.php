{{-- resources/views/riwayat/tiket.blade.php --}}
@extends('layouts.app')
@section('title', 'Tiket - ' . $pemesanan->kode_pemesanan)
@section('content')
<div class="container py-5" style="min-height:80vh">
  <div class="row justify-content-center">
    <div class="col-lg-6">

      <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <a href="{{ route('riwayat.index') }}" class="btn-outline-gotrav btn btn-sm">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-gotrav btn btn-sm">
          <i class="bi bi-printer me-1"></i> Cetak
        </button>
      </div>

      <div class="ticket-card" data-aos="fade-up">
        {{-- Header --}}
        <div class="ticket-header">
          <div>
            <div class="ticket-brand"><i class="bi bi-bus-front-fill me-2"></i>PR GOTRAV</div>
            <div style="font-size:.8rem;opacity:.75;margin-top:.2rem;">Mitra Abadi</div>
          </div>
          <span class="badge-status {{ $pemesanan->status_badge }}" style="font-size:.82rem;">
            {{ $pemesanan->status_label }}
          </span>
        </div>

        {{-- Body --}}
        <div class="ticket-body">
          <div class="ticket-kode">{{ $pemesanan->kode_pemesanan }}</div>

          {{-- Barcode placeholder --}}
          <div class="text-center my-3">
            <svg viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg" style="max-width:180px">
              @php
                $bars = [3,1,2,1,3,2,1,4,1,2,3,1,2,1,3,1,2,3,1,4,2,1,3,1,2,1,3,2,1,3];
                $x = 10;
                foreach($bars as $i => $w):
              @endphp
              <rect x="{{ $x }}" y="5" width="{{ $w * 2 }}" height="50" fill="{{ $i % 2 === 0 ? '#1A3A5C' : 'transparent' }}"/>
              @php $x += $w * 2 + 1; endforeach; @endphp
            </svg>
            <div style="font-size:.7rem;color:var(--muted);letter-spacing:.05em;margin-top:.3rem;">
              {{ $pemesanan->kode_pemesanan }}
            </div>
          </div>

          {{-- Route visual --}}
          <div style="padding:1rem;background:var(--bg-section);border-radius:var(--radius);margin:1rem 0">
            <div class="d-flex align-items-center gap-2 mb-2">
              <div style="width:10px;height:10px;background:var(--success);border-radius:50%;flex-shrink:0"></div>
              <div>
                <div style="font-size:.75rem;color:var(--muted)">Penjemputan</div>
                <div style="font-weight:600;font-size:.9rem;">{{ $pemesanan->titik_penjemputan }}</div>
              </div>
            </div>
            <div class="d-flex align-items-center gap-2">
              <div style="width:10px;height:10px;background:var(--danger);border-radius:50%;flex-shrink:0"></div>
              <div>
                <div style="font-size:.75rem;color:var(--muted)">Tujuan</div>
                <div style="font-weight:600;font-size:.9rem;">{{ $pemesanan->titik_tujuan }}</div>
              </div>
            </div>
          </div>

          <div class="ticket-divider"></div>

          <div class="ticket-row"><div class="ticket-row-label">Tanggal</div><div class="ticket-row-value">{{ $pemesanan->jadwal->tanggal->format('d M Y') }}</div></div>
          <div class="ticket-row"><div class="ticket-row-label">Waktu Jemput</div><div class="ticket-row-value">{{ $pemesanan->jam_penjemputan_format }} WIB</div></div>
          <div class="ticket-row">
            <div class="ticket-row-label">Armada</div>
            <div class="ticket-row-value">{{ $pemesanan->jadwal->armada->nama }}</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Kursi</div>
            <div class="ticket-row-value">{{ $pemesanan->kursis->pluck('nomor_kursi')->join(', ') }}</div>
          </div>
          <div class="ticket-row"><div class="ticket-row-label">Penumpang</div><div class="ticket-row-value">{{ $pemesanan->jumlah_penumpang }} Orang</div></div>
          <div class="ticket-row">
            <div class="ticket-row-label">Bagasi</div>
            <div class="ticket-row-value">
              {{ $pemesanan->jumlah_bagasi }} Tas, 1 Kardus (Gratis)
              @if($pemesanan->biaya_bagasi_tambahan > 0)
              <div class="text-danger" style="font-size:.8rem">Kelebihan bagasi dikenakan biaya tambahan</div>
              @endif
            </div>
          </div>

          <div class="ticket-divider"></div>

          <div class="p-3 rounded-3" style="background:rgba(26,58,92,.04);font-size:.82rem;color:var(--muted);text-align:center;line-height:1.6">
            <i class="bi bi-info-circle me-1"></i>
            Pembayaran dilakukan secara langsung kepada driver saat tiba di tujuan.<br>
            Terima kasih telah mempercayakan perjalanan Anda kepada kami.
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@push('styles')
<style>
  @media print {
    .navbar-gotrav, footer, .btn, a.btn { display: none !important; }
    body { background: white; }
    .ticket-card { box-shadow: none; border: 1px solid #ddd; }
  }
</style>
@endpush
@endsection
