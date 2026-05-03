@extends('layouts.app')
@section('title', 'Booking - Pilih Kursi')
@section('content')
<div class="container py-5" style="min-height:80vh">

  {{-- Steps --}}
  <div class="steps-bar" data-aos="fade-down">
    @foreach(['Pilih Armada','Rute & Jadwal','Detail Pemesan','Konfirmasi'] as $i => $s)
    <div class="step-item {{ $i === 1 ? 'active' : ($i < 1 ? 'done' : '') }}">
      <div class="step-circle">{{ $i < 1 ? '✓' : $i+1 }}</div>
      <div class="step-label">{{ $s }}</div>
    </div>
    @endforeach
  </div>

  <h2 class="section-title mb-1" data-aos="fade-up">Pilih Kursi - {{ $armada->nama }}</h2>
  <div class="section-divider" data-aos="fade-up"></div>

  <div class="row g-4">
    <div class="col-lg-7" data-aos="fade-right">
      <div class="card-gotrav">
        <div class="card-header-gotrav">
          <i class="bi bi-grid me-2"></i>Denah Kursi
        </div>
        <div class="card-body-gotrav">
          <div class="d-flex justify-content-center">
            <div class="seat-grid" id="seatGrid" style="grid-template-columns: repeat(4, 1fr);">
              @for($i = 1; $i <= $armada->jumlah_kursi; $i++)
              @php $taken = in_array($i, $kursiTerpesan); @endphp
              <button type="button"
                class="seat-btn {{ $taken ? 'taken' : '' }}"
                data-seat="{{ $i }}"
                {{ $taken ? 'disabled' : '' }}>
                {{ $i }}
              </button>
              @endfor
            </div>
          </div>

          <div class="seat-legend mt-4 justify-content-center">
            <div class="seat-legend-item"><div class="legend-dot available"></div> Tersedia</div>
            <div class="seat-legend-item"><div class="legend-dot selected"></div> Dipilih</div>
            <div class="seat-legend-item"><div class="legend-dot taken"></div> Tidak Tersedia</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5" data-aos="fade-left">
      <div class="card-gotrav sticky-top" style="top:90px">
        <div class="card-header-gotrav">Kursi yang Dipilih</div>
        <div class="card-body-gotrav">
          <div id="selectedSeatsDisplay" class="d-flex flex-wrap gap-2 mb-3" style="min-height:40px">
            <span class="text-muted" style="font-size:.88rem" id="emptyMsg">Belum ada kursi dipilih</span>
          </div>

          <div class="ticket-row">
            <div class="ticket-row-label">Total Penumpang</div>
            <div class="ticket-row-value fw-bold" id="totalPenumpang">0 Orang</div>
          </div>

          <div class="alert-gotrav alert-gotrav-danger mt-3 d-none" id="seatWarning">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Pilih minimal 1 kursi.
          </div>

          <div class="mt-3 p-3 rounded-3" style="background:var(--bg-section);font-size:.82rem;color:var(--muted)">
            <i class="bi bi-info-circle me-1" style="color:var(--primary)"></i>
            Pastikan pilih kursi sesuai kebutuhan Anda. Kursi tidak dapat diubah setelah konfirmasi.
          </div>

          <form method="POST" action="{{ route('booking.simpan-kursi') }}" id="kursiForm" class="mt-4">
            @csrf
            <div id="hiddenKursi"></div>
            <div class="d-flex gap-2">
              <a href="{{ route('booking.rute-jadwal') }}" class="btn-outline-gotrav btn flex-fill">
                <i class="bi bi-arrow-left"></i> Kembali
              </a>
              <button type="submit" class="btn-gotrav btn flex-fill">
                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const selected = new Set();

  document.querySelectorAll('.seat-btn:not(.taken)').forEach(btn => {
    btn.addEventListener('click', () => {
      const seat = parseInt(btn.dataset.seat);
      if (selected.has(seat)) {
        selected.delete(seat);
        btn.classList.remove('selected');
      } else {
        selected.add(seat);
        btn.classList.add('selected');
      }
      updateDisplay();
    });
  });

  function updateDisplay() {
  const arr = [...selected].sort((a, b) => a - b);
  const display = document.getElementById('selectedSeatsDisplay');
  const hidden = document.getElementById('hiddenKursi');
  const total = document.getElementById('totalPenumpang');

  // Bersihkan tampilan dan input tersembunyi
  display.innerHTML = '';
  hidden.innerHTML = '';

  if (arr.length === 0) {
    // Jika kosong, tambahkan kembali pesan kosongnya secara dinamis
    display.innerHTML = `<span class="text-muted" style="font-size:.88rem" id="emptyMsg">Belum ada kursi dipilih</span>`;
  } else {
    // Jika ada kursi, buat elemen span untuk masing-masing kursi
    arr.forEach(s => {
      // Update Tampilan Visual
      const span = document.createElement('span');
      span.className = "px-3 py-1 rounded-2 fw-bold";
      span.style.background = "var(--primary)";
      span.style.color = "#fff";
      span.style.fontSize = ".9rem";
      span.textContent = s;
      display.appendChild(span);

      // Update Input Hidden untuk Form
      const input = document.createElement('input');
      input.type = "hidden";
      input.name = "kursi[]";
      input.value = s;
      hidden.appendChild(input);
    });
  }

  total.textContent = arr.length + ' Orang';
}

  document.getElementById('kursiForm').addEventListener('submit', function(e) {
    if (selected.size === 0) {
      e.preventDefault();
      document.getElementById('seatWarning').classList.remove('d-none');
    }
  });
</script>
@endpush
@endsection
