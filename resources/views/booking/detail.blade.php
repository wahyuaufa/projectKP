{{-- resources/views/booking/detail.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking - Detail Pemesan')

@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
/* Wilayah select */
.wilayah-select-wrap { position: relative; }
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
  opacity: .6;
}
.wilayah-spinner {
  position: absolute; right: 2.2rem; top: 50%;
  transform: translateY(-50%);
  width: 14px; height: 14px;
  border: 2px solid var(--border);
  border-top-color: var(--primary);
  border-radius: 50%;
  display: none;
  animation: spin .6s linear infinite;
}
.wilayah-spinner.show { display: block; }
@keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

/* Leaflet z-index fix — pastikan peta selalu di atas */
.leaflet-pane,
.leaflet-tile,
.leaflet-marker-icon,
.leaflet-marker-shadow,
.leaflet-tile-container,
.leaflet-pane > svg,
.leaflet-pane > canvas,
.leaflet-zoom-box,
.leaflet-image-layer,
.leaflet-layer {
  z-index: auto !important;
}
.leaflet-top, .leaflet-bottom {
  z-index: 400 !important;
}
.leaflet-control {
  z-index: 400 !important;
}
.leaflet-popup-pane {
  z-index: 450 !important;
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
              <label class="form-label-gotrav">
                Catatan untuk Driver <small class="text-muted">(opsional)</small>
              </label>
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

    {{-- Sidebar --}}
    <div class="col-lg-5" data-aos="fade-left">
      <div class="card-gotrav sticky-top" style="top:90px">
        <div class="card-header-gotrav">
          <i class="bi bi-receipt me-2"></i>Ringkasan Pemesanan
        </div>
        <div class="card-body-gotrav">
          <div class="ticket-row">
            <div class="ticket-row-label">Armada</div>
            <div class="ticket-row-value">{{ $armada->nama }}</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Arah</div>
            <div class="ticket-row-value">{{ $arahRute }}</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Tanggal</div>
            <div class="ticket-row-value">
              {{ \Carbon\Carbon::parse(session('booking.tanggal'))->format('d M Y') }}
            </div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Jam Jemput</div>
            <div class="ticket-row-value">{{ $jamPenjemputan }} WIB</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Kursi</div>
            <div class="ticket-row-value">
              @foreach($kursi as $k)
              <span class="px-2 py-1 rounded-2 me-1 mb-1 d-inline-block"
                    style="background:var(--primary);color:#fff;font-size:.8rem">{{ $k }}</span>
              @endforeach
            </div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Penumpang</div>
            <div class="ticket-row-value">{{ count($kursi) }} Orang</div>
          </div>
          <hr style="border-color:var(--border)">
          <div class="ticket-row">
            <div class="ticket-row-label">Harga/Orang</div>
            <div class="ticket-row-value">
              Rp {{ number_format($hargaPerOrang, 0, ',', '.') }}
            </div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Subtotal</div>
            <div class="ticket-row-value">
              Rp {{ number_format($hargaPerOrang * count($kursi), 0, ',', '.') }}
            </div>
          </div>
          <div class="ticket-row" id="rowBiayaBagasi" style="display:none">
            <div class="ticket-row-label" style="color:var(--danger)">Bagasi Tambahan</div>
            <div class="ticket-row-value" style="color:var(--danger)" id="nilaiByBagasi">Rp 0</div>
          </div>
          <hr style="border-color:var(--border)">
          <div class="ticket-row">
            <div class="ticket-row-label fw-bold" style="color:var(--text-dark)">Total</div>
            <div class="ticket-row-value" id="totalHarga"
                 style="font-size:1.2rem;font-weight:800;color:var(--primary)">
              Rp {{ number_format($hargaPerOrang * count($kursi), 0, ',', '.') }}
            </div>
          </div>
          <div class="mt-3 p-3 rounded-3"
               style="background:rgba(40,167,69,.08);font-size:.82rem;color:#1a7a32">
            <i class="bi bi-cash-coin me-1"></i>
            Pembayaran langsung ke driver saat tiba di tujuan.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
{{-- Leaflet JS — HARUS dimuat sebelum script kita --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ════════════════════════════════════════════════════════════
// 1. BAGASI CALCULATOR
// ════════════════════════════════════════════════════════════
const hargaPerOrang   = {{ $hargaPerOrang }};
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
    document.getElementById('biayaBagasiInfo').textContent   =
      `${tambahan} item kelebihan → tambahan ${fmtIDR(biaya)}`;
  } else {
    document.getElementById('rowBiayaBagasi').style.display = 'none';
    document.getElementById('biayaBagasiInfo').style.display = 'none';
  }
});

// ════════════════════════════════════════════════════════════
// 2. WILAYAH CASCADE AJAX
// ════════════════════════════════════════════════════════════
const WAPI = {
  provinces: ()  => fetch('/api/wilayah/provinces').then(r => r.json()),
  regencies: id  => fetch(`/api/wilayah/regencies/${id}`).then(r => r.json()),
  districts: id  => fetch(`/api/wilayah/districts/${id}`).then(r => r.json()),
  villages:  id  => fetch(`/api/wilayah/villages/${id}`).then(r => r.json()),
};

function isiSelect(el, data, ph) {
  el.innerHTML = `<option value="">${ph}</option>`;
  data.forEach(d => {
    const o = document.createElement('option');
    o.value = d.id; o.textContent = d.name;
    el.appendChild(o);
  });
  el.disabled = false;
}
function kosongkan(el, ph) {
  el.innerHTML = `<option value="">${ph}</option>`;
  el.disabled = true;
}
function spin(p, l, show) {
  document.getElementById(`${p}_${l}_spin`)?.classList.toggle('show', show);
}
function simpanNama(p, l, el) {
  const h = document.getElementById(`${p}_${l}_name`);
  if (h) h.value = el.options[el.selectedIndex]?.value
    ? el.options[el.selectedIndex].textContent : '';
}

function initWilayah(prefix) {
  const g = id => document.getElementById(`${prefix}_${id}`);

  spin(prefix, 'province', true);
  WAPI.provinces().then(data => {
    isiSelect(g('province'), data, '-- Pilih Provinsi --');
    spin(prefix, 'province', false);
  });

  g('province').addEventListener('change', function () {
    simpanNama(prefix, 'province', this);
    kosongkan(g('regency'),  '-- Pilih setelah Provinsi --');
    kosongkan(g('district'), '-- Pilih setelah Kab/Kota --');
    kosongkan(g('village'),  '-- Pilih setelah Kecamatan --');
    if (!this.value) return;
    spin(prefix, 'regency', true);
    WAPI.regencies(this.value).then(data => {
      isiSelect(g('regency'), data, '-- Pilih Kab/Kota --');
      spin(prefix, 'regency', false);
    });
  });

  g('regency').addEventListener('change', function () {
    simpanNama(prefix, 'regency', this);
    kosongkan(g('district'), '-- Pilih Kecamatan --');
    kosongkan(g('village'),  '-- Pilih setelah Kecamatan --');
    if (!this.value) return;
    spin(prefix, 'district', true);
    WAPI.districts(this.value).then(data => {
      isiSelect(g('district'), data, '-- Pilih Kecamatan --');
      spin(prefix, 'district', false);
    });
  });

  g('district').addEventListener('change', function () {
    simpanNama(prefix, 'district', this);
    kosongkan(g('village'), '-- Pilih Desa/Kelurahan --');
    if (!this.value) return;
    spin(prefix, 'village', true);
    WAPI.villages(this.value).then(data => {
      isiSelect(g('village'), data, '-- Pilih Desa/Kelurahan --');
      spin(prefix, 'village', false);
    });
  });

  g('village').addEventListener('change', function () {
    simpanNama(prefix, 'village', this);
  });
}

initWilayah('asal');
initWilayah('tujuan');

// ════════════════════════════════════════════════════════════
// 3. TOGGLE OPSIONAL
// ════════════════════════════════════════════════════════════
['asal', 'tujuan'].forEach(p => {
  const t = document.getElementById(`toggle_${p}`);
  const b = document.getElementById(`opsional_${p}`);
  if (t && b) {
    t.addEventListener('change', () => {
      b.style.display = t.checked ? '' : 'none';
    });
  }
});

// ════════════════════════════════════════════════════════════
// 4. LEAFLET INLINE MAP
// Tidak pakai modal — langsung render di dalam form
// ════════════════════════════════════════════════════════════
const leafletMaps    = {};  // { prefix: L.Map }
const leafletMarkers = {};  // { prefix: L.Marker }

const DEF_LAT  = -6.2088;
const DEF_LNG  = 106.8456;
const DEF_ZOOM = 13;

// Ikon marker bergaya Apple Maps / Google Maps
function buatIcon() {
  return L.divIcon({
    className: '',
    html: `
      <div style="position:relative;display:flex;flex-direction:column;align-items:center">
        <div style="
          width:36px;height:36px;
          background:#EA4335;
          border:3px solid #fff;
          border-radius:50% 50% 50% 0;
          transform:rotate(-45deg);
          box-shadow:0 3px 10px rgba(0,0,0,.3);
          display:flex;align-items:center;justify-content:center;
          position:relative;z-index:2
        ">
          <div style="
            width:12px;height:12px;
            background:#fff;
            border-radius:50%;
          "></div>
        </div>
        <div style="
          width:18px;height:6px;
          background:rgba(0,0,0,.2);
          border-radius:50%;
          margin-top:2px;
          filter:blur(2px);
          flex-shrink:0
        "></div>
      </div>`,
    iconSize:   [36, 48],
    iconAnchor: [18, 44],
    popupAnchor:[0, -44],
  });
}

function tampilkanPeta(prefix) {
  const container = document.getElementById(`mapContainer_${prefix}`);
  const btnLabel  = document.getElementById(`btnMapLabel_${prefix}`);

  // Toggle tampilan
  const sedangTampil = container.style.display !== 'none';
  if (sedangTampil) {
    container.style.display = 'none';
    btnLabel.textContent = 'Buka Peta';
    return;
  }

  container.style.display = 'block';
  btnLabel.textContent = 'Tutup Peta';

  // Jika peta sudah pernah dibuat, cukup invalidate size
  if (leafletMaps[prefix]) {
    setTimeout(() => leafletMaps[prefix].invalidateSize(), 100);
    return;
  }

  // Ambil koordinat tersimpan (jika ada)
  const savedLat = parseFloat(document.getElementById(`${prefix}_lat`).value) || DEF_LAT;
  const savedLng = parseFloat(document.getElementById(`${prefix}_lng`).value) || DEF_LNG;

  // Beri waktu DOM render dulu sebelum init Leaflet
  setTimeout(() => {
    const map = L.map(`leafletMap_${prefix}`, {
      center: [savedLat, savedLng],
      zoom: DEF_ZOOM,
      zoomControl: true,
    });

    leafletMaps[prefix] = map;

    // Tile OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 19,
    }).addTo(map);

    // Marker draggable
    const marker = L.marker([savedLat, savedLng], {
      draggable: true,
      icon: buatIcon(),
    }).addTo(map);

    leafletMarkers[prefix] = marker;

    // Event: marker digeser
    marker.on('dragend', function () {
      const pos = this.getLatLng();
      setKoordinat(prefix, pos.lat, pos.lng);
    });

    // Event: klik di peta → pindahkan marker
    map.on('click', function (e) {
      marker.setLatLng(e.latlng);
      setKoordinat(prefix, e.latlng.lat, e.latlng.lng);
    });

    // Set nilai awal
    setKoordinat(prefix, savedLat, savedLng);

    // Paksa ukuran ulang
    map.invalidateSize();
  }, 150);
}

function setKoordinat(prefix, lat, lng) {
  const latStr = parseFloat(lat).toFixed(7);
  const lngStr = parseFloat(lng).toFixed(7);

  // Update hidden input (yang dikirim ke server)
  document.getElementById(`${prefix}_lat`).value = latStr;
  document.getElementById(`${prefix}_lng`).value = lngStr;

  // Update display input (yang terlihat user)
  document.getElementById(`${prefix}_lat_display`).value = latStr;
  document.getElementById(`${prefix}_lng_display`).value = lngStr;

  // Update text display
  const display = document.getElementById(`${prefix}_coords_display`);
  display.innerHTML = `
    <i class="bi bi-geo-alt-fill" style="color:var(--danger)"></i>
    <strong>${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}</strong>`;

  // Tampilkan link Google Maps
  const mapsLink   = document.getElementById(`${prefix}_maps_link`);
  const mapsAnchor = document.getElementById(`${prefix}_maps_anchor`);
  if (mapsLink && mapsAnchor) {
    mapsLink.style.display = 'block';
    mapsAnchor.href = `https://maps.google.com/?q=${latStr},${lngStr}`;
  }
}

function syncKoordDariInput(prefix) {
  const lat = parseFloat(document.getElementById(`${prefix}_lat_display`).value);
  const lng = parseFloat(document.getElementById(`${prefix}_lng_display`).value);
  if (!isNaN(lat) && !isNaN(lng)) {
    document.getElementById(`${prefix}_lat`).value = lat.toFixed(7);
    document.getElementById(`${prefix}_lng`).value = lng.toFixed(7);
  }
}

function pindahMarkerKoord(prefix) {
  const lat = parseFloat(document.getElementById(`${prefix}_lat_display`).value);
  const lng = parseFloat(document.getElementById(`${prefix}_lng_display`).value);

  if (isNaN(lat) || isNaN(lng)) {
    alert('Masukkan koordinat yang valid terlebih dahulu.');
    return;
  }

  if (leafletMaps[prefix] && leafletMarkers[prefix]) {
    leafletMarkers[prefix].setLatLng([lat, lng]);
    leafletMaps[prefix].setView([lat, lng], DEF_ZOOM);
    setKoordinat(prefix, lat, lng);
  }
}

// Gunakan lokasi GPS perangkat
function lokasiSaya(prefix) {
  if (! navigator.geolocation) {
    alert('Browser Anda tidak mendukung geolokasi.');
    return;
  }

  const loading = document.getElementById(`mapLoading_${prefix}`);
  if (loading) loading.style.display = 'flex';

  navigator.geolocation.getCurrentPosition(
    pos => {
      if (loading) loading.style.display = 'none';
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;

      // Tampilkan peta dulu jika belum terbuka
      const container = document.getElementById(`mapContainer_${prefix}`);
      if (container.style.display === 'none' || !container.style.display) {
        tampilkanPeta(prefix);
        setTimeout(() => {
          if (leafletMaps[prefix] && leafletMarkers[prefix]) {
            leafletMarkers[prefix].setLatLng([lat, lng]);
            leafletMaps[prefix].setView([lat, lng], 17);
            setKoordinat(prefix, lat, lng);
          }
        }, 400);
      } else {
        if (leafletMaps[prefix] && leafletMarkers[prefix]) {
          leafletMarkers[prefix].setLatLng([lat, lng]);
          leafletMaps[prefix].setView([lat, lng], 17);
          setKoordinat(prefix, lat, lng);
        }
      }
    },
    err => {
      if (loading) loading.style.display = 'none';
      alert('Tidak dapat mengakses lokasi Anda. Pastikan izin lokasi diaktifkan.');
    },
    { timeout: 10000, maximumAge: 60000 }
  );
}
</script>
@endpush