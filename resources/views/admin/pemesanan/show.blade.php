{{-- resources/views/admin/pemesanan/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Detail Pemesanan — ' . $pemesanan->kode_pemesanan)
@section('page-title', 'Detail Pemesanan')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
  <a href="{{ route('admin.pemesanan.index') }}" class="abtn abtn-outline abtn-sm">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
  <button onclick="window.print()" class="abtn abtn-outline abtn-sm">
    <i class="bi bi-printer"></i> Cetak
  </button>
</div>

<div class="row g-4">

  {{-- ── Kiri: Detail Pemesanan ──────────────────────────── --}}
  <div class="col-lg-7">

    {{-- Kode & Status --}}
    <div class="acard mb-3">
      <div class="acard-body">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
          <div>
            <div style="font-size:.75rem;color:var(--muted);font-weight:700;
                        letter-spacing:.06em;text-transform:uppercase">Kode Pemesanan</div>
            <div style="font-family:var(--font-head);font-size:1.6rem;font-weight:800;
                        color:var(--primary);letter-spacing:.04em">
              {{ $pemesanan->kode_pemesanan }}
            </div>
            <div style="font-size:.82rem;color:var(--muted);margin-top:.2rem">
              Dipesan {{ $pemesanan->tanggal_pesan->diffForHumans() }} ·
              {{ $pemesanan->tanggal_pesan->format('d M Y H:i') }}
            </div>
          </div>
          @php $badge = match($pemesanan->status) {
            'akan_datang' => 'abadge-akan',
            'selesai'     => 'abadge-selesai',
            'dibatalkan'  => 'abadge-batal',
            default       => ''
          }; @endphp
          <span class="abadge {{ $badge }}" style="font-size:.85rem;padding:.4rem 1rem">
            {{ $pemesanan->status_label }}
          </span>
        </div>
      </div>
    </div>

    {{-- Info Jadwal --}}
    <div class="acard mb-3">
      <div class="acard-header">
        <i class="bi bi-calendar-check me-2" style="color:var(--primary)"></i>
        Informasi Jadwal
      </div>
      <div class="acard-body">
        <div class="row g-0">
          @php $infoJadwal = [
            ['Armada',        $pemesanan->jadwal->armada->nama],
            ['Tanggal',       $pemesanan->jadwal->tanggal->format('l, d F Y')],
            ['Jam Berangkat', $pemesanan->jadwal->jam_berangkat === '00:00:00'
                              ? 'Fleksibel' : substr($pemesanan->jadwal->jam_berangkat, 0, 5) . ' WIB'],
            ['Jam Penjemputan', substr($pemesanan->jam_penjemputan ?? '00:00:00', 0, 5) . ' WIB'],
            ['Arah',          $pemesanan->jadwal->rute->kota_asal . ' → ' . $pemesanan->jadwal->rute->kota_tujuan],
            ['Driver',        $pemesanan->jadwal->driver?->nama ?? '— Belum ditugaskan'],
          ]; @endphp
          @foreach($infoJadwal as $item)
          <div class="col-12" style="padding:.55rem 0;border-bottom:.5px solid var(--border);
                                     display:flex;justify-content:space-between;gap:1rem">
            <span style="font-size:.82rem;color:var(--muted);font-weight:600">{{ $item[0] }}</span>
            <span style="font-size:.85rem;font-weight:600;text-align:right">{{ $item[1] }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Info Penumpang --}}
    <div class="acard mb-3">
      <div class="acard-header">
        <i class="bi bi-person me-2" style="color:var(--primary)"></i>
        Informasi Penumpang
      </div>
      <div class="acard-body">
        <div class="row g-0">
          @php $infoPenumpang = [
            ['Nama',         $pemesanan->user->nama_lengkap],
            ['No. WhatsApp', $pemesanan->user->no_whatsapp],
            ['Jml Penumpang',$pemesanan->jumlah_penumpang . ' orang'],
            ['Nomor Kursi',  $pemesanan->kursis->pluck('nomor_kursi')->join(', ')],
            ['Jumlah Bagasi',$pemesanan->jumlah_bagasi . ' item'],
          ]; @endphp
          @foreach($infoPenumpang as $item)
          <div class="col-12" style="padding:.55rem 0;border-bottom:.5px solid var(--border);
                                     display:flex;justify-content:space-between;gap:1rem">
            <span style="font-size:.82rem;color:var(--muted);font-weight:600">{{ $item[0] }}</span>
            <span style="font-size:.85rem;font-weight:600;text-align:right">{{ $item[1] }}</span>
          </div>
          @endforeach
          @if($pemesanan->catatan)
          <div class="col-12" style="padding:.55rem 0">
            <span style="font-size:.82rem;color:var(--muted);font-weight:600">Catatan</span>
            <div style="font-size:.85rem;margin-top:.3rem;padding:.6rem .8rem;
                        background:rgba(244,160,32,.08);border-radius:6px;
                        border-left:3px solid var(--accent)">
              {{ $pemesanan->catatan }}
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Titik Penjemputan & Tujuan --}}
    <div class="acard mb-3">
      <div class="acard-header">
        <i class="bi bi-geo-alt me-2" style="color:var(--primary)"></i>
        Lokasi Penjemputan & Tujuan
      </div>
      <div class="acard-body">

        {{-- Penjemputan --}}
        <div class="mb-3">
          <div class="d-flex align-items-center gap-2 mb-1">
            <div style="width:10px;height:10px;background:#1e7e34;border-radius:50%;flex-shrink:0"></div>
            <span style="font-size:.75rem;font-weight:700;color:#1e7e34;text-transform:uppercase;
                         letter-spacing:.05em">Penjemputan</span>
          </div>
          <div style="font-size:.85rem;line-height:1.6;padding-left:18px">
            {{ preg_replace('/\s*\[GPS:[^\]]+\]/', '', $pemesanan->titik_penjemputan) }}
          </div>
          @if($pemesanan->lat_penjemputan && $pemesanan->lng_penjemputan)
          <div style="padding-left:18px;margin-top:.4rem;display:flex;gap:.5rem;flex-wrap:wrap">
            <a href="{{ $pemesanan->maps_jemput }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:4px;
                      background:#EA4335;color:#fff;padding:.25rem .7rem;
                      border-radius:20px;font-size:.75rem;font-weight:600;text-decoration:none">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="#fff">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
              </svg>
              Lihat di Google Maps
            </a>
            @if($pemesanan->maps_directions)
            <a href="{{ $pemesanan->maps_directions }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:4px;
                      background:#1a73e8;color:#fff;padding:.25rem .7rem;
                      border-radius:20px;font-size:.75rem;font-weight:600;text-decoration:none">
              <i class="bi bi-signpost-split" style="font-size:.8rem"></i>
              Rute Navigasi
            </a>
            @endif
          </div>
          @endif
        </div>

        <div style="border-top:.5px solid var(--border);margin:.5rem 0"></div>

        {{-- Tujuan --}}
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <div style="width:10px;height:10px;background:#c0392b;border-radius:50%;flex-shrink:0"></div>
            <span style="font-size:.75rem;font-weight:700;color:#c0392b;text-transform:uppercase;
                         letter-spacing:.05em">Tujuan</span>
          </div>
          <div style="font-size:.85rem;line-height:1.6;padding-left:18px">
            {{ preg_replace('/\s*\[GPS:[^\]]+\]/', '', $pemesanan->titik_tujuan) }}
          </div>
          @if($pemesanan->lat_tujuan && $pemesanan->lng_tujuan)
          <div style="padding-left:18px;margin-top:.4rem">
            <a href="{{ $pemesanan->maps_tujuan }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:4px;
                      background:#EA4335;color:#fff;padding:.25rem .7rem;
                      border-radius:20px;font-size:.75rem;font-weight:600;text-decoration:none">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="#fff">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
              </svg>
              Lihat di Google Maps
            </a>
          </div>
          @endif
        </div>

      </div>
    </div>

  </div>

  {{-- ── Kanan: Sidebar ──────────────────────────────────── --}}
  <div class="col-lg-5">

    {{-- Rincian Biaya --}}
    <div class="acard mb-3 sticky-top" style="top:80px">
      <div class="acard-header">
        <i class="bi bi-wallet2 me-2" style="color:var(--primary)"></i>
        Rincian Biaya
      </div>
      <div class="acard-body">
        <div style="display:flex;justify-content:space-between;padding:.5rem 0;
                    border-bottom:.5px solid var(--border);font-size:.85rem">
          <span style="color:var(--muted)">Harga × {{ $pemesanan->jumlah_penumpang }} orang</span>
          <span>Rp {{ number_format($pemesanan->jadwal->harga * $pemesanan->jumlah_penumpang, 0, ',', '.') }}</span>
        </div>
        @if($pemesanan->biaya_bagasi_tambahan > 0)
        <div style="display:flex;justify-content:space-between;padding:.5rem 0;
                    border-bottom:.5px solid var(--border);font-size:.85rem">
          <span style="color:var(--muted)">Biaya Bagasi Tambahan</span>
          <span style="color:var(--danger)">
            + Rp {{ number_format($pemesanan->biaya_bagasi_tambahan, 0, ',', '.') }}
          </span>
        </div>
        @endif
        <div style="display:flex;justify-content:space-between;padding:.7rem 0;
                    font-size:1rem;font-weight:800">
          <span style="color:var(--text-dark)">Total</span>
          <span style="color:var(--primary)">
            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
          </span>
        </div>
        <div style="padding:.7rem;background:rgba(40,167,69,.08);border-radius:8px;
                    font-size:.8rem;color:#1a7a32;margin-top:.5rem">
          <i class="bi bi-cash-coin me-1"></i>
          Pembayaran langsung ke driver saat tiba di tujuan
        </div>
      </div>

      {{-- Aksi --}}
      @if($pemesanan->status === 'akan_datang')
      <div class="acard-body" style="border-top:.5px solid var(--border);display:grid;gap:.5rem">
        <div style="font-size:.78rem;font-weight:700;color:var(--muted);
                    text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem">
          Ubah Status
        </div>
        <form method="POST"
              action="{{ route('admin.pemesanan.update-status', $pemesanan->id) }}"
              onsubmit="return confirm('Tandai pemesanan ini sebagai Selesai?')">
          @csrf
          <input type="hidden" name="status" value="selesai">
          <button type="submit" class="abtn abtn-success w-100">
            <i class="bi bi-check-circle me-1"></i> Tandai Selesai
          </button>
        </form>
        <form method="POST"
              action="{{ route('admin.pemesanan.update-status', $pemesanan->id) }}"
              onsubmit="return confirm('Batalkan pemesanan ini?')">
          @csrf
          <input type="hidden" name="status" value="dibatalkan">
          <button type="submit" class="abtn abtn-danger w-100">
            <i class="bi bi-x-circle me-1"></i> Batalkan Pemesanan
          </button>
        </form>
      </div>
      @endif

      {{-- Info Kontak User --}}
      <div class="acard-body" style="border-top:.5px solid var(--border)">
        <div style="font-size:.78rem;font-weight:700;color:var(--muted);
                    text-transform:uppercase;letter-spacing:.05em;margin-bottom:.6rem">
          Hubungi Penumpang
        </div>
        @php
          $noWa = preg_replace('/[^0-9]/', '', $pemesanan->user->no_whatsapp);
          if (str_starts_with($noWa, '0')) $noWa = '62' . substr($noWa, 1);
        @endphp
        <a href="https://wa.me/{{ $noWa }}" target="_blank"
           class="abtn abtn-wa w-100">
          <i class="bi bi-whatsapp me-1"></i>
          Chat {{ $pemesanan->user->nama_lengkap }}
        </a>
      </div>
    </div>

  </div>
</div>

@push('styles')
<style>
  @media print {
    .admin-sidebar, .admin-topbar, .abtn, form { display: none !important; }
    .admin-main { margin: 0; padding: 0; }
    .acard { box-shadow: none; border: 1px solid #ddd; }
  }
</style>
@endpush

@endsection