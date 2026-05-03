{{-- resources/views/booking/_pin-maps.blade.php --}}
{{-- Parameter: $prefix = 'asal' | 'tujuan' --}}

<input type="hidden" name="{{ $prefix }}_lat" id="{{ $prefix }}_lat">
<input type="hidden" name="{{ $prefix }}_lng" id="{{ $prefix }}_lng">

<label class="form-label-gotrav" style="font-size:.82rem">
  <i class="bi bi-pin-map-fill me-1" style="color:var(--danger)"></i>Pin Lokasi Google Maps
</label>
<div id="{{ $prefix }}_coords_display"
     class="mb-2 px-3 py-2 rounded-2 d-flex align-items-center gap-2"
     style="background:#fff;border:1.5px solid var(--border);font-size:.83rem;min-height:38px">
  <i class="bi bi-geo-alt" style="color:var(--muted)"></i>
  <span style="color:var(--muted);font-style:italic">Belum ada pin dipilih</span>
</div>
<button type="button"
        class="btn-outline-gotrav btn d-flex align-items-center gap-2"
        style="font-size:.83rem;padding:.45rem .9rem"
        onclick="bukaMapModal('{{ $prefix }}')">
  <i class="bi bi-map"></i> Buka Peta & Pilih Lokasi
</button>
