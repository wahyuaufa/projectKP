{{--
  resources/views/booking/_alamat-form.blade.php
  Reusable partial: form alamat lengkap cascade wilayah Indonesia
  Parameter:
    $prefix  = 'asal' | 'tujuan'
--}}

{{-- ── Baris 1: Provinsi + Kab/Kota ──────────────────────── --}}
<div class="row g-3 mb-3">
  <div class="col-sm-6">
    <label class="form-label-gotrav">
      Provinsi <span class="text-danger">*</span>
    </label>
    <div class="wilayah-select-wrap">
      <select name="{{ $prefix }}_province_id"
              id="{{ $prefix }}_province"
              class="form-control-gotrav wilayah-sel"
              required>
        <option value="">-- Pilih Provinsi --</option>
      </select>
      <div class="wilayah-spinner" id="{{ $prefix }}_province_spin"></div>
    </div>
  </div>

  <div class="col-sm-6">
    <label class="form-label-gotrav">
      Kabupaten / Kota <span class="text-danger">*</span>
    </label>
    <div class="wilayah-select-wrap">
      <select name="{{ $prefix }}_regency_id"
              id="{{ $prefix }}_regency"
              class="form-control-gotrav wilayah-sel"
              required disabled>
        <option value="">-- Pilih setelah Provinsi --</option>
      </select>
      <div class="wilayah-spinner" id="{{ $prefix }}_regency_spin"></div>
    </div>
  </div>
</div>

{{-- ── Baris 2: Kecamatan + Desa/Kelurahan ────────────────── --}}
<div class="row g-3 mb-3">
  <div class="col-sm-6">
    <label class="form-label-gotrav">
      Kecamatan <span class="text-danger">*</span>
    </label>
    <div class="wilayah-select-wrap">
      <select name="{{ $prefix }}_district_id"
              id="{{ $prefix }}_district"
              class="form-control-gotrav wilayah-sel"
              required disabled>
        <option value="">-- Pilih setelah Kab/Kota --</option>
      </select>
      <div class="wilayah-spinner" id="{{ $prefix }}_district_spin"></div>
    </div>
  </div>

  <div class="col-sm-6">
    <label class="form-label-gotrav">
      Desa / Kelurahan <span class="text-danger">*</span>
    </label>
    <div class="wilayah-select-wrap">
      <select name="{{ $prefix }}_village_id"
              id="{{ $prefix }}_village"
              class="form-control-gotrav wilayah-sel"
              required disabled>
        <option value="">-- Pilih setelah Kecamatan --</option>
      </select>
      <div class="wilayah-spinner" id="{{ $prefix }}_village_spin"></div>
    </div>
  </div>
</div>

{{-- Hidden: nama teks wilayah (untuk disimpan ke DB) --}}
<input type="hidden" name="{{ $prefix }}_province_name" id="{{ $prefix }}_province_name">
<input type="hidden" name="{{ $prefix }}_regency_name"  id="{{ $prefix }}_regency_name">
<input type="hidden" name="{{ $prefix }}_district_name" id="{{ $prefix }}_district_name">
<input type="hidden" name="{{ $prefix }}_village_name"  id="{{ $prefix }}_village_name">

{{-- ── Nama Jalan ───────────────────────────────────────────── --}}
<div class="mb-3">
  <label class="form-label-gotrav">
    Nama Jalan / Alamat Lengkap <span class="text-danger">*</span>
  </label>
  <div class="input-icon-wrap">
    <i class="bi bi-signpost-2 input-icon"></i>
    <input type="text"
           name="{{ $prefix }}_jalan"
           class="form-control-gotrav"
           placeholder="Contoh: Jl. Panjang No. 12A"
           value="{{ old($prefix . '_jalan') }}"
           required>
  </div>
</div>

{{-- ── Toggle Opsional ─────────────────────────────────────── --}}
<div class="mt-1 mb-2">
  <label class="d-flex align-items-center gap-2 user-select-none"
         style="cursor:pointer;font-size:.88rem;font-weight:600;color:var(--text-dark)">
    <input type="checkbox"
           id="toggle_{{ $prefix }}"
           class="form-check-input m-0"
           style="width:17px;height:17px;cursor:pointer;accent-color:var(--primary)">
    <i class="bi bi-plus-circle-fill" style="color:var(--primary);font-size:.95rem"></i>
    Tambah detail alamat — RT/RW, Patokan & Pin Maps <em style="font-weight:400;color:var(--muted)">(opsional)</em>
  </label>
</div>

{{-- ── Opsional Box ─────────────────────────────────────────── --}}
<div id="opsional_{{ $prefix }}"
     style="display:none;border:1.5px dashed var(--border);border-radius:var(--radius);padding:1.2rem;margin-top:.5rem;background:var(--bg-section)">

  <div class="row g-3 mb-3">
    {{-- RT --}}
    <div class="col-4 col-sm-3">
      <label class="form-label-gotrav" style="font-size:.82rem">RT</label>
      <input type="text"
             name="{{ $prefix }}_rt"
             class="form-control-gotrav"
             placeholder="001"
             maxlength="3"
             value="{{ old($prefix . '_rt') }}"
             style="font-size:.9rem">
    </div>
    {{-- RW --}}
    <div class="col-4 col-sm-3">
      <label class="form-label-gotrav" style="font-size:.82rem">RW</label>
      <input type="text"
             name="{{ $prefix }}_rw"
             class="form-control-gotrav"
             placeholder="001"
             maxlength="3"
             value="{{ old($prefix . '_rw') }}"
             style="font-size:.9rem">
    </div>
    {{-- Kode Pos --}}
    <div class="col-4 col-sm-6">
      <label class="form-label-gotrav" style="font-size:.82rem">Kode Pos</label>
      <input type="text"
             name="{{ $prefix }}_kodepos"
             class="form-control-gotrav"
             placeholder="11820"
             maxlength="5"
             value="{{ old($prefix . '_kodepos') }}"
             style="font-size:.9rem">
    </div>

    {{-- Patokan --}}
    <div class="col-12">
      <label class="form-label-gotrav" style="font-size:.82rem">
        Patokan / Landmark
      </label>
      <div class="input-icon-wrap">
        <i class="bi bi-building input-icon" style="font-size:.9rem"></i>
        <input type="text"
               name="{{ $prefix }}_patokan"
               class="form-control-gotrav"
               placeholder="Contoh: Depan Alfamart, Sebelah SPBU Pertamina..."
               value="{{ old($prefix . '_patokan') }}"
               style="font-size:.9rem">
      </div>
      <div style="font-size:.75rem;color:var(--muted);margin-top:.3rem">
        Sebutkan tempat yang mudah dikenali driver
      </div>
    </div>
  </div>

  {{-- Pin Point Google Maps --}}
  <div>
    <label class="form-label-gotrav" style="font-size:.82rem">
      <i class="bi bi-pin-map-fill me-1" style="color:var(--danger)"></i>
      Pin Point Google Maps
    </label>

    {{-- Hidden lat/lng --}}
    <input type="hidden" name="{{ $prefix }}_lat" id="{{ $prefix }}_lat" value="{{ old($prefix . '_lat') }}">
    <input type="hidden" name="{{ $prefix }}_lng" id="{{ $prefix }}_lng" value="{{ old($prefix . '_lng') }}">

    {{-- Koordinat display --}}
    <div id="{{ $prefix }}_coords_display"
         class="mb-2 px-3 py-2 rounded-2 d-flex align-items-center gap-2"
         style="background:#fff;border:1.5px solid var(--border);font-size:.83rem;min-height:38px">
      @if(old($prefix . '_lat'))
        <i class="bi bi-geo-alt-fill" style="color:var(--danger)"></i>
        <span>{{ old($prefix . '_lat') }}, {{ old($prefix . '_lng') }}</span>
      @else
        <i class="bi bi-geo-alt" style="color:var(--muted)"></i>
        <span style="color:var(--muted);font-style:italic">Belum ada pin dipilih</span>
      @endif
    </div>

    <div class="d-flex flex-wrap gap-2">
      <button type="button"
              class="btn-outline-gotrav btn d-flex align-items-center gap-2"
              style="font-size:.85rem;padding:.5rem 1rem"
              onclick="bukaMapModal('{{ $prefix }}')">
        <i class="bi bi-map" style="color:var(--primary)"></i>
        Buka Peta & Pilih Lokasi
      </button>

      {{-- Input manual koordinat --}}
      <button type="button"
              class="btn btn-sm"
              style="font-size:.82rem;border:1.5px solid var(--border);background:#fff;border-radius:var(--radius-sm);padding:.4rem .9rem;color:var(--muted)"
              onclick="document.getElementById('koord_manual_{{ $prefix }}').style.display = document.getElementById('koord_manual_{{ $prefix }}').style.display === 'none' ? '' : 'none'">
        <i class="bi bi-keyboard me-1"></i> Input Manual
      </button>
    </div>

    {{-- Manual koordinat input --}}
    <div id="koord_manual_{{ $prefix }}"
         style="display:none;margin-top:.7rem;padding:.8rem;background:#fff;border-radius:var(--radius-sm);border:1.5px solid var(--border)">
      <div style="font-size:.78rem;color:var(--muted);margin-bottom:.5rem">
        <i class="bi bi-info-circle me-1"></i>
        Cara ambil koordinat: Buka Google Maps → Klik kanan lokasi → Salin koordinat
      </div>
      <div class="row g-2">
        <div class="col-6">
          <input type="text" id="{{ $prefix }}_lat_manual"
                 class="form-control-gotrav"
                 placeholder="Latitude: -6.1751"
                 style="font-size:.85rem"
                 value="{{ old($prefix . '_lat') }}"
                 oninput="updateKoordinat('{{ $prefix }}', this.value, document.getElementById('{{ $prefix }}_lng_manual').value)">
        </div>
        <div class="col-6">
          <input type="text" id="{{ $prefix }}_lng_manual"
                 class="form-control-gotrav"
                 placeholder="Longitude: 106.8272"
                 style="font-size:.85rem"
                 value="{{ old($prefix . '_lng') }}"
                 oninput="updateKoordinat('{{ $prefix }}', document.getElementById('{{ $prefix }}_lat_manual').value, this.value)">
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════ --}}
{{-- Modal Peta                                                  --}}
{{-- ══════════════════════════════════════════════════════════ --}}
<div class="modal fade"
     id="mapModal_{{ $prefix }}"
     tabindex="-1"
     data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content" style="border-radius:var(--radius-lg);border:none;overflow:hidden">

      {{-- Modal Header --}}
      <div class="modal-header" style="background:var(--primary);color:#fff;border:none;padding:1rem 1.5rem">
        <div>
          <h6 class="modal-title mb-0" style="font-family:var(--font-heading);font-weight:700">
            <i class="bi bi-geo-alt-fill me-2"></i>
            Pilih Lokasi {{ $prefix === 'asal' ? 'Penjemputan' : 'Tujuan' }}
          </h6>
          <div style="font-size:.75rem;opacity:.75;margin-top:.1rem">
            Klik pada peta untuk menentukan pin lokasi
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      {{-- Modal Body --}}
      <div class="modal-body p-0" style="max-height:80vh;overflow-y:auto">

        {{-- Search Bar --}}
        <div class="p-3" style="border-bottom:1px solid var(--border)">
          <div class="input-icon-wrap">
            <i class="bi bi-search input-icon"></i>
            <input type="text"
                   id="mapSearch_{{ $prefix }}"
                   class="form-control-gotrav"
                   placeholder="Cari alamat, nama tempat, atau landmark..."
                   style="padding-right:6rem">
            <button type="button"
                    onclick="cariLokasiMap('{{ $prefix }}')"
                    class="btn-gotrav btn btn-sm"
                    style="position:absolute;right:.4rem;top:50%;transform:translateY(-50%);font-size:.8rem;padding:.3rem .8rem">
              Cari
            </button>
          </div>
        </div>

        {{-- Map Frame --}}
        <div style="position:relative">
          <div id="mapEl_{{ $prefix }}"
               style="width:100%;height:420px;background:var(--bg-section);display:flex;align-items:center;justify-content:center">
            <div class="text-center text-muted p-4">
              <i class="bi bi-map fs-1 d-block mb-2" style="color:var(--border)"></i>
              <div style="font-size:.9rem;font-weight:600">Peta akan dimuat saat tombol diklik</div>
              <div style="font-size:.8rem;margin-top:.3rem">Atau gunakan input koordinat manual di bawah</div>
            </div>
          </div>

          {{-- Loading overlay --}}
          <div id="mapLoading_{{ $prefix }}"
               style="display:none;position:absolute;inset:0;background:rgba(255,255,255,.8);align-items:center;justify-content:center;z-index:10">
            <div class="text-center">
              <div class="spinner-border" style="color:var(--primary);width:2rem;height:2rem"></div>
              <div style="margin-top:.5rem;font-size:.85rem;color:var(--muted)">Memuat peta...</div>
            </div>
          </div>
        </div>

        {{-- Koordinat Panel --}}
        <div class="p-3" style="border-top:1px solid var(--border);background:var(--bg-section)">
          <div class="row g-2 align-items-end">
            <div class="col-sm-4">
              <label style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.25rem">LATITUDE</label>
              <input type="text"
                     id="{{ $prefix }}_modal_lat"
                     class="form-control-gotrav"
                     placeholder="-6.1751"
                     style="font-size:.88rem"
                     oninput="previewKoordinat('{{ $prefix }}')">
            </div>
            <div class="col-sm-4">
              <label style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.25rem">LONGITUDE</label>
              <input type="text"
                     id="{{ $prefix }}_modal_lng"
                     class="form-control-gotrav"
                     placeholder="106.8272"
                     style="font-size:.88rem"
                     oninput="previewKoordinat('{{ $prefix }}')">
            </div>
            <div class="col-sm-4">
              <button type="button"
                      class="btn-outline-gotrav btn w-100"
                      style="font-size:.85rem"
                      onclick="muatMapDariKoordinat('{{ $prefix }}')">
                <i class="bi bi-crosshair me-1"></i> Tampilkan di Peta
              </button>
            </div>
          </div>
          <div style="font-size:.74rem;color:var(--muted);margin-top:.5rem">
            <i class="bi bi-lightbulb me-1"></i>
            Tips: Buka Google Maps → Klik kanan di lokasi → Koordinat muncul di bagian atas menu konteks
          </div>
        </div>

      </div>

      {{-- Modal Footer --}}
      <div class="modal-footer" style="border:none;padding:1rem 1.5rem;background:#fff">
        <div id="{{ $prefix }}_modal_coords_preview"
             class="me-auto d-flex align-items-center gap-2"
             style="font-size:.85rem;color:var(--muted)">
          <i class="bi bi-geo-alt"></i>
          <span>Belum ada koordinat</span>
        </div>
        <button type="button" class="btn-outline-gotrav btn" data-bs-dismiss="modal">
          Batal
        </button>
        <button type="button"
                class="btn-gotrav btn"
                onclick="konfirmasiPin('{{ $prefix }}')">
          <i class="bi bi-check-circle me-1"></i> Gunakan Lokasi Ini
        </button>
      </div>

    </div>
  </div>
</div>
