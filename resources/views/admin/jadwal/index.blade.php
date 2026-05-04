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
    <!-- <button class="abtn abtn-outline" data-bs-toggle="modal" data-bs-target="#modalMassal">
      <i class="bi bi-calendar-plus"></i> Jadwal Massal
    </button> -->
    <button class="abtn abtn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
      <i class="bi bi-plus-lg"></i> Tambah Jadwal
    </button>
  </div>
</div>

{{-- ── Navigasi Tanggal Cepat ───────────────────────────────── --}}
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
      <div class="text-center" style="min-width:70px">
        <div style="font-size:1.5rem;font-weight:800;color:var(--primary);line-height:1">
          {{ $jadwal->jam_berangkat === '00:00:00' ? '--:--' : substr($jadwal->jam_berangkat, 0, 5) }}
        </div>
        <div style="font-size:.7rem;color:var(--muted);margin-top:.1rem">
          {{ $jadwal->jam_berangkat === '00:00:00' ? 'Fleksibel' : 'Jam Berangkat' }}
        </div>
      </div>

      {{-- Divider --}}
      <div style="width:1px;height:40px;background:var(--border)"></div>

      {{-- Info Armada & Rute --}}
      <div>
        <div style="font-weight:700;font-size:.95rem">{{ $jadwal->armada->nama }}</div>
        <div style="font-size:.8rem;color:var(--muted)">
          {{ $jadwal->rute->kota_asal }} → {{ $jadwal->rute->kota_tujuan }}
        </div>
        @if($jadwal->catatan_admin)
        <div style="font-size:.75rem;color:var(--accent);margin-top:.2rem">
          <i class="bi bi-sticky me-1"></i>{{ $jadwal->catatan_admin }}
        </div>
        @endif
      </div>

      {{-- Stat: Penumpang & Pemesanan --}}
      <div class="ms-auto d-flex align-items-center gap-3">
        <div class="text-center">
          <div style="font-weight:800;font-size:1.2rem;color:var(--primary)">
            {{ $jadwal->pemesanans->where('status', 'akan_datang')->sum('jumlah_penumpang') }}
          </div>
          <div style="font-size:.7rem;color:var(--muted)">Penumpang</div>
        </div>
        <div class="text-center">
          <div style="font-weight:800;font-size:1.2rem">
            {{ $jadwal->pemesanans->where('status', 'akan_datang')->count() }}
          </div>
          <div style="font-size:.7rem;color:var(--muted)">Pemesanan</div>
        </div>

        {{-- Driver Badge --}}
        @if($jadwal->driver)
        <span class="abadge abadge-assign">
          <i class="bi bi-person-check"></i>{{ $jadwal->driver->nama }}
        </span>
        @else
        <span class="abadge abadge-batal">
          <i class="bi bi-person-x"></i>Belum ada driver
        </span>
        @endif

        {{-- Tombol Edit --}}
        <button class="abtn abtn-outline abtn-sm" data-bs-toggle="modal"
                data-bs-target="#modalEdit{{ $jadwal->id }}">
          <i class="bi bi-pencil"></i>
        </button>

        {{-- Tombol Share WA --}}
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
          <th>Jam Jemput</th>
          <th>Kursi</th>
          <th>Penjemputan</th>
          <th>Tujuan</th>
          <th>Bagasi</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($jadwal->pemesanans->sortBy('jam_penjemputan') as $p)
        <tr style="{{ $p->status === 'dibatalkan' ? 'opacity:.5' : '' }}">

          {{-- Kode --}}
          <td>
            <strong style="color:var(--primary);font-size:.82rem">{{ $p->kode_pemesanan }}</strong><br>
            <span style="font-size:.73rem;color:var(--muted)">{{ $p->user->no_whatsapp }}</span>
          </td>

          {{-- Penumpang --}}
          <td style="font-size:.85rem">
            <strong>{{ $p->user->nama_lengkap }}</strong><br>
            <span style="color:var(--muted);font-size:.75rem">{{ $p->jumlah_penumpang }} orang</span>
          </td>

          {{-- Jam Penjemputan --}}
          <td>
            <span style="font-family:var(--font-head);font-weight:800;font-size:.95rem;color:var(--primary)">
              {{ substr($p->jam_penjemputan ?? '00:00:00', 0, 5) }}
            </span>
            <span style="font-size:.72rem;color:var(--muted)"> WIB</span>
          </td>

          {{-- Kursi --}}
          <td>
            @foreach($p->kursis as $k)
            <span style="background:var(--primary);color:#fff;padding:.2rem .5rem;
                         border-radius:5px;font-size:.75rem;margin:1px;display:inline-block">
              {{ $k->nomor_kursi }}
            </span>
            @endforeach
          </td>

          {{-- Titik Penjemputan --}}
          <td style="font-size:.8rem;max-width:200px">
            {{ Str::limit(preg_replace('/\s*\[GPS:[^\]]+\]/', '', $p->titik_penjemputan), 60) }}
            @if($p->lat_penjemputan && $p->lng_penjemputan)
            <a href="{{ $p->maps_jemput }}" target="_blank"
               style="color:#EA4335;font-size:.72rem;display:inline-flex;align-items:center;gap:2px;margin-top:2px;text-decoration:none;font-weight:600">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="#EA4335">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
              </svg>
              Lihat Maps
            </a>
            @endif
          </td>

          {{-- Titik Tujuan --}}
          <td style="font-size:.8rem;max-width:200px">
            {{ Str::limit(preg_replace('/\s*\[GPS:[^\]]+\]/', '', $p->titik_tujuan), 60) }}
            @if($p->lat_tujuan && $p->lng_tujuan)
            <a href="{{ $p->maps_tujuan }}" target="_blank"
               style="color:#EA4335;font-size:.72rem;display:inline-flex;align-items:center;gap:2px;margin-top:2px;text-decoration:none;font-weight:600">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="#EA4335">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
              </svg>
              Lihat Maps
            </a>
            @endif
          </td>

          {{-- Bagasi --}}
          <td style="font-size:.82rem">{{ $p->jumlah_bagasi }} item</td>

          {{-- Status --}}
          <td>
            @php $badge = match($p->status) {
              'akan_datang' => 'abadge-akan',
              'selesai'     => 'abadge-selesai',
              'dibatalkan'  => 'abadge-batal',
              default       => ''
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
            Edit Jadwal —
            {{ $jadwal->jam_berangkat === '00:00:00' ? 'Fleksibel' : substr($jadwal->jam_berangkat, 0, 5) }}
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="display:grid;gap:1rem">
          <div>
            <label class="aform-label">Jam Berangkat</label>
            <input type="time" name="jam_berangkat" class="aform-control"
                   value="{{ $jadwal->jam_berangkat === '00:00:00' ? '' : substr($jadwal->jam_berangkat, 0, 5) }}">
            <div style="font-size:.75rem;color:var(--muted);margin-top:.3rem">
              Kosongkan jika jam mengikuti jam penjemputan masing-masing penumpang
            </div>
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
  <label class="aform-label">Arah *</label>
  <select name="arah" class="aform-control" required>
    <option value="">-- Pilih Arah --</option>
    <option value="barat_timur">🌅 Barat → Timur</option>
    <option value="timur_barat">🌇 Timur → Barat</option>
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
                     value="17:00" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Harga / Orang (Rp) *</label>
              <input type="number" name="harga" class="aform-control"
                     placeholder="250000" required>
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
          <button type="submit" class="abtn abtn-primary">
            <i class="bi bi-check-lg"></i> Tambah Jadwal
          </button>
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
          <div style="padding:.8rem;background:rgba(244,160,32,.08);border-radius:8px;
                      font-size:.82rem;color:#7a5000">
            <i class="bi bi-info-circle me-1"></i>
            Sistem akan membuat jadwal untuk setiap hari dalam rentang tanggal yang dipilih.
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
                     min="{{ today()->format('Y-m-d') }}"
                     value="{{ today()->format('Y-m-d') }}" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Tanggal Akhir *</label>
              <input type="date" name="tanggal_akhir" class="aform-control"
                     min="{{ today()->format('Y-m-d') }}"
                     value="{{ today()->addMonth()->format('Y-m-d') }}" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Jam Berangkat *</label>
              <input type="time" name="jam_berangkat" class="aform-control"
                     value="17:00" required>
            </div>
            <div class="col-6">
              <label class="aform-label">Harga / Orang (Rp) *</label>
              <input type="number" name="harga" class="aform-control"
                     placeholder="350000" required>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--border)">
          <button type="button" class="abtn abtn-outline" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="abtn abtn-accent">
            <i class="bi bi-calendar-range"></i> Buat Jadwal Massal
          </button>
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

  // Reset link WA
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
  setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.style.color = ''; }, 2000);
}
</script>
@endpush