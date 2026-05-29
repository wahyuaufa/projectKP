{{-- resources/views/booking/pilih-armada.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking - Pilih Armada')
@section('content')
<div class="container py-5" style="min-height:80vh">
  {{-- Steps --}}
  <div class="steps-bar" data-aos="fade-down">
    @foreach(['Pilih Armada','Rute & Jadwal','Detail Pemesan','Konfirmasi'] as $i => $s)
    <div class="step-item {{ $i === 0 ? 'active' : '' }}">
      <div class="step-circle">{{ $i === 0 ? $i+1 : ($i < 0 ? '✓' : $i+1) }}</div>
      <div class="step-label">{{ $s }}</div>
    </div>
    @endforeach
  </div>

  <h2 class="section-title mb-1" data-aos="fade-up">Pilih Armada</h2>
  <div class="section-divider" data-aos="fade-up"></div>

  <form method="POST" action="{{ route('booking.simpan-armada') }}">
    @csrf
    <div class="row g-4 mb-4">
      @foreach($armadas as $idx => $armada)
      <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $idx * 100 }}">
        <label class="armada-card d-block" style="cursor:pointer;">
          <input type="radio" name="armada_id" value="{{ $armada->id }}" class="d-none armada-radio" {{ $loop->first ? 'checked' : '' }}>
          <div class="armada-card-image">
            <div class="armada-card-image">
  <img src="{{ $armada->foto_url }}" alt="{{ $armada->nama }}">
</div>
          </div>
          <div class="armada-card-body">
            <div class="armada-card-title">{{ $armada->nama }}</div>
            <div style="font-size:.82rem;color:var(--muted);">{{ $armada->tipe }}</div>
            <div class="armada-meta mt-2">
              <div class="armada-meta-item"><i class="bi bi-people"></i> {{ $armada->jumlah_kursi }} Kursi</div>
              <div class="armada-meta-item"><i class="bi bi-person"></i> {{ $armada->jumlah_supir }} Supir</div>
              @if($armada->has_ac)<div class="armada-meta-item"><i class="bi bi-thermometer-snow"></i> AC</div>@endif
            </div>
          </div>
        </label>
      </div>
      @endforeach
    </div>

    <div class="bagasi-info-box mb-4" data-aos="fade-up">
      <i class="bi bi-info-circle"></i>
      <div class="bagasi-info-text">
        <strong>Informasi Bagasi:</strong><br>
        Gratis 1 tas dan 1 kardus per penumpang. Jika melebihi, akan dikenakan biaya tambahan.<br>
        Biaya tambahan: Rp 50.000 / item (tas/kardus)
      </div>
    </div>

    <div class="d-flex justify-content-end" data-aos="fade-up">
      <button type="submit" class="btn-gotrav btn btn-lg-custom">
        Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
      </button>
    </div>
  </form>
</div>

@push('scripts')
<script>
  document.querySelectorAll('.armada-radio').forEach(r => {
    r.addEventListener('change', () => {
      document.querySelectorAll('.armada-card').forEach(c => c.classList.remove('selected'));
      r.closest('.armada-card').classList.add('selected');
    });
    if (r.checked) r.closest('.armada-card').classList.add('selected');
  });
</script>
@endpush
@endsection
