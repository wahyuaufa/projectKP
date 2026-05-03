{{-- resources/views/booking/detail.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking - Detail Pemesan')

@push('styles')
<style>
/* ── Wilayah Select ─────────────────────────────────────────── */
.wilayah-select-wrap {
  position: relative;
}
.wilayah-sel {
  appearance: none;
  padding-right: 2.5rem;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%236C757D' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right .9rem center;
  cursor: pointer;
}
.wilayah-sel:disabled {
  background-color: var(--bg-section);
  cursor: not-allowed;
  opacity: .65;
}
.wilayah-spinner {
  position: absolute;
  right: 2.2rem;
  top: 50%;
  transform: translateY(-50%);
  width: 14px;
  height: 14px;
  border: 2px solid var(--border);
  border-top-color: var(--primary);
  border-radius: 50%;
  display: none;
  animation: spin .6s linear infinite;
}
@keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }
.wilayah-spinner.show { display: block; }

/* ── Opsional box ───────────────────────────────────────────── */
#opsional_asal, #opsional_tujuan {
  transition: opacity .2s;
}
</style>
@endpush

@section('content')
<div class="container py-5" style="min-height:80vh">

  {{-- Steps --}}
  <div class="steps-bar" data-aos="fade-down">
    @foreach(['Pilih Armada','Rute & Jadwal','Detail Pemesan','Konfirmasi'] as $i => $s)
    <div class="step-item {{ $i === 2 ? 'active' : ($i < 2 ? 'done' : '') }}">
      <div class="step-circle">{{ $i < 2 ? '✓' : $i+1 }}</div>
      <div class="step-label">{{ $s }}</div>
    </div>
    @endforeach
  </div>

  <h2 class="section-title mb-1" data-aos="fade-up">Detail Pemesan</h2>
  <div class="section-divider" data-aos="fade-up"></div>

  <div class="row g-4">

    {{-- ── Form ───────────────────────────────────────────── --}}
    <div class="col-lg-7">
      <form method="POST" action="{{ route('booking.simpan-detail') }}" id="detailForm">
        @csrf
        <input type="hidden" name="jam_penjemputan" value="{{ $jamPenjemputan }}">

        {{-- Info Pemesan --}}
        <div class="card-gotrav mb-4" data-aos="fade-up">
          <div class="card-header-gotrav">
            <i class="bi bi-person-lines-fill me-2"></i>Informasi Pemesan
          </div>
          <div class="card-body-gotrav">
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label-gotrav">Nama Lengkap</label>
                <div class="input-icon-wrap">
                  <i class="bi bi-person input-icon"></i>
                  <input type="text" class="form-control-gotrav"
                         value="{{ Auth::user()->nama_lengkap }}" readonly
                         style="background:var(--bg-section);cursor:not-allowed">
                </div>
              </div>
              <div class="col-sm-6">
                <label class="form-label-gotrav">No. WhatsApp</label>
                <div class="input-icon-wrap">
                  <i class="bi bi-whatsapp input-icon"></i>
                  <input type="text" class="form-control-gotrav"
                         value="{{ Auth::user()->no_whatsapp }}" readonly
                         style="background:var(--bg-section);cursor:not-allowed">
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Alamat Penjemputan --}}
        <div class="card-gotrav mb-4" data-aos="fade-up" data-aos-delay="80">
          <div class="card-header-gotrav" style="background:#1e7e34">
            <i class="bi bi-geo-alt-fill me-2"></i>Alamat Penjemputan
          </div>
          <div class="card-body-gotrav">
            @include('booking._alamat-form', ['prefix' => 'asal'])
          </div>
        </div>

        {{-- Alamat Tujuan --}}
        <div class="card-gotrav mb-4" data-aos="fade-up" data-aos-delay="160">
          <div class="card-header-gotrav" style="background:#c0392b">
            <i class="bi bi-geo-alt-fill me-2"></i>Alamat Tujuan
          </div>
          <div class="card-body-gotrav">
            @include('booking._alamat-form', ['prefix' => 'tujuan'])
          </div>
        </div>

        {{-- Bagasi & Catatan --}}
        <div class="card-gotrav mb-4" data-aos="fade-up" data-aos-delay="240">
          <div class="card-header-gotrav">
            <i class="bi bi-bag me-2"></i>Bagasi & Catatan
          </div>
          <div class="card-body-gotrav">
            <div class="mb-3">
              <label class="form-label-gotrav">Jumlah Bagasi (Tas/Kardus)</label>
              <div class="input-icon-wrap" style="max-width:200px">
                <i class="bi bi-bag input-icon"></i>
                <input type="number" name="jumlah_bagasi" id="jumlahBagasi"
       class="form-control-gotrav" min="0" max="20" step="1"
       value="{{ old('jumlah_bagasi', 0) }}"
       oninput="this.value = this.value.replace(/[^0-9]/g, '') || '0'">
              </div>
            </div>
            <div class="bagasi-info-box mb-3">
              <i class="bi bi-info-circle"></i>
              <div class="bagasi-info-text">
                Gratis <strong>{{ $armada->bagasi_gratis }}</strong> tas &
                <strong>{{ $armada->kardus_gratis }}</strong> kardus per penumpang
                (total gratis <strong>{{ $armada->bagasi_gratis * count($kursi) }}</strong> item).
                Biaya tambahan: <strong>Rp {{ number_format($armada->biaya_bagasi_tambahan, 0, ',', '.') }}</strong>/item.
                <div id="biayaBagasiInfo" class="mt-1 fw-bold" style="color:var(--danger);display:none"></div>
              </div>
            </div>
            <div>
              <label class="form-label-gotrav">Catatan untuk Driver <small class="text-muted">(opsional)</small></label>
              <textarea name="catatan" class="form-control-gotrav" rows="3"
                        placeholder="Contoh: Tolong hubungi 15 menit sebelum jemput..."
                        style="resize:vertical">{{ old('catatan') }}</textarea>
            </div>
          </div>
        </div>

        @if($errors->any())
        <div class="alert-gotrav alert-gotrav-danger p-3 rounded-2 mb-3 d-flex align-items-center gap-2">
          <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
        @endif

        <div class="d-flex gap-3" data-aos="fade-up">
          <a href="{{ route('booking.kursi') }}" class="btn-outline-gotrav btn btn-lg-custom flex-fill">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
          <button type="submit" class="btn-gotrav btn btn-lg-custom flex-fill">
            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
          </button>
        </div>
      </form>
    </div>

    {{-- ── Sidebar Ringkasan ────────────────────────────────── --}}
    <div class="col-lg-5" data-aos="fade-left">
      <div class="card-gotrav sticky-top" style="top:90px">
        <div class="card-header-gotrav"><i class="bi bi-receipt me-2"></i>Ringkasan Pemesanan</div>
        <div class="card-body-gotrav">
          <div class="ticket-row"><div class="ticket-row-label">Armada</div><div class="ticket-row-value">{{ $armada->nama }}</div></div>
          <div class="ticket-row"><div class="ticket-row-label">Rute</div><div class="ticket-row-value" style="font-size:.85rem">{{ $arahRute == 'timur_ke_barat' ? 'Timur ke Barat' : 'Barat ke Timur' }}</div></div>
          <div class="ticket-row"><div class="ticket-row-label">Tanggal</div><div class="ticket-row-value">{{ \Carbon\Carbon::parse($jadwalAda->tanggal ?? session('booking.tanggal'))->format('d M Y') }}</div></div>
          <div class="ticket-row"><div class="ticket-row-label">Jam</div><div class="ticket-row-value">{{ $jamPenjemputan ? $jamPenjemputan . ' WIB' : '-' }}</div></div>
          <div class="ticket-row">
            <div class="ticket-row-label">Kursi</div>
            <div class="ticket-row-value">
              @foreach($kursi as $k)
              <span class="px-2 py-1 rounded-2 me-1 mb-1 d-inline-block"
                    style="background:var(--primary);color:#fff;font-size:.8rem">{{ $k }}</span>
              @endforeach
            </div>
          </div>
          <div class="ticket-row"><div class="ticket-row-label">Penumpang</div><div class="ticket-row-value">{{ count($kursi) }} Orang</div></div>
          <hr style="border-color:var(--border)">
          <div class="ticket-row"><div class="ticket-row-label">Harga/Orang</div><div class="ticket-row-value">Rp {{ number_format($armada->harga_per_rute, 0, ',', '.') }}</div></div>
          <div class="ticket-row"><div class="ticket-row-label">Subtotal</div><div class="ticket-row-value">Rp {{ number_format($armada->harga_per_rute * count($kursi), 0, ',', '.') }}</div></div>
          <div class="ticket-row" id="rowBiayaBagasi" style="display:none">
            <div class="ticket-row-label" style="color:var(--danger)">Bagasi Tambahan</div>
            <div class="ticket-row-value" style="color:var(--danger)" id="nilaiByBagasi">Rp 0</div>
          </div>
          <hr style="border-color:var(--border)">
          <div class="ticket-row">
            <div class="ticket-row-label fw-bold" style="color:var(--text-dark)">Total</div>
            <div class="ticket-row-value" id="totalHarga"
                 style="font-size:1.2rem;font-weight:800;color:var(--primary)">
              Rp {{ number_format($armada->harga_per_rute * count($kursi), 0, ',', '.') }}
            </div>
          </div>
          <div class="mt-3 p-3 rounded-3" style="background:rgba(40,167,69,.08);font-size:.82rem;color:#1a7a32">
            <i class="bi bi-cash-coin me-1"></i> Pembayaran langsung ke driver saat tiba di tujuan.
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>

// ════════════════════════════════════════════════════════════════
// 1. BAGASI CALCULATOR
// ════════════════════════════════════════════════════════════════
const hargaPerOrang   = {{ $armada->harga_per_rute }};
const jumlahPenumpang = {{ count($kursi) }};
const bagasiGratis    = {{ $armada->bagasi_gratis * count($kursi) }};
const biayaPerItem    = {{ $armada->biaya_bagasi_tambahan }};
const subtotal        = hargaPerOrang * jumlahPenumpang;
const fmtIDR = n => 'Rp ' + parseInt(n).toLocaleString('id-ID');

document.getElementById('jumlahBagasi').addEventListener('input', function () {
  const jumlah   = parseInt(this.value) || 0;
  const tambahan = Math.max(0, jumlah - bagasiGratis);
  const biaya    = tambahan * biayaPerItem;
  const total    = subtotal + biaya;

  document.getElementById('totalHarga').textContent = fmtIDR(total);
  if (biaya > 0) {
    document.getElementById('rowBiayaBagasi').style.display = '';
    document.getElementById('nilaiByBagasi').textContent    = '+ ' + fmtIDR(biaya);
    document.getElementById('biayaBagasiInfo').style.display = '';
    document.getElementById('biayaBagasiInfo').textContent  =
      `${tambahan} item kelebihan → biaya tambahan ${fmtIDR(biaya)}`;
  } else {
    document.getElementById('rowBiayaBagasi').style.display = 'none';
    document.getElementById('biayaBagasiInfo').style.display = 'none';
  }
});

// ════════════════════════════════════════════════════════════════
// 2. TOGGLE OPSIONAL
// ════════════════════════════════════════════════════════════════
['asal', 'tujuan'].forEach(prefix => {
  const toggle = document.getElementById(`toggle_${prefix}`);
  const box    = document.getElementById(`opsional_${prefix}`);
  if (toggle && box) {
    toggle.addEventListener('change', () => {
      box.style.display = toggle.checked ? '' : 'none';
    });
  }
});

// ════════════════════════════════════════════════════════════════
// 3. WILAYAH CASCADE AJAX
// ════════════════════════════════════════════════════════════════
const WILAYAH_API = {
  provinces : ()   => fetch('/api/wilayah/provinces').then(r => r.json()),
  regencies : id   => fetch(`/api/wilayah/regencies/${id}`).then(r => r.json()),
  districts : id   => fetch(`/api/wilayah/districts/${id}`).then(r => r.json()),
  villages  : id   => fetch(`/api/wilayah/villages/${id}`).then(r => r.json()),
};

function isi(selectEl, data, placeholder) {
  selectEl.innerHTML = `<option value="">${placeholder}</option>`;
  data.forEach(item => {
    const o = document.createElement('option');
    o.value = item.id;
    o.textContent = item.name;
    selectEl.appendChild(o);
  });
  selectEl.disabled = false;
}

function kosongkan(selectEl, placeholder) {
  selectEl.innerHTML = `<option value="">${placeholder}</option>`;
  selectEl.disabled  = true;
}

function setSpinner(prefix, level, show) {
  const el = document.getElementById(`${prefix}_${level}_spin`);
  if (el) el.classList.toggle('show', show);
}

function simpanNama(prefix, level, selectEl) {
  const opt = selectEl.options[selectEl.selectedIndex];
  const nameEl = document.getElementById(`${prefix}_${level}_name`);
  if (nameEl) nameEl.value = opt ? opt.textContent : '';
}

function initWilayah(prefix) {
  const g = id => document.getElementById(`${prefix}_${id}`);

  // Load provinsi
  setSpinner(prefix, 'province', true);
  WILAYAH_API.provinces().then(data => {
    isi(g('province'), data, '-- Pilih Provinsi --');
    setSpinner(prefix, 'province', false);
  });

  // Provinsi → Kabupaten/Kota
  g('province').addEventListener('change', function () {
    simpanNama(prefix, 'province', this);
    kosongkan(g('regency'),  '-- Pilih Kab/Kota --');
    kosongkan(g('district'), '-- Pilih setelah Kab/Kota --');
    kosongkan(g('village'),  '-- Pilih setelah Kecamatan --');
    if (!this.value) return;
    setSpinner(prefix, 'regency', true);
    WILAYAH_API.regencies(this.value).then(data => {
      isi(g('regency'), data, '-- Pilih Kab/Kota --');
      setSpinner(prefix, 'regency', false);
    });
  });

  // Kab/Kota → Kecamatan
  g('regency').addEventListener('change', function () {
    simpanNama(prefix, 'regency', this);
    kosongkan(g('district'), '-- Pilih Kecamatan --');
    kosongkan(g('village'),  '-- Pilih setelah Kecamatan --');
    if (!this.value) return;
    setSpinner(prefix, 'district', true);
    WILAYAH_API.districts(this.value).then(data => {
      isi(g('district'), data, '-- Pilih Kecamatan --');
      setSpinner(prefix, 'district', false);
    });
  });

  // Kecamatan → Desa/Kelurahan
  g('district').addEventListener('change', function () {
    simpanNama(prefix, 'district', this);
    kosongkan(g('village'), '-- Pilih Desa/Kelurahan --');
    if (!this.value) return;
    setSpinner(prefix, 'village', true);
    WILAYAH_API.villages(this.value).then(data => {
      isi(g('village'), data, '-- Pilih Desa/Kelurahan --');
      setSpinner(prefix, 'village', false);
    });
  });

  g('village').addEventListener('change', function () {
    simpanNama(prefix, 'village', this);
  });
}

initWilayah('asal');
initWilayah('tujuan');

// ════════════════════════════════════════════════════════════════
// 4. GOOGLE MAPS PIN
// ════════════════════════════════════════════════════════════════
function bukaMapModal(prefix) {
  const modal = new bootstrap.Modal(document.getElementById(`mapModal_${prefix}`));
  modal.show();

  // Load iframe peta
  const mapEl = document.getElementById(`mapEl_${prefix}`);
  if (mapEl._loaded) return;
  mapEl._loaded = true;

  const lat = document.getElementById(`${prefix}_lat`).value || '-6.2088';
  const lng = document.getElementById(`${prefix}_lng`).value || '106.8456';

  document.getElementById(`${prefix}_modal_lat`).value = lat !== '-6.2088' ? lat : '';
  document.getElementById(`${prefix}_modal_lng`).value = lng !== '106.8456' ? lng : '';

  muatIframe(prefix, lat, lng);
}

function muatIframe(prefix, lat, lng) {
  const mapEl = document.getElementById(`mapEl_${prefix}`);
  mapEl.innerHTML = `
    <div style="position:relative;width:100%;height:420px">
      <iframe
        src="https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed&hl=id"
        width="100%" height="420"
        style="border:0;display:block"
        allowfullscreen loading="lazy">
      </iframe>
      <div style="position:absolute;inset:0;cursor:crosshair"
           onclick="ambilKoordinatDariKlik(event,'${prefix}',${lat},${lng})"
           title="Klik untuk memilih lokasi">
      </div>
      <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-100%);font-size:2rem;pointer-events:none">
        📍
      </div>
    </div>`;
}

function cariLokasiMap(prefix) {
  const q = document.getElementById(`mapSearch_${prefix}`).value.trim();
  if (!q) return;

  // Gunakan geocoding via nominatim (gratis, tanpa API key)
  document.getElementById(`mapLoading_${prefix}`).style.display = 'flex';

  fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q + ', Indonesia')}&format=json&limit=1`)
    .then(r => r.json())
    .then(data => {
      document.getElementById(`mapLoading_${prefix}`).style.display = 'none';
      if (data.length) {
        const lat = parseFloat(data[0].lat).toFixed(6);
        const lng = parseFloat(data[0].lon).toFixed(6);
        document.getElementById(`${prefix}_modal_lat`).value = lat;
        document.getElementById(`${prefix}_modal_lng`).value = lng;
        muatIframe(prefix, lat, lng);
        previewKoordinat(prefix);
      } else {
        alert('Lokasi tidak ditemukan. Coba kata kunci lain.');
      }
    })
    .catch(() => {
      document.getElementById(`mapLoading_${prefix}`).style.display = 'none';
    });
}

function muatMapDariKoordinat(prefix) {
  const lat = document.getElementById(`${prefix}_modal_lat`).value.trim();
  const lng = document.getElementById(`${prefix}_modal_lng`).value.trim();
  if (!lat || !lng) return alert('Masukkan koordinat terlebih dahulu.');
  muatIframe(prefix, lat, lng);
  previewKoordinat(prefix);
}

function previewKoordinat(prefix) {
  const lat = document.getElementById(`${prefix}_modal_lat`).value;
  const lng = document.getElementById(`${prefix}_modal_lng`).value;
  const el  = document.getElementById(`${prefix}_modal_coords_preview`);
  if (lat && lng) {
    el.innerHTML = `<i class="bi bi-geo-alt-fill" style="color:var(--danger)"></i>
      <span style="font-weight:600;color:var(--text-dark)">${lat}, ${lng}</span>`;
  }
}

function konfirmasiPin(prefix) {
  const lat = document.getElementById(`${prefix}_modal_lat`).value.trim();
  const lng = document.getElementById(`${prefix}_modal_lng`).value.trim();

  if (!lat || !lng) {
    alert('Silakan pilih lokasi di peta atau masukkan koordinat terlebih dahulu.');
    return;
  }

  // Simpan ke hidden input
  document.getElementById(`${prefix}_lat`).value = lat;
  document.getElementById(`${prefix}_lng`).value = lng;

  // Update display di form
  document.getElementById(`${prefix}_coords_display`).innerHTML =
    `<i class="bi bi-geo-alt-fill" style="color:var(--danger)"></i>
     <span style="font-weight:600;color:var(--text-dark)">${lat}, ${lng}</span>
     <a href="https://maps.google.com/maps?q=${lat},${lng}" target="_blank"
        style="font-size:.78rem;color:var(--primary);margin-left:.3rem">
       <i class="bi bi-box-arrow-up-right"></i> Lihat
     </a>`;

  // Update input manual juga
  const latM = document.getElementById(`${prefix}_lat_manual`);
  const lngM = document.getElementById(`${prefix}_lng_manual`);
  if (latM) latM.value = lat;
  if (lngM) lngM.value = lng;

  bootstrap.Modal.getInstance(document.getElementById(`mapModal_${prefix}`)).hide();
}

function updateKoordinat(prefix, lat, lng) {
  document.getElementById(`${prefix}_lat`).value = lat;
  document.getElementById(`${prefix}_lng`).value = lng;

  const display = document.getElementById(`${prefix}_coords_display`);
  if (lat && lng) {
    display.innerHTML = `<i class="bi bi-geo-alt-fill" style="color:var(--danger)"></i>
      <span style="font-weight:600;color:var(--text-dark)">${lat}, ${lng}</span>`;
  }
}

// Enter untuk cari di modal map
['asal', 'tujuan'].forEach(prefix => {
  const searchEl = document.getElementById(`mapSearch_${prefix}`);
  if (searchEl) {
    searchEl.addEventListener('keydown', e => {
      if (e.key === 'Enter') { e.preventDefault(); cariLokasiMap(prefix); }
    });
  }
});
</script>
@endpush
