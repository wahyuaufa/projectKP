{{--
  resources/views/booking/_alamat-form.blade.php
  Parameter: $prefix = 'asal' | 'tujuan'
--}}

{{-- Wilayah Cascade --}}
<div class="row g-3 mb-3">
  <div class="col-sm-6">
    <label class="form-label-gotrav">Provinsi <span class="text-danger">*</span></label>
    <div class="wilayah-select-wrap">
      <select name="{{ $prefix }}_province_id" id="{{ $prefix }}_province"
              class="form-control-gotrav wilayah-sel" required>
        <option value="">-- Pilih Provinsi --</option>
      </select>
      <div class="wilayah-spinner" id="{{ $prefix }}_province_spin"></div>
    </div>
  </div>
  <div class="col-sm-6">
    <label class="form-label-gotrav">Kabupaten / Kota <span class="text-danger">*</span></label>
    <div class="wilayah-select-wrap">
      <select name="{{ $prefix }}_regency_id" id="{{ $prefix }}_regency"
              class="form-control-gotrav wilayah-sel" required disabled>
        <option value="">-- Pilih setelah Provinsi --</option>
      </select>
      <div class="wilayah-spinner" id="{{ $prefix }}_regency_spin"></div>
    </div>
  </div>
  <div class="col-sm-6">
    <label class="form-label-gotrav">Kecamatan <span class="text-danger">*</span></label>
    <div class="wilayah-select-wrap">
      <select name="{{ $prefix }}_district_id" id="{{ $prefix }}_district"
              class="form-control-gotrav wilayah-sel" required disabled>
        <option value="">-- Pilih setelah Kab/Kota --</option>
      </select>
      <div class="wilayah-spinner" id="{{ $prefix }}_district_spin"></div>
    </div>
  </div>
  <div class="col-sm-6">
    <label class="form-label-gotrav">Desa / Kelurahan <span class="text-danger">*</span></label>
    <div class="wilayah-select-wrap">
      <select name="{{ $prefix }}_village_id" id="{{ $prefix }}_village"
              class="form-control-gotrav wilayah-sel" required disabled>
        <option value="">-- Pilih setelah Kecamatan --</option>
      </select>
      <div class="wilayah-spinner" id="{{ $prefix }}_village_spin"></div>
    </div>
  </div>
</div>

{{-- Hidden nama wilayah --}}
<input type="hidden" name="{{ $prefix }}_province_name" id="{{ $prefix }}_province_name">
<input type="hidden" name="{{ $prefix }}_regency_name"  id="{{ $prefix }}_regency_name">
<input type="hidden" name="{{ $prefix }}_district_name" id="{{ $prefix }}_district_name">
<input type="hidden" name="{{ $prefix }}_village_name"  id="{{ $prefix }}_village_name">

{{-- Nama Jalan --}}
<div class="mb-3">
  <label class="form-label-gotrav">
    Nama Jalan / Alamat Lengkap <span class="text-danger">*</span>
  </label>
  <div class="input-icon-wrap">
    <i class="bi bi-signpost-2 input-icon"></i>
    <input type="text" name="{{ $prefix }}_jalan" class="form-control-gotrav"
           placeholder="Contoh: Jl. Panjang No. 12A"
           value="{{ old($prefix . '_jalan') }}" required>
  </div>
</div>

{{-- Toggle Opsional --}}
<div class="mt-1 mb-2">
  <label class="d-flex align-items-center gap-2 user-select-none"
         style="cursor:pointer;font-size:.88rem;font-weight:600;color:var(--text-dark)">
    <input type="checkbox" id="toggle_{{ $prefix }}" class="form-check-input m-0"
           style="width:17px;height:17px;cursor:pointer;accent-color:var(--primary)">
    <i class="bi bi-plus-circle-fill" style="color:var(--primary);font-size:.95rem"></i>
    Tambah RT/RW, Patokan & Pin Lokasi
    <em style="font-weight:400;color:var(--muted)">(opsional)</em>
  </label>
</div>

{{-- Opsional Box --}}
<div id="opsional_{{ $prefix }}"
     style="display:none;border:1.5px dashed var(--border);border-radius:var(--radius);
            padding:1.2rem;margin-top:.5rem;background:var(--bg-section)">

  <div class="row g-3 mb-3">
    <div class="col-4 col-sm-3">
      <label class="form-label-gotrav" style="font-size:.82rem">RT</label>
      <input type="text" name="{{ $prefix }}_rt" class="form-control-gotrav"
             placeholder="001" maxlength="3" style="font-size:.9rem"
             value="{{ old($prefix . '_rt') }}">
    </div>
    <div class="col-4 col-sm-3">
      <label class="form-label-gotrav" style="font-size:.82rem">RW</label>
      <input type="text" name="{{ $prefix }}_rw" class="form-control-gotrav"
             placeholder="001" maxlength="3" style="font-size:.9rem"
             value="{{ old($prefix . '_rw') }}">
    </div>
    <div class="col-4 col-sm-6">
      <label class="form-label-gotrav" style="font-size:.82rem">Kode Pos</label>
      <input type="text" name="{{ $prefix }}_kodepos" class="form-control-gotrav"
             placeholder="11820" maxlength="5" style="font-size:.9rem"
             value="{{ old($prefix . '_kodepos') }}">
    </div>
    <div class="col-12">
      <label class="form-label-gotrav" style="font-size:.82rem">Patokan / Landmark</label>
      <div class="input-icon-wrap">
        <i class="bi bi-building input-icon" style="font-size:.9rem"></i>
        <input type="text" name="{{ $prefix }}_patokan" class="form-control-gotrav"
               placeholder="Contoh: Depan Alfamart, Sebelah SPBU..."
               style="font-size:.9rem" value="{{ old($prefix . '_patokan') }}">
      </div>
    </div>
  </div>

  {{-- ── PIN LOKASI ─────────────────────────────────────────── --}}
  <div>
    <label class="form-label-gotrav" style="font-size:.82rem">
      <svg style="width:14px;height:14px;margin-right:4px;vertical-align:-2px" viewBox="0 0 24 24" fill="#EA4335">
        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
      </svg>
      Pin Lokasi di Peta
    </label>

    {{-- Hidden koordinat untuk dikirim ke server --}}
    <input type="hidden" name="{{ $prefix }}_lat" id="{{ $prefix }}_lat"
           value="{{ old($prefix . '_lat') }}">
    <input type="hidden" name="{{ $prefix }}_lng" id="{{ $prefix }}_lng"
           value="{{ old($prefix . '_lng') }}">

    {{-- ── Tombol buka peta + Lokasi Saya ──────────────────── --}}
    <div class="d-flex gap-2 mb-2 flex-wrap">
      <button type="button"
              class="btn-outline-gotrav btn d-flex align-items-center gap-2"
              style="font-size:.85rem;padding:.45rem 1rem"
              onclick="tampilkanPeta('{{ $prefix }}')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
          <path d="M9 20L3 17V4l6 3 6-3 6 3v7M9 20V7M15 14v7"/>
          <circle cx="15" cy="18" r="3" fill="#EA4335" stroke="none"/>
        </svg>
        <span id="btnMapLabel_{{ $prefix }}">Buka Peta</span>
      </button>

      <button type="button"
              onclick="lokasiSaya('{{ $prefix }}')"
              class="btn d-flex align-items-center gap-2"
              style="font-size:.85rem;padding:.45rem 1rem;border-radius:var(--radius-sm);
                     border:1.5px solid #1a73e8;background:transparent;color:#1a73e8;
                     font-family:var(--font-heading);font-weight:600;transition:all .2s"
              onmouseover="this.style.background='rgba(26,115,232,.08)'"
              onmouseout="this.style.background='transparent'">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
        </svg>
        Lokasi Saya
      </button>
    </div>

    {{-- Container peta inline --}}
    <div id="mapContainer_{{ $prefix }}"
         style="display:none;border-radius:12px;overflow:hidden;
                border:1.5px solid var(--border);
                box-shadow:0 4px 20px rgba(0,0,0,.1)">

      {{-- ── Peta Leaflet ────────────────────────────────────── --}}
      <div style="position:relative">
        <div id="leafletMap_{{ $prefix }}"
             style="height:320px;width:100%"></div>

        {{-- Loading overlay --}}
        <div id="mapLoading_{{ $prefix }}"
             style="display:none;position:absolute;inset:0;
                    background:rgba(255,255,255,.85);z-index:999;
                    align-items:center;justify-content:center;flex-direction:column;gap:8px">
          <div class="spinner-border spinner-border-sm" style="color:#1a73e8"></div>
          <div style="font-size:13px;color:#5f6368">Mencari lokasi...</div>
        </div>
      </div>

      {{-- ── Panel koordinat --}}
      <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;
                  background:#f8f9fa;border-top:1px solid #e8eaed;flex-wrap:wrap">

        {{-- Input lat dengan floating label --}}
        <div style="position:relative;flex:1;min-width:120px">
          <label style="position:absolute;top:-8px;left:10px;font-size:10px;
                        font-weight:600;color:#1a73e8;background:#f8f9fa;
                        padding:0 3px;letter-spacing:.05em;pointer-events:none">
            LATITUDE
          </label>
          <input type="text" id="{{ $prefix }}_lat_display"
                 style="width:100%;border:1.5px solid #1a73e8;border-radius:8px;
                        padding:7px 10px;font-size:13px;background:#fff;
                        color:#202124;outline:none;font-family:'Courier New',monospace"
                 oninput="syncKoordDariInput('{{ $prefix }}')">
        </div>

        {{-- Input lng dengan floating label --}}
        <div style="position:relative;flex:1;min-width:120px">
          <label style="position:absolute;top:-8px;left:10px;font-size:10px;
                        font-weight:600;color:#1a73e8;background:#f8f9fa;
                        padding:0 3px;letter-spacing:.05em;pointer-events:none">
            LONGITUDE
          </label>
          <input type="text" id="{{ $prefix }}_lng_display"
                 style="width:100%;border:1.5px solid #1a73e8;border-radius:8px;
                        padding:7px 10px;font-size:13px;background:#fff;
                        color:#202124;outline:none;font-family:'Courier New',monospace"
                 oninput="syncKoordDariInput('{{ $prefix }}')">
        </div>

        {{-- Tombol pindah marker --}}
        <button type="button" onclick="pindahMarkerKoord('{{ $prefix }}')"
                style="display:flex;align-items:center;gap:5px;
                       padding:7px 12px;border-radius:8px;
                       border:1.5px solid #dadce0;background:#fff;
                       color:#5f6368;font-size:13px;cursor:pointer;
                       white-space:nowrap;transition:all .15s;font-family:inherit"
                onmouseover="this.style.borderColor='#1a73e8';this.style.color='#1a73e8'"
                onmouseout="this.style.borderColor='#dadce0';this.style.color='#5f6368'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="3"/>
            <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
          </svg>
          Pindah
        </button>
      </div>

      {{-- ── Koordinat terpilih + Link Maps ──────────────────── --}}
      <div style="display:flex;align-items:center;gap:8px;padding:8px 14px;
                  background:#fff;border-top:1px solid #e8eaed;flex-wrap:wrap">

        {{-- Display koordinat --}}
        <div id="{{ $prefix }}_coords_display"
             style="font-size:13px;color:#5f6368;display:flex;align-items:center;gap:5px">
          @if(old($prefix . '_lat'))
          <svg width="13" height="13" viewBox="0 0 24 24" fill="#EA4335">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
          </svg>
          <span style="font-weight:600;color:#202124;font-family:'Courier New',monospace;font-size:12px">
            {{ old($prefix . '_lat') }}, {{ old($prefix . '_lng') }}
          </span>
          @else
          <span style="font-style:italic;color:#9aa0a6;font-size:13px">Klik peta untuk memilih lokasi</span>
          @endif
        </div>

        {{-- Link Google Maps --}}
        <div id="{{ $prefix }}_maps_link"
             style="display:{{ old($prefix . '_lat') ? 'flex' : 'none' }};
                    align-items:center;gap:5px;margin-left:auto">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="#EA4335">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
          </svg>
          <a id="{{ $prefix }}_maps_anchor"
             href="{{ old($prefix.'_lat') ? 'https://maps.google.com/?q='.old($prefix.'_lat').','.old($prefix.'_lng') : '#' }}"
             target="_blank"
             style="color:#1a73e8;font-size:13px;font-weight:500;text-decoration:none"
             onmouseover="this.style.textDecoration='underline'"
             onmouseout="this.style.textDecoration='none'">
            Lihat di Google Maps
          </a>
          <!-- <span style="color:#9aa0a6;font-size:12px">— dikirim ke driver</span> -->
        </div>
      </div>

      {{-- Instruksi --}}
      <div style="padding:7px 14px;background:#e8f0fe;border-top:1px solid #c5d8f6;
                  font-size:12px;color:#1558b0;display:flex;align-items:center;gap:6px">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 16v-4M12 8h.01"/>
        </svg>
        Klik di mana saja pada peta atau geser marker untuk memilih lokasi
      </div>

    </div>
  </div>
</div>