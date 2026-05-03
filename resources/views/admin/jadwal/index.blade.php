{{-- resources/views/admin/jadwal/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Jadwal Keberangkatan')
@section('page-title', 'Jadwal Keberangkatan')

@section('content')

{{-- ── Filter Tanggal + Tombol Tambah ──────────────────────── --}}
<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
  <form method="GET" class="d-flex gap-2 align-items-center">
    <input type="date" name="tanggal" value="{{ $tanggal }}" class="aform-control" style="width:180px"
           onchange="this.form.submit()">
    <button type="submit" class="abtn abtn-primary">
      <i class="bi bi-search"></i> Tampilkan
    </button>
  </form>
  <div class="d-flex gap-2">
    <button class="abtn abtn-outline" data-bs-toggle="modal" data-bs-target="#modalMassal">
      <i class="bi bi-calendar-plus"></i> Jadwal Massal
    </button>
    <button class="abtn abtn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
      <i class="bi bi-plus-lg"></i> Tambah Jadwal
    </button>
  </div>
</div>

{{-- ── Navigasi Tanggal cepat ──────────────────────────────── --}}
<div class="d-flex gap-1 mb-3 flex-wrap">
  @for($i = -1; $i <= 6; $i++)
  @php $tgl = now()->addDays($i)->format('Y-m-d'); $label = now()->addDays($i)->format('d/m'); @endphp
  <a href="?tanggal={{ $tgl }}"
     class="abtn abtn-sm {{ $tgl === $tanggal ? 'abtn-primary' : 'abtn-outline' }}">
    {{ $i === 0 ? 'Hari Ini' : ($i === 1 ? 'Besok' : $label) }}
  </a>
  @endfor
</div>

{{-- ── Jadwal List ─────────────────────────────────────────── --}}
@forelse($jadwals as $jadwal)
<div class="acard mb-3">
  <div class="acard-header">
    <div class="d-flex align-items-center gap-3 flex-wrap">
      {{-- Jam --}}
      <div style="font-size:1.4rem;font-weight:800;color:var(--primary);min-width:60px">
        {{ substr($jadwal->jam_berangkat,0,5) }}
      </div>
      {{-- Info --}}
      <div>
        <div style="font-weight:700;font-size:.95rem">{{ $jadwal->armada->nama }}</div>
        <div style="font-size:.8rem;color:var(--muted)">
          {{ $jadwal->rute->kota_asal }} → {{ $jadwal->rute->kota_tujuan }}
        </div>
      </div>
      {{-- Penumpang --}}
      <div class="ms-auto d-flex align-items-center gap-3">
        <div class="text-center">
          <div style="font-weight:800;font-size:1.2rem;color:var(--primary)">
            {{ $jadwal->pemesanans->sum('jumlah_penumpang') }}
          </div>
          <div style="font-size:.7rem;color:var(--muted)">Penumpang</div>
        </div>
        <div class="text-center">
          <div style="font-weight:800;font-size:1.2rem">
            {{ $jadwal->pemesanans->count() }}
          </div>
          <div style="font-size:.7rem;color:var(--muted)">Pemesanan</div>
        </div>
        {{-- Driver badge --}}
        @if($jadwal->driver)
        <span class="abadge abadge-assign">
          <i class="bi bi-person-check"></i>{{ $jadwal->driver->nama }}
        </span>
        @else
        <span class="abadge abadge-batal">
          <i class="bi bi-person-x"></i>Belum ada driver
        </span>
        @endif

        {{-- Edit btn --}}
        <button class="abtn abtn-outline abtn-sm" data-bs-toggle="modal"
                data-bs-target="#modalEdit{{ $jadwal->id }}">
          <i class="bi bi-pencil"></i>
        </button>
        {{-- Share WA --}}
        <button class="abtn abtn-wa abtn-sm" onclick="shareWA({{ $jadwal->id }})">
          <i class="bi bi-whatsapp"></i> Share ke Driver
        </button>
      </div>
    </div>
  </div>

  {{-- Daftar Pemesanan --}}
  @if($jadwal->pemesanans->count())
  <div style="overflow-x:auto">
    <table class="atable">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Penumpang</th>
          <th>Kursi</th>
          <th>Penjemputan</th>
          <th>Tujuan</th>
          <th>Bagasi</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($jadwal->pemesanans as $p)
        <tr>
          <td>
            <strong style="color:var(--primary);font-size:.82rem">{{ $p->kode_pemesanan }}</strong><br>
            <span style="font-size:.73rem;color:var(--muted)">{{ $p->user->no_whatsapp }}</span>
          </td>
          <td style="font-size:.85rem">
            <strong>{{ $p->user->nama_lengkap }}</strong><br>
            <span style="color:var(--muted);font-size:.75rem">{{ $p->jumlah_penumpang }} orang</span>
          </td>
          <td>
            @foreach($p->kursis as $k)
            <span style="background:var(--primary);color:#fff;padding:.2rem .5rem;border-radius:5px;font-size:.75rem;margin:1px;display:inline-block">
              {{ $k->nomor_kursi }}
            </span>
            @endforeach
          </td>
          <td style="font-size:.8rem;max-width:180px">
            {{ Str::limit($p->titik_penjemputan, 60) }}
            @if(preg_match('/\[GPS: ([\-\d\.]+),([\-\d\.]+)\]/', $p->titik_penjemputan, $m))
            <a href="https://maps.google.com/?q={{ $m[1] }},{{ $m[2] }}" target="_blank"
               style="color:var(--primary);font-size:.72rem;display:block">
              <i class="bi bi-geo-alt-fill"></i> Lihat Maps
            </a>
            @endif
          </td>
          <td style="font-size:.8rem;max-width:180px">
            {{ Str::limit($p->titik_tujuan, 60) }}
            @if(preg_match('/\[GPS: ([\-\d\.]+),([\-\d\.]+)\]/', $p->titik_tujuan, $m))
            <a href="https://maps.google.com/?q={{ $m[1] }},{{ $m[2] }}" target="_blank"
               style="color:var(--primary);font-size:.72rem;display:block">
              <i class="bi bi-geo-alt-fill"></i> Lihat Maps
            </a>
            @endif
          </td>
          <td style="font-size:.82rem">{{ $p->jumlah_bagasi }} item</td>
          <td>
            @php $badge = match($p->status) {
              'akan_datang' => 'abadge-akan', 'selesai' => 'abadge-selesai',
              'dibatalkan'  => 'abadge-batal', default => ''
            }; @endphp
            <span class="abadge {{ $badge }}">{{ $p->status_label }}</span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
  <div class="p-3 text-center" style="color:var(--muted);font-size:.85rem">
    Belum ada pemesanan untuk jadwal ini
  </div>
  @endif
</div>

{{-- Modal Edit Jadwal --}}
<div class="modal fade" id="modalEdit{{ $jadwal->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius);border:none">
      <form method="POST" action="{{ route('admin.jadwal.update', $jadwal->id) }}">
        @csrf @method('PUT')
        <div class="modal-header" style="border-bottom:1px solid var(--border)">
          <h6 class="modal-title" style="font-family:var(--font-head);font-weight:700">
            Edit Jadwal — {{ substr($jadwal->jam_berangkat,0,5) }}
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="display:grid;gap:1rem">
          <div>
            <label class="aform-label">Jam Berangkat</label>
            <input type="time" name="jam_berangkat" class="aform-control"
                   value="{{ substr($jadwal->jam_berangkat,0,5) }}" required>
          </div>
          <div>
            <label class="aform-label">Harga / Orang (Rp)</label>
            <input type="number" name="harga" class="aform-control"
                   value="{{ $jadwal->harga }}" required>
          </div>
          <div>
            <label class="aform-label">Assign Driver</label>
            <select name="driver_id" class="aform-control">
              <option value="">-- Pilih Driver --</option>
              @foreach($drivers as $d)
              <option value="{{ $d->id }}" {{ $jadwal->driver_id == $d->id ? 'selected' : '' }}>
                {{ $d->nama }} · {{ $d->no_kendaraan ?? '-' }}
              </option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="aform-label">Catatan Admin</label>
            <textarea name="catatan_admin" class="aform-control" rows="2"
                      placeholder="Instruksi khusus untuk jadwal ini...">{{ $jadwal->catatan_admin }}</textarea>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--border)">
          <button type="button" class="abtn abtn-outline" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="abtn abtn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@empty
<div class="acard p-5 text-center">
  <i class="bi bi-calendar-x" style="font-size:2.5rem;color:var(--border);display:block;margin-bottom:.8rem"></i>
  <div style="font-family:var(--font-head);font-weight:700;color:var(--muted)">
    Tidak ada jadwal untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
  </div>
  <button class="abtn abtn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-lg"></i> Tambah Jadwal
  </button>
</div>
@endforelse

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- Modal Tambah Jadwal                                          --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius);border:none">
      <form method="POST" action="{{ route('admin.jadwal.store') }}">
        @csrf
        <div class="modal-header" style="background:var(--primary);color:#fff;border:none">
          <h6 class="modal-title" style="font-family:var(--font-head);font-weight:700">
            <i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Baru
          </h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="display:grid;gap:1rem">
          <div class="row g-3">
            <div class="col-6">
              <label class="aform-label">Armada *</label>
              <select name="armada_id" class="aform-control" required>
                <option value="">-- Pilih --</option>
                @foreach($armadas as $a)
                <option value="{{ $a->id }}">{{ $a->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="aform-label">Rute *</label>
              <select name="rute_id" class="aform-control" required>
                <option value="">-- Pilih --</option>
                @foreach($rutes as $r)
                <option value="{{ $r->id }}">{{ $r->label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="aform-label">Tanggal *</label>
              <input type="date" name="tanggal" class="aform-control"
                     value="{{ $tanggal }}" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Jam Berangkat *</label>
              <input type="time" name="jam_berangkat" class="aform-control"
                     value="08:00" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Harga / Orang (Rp) *</label>
              <input type="number" name="harga" class="aform-control"
                     placeholder="350000" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Driver (opsional)</label>
              <select name="driver_id" class="aform-control">
                <option value="">-- Pilih Driver --</option>
                @foreach($drivers as $d)
                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="aform-label">Catatan Admin</label>
              <textarea name="catatan_admin" class="aform-control" rows="2"
                        placeholder="Instruksi khusus..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--border)">
          <button type="button" class="abtn abtn-outline" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="abtn abtn-primary"><i class="bi bi-check-lg"></i> Tambah Jadwal</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Jadwal Massal --}}
<div class="modal fade" id="modalMassal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius);border:none">
      <form method="POST" action="{{ route('admin.jadwal.massal') }}">
        @csrf
        <div class="modal-header" style="background:var(--accent);color:#fff;border:none">
          <h6 class="modal-title" style="font-family:var(--font-head);font-weight:700">
            <i class="bi bi-calendar-range me-2"></i>Buat Jadwal Massal
          </h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="display:grid;gap:1rem">
          <div style="padding:.8rem;background:rgba(244,160,32,.08);border-radius:8px;font-size:.82rem;color:#7a5000">
            <i class="bi bi-info-circle me-1"></i>
            Sistem akan membuat jadwal secara otomatis untuk setiap hari dalam rentang tanggal yang dipilih.
            Hari yang sudah ada jadwalnya akan dilewati.
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="aform-label">Armada *</label>
              <select name="armada_id" class="aform-control" required>
                <option value="">-- Pilih --</option>
                @foreach($armadas as $a)
                <option value="{{ $a->id }}">{{ $a->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="aform-label">Rute *</label>
              <select name="rute_id" class="aform-control" required>
                <option value="">-- Pilih --</option>
                @foreach($rutes as $r)
                <option value="{{ $r->id }}">{{ $r->label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="aform-label">Tanggal Mulai *</label>
              <input type="date" name="tanggal_mulai" class="aform-control"
                     min="{{ today()->format('Y-m-d') }}" value="{{ today()->format('Y-m-d') }}" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Tanggal Akhir *</label>
              <input type="date" name="tanggal_akhir" class="aform-control"
                     min="{{ today()->format('Y-m-d') }}" value="{{ today()->addMonth()->format('Y-m-d') }}" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Jam Berangkat *</label>
              <input type="time" name="jam_berangkat" class="aform-control" value="08:00" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Harga / Orang (Rp) *</label>
              <input type="number" name="harga" class="aform-control" placeholder="350000" required>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--border)">
          <button type="button" class="abtn abtn-outline" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="abtn abtn-accent"><i class="bi bi-calendar-range"></i> Buat Jadwal Massal</button>
        </div>
      </form>
    </div>
  </div>
</div>

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
            <textarea id="pesanWA" class="aform-control" rows="16"
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
let currentJadwalId = null;

async function shareWA(jadwalId) {
  currentJadwalId = jadwalId;
  const modal = new bootstrap.Modal(document.getElementById('modalShareWA'));
  modal.show();

  document.getElementById('shareLoading').style.display = '';
  document.getElementById('shareContent').style.display = 'none';

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
    document.getElementById('linkWA').href   = data.wa_link || '#';
    if (!data.wa_link) {
      document.getElementById('linkWA').innerHTML = '<i class="bi bi-whatsapp"></i> Driver belum di-assign';
      document.getElementById('linkWA').classList.add('disabled');
    }

    document.getElementById('shareLoading').style.display = 'none';
    document.getElementById('shareContent').style.display = '';
  } catch(e) {
    alert('Gagal memuat data. Silakan coba lagi.');
    bootstrap.Modal.getInstance(document.getElementById('modalShareWA')).hide();
  }
}

function copyPesan() {
  const el = document.getElementById('pesanWA');
  el.select();
  document.execCommand('copy');
  // Feedback
  const btn = event.target.closest('button');
  const orig = btn.innerHTML;
  btn.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin!';
  btn.style.background = 'var(--success)';
  btn.style.color = '#fff';
  setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.style.color = ''; }, 2000);
}
</script>
@endpush
