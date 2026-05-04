{{-- resources/views/booking/pilih-kursi.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking - Pilih Kursi')

@push('styles')
<style>
.bus-wrap {
  background: #f8f9fa;
  border: 2.5px solid var(--border);
  border-radius: 24px;
  padding: 1.6rem 1.4rem;
  display: inline-block;
}
.bus-title {
  font-size: .7rem;
  font-weight: 700;
  color: var(--muted);
  letter-spacing: .08em;
  text-transform: uppercase;
  text-align: center;
  margin-bottom: 1rem;
}
.seat-btn {
  width: 50px;
  height: 50px;
  border: 2px solid #c8d0d8;
  border-radius: 10px 10px 5px 5px;
  background: #fff;
  font-size: .88rem;
  font-weight: 700;
  color: var(--text-dark);
  cursor: pointer;
  transition: all .15s;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 3px 0 #adb5bd;
}
.seat-btn:hover:not(:disabled) {
  border-color: var(--primary);
  background: rgba(26,58,92,.06);
  transform: translateY(-2px);
  box-shadow: 0 5px 0 rgba(26,58,92,.2);
}
.seat-btn.selected {
  background: var(--primary);
  border-color: var(--primary);
  color: #fff;
  box-shadow: 0 3px 0 rgba(26,58,92,.45);
}
.seat-btn.taken {
  background: #ebebeb;
  border-color: #d5d5d5;
  color: #bbb;
  cursor: not-allowed;
  box-shadow: 0 3px 0 #c8c8c8;
}
.seat-driver {
  width: 50px;
  height: 50px;
  border: 2px dashed #c8d0d8;
  border-radius: 10px 10px 5px 5px;
  background: #f0f2f4;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 2px;
  font-size: .62rem;
  font-weight: 700;
  color: #aaa;
  box-shadow: 0 3px 0 #d0d5da;
}
.seat-empty { width: 50px; height: 50px; }
.aisle-gap  { width: 24px; }

/* Ikon setir sederhana */
.wheel {
  width: 20px; height: 20px;
  border: 2.5px solid #bbb;
  border-radius: 50%;
  position: relative;
  flex-shrink: 0;
}
.wheel::before {
  content: '';
  position: absolute;
  top: 50%; left: 0; right: 0;
  height: 2px;
  background: #bbb;
  transform: translateY(-50%);
}
.wheel::after {
  content: '';
  position: absolute;
  top: 0; bottom: 0; left: 50%;
  width: 2px;
  background: #bbb;
  transform: translateX(-50%);
}

.front-indicator {
  text-align: center;
  margin-bottom: .5rem;
  font-size: .75rem;
  color: var(--primary);
  font-weight: 700;
  letter-spacing: .05em;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .4rem;
}
</style>
@endpush

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

  <h2 class="section-title mb-1" data-aos="fade-up">Pilih Kursi</h2>
  <p class="text-muted mb-1" data-aos="fade-up">
    {{ $armada->nama }} · {{ $armada->jumlah_kursi }} Kursi
  </p>
  <div class="section-divider" data-aos="fade-up"></div>

  <div class="row g-4">
    <div class="col-lg-7" data-aos="fade-right">
      <div class="card-gotrav">
        <div class="card-header-gotrav">
          <i class="bi bi-grid me-2"></i>Denah Kursi
        </div>
        <div class="card-body-gotrav">
          <div class="d-flex flex-column align-items-center">

            {{-- Indikator depan --}}
            

            @php $taken = $kursiTerpesan; @endphp

            {{-- ══════════════════════════════════════════════ --}}
            {{-- INNOVA — 6 kursi                               --}}
            {{-- Layout:                                         --}}
            {{-- Baris 1: [D] [1]                               --}}
            {{-- Baris 2: [2] [3] [4]                           --}}
            {{-- Baris 3: [5] [6]                               --}}
            {{-- ══════════════════════════════════════════════ --}}
            @if($armada->jumlah_kursi == 6)
            <div class="bus-wrap">
              <div class="bus-title">Toyota Innova</div>

              {{-- Baris 1: Driver + Kursi 1 --}}
              <div style="display:flex;gap:10px;justify-content:flex-start;margin-bottom:10px">
  <button type="button"
          class="seat-btn {{ in_array(1,$taken)?'taken':'' }}"
          data-seat="1"
          {{ in_array(1,$taken)?'disabled':'' }}>
    1
  </button>

  <div class="seat-driver">
    <div class="wheel"></div>
    <span>D</span>
  </div>
</div>

              {{-- Baris 2: Kursi 2, 3, 4 --}}
              <div style="display:flex;gap:10px;justify-content:flex-start;margin-bottom:10px">
                @foreach([2,3,4] as $s)
                <button type="button"
                        class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}"
                        {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                @endforeach
              </div>

              {{-- Baris 3: Kursi 5, 6 --}}
              <div style="display:flex;gap:10px;justify-content:flex-start">
                @foreach([5,6] as $s)
                <button type="button"
                        class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}"
                        {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                @endforeach
              </div>

            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- HIACE — 14 kursi                               --}}
            {{-- Dari foto (tampak atas, depan = kiri foto):    --}}
            {{-- Kolom kiri: [1] lalu kosong di baris 1,3,4     --}}
            {{-- Kolom tengah-kiri: [2][3][4] (baris 1,2,3)    --}}
            {{-- LORONG                                          --}}
            {{-- Kolom tengah-kanan: [5][6][7]                  --}}
            {{-- Kolom kanan-tengah: [8][9][10]                  --}}
            {{-- Kolom paling kanan: [11][12][13][14]            --}}
            {{-- ══════════════════════════════════════════════ --}}
            @elseif($armada->jumlah_kursi == 14)
            <div class="bus-wrap">
              <div class="bus-title">Toyota Hiace</div>

              {{--
                Baca ulang dari foto hiace:
                Baris terbawah foto (= DEPAN kendaraan karena foto tampak kiri = depan):
                Paling kiri kendaraan (samping driver): kursi 1
                Lalu ke dalam (kanan): 2, 3, 4 (3 baris ke belakang di kolom ini)
                Lorong di tengah
                Lalu: 5, 6, 7 (kolom berikutnya)
                Lalu: 8, 9, 10
                Paling kanan: 11, 12, 13, 14

                Representasi grid (baris = dari depan ke belakang):
                       [kol A] [kol B] [lorong] [kol C] [kol D] [kol E]
                Baris1: [ D ]   [ 2 ]            [ 5 ]   [ 8 ]   [11]
                Baris2: [ 1 ]   [ 3 ]            [ 6 ]   [ 9 ]   [12]
                Baris3: [   ]   [ 4 ]            [ 7 ]   [10]    [13]
                Baris4: [   ]   [   ]            [   ]   [   ]   [14]
              --}}

              {{-- Baris 1: D, 2 | 5, 8, 11 --}}
              <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
                <div class="seat-driver"><div class="wheel"></div><span>D</span></div>
                @php $s=2; @endphp
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                <div class="aisle-gap"></div>
                @foreach([5,8,11] as $s)
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                @endforeach
              </div>

              {{-- Baris 2: 3 | 6, 9, 12 --}}
              <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
                <div class="seat-empty"></div>
                @php $s=3; @endphp
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                <div class="aisle-gap"></div>
                @foreach([6,9,12] as $s)
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                @endforeach
              </div>

              {{-- Baris 3: 4 | 13 --}}
              <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
                <div class="seat-empty"></div>
                @php $s=4; @endphp
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                <div class="aisle-gap"></div>
                <div class="seat-empty"></div>
                <div class="seat-empty"></div>
                @php $s=13; @endphp
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
              </div>

              {{-- Baris 4: 1 | 7, 10, 14 --}}
              <div style="display:flex;gap:8px;align-items:center">
                @php $s=1; @endphp
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                <div class="seat-empty"></div>
                <div class="aisle-gap"></div>
                @foreach([7,10,14] as $s)
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                @endforeach
              </div>

            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- FALLBACK — grid biasa                          --}}
            {{-- ══════════════════════════════════════════════ --}}
            @else
            <div class="bus-wrap">
              <div class="bus-title">{{ $armada->nama }}</div>
              <div style="display:grid;grid-template-columns:repeat(4,50px);gap:8px">
                @for($i=1; $i<=$armada->jumlah_kursi; $i++)
                @php $s=$i; @endphp
                <button type="button" class="seat-btn {{ in_array($s,$taken)?'taken':'' }}"
                        data-seat="{{ $s }}" {{ in_array($s,$taken)?'disabled':'' }}>{{ $s }}</button>
                @endfor
              </div>
            </div>
            @endif

            {{-- Legend --}}
            <div style="display:flex;gap:1.5rem;margin-top:1.5rem;flex-wrap:wrap;justify-content:center">
              <div style="display:flex;align-items:center;gap:.5rem;font-size:.82rem">
                <div style="width:22px;height:22px;border-radius:6px;border:2px solid #c8d0d8;
                            background:#fff;box-shadow:0 2px 0 #adb5bd"></div>
                Tersedia
              </div>
              <div style="display:flex;align-items:center;gap:.5rem;font-size:.82rem">
                <div style="width:22px;height:22px;border-radius:6px;border:2px solid var(--primary);
                            background:var(--primary);box-shadow:0 2px 0 rgba(26,58,92,.4)"></div>
                Dipilih
              </div>
              <div style="display:flex;align-items:center;gap:.5rem;font-size:.82rem">
                <div style="width:22px;height:22px;border-radius:6px;border:2px solid #d5d5d5;
                            background:#ebebeb;box-shadow:0 2px 0 #c8c8c8"></div>
                Tidak Tersedia
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-5" data-aos="fade-left">
      <div class="card-gotrav sticky-top" style="top:90px">
        <div class="card-header-gotrav">
          <i class="bi bi-ticket-perforated me-2"></i>Kursi Dipilih
        </div>
        <div class="card-body-gotrav">

          <div id="selectedSeatsDisplay"
               style="display:flex;flex-wrap:wrap;gap:.5rem;min-height:44px;margin-bottom:1rem">
            <span style="color:var(--muted);font-size:.88rem" id="emptyMsg">
              Belum ada kursi dipilih
            </span>
          </div>

          <div class="ticket-row">
            <div class="ticket-row-label">Total Penumpang</div>
            <div class="ticket-row-value fw-bold" id="totalPenumpang">0 Orang</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Armada</div>
            <div class="ticket-row-value">{{ $armada->nama }}</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Kapasitas</div>
            <div class="ticket-row-value">{{ $armada->jumlah_kursi }} Kursi</div>
          </div>

          <div class="alert-gotrav alert-gotrav-danger mt-3 d-none" id="seatWarning">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Pilih minimal 1 kursi sebelum melanjutkan.
          </div>

          <div class="mt-3 p-3 rounded-3"
               style="background:var(--bg-section);font-size:.82rem;color:var(--muted)">
            <i class="bi bi-info-circle me-1" style="color:var(--primary)"></i>
            Klik kursi untuk memilih. Kursi tidak dapat diubah setelah konfirmasi.
          </div>

          <form method="POST" action="{{ route('booking.simpan-kursi') }}"
                id="kursiForm" class="mt-4">
            @csrf
            <div id="hiddenKursi"></div>
            <div class="d-flex gap-2">
              <a href="{{ route('booking.rute-jadwal') }}"
                 class="btn-outline-gotrav btn flex-fill">
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
  const arr     = [...selected].sort((a,b) => a-b);
  const display = document.getElementById('selectedSeatsDisplay');
  const hidden  = document.getElementById('hiddenKursi');
  const total   = document.getElementById('totalPenumpang');

  hidden.innerHTML = '';

  if (arr.length === 0) {
    display.innerHTML = `
      <span style="color:var(--muted);font-size:.88rem" id="emptyMsg">
        Belum ada kursi dipilih
      </span>
    `;
  } else {
    let html = '';

    arr.forEach(s => {
      html += `
        <span style="
          background:var(--primary);
          color:#fff;
          padding:.3rem .8rem;
          border-radius:8px;
          font-weight:700;
          font-size:.9rem">
          Kursi ${s}
        </span>
      `;

      hidden.innerHTML += `
        <input type="hidden" name="kursi[]" value="${s}">
      `;
    });

    display.innerHTML = html;
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