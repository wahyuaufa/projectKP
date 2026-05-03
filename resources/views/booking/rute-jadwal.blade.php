{{-- resources/views/booking/rute-jadwal.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking - Pilih Rute & Jadwal')

@push('styles')
<style>
.arah-card {
  border: 2.5px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
  cursor: pointer;
  transition: all .2s;
  background: #fff;
  position: relative;
  overflow: hidden;
  user-select: none;
}
.arah-card:hover {
  border-color: var(--primary);
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(26,58,92,.12);
}
.arah-card.selected {
  border-color: var(--primary);
  background: rgba(26,58,92,.03);
  box-shadow: 0 4px 20px rgba(26,58,92,.15);
}
.arah-card.selected::after {
  content: '✓';
  position: absolute;
  top: .8rem; right: .8rem;
  width: 26px; height: 26px;
  background: var(--primary);
  color: #fff;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: .85rem; font-weight: 700;
}
.arah-icon {
  font-size: 2.5rem;
  margin-bottom: .8rem;
  display: block;
}
.arah-title {
  font-family: var(--font-heading);
  font-weight: 800;
  font-size: 1.1rem;
  color: var(--text-dark);
  margin-bottom: .3rem;
}
.arah-subtitle {
  font-size: .82rem;
  color: var(--muted);
  line-height: 1.5;
}
.arah-arrow {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
  font-size: .9rem;
  font-weight: 700;
  margin: .6rem 0;
  color: var(--primary);
}
.jadwal-status-box {
  border-radius: var(--radius-sm);
  padding: .7rem 1rem;
  font-size: .82rem;
  display: flex;
  align-items: center;
  gap: .5rem;
  margin-top: .8rem;
}
.jadwal-status-box.ada {
  background: rgba(30,126,52,.08);
  border: 1px solid rgba(30,126,52,.2);
  color: #1e7e34;
}
.jadwal-status-box.belum {
  background: rgba(26,58,92,.06);
  border: 1px solid rgba(26,58,92,.15);
  color: var(--primary);
}
.jadwal-status-box.tidak-ada {
  background: rgba(220,53,69,.07);
  border: 1px solid rgba(220,53,69,.2);
  color: var(--danger);
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

  <h2 class="section-title mb-1" data-aos="fade-up">Pilih Rute & Jadwal</h2>
  <p class="text-muted mb-1" data-aos="fade-up">
    Armada: <strong style="color:var(--primary)">{{ $armada->nama }}</strong>
    ({{ $armada->jumlah_kursi }} Kursi) ·
    <strong style="color:var(--primary)">Rp {{ number_format($armada->harga_per_rute, 0, ',', '.') }}</strong>/orang
  </p>
  <div class="section-divider" data-aos="fade-up"></div>

  @if($errors->any())
  <div class="alert-gotrav alert-gotrav-danger p-3 rounded-2 mb-4 d-flex align-items-center gap-2" data-aos="fade-up">
    <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
  </div>
  @endif

  <form method="POST" action="{{ route('booking.simpan-rute-jadwal') }}" id="mainForm">
    @csrf
    <input type="hidden" name="arah" id="arahInput" value="{{ old('arah') }}">

    <div class="row g-4">

      {{-- ── KIRI: Pilih Arah + Tanggal + Jam ─────────────── --}}
      <div class="col-lg-7">

        {{-- PILIH ARAH --}}
        <div class="card-gotrav mb-4" data-aos="fade-up">
          <div class="card-header-gotrav">
            <i class="bi bi-arrow-left-right me-2"></i>Pilih Arah Perjalanan
          </div>
          <div class="card-body-gotrav">
            <div class="row g-3">

              {{-- Barat → Timur --}}
              <div class="col-sm-6">
                <div class="arah-card {{ old('arah') === 'barat_timur' ? 'selected' : '' }}"
                     id="cardBaratTimur"
                     onclick="pilihArah('barat_timur')">
                  <span class="arah-icon">🌅</span>
                  <div class="arah-title">Barat → Timur</div>
                  <div class="arah-arrow">
                    <i class="bi bi-arrow-right fs-4"></i>
                  </div>
                  <div class="arah-subtitle">
                    Berangkat dari wilayah Barat menuju wilayah Timur
                  </div>
                  <div id="statusBaratTimur" class="jadwal-status-box belum">
                    <i class="bi bi-clock"></i>
                    <span>Pilih tanggal untuk cek jadwal</span>
                  </div>
                </div>
              </div>

              {{-- Timur → Barat --}}
              <div class="col-sm-6">
                <div class="arah-card {{ old('arah') === 'timur_barat' ? 'selected' : '' }}"
                     id="cardTimurBarat"
                     onclick="pilihArah('timur_barat')">
                  <span class="arah-icon">🌇</span>
                  <div class="arah-title">Timur → Barat</div>
                  <div class="arah-arrow">
                    <i class="bi bi-arrow-right fs-4"></i>
                  </div>
                  <div class="arah-subtitle">
                    Berangkat dari wilayah Timur menuju wilayah Barat
                  </div>
                  <div id="statusTimurBarat" class="jadwal-status-box belum">
                    <i class="bi bi-clock"></i>
                    <span>Pilih tanggal untuk cek jadwal</span>
                  </div>
                </div>
              </div>

            </div>

            <div id="arahError" class="mt-2" style="display:none;font-size:.82rem;color:var(--danger)">
              <i class="bi bi-exclamation-circle me-1"></i>Pilih salah satu arah perjalanan
            </div>
          </div>
        </div>

        {{-- TANGGAL & JAM --}}
        <div class="card-gotrav mb-4" data-aos="fade-up" data-aos-delay="80">
          <div class="card-header-gotrav">
            <i class="bi bi-calendar3 me-2"></i>Tanggal & Jam Penjemputan
          </div>
          <div class="card-body-gotrav">
            <div class="row g-3">

              {{-- Tanggal --}}
              <div class="col-sm-6">
                <label class="form-label-gotrav">
                  Tanggal <span class="text-danger">*</span>
                </label>
                <div class="input-icon-wrap">
                  <i class="bi bi-calendar3 input-icon"></i>
                  <input type="date" name="tanggal" id="tanggalInput"
                         class="form-control-gotrav"
                         min="{{ date('Y-m-d') }}"
                         value="{{ old('tanggal', date('Y-m-d')) }}"
                         required>
                </div>
              </div>

              {{-- Jam Penjemputan — hanya 17:00–22:00 --}}
              <div class="col-sm-6">
                <label class="form-label-gotrav">
                  Jam Penjemputan <span class="text-danger">*</span>
                </label>
                <div class="input-icon-wrap">
                  <i class="bi bi-clock input-icon"></i>
                  <input type="time" name="jam_penjemputan" id="jamInput"
                         class="form-control-gotrav"
                         min="17:00"
                         max="22:00"
                         value="{{ old('jam_penjemputan', '17:00') }}"
                         required>
                </div>
                <div style="font-size:.75rem;color:var(--muted);margin-top:.3rem">
                  <i class="bi bi-clock me-1"></i>
                  Tersedia pukul <strong>17.00 – 22.00 WIB</strong>
                </div>
              </div>

            </div>

            {{-- Info jam di luar rentang --}}
            <div id="jamError" class="mt-2" style="display:none">
              <div style="background:rgba(220,53,69,.07);border:1px solid rgba(220,53,69,.2);border-radius:8px;padding:.7rem 1rem;font-size:.82rem;color:var(--danger);display:flex;gap:.5rem;align-items:center">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Jam penjemputan harus antara 17.00 sampai 22.00 WIB</span>
              </div>
            </div>

            {{-- Info sistem jadwal Innova --}}
            @if(! $armada->isAdminOnly())
            <div class="bagasi-info-box mt-3" style="margin:0">
              <i class="bi bi-info-circle" style="color:var(--primary)"></i>
              <div style="font-size:.82rem;color:var(--text-body)">
                <strong>Jadwal Otomatis:</strong> Jika belum ada jadwal untuk arah yang dipilih
                pada hari itu, sistem akan membuatkan jadwal baru secara otomatis.
                Penumpang lain dengan arah yang sama akan bergabung dalam satu jadwal.
              </div>
            </div>
            @endif
          </div>
        </div>

        {{-- TOMBOL --}}
        <div class="d-flex gap-3" data-aos="fade-up">
          <a href="{{ route('booking.armada') }}" class="btn-outline-gotrav btn btn-lg-custom flex-fill">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
          <button type="button" class="btn-gotrav btn btn-lg-custom flex-fill" id="btnLanjut"
                  onclick="submitForm()">
            Pilih Kursi <i class="bi bi-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      {{-- ── KANAN: Info Armada + Ringkasan ──────────────── --}}
      <div class="col-lg-5" data-aos="fade-left">
        <div class="card-gotrav sticky-top" style="top:90px">
          <div class="card-header-gotrav">
            <i class="bi bi-car-front me-2"></i>Armada Dipilih
          </div>
          <div class="card-body-gotrav">

            <div class="ticket-row">
              <div class="ticket-row-label">Nama</div>
              <div class="ticket-row-value">{{ $armada->nama }}</div>
            </div>
            <div class="ticket-row">
              <div class="ticket-row-label">Kapasitas</div>
              <div class="ticket-row-value">{{ $armada->jumlah_kursi }} Kursi</div>
            </div>
            <div class="ticket-row">
              <div class="ticket-row-label">AC</div>
              <div class="ticket-row-value">{{ $armada->has_ac ? '✅ Ya' : '❌ Tidak' }}</div>
            </div>
            <div class="ticket-row">
              <div class="ticket-row-label">Harga/Orang</div>
              <div class="ticket-row-value" style="color:var(--primary);font-weight:800">
                Rp {{ number_format($armada->harga_per_rute, 0, ',', '.') }}
              </div>
            </div>

            <hr style="border-color:var(--border)">

            {{-- Ringkasan pilihan --}}
            <div style="padding:.8rem;background:var(--bg-section);border-radius:var(--radius-sm)">
              <div style="font-size:.8rem;font-weight:700;color:var(--text-dark);margin-bottom:.6rem">
                <i class="bi bi-clipboard-check me-1"></i>Pilihan Anda
              </div>
              <div class="ticket-row" style="padding:.2rem 0">
                <div class="ticket-row-label" style="font-size:.78rem">Arah</div>
                <div class="ticket-row-value" id="ringkasArah" style="font-size:.82rem;color:var(--muted)">
                  Belum dipilih
                </div>
              </div>
              <div class="ticket-row" style="padding:.2rem 0">
                <div class="ticket-row-label" style="font-size:.78rem">Tanggal</div>
                <div class="ticket-row-value" id="ringkasDate" style="font-size:.82rem;color:var(--muted)">
                  —
                </div>
              </div>
              <div class="ticket-row" style="padding:.2rem 0">
                <div class="ticket-row-label" style="font-size:.78rem">Jam Jemput</div>
                <div class="ticket-row-value" id="ringkasJam" style="font-size:.82rem;color:var(--muted)">
                  —
                </div>
              </div>
            </div>

            <hr style="border-color:var(--border)">

            {{-- Sistem jadwal --}}
            @if($armada->isAdminOnly())
            <div style="padding:.8rem;background:rgba(220,53,69,.06);border-radius:var(--radius-sm);font-size:.78rem">
              <div style="font-weight:700;color:var(--danger);margin-bottom:.3rem">
                <i class="bi bi-lock me-1"></i>Jadwal oleh Admin
              </div>
              <div style="color:var(--muted);line-height:1.6">
                Jadwal {{ $armada->nama }} hanya tersedia pada tanggal yang sudah ditetapkan admin.
              </div>
              <a href="https://wa.me/6281200000000" target="_blank"
                 class="btn-whatsapp btn btn-sm mt-2 w-100" style="font-size:.78rem;padding:.35rem .6rem">
                <i class="bi bi-whatsapp"></i> Tanya Ketersediaan
              </a>
            </div>
            @else
            <div style="padding:.8rem;background:rgba(26,58,92,.05);border-radius:var(--radius-sm);font-size:.78rem">
              <div style="font-weight:700;color:var(--primary);margin-bottom:.3rem">
                <i class="bi bi-calendar-check me-1"></i>Jadwal Otomatis
              </div>
              <div style="color:var(--muted);line-height:1.7">
                ✅ Tersedia setiap hari<br>
                ✅ Maks. 1 jadwal/hari per arah<br>
                ✅ Jam penjemputan <strong>17.00 – 22.00 WIB</strong><br>
                ✅ Bergabung dengan penumpang lain se-arah
              </div>
            </div>
            @endif

            <div class="bagasi-info-box mt-3">
              <i class="bi bi-bag-check"></i>
              <div class="bagasi-info-text">
                Gratis <strong>{{ $armada->bagasi_gratis }} tas</strong> &
                <strong>{{ $armada->kardus_gratis }} kardus</strong>/penumpang.
                Tambahan: Rp {{ number_format($armada->biaya_bagasi_tambahan, 0, ',', '.') }}/item.
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
const isAdminOnly = {{ $armada->isAdminOnly() ? 'true' : 'false' }};
const armadaId    = {{ $armada->id }};
let arahDipilih   = '{{ old('arah', '') }}';

// ── Pilih Arah ────────────────────────────────────────────
function pilihArah(arah) {
  arahDipilih = arah;
  document.getElementById('arahInput').value = arah;
  document.getElementById('cardBaratTimur').classList.toggle('selected', arah === 'barat_timur');
  document.getElementById('cardTimurBarat').classList.toggle('selected', arah === 'timur_barat');
  document.getElementById('arahError').style.display = 'none';

  document.getElementById('ringkasArah').textContent =
    arah === 'barat_timur' ? '🌅 Barat → Timur' : '🌇 Timur → Barat';
  document.getElementById('ringkasArah').style.color = 'var(--primary)';

  const tanggal = document.getElementById('tanggalInput').value;
  if (tanggal) cekJadwal(arah, tanggal);
}

// ── Update Ringkasan Tanggal ──────────────────────────────
document.getElementById('tanggalInput').addEventListener('change', function () {
  const tanggal = this.value;
  if (!tanggal) return;

  const tgl = new Date(tanggal);
  const opt = { weekday:'long', day:'numeric', month:'long', year:'numeric' };
  document.getElementById('ringkasDate').textContent = tgl.toLocaleDateString('id-ID', opt);
  document.getElementById('ringkasDate').style.color = 'var(--text-dark)';

  cekJadwalSemua(tanggal);
});

// ── Validasi & Update Ringkasan Jam ──────────────────────
document.getElementById('jamInput').addEventListener('change', function () {
  const jam     = this.value;
  const jamErr  = document.getElementById('jamError');

  if (!jam) return;

  // Validasi frontend: 17:00 – 22:00
  const [h, m]  = jam.split(':').map(Number);
  const menit   = h * 60 + m;
  const minMenit = 17 * 60;   // 17:00
  const maxMenit = 22 * 60;   // 22:00

  if (menit < minMenit || menit > maxMenit) {
    jamErr.style.display = '';
    this.value = '';
    document.getElementById('ringkasJam').textContent = '—';
    document.getElementById('ringkasJam').style.color = 'var(--muted)';
    return;
  }

  jamErr.style.display = 'none';
  document.getElementById('ringkasJam').textContent = jam + ' WIB';
  document.getElementById('ringkasJam').style.color = 'var(--text-dark)';
});

// ── Cek Jadwal Real-time ──────────────────────────────────
function cekJadwalSemua(tanggal) {
  ['barat_timur', 'timur_barat'].forEach(arah => cekJadwal(arah, tanggal));
}

async function cekJadwal(arah, tanggal) {
  const statusId = arah === 'barat_timur' ? 'statusBaratTimur' : 'statusTimurBarat';
  const statusEl = document.getElementById(statusId);

  statusEl.className = 'jadwal-status-box belum';
  statusEl.innerHTML = '<span class="spinner-border spinner-border-sm"></span><span>Memeriksa jadwal...</span>';

  try {
    const res  = await fetch(`/api/wilayah/cek-jadwal?armada_id=${armadaId}&tanggal=${tanggal}&arah=${arah}`);
    const data = await res.json();

    if (data.tersedia) {
      statusEl.className = 'jadwal-status-box ada';
      statusEl.innerHTML = `<i class="bi bi-check-circle-fill"></i>
        <span>Jadwal ada · ${data.kursi_tersisa} kursi tersisa</span>`;
    } else if (isAdminOnly) {
      statusEl.className = 'jadwal-status-box tidak-ada';
      statusEl.innerHTML = `<i class="bi bi-x-circle-fill"></i>
        <span>Belum ada jadwal — hubungi admin</span>`;
    } else {
      statusEl.className = 'jadwal-status-box belum';
      statusEl.innerHTML = `<i class="bi bi-plus-circle"></i>
        <span>Belum ada jadwal — akan dibuat otomatis</span>`;
    }
  } catch (e) {
    statusEl.className = 'jadwal-status-box belum';
    statusEl.innerHTML = `<i class="bi bi-clock"></i><span>Pilih tanggal untuk cek jadwal</span>`;
  }
}

// ── Submit Form ───────────────────────────────────────────
function submitForm() {
  // Validasi arah
  if (!arahDipilih) {
    document.getElementById('arahError').style.display = '';
    document.getElementById('cardBaratTimur').scrollIntoView({ behavior:'smooth', block:'center' });
    return;
  }

  // Validasi jam
  const jam = document.getElementById('jamInput').value;
  if (!jam) {
    document.getElementById('jamError').style.display = '';
    document.getElementById('jamInput').focus();
    return;
  }
  const [h, m] = jam.split(':').map(Number);
  const menit  = h * 60 + m;
  if (menit < 17 * 60 || menit > 22 * 60) {
    document.getElementById('jamError').style.display = '';
    document.getElementById('jamInput').focus();
    return;
  }

  const btn = document.getElementById('btnLanjut');
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
  btn.disabled  = true;
  document.getElementById('mainForm').submit();
}

// ── Init ──────────────────────────────────────────────────
const tanggalAwal = document.getElementById('tanggalInput').value;
if (tanggalAwal) {
  cekJadwalSemua(tanggalAwal);
  const tgl = new Date(tanggalAwal);
  document.getElementById('ringkasDate').textContent =
    tgl.toLocaleDateString('id-ID', {weekday:'long',day:'numeric',month:'long',year:'numeric'});
  document.getElementById('ringkasDate').style.color = 'var(--text-dark)';
}

const jamAwal = document.getElementById('jamInput').value;
if (jamAwal) {
  document.getElementById('ringkasJam').textContent = jamAwal + ' WIB';
  document.getElementById('ringkasJam').style.color = 'var(--text-dark)';
}

if (arahDipilih) pilihArah(arahDipilih);
</script>
@endpush