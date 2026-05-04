{{-- resources/views/admin/pemesanan/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Data Pemesanan')
@section('page-title', 'Data Pemesanan')

@section('content')

{{-- ── Filter ───────────────────────────────────────────────── --}}
<form method="GET" class="d-flex flex-wrap gap-2 align-items-center mb-4">
  <input type="date" name="tanggal" value="{{ $tanggal }}"
         class="aform-control" style="width:180px"
         onchange="this.form.submit()">

  <div class="d-flex gap-1">
    @foreach(['semua' => 'Semua', 'akan_datang' => 'Akan Datang', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $key => $label)
    <a href="?tanggal={{ $tanggal }}&status={{ $key }}"
       class="abtn abtn-sm {{ $status === $key ? 'abtn-primary' : 'abtn-outline' }}">
      {{ $label }}
    </a>
    @endforeach
  </div>

  <a href="{{ route('admin.pemesanan.cetak-harian', ['tanggal' => $tanggal]) }}"
     target="_blank" class="abtn abtn-outline abtn-sm ms-auto">
    <i class="bi bi-printer"></i> Cetak Harian
  </a>
</form>

{{-- ── Navigasi Tanggal Cepat ───────────────────────────────── --}}
<div class="d-flex gap-1 mb-3 flex-wrap">
  @for($i = -1; $i <= 6; $i++)
  @php $tgl = now()->addDays($i)->format('Y-m-d'); @endphp
  <a href="?tanggal={{ $tgl }}&status={{ $status }}"
     class="abtn abtn-sm {{ $tgl === $tanggal ? 'abtn-primary' : 'abtn-outline' }}">
    {{ $i === -1 ? 'Kemarin' : ($i === 0 ? 'Hari Ini' : ($i === 1 ? 'Besok' : now()->addDays($i)->format('d/m'))) }}
  </a>
  @endfor
</div>

{{-- ── Ringkasan Per Jadwal ────────────────────────────────── --}}
@if($perJadwal->count())
@foreach($perJadwal as $jadwalId => $items)
@php $jadwal = $items->first()->jadwal; @endphp
<div class="acard mb-4">

  {{-- Header jadwal --}}
  <div class="acard-header">
    <div class="d-flex align-items-center gap-3 flex-wrap">
      <div class="text-center" style="min-width:65px">
        <div style="font-size:1.4rem;font-weight:800;color:var(--primary);line-height:1">
          {{ $jadwal->jam_berangkat === '00:00:00' ? '--:--' : substr($jadwal->jam_berangkat, 0, 5) }}
        </div>
        <div style="font-size:.7rem;color:var(--muted)">
          {{ $jadwal->jam_berangkat === '00:00:00' ? 'Fleksibel' : 'Jam Berangkat' }}
        </div>
      </div>
      <div style="width:1px;height:36px;background:var(--border)"></div>
      <div>
        <div style="font-weight:700;font-size:.95rem">{{ $jadwal->armada->nama }}</div>
        <div style="font-size:.8rem;color:var(--muted)">
          {{ $jadwal->rute->kota_asal }} → {{ $jadwal->rute->kota_tujuan }}
        </div>
      </div>
      <div class="ms-auto d-flex align-items-center gap-3">
        <div class="text-center">
          <div style="font-weight:800;font-size:1.1rem;color:var(--primary)">
            {{ $items->where('status', 'akan_datang')->sum('jumlah_penumpang') }}
          </div>
          <div style="font-size:.7rem;color:var(--muted)">Penumpang</div>
        </div>
        @if($jadwal->driver)
        <span class="abadge abadge-assign">
          <i class="bi bi-person-check"></i>{{ $jadwal->driver->nama }}
        </span>
        @else
        <span class="abadge abadge-batal">
          <i class="bi bi-person-x"></i>Belum ada driver
        </span>
        @endif
        <button class="abtn abtn-wa abtn-sm" onclick="shareWA({{ $jadwalId }})">
          <i class="bi bi-whatsapp"></i> Share ke Driver
        </button>
      </div>
    </div>
  </div>

  {{-- Tabel pemesanan --}}
  <div style="overflow-x:auto">
    <table class="atable">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Penumpang</th>
          <th>Jam Jemput</th>
          <th>Kursi</th>
          <th>Penjemputan</th>
          <th>Tujuan</th>
          <th>Bagasi</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items->sortBy('jam_penjemputan') as $p)
        <tr style="{{ $p->status === 'dibatalkan' ? 'opacity:.5' : '' }}">

          <td>
            <strong style="color:var(--primary);font-size:.82rem">{{ $p->kode_pemesanan }}</strong><br>
            <span style="font-size:.73rem;color:var(--muted)">{{ $p->user->no_whatsapp }}</span>
          </td>

          <td style="font-size:.85rem">
            <strong>{{ $p->user->nama_lengkap }}</strong><br>
            <span style="color:var(--muted);font-size:.75rem">{{ $p->jumlah_penumpang }} orang</span>
          </td>

          <td>
            <span style="font-family:var(--font-head);font-weight:800;
                         font-size:.95rem;color:var(--primary)">
              {{ substr($p->jam_penjemputan ?? '00:00:00', 0, 5) }}
            </span>
            <span style="font-size:.72rem;color:var(--muted)"> WIB</span>
          </td>

          <td>
            @foreach($p->kursis as $k)
            <span style="background:var(--primary);color:#fff;padding:.2rem .5rem;
                         border-radius:5px;font-size:.75rem;margin:1px;display:inline-block">
              {{ $k->nomor_kursi }}
            </span>
            @endforeach
          </td>

          <td style="font-size:.8rem;max-width:180px">
            {{ Str::limit(preg_replace('/\s*\[GPS:[^\]]+\]/', '', $p->titik_penjemputan), 55) }}
            @if($p->lat_penjemputan && $p->lng_penjemputan)
            <a href="{{ $p->maps_jemput }}" target="_blank"
               style="color:#EA4335;font-size:.72rem;display:inline-flex;
                      align-items:center;gap:2px;margin-top:2px;
                      text-decoration:none;font-weight:600">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="#EA4335">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
              </svg>
              Lihat Maps
            </a>
            @endif
          </td>

          <td style="font-size:.8rem;max-width:180px">
            {{ Str::limit(preg_replace('/\s*\[GPS:[^\]]+\]/', '', $p->titik_tujuan), 55) }}
            @if($p->lat_tujuan && $p->lng_tujuan)
            <a href="{{ $p->maps_tujuan }}" target="_blank"
               style="color:#EA4335;font-size:.72rem;display:inline-flex;
                      align-items:center;gap:2px;margin-top:2px;
                      text-decoration:none;font-weight:600">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="#EA4335">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
              </svg>
              Lihat Maps
            </a>
            @endif
          </td>

          <td style="font-size:.82rem">{{ $p->jumlah_bagasi }} item</td>

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
            <div class="d-flex gap-1">
              <a href="{{ route('admin.pemesanan.show', $p->id) }}"
                 class="abtn abtn-outline abtn-sm" title="Detail">
                <i class="bi bi-eye"></i>
              </a>
              @if($p->status === 'akan_datang')
              <form method="POST"
                    action="{{ route('admin.pemesanan.update-status', $p->id) }}"
                    onsubmit="return confirm('Tandai pesanan ini sebagai Selesai?')">
                @csrf
                <input type="hidden" name="status" value="selesai">
                <button type="submit" class="abtn abtn-success abtn-sm" title="Selesai">
                  <i class="bi bi-check-lg"></i>
                </button>
              </form>
              <form method="POST"
                    action="{{ route('admin.pemesanan.update-status', $p->id) }}"
                    onsubmit="return confirm('Batalkan pesanan ini?')">
                @csrf
                <input type="hidden" name="status" value="dibatalkan">
                <button type="submit" class="abtn abtn-danger abtn-sm" title="Batalkan">
                  <i class="bi bi-x-lg"></i>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endforeach

@else
<div class="acard p-5 text-center">
  <i class="bi bi-inbox" style="font-size:2.5rem;color:var(--border);display:block;margin-bottom:.8rem"></i>
  <div style="font-family:var(--font-head);font-weight:700;color:var(--muted)">
    Tidak ada pemesanan untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
  </div>
</div>
@endif

{{-- Modal Share WA --}}
<div class="modal fade" id="modalShareWA" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius:var(--radius);border:none">
      <div class="modal-header" style="background:#25D366;color:#fff;border:none">
        <h6 class="modal-title" style="font-family:var(--font-head);font-weight:700">
          <i class="bi bi-whatsapp me-2"></i>Share Data Penjemputan ke Driver
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="shareLoading" class="text-center py-4">
          <div class="spinner-border" style="color:#25D366"></div>
          <div class="mt-2" style="font-size:.85rem;color:var(--muted)">Memuat data...</div>
        </div>
        <div id="shareContent" style="display:none">
          <div class="mb-3">
            <label class="aform-label">Preview Pesan WhatsApp</label>
            <textarea id="pesanWA" class="aform-control" rows="18"
                      style="font-family:monospace;font-size:.8rem;background:#f9f9f9"></textarea>
          </div>
          <div class="d-flex gap-2 flex-wrap">
            <button class="abtn abtn-outline abtn-sm" onclick="copyPesan()">
              <i class="bi bi-clipboard"></i> Salin Pesan
            </button>
            <a id="linkWA" href="#" target="_blank" class="abtn abtn-wa">
              <i class="bi bi-whatsapp"></i> Buka WhatsApp
            </a>
          </div>
          <div style="font-size:.75rem;color:var(--muted);margin-top:.5rem">
            <i class="bi bi-info-circle me-1"></i>
            Klik "Buka WhatsApp" untuk langsung chat ke nomor driver yang sudah di-assign.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
async function shareWA(jadwalId) {
  const modal = new bootstrap.Modal(document.getElementById('modalShareWA'));
  modal.show();

  document.getElementById('shareLoading').style.display = '';
  document.getElementById('shareContent').style.display = 'none';

  const linkEl = document.getElementById('linkWA');
  linkEl.classList.remove('disabled');
  linkEl.innerHTML = '<i class="bi bi-whatsapp"></i> Buka WhatsApp';

  try {
    const res = await fetch('{{ route("admin.pemesanan.share-driver") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ jadwal_id: jadwalId }),
    });

    const data = await res.json();
    document.getElementById('pesanWA').value = data.pesan;

    if (data.wa_link) {
      linkEl.href = data.wa_link;
    } else {
      linkEl.href = '#';
      linkEl.innerHTML = '<i class="bi bi-whatsapp"></i> Driver belum di-assign';
      linkEl.classList.add('disabled');
    }

    document.getElementById('shareLoading').style.display = 'none';
    document.getElementById('shareContent').style.display = '';
  } catch(e) {
    alert('Gagal memuat data. Silakan coba lagi.');
    bootstrap.Modal.getInstance(document.getElementById('modalShareWA')).hide();
  }
}

function copyPesan() {
  const el  = document.getElementById('pesanWA');
  const btn = event.target.closest('button');
  el.select();
  document.execCommand('copy');
  const orig = btn.innerHTML;
  btn.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin!';
  btn.style.background = 'var(--success)';
  btn.style.color = '#fff';
  setTimeout(() => {
    btn.innerHTML = orig;
    btn.style.background = '';
    btn.style.color = '';
  }, 2000);
}
</script>
@endpush