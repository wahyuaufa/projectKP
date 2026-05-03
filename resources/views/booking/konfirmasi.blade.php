{{-- resources/views/booking/konfirmasi.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking - Konfirmasi Pemesanan')
@section('content')
<div class="container py-5" style="min-height:80vh">

  {{-- Steps --}}
  <div class="steps-bar" data-aos="fade-down">
    @foreach(['Pilih Armada','Rute & Jadwal','Detail Pemesan','Konfirmasi'] as $i => $s)
    <div class="step-item {{ $i === 3 ? 'active' : 'done' }}">
      <div class="step-circle">{{ $i < 3 ? '✓' : 4 }}</div>
      <div class="step-label">{{ $s }}</div>
    </div>
    @endforeach
  </div>

  <h2 class="section-title mb-1" data-aos="fade-up">Konfirmasi Pemesanan</h2>
  <p class="text-muted mb-0" data-aos="fade-up">Periksa kembali detail pemesanan Anda sebelum mengkonfirmasi.</p>
  <div class="section-divider" data-aos="fade-up"></div>

  <div class="row g-4 justify-content-center">
    <div class="col-lg-7" data-aos="fade-up">

      {{-- Detail Perjalanan --}}
      <div class="card-gotrav mb-4">
        <div class="card-header-gotrav">
          <i class="bi bi-map me-2"></i>Detail Perjalanan
        </div>
        <div class="card-body-gotrav">

          {{-- Route Visual --}}
          <div class="p-3 rounded-3 mb-3" style="background:var(--bg-section)">
            <div class="d-flex align-items-center gap-3 mb-2">
              <div style="width:12px;height:12px;background:var(--success);border-radius:50%;flex-shrink:0"></div>
              <div>
                <div style="font-size:.75rem;color:var(--muted);font-weight:600">PENJEMPUTAN</div>
                <div style="font-weight:700;color:var(--text-dark)">{{ $detail['titik_penjemputan'] }}</div>
              </div>
            </div>
            <div class="d-flex align-items-center gap-3">
              <div style="width:12px;height:12px;background:var(--danger);border-radius:50%;flex-shrink:0"></div>
              <div>
                <div style="font-size:.75rem;color:var(--muted);font-weight:600">TUJUAN</div>
                <div style="font-weight:700;color:var(--text-dark)">{{ $detail['titik_tujuan'] }}</div>
              </div>
            </div>
          </div>

          <div class="ticket-row">
            <div class="ticket-row-label">Armada</div>
            <div class="ticket-row-value">{{ $armada->nama }}</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Rute</div>
            <div class="ticket-row-value" style="font-size:.88rem">
    @if($arah == 'timur_barat')
        Timur ke Barat
    @elseif($arah == 'barat_timur')
        Barat ke Timur
    @else
        {{ $arah }}
    @endif
</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Tanggal</div>
              <div class="ticket-row-value">{{ \Carbon\Carbon::parse($jadwalAda->tanggal ?? session('booking.tanggal'))->format('d M Y') }}</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Jam Berangkat</div>
            <div class="ticket-row-value">{{ $jamPenjemputan }}</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Kursi Dipilih</div>
            <div class="ticket-row-value">
              @foreach($kursi as $k)
              <span class="px-2 py-1 rounded-2 me-1"
                    style="background:var(--primary);color:#fff;font-size:.82rem;display:inline-block;margin-bottom:2px">
                {{ $k }}
              </span>
              @endforeach
            </div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Jumlah Penumpang</div>
            <div class="ticket-row-value">{{ $jumlahPenumpang }} Orang</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">Jumlah Bagasi</div>
            <div class="ticket-row-value">{{ $detail['jumlah_bagasi'] ?? 0 }} Item</div>
          </div>

          @if(!empty($detail['catatan']))
          <div class="ticket-row">
            <div class="ticket-row-label">Catatan</div>
            <div class="ticket-row-value" style="font-size:.85rem;color:var(--muted)">{{ $detail['catatan'] }}</div>
          </div>
          @endif
        </div>
      </div>

      {{-- Detail Pemesan --}}
      <div class="card-gotrav mb-4">
        <div class="card-header-gotrav">
          <i class="bi bi-person-badge me-2"></i>Informasi Pemesan
        </div>
        <div class="card-body-gotrav">
          <div class="ticket-row">
            <div class="ticket-row-label">Nama</div>
            <div class="ticket-row-value">{{ Auth::user()->nama_lengkap }}</div>
          </div>
          <div class="ticket-row">
            <div class="ticket-row-label">No. WhatsApp</div>
            <div class="ticket-row-value">{{ Auth::user()->no_whatsapp }}</div>
          </div>
        </div>
      </div>

      {{-- Rincian Biaya --}}
      <div class="card-gotrav mb-4">
        <div class="card-header-gotrav">
          <i class="bi bi-wallet2 me-2"></i>Rincian Biaya
        </div>
        <div class="card-body-gotrav">
          <div class="ticket-row">
            <div class="ticket-row-label">
              Harga Tiket ({{ $jumlahPenumpang }} × Rp {{ number_format($hargaPerOrang, 0, ',', '.') }})
            </div>
            <div class="ticket-row-value">
              Rp {{ number_format($hargaPerOrang * $jumlahPenumpang, 0, ',', '.') }}
            </div>
          </div>

          @if($biayaBagasiTambahan > 0)
          <div class="ticket-row">
            <div class="ticket-row-label" style="color:var(--danger)">Biaya Bagasi Tambahan</div>
            <div class="ticket-row-value" style="color:var(--danger)">
              + Rp {{ number_format($biayaBagasiTambahan, 0, ',', '.') }}
            </div>
          </div>
          @endif

          <hr style="border-color:var(--border);margin:1rem 0">

          <div class="ticket-row">
            <div class="ticket-row-label" style="font-size:1rem;font-weight:700;color:var(--text-dark)">
              Total Pembayaran
            </div>
            <div class="ticket-row-value" style="font-size:1.4rem;font-weight:800;color:var(--primary)">
              Rp {{ number_format($totalHarga, 0, ',', '.') }}
            </div>
          </div>

          <div class="mt-3 p-3 rounded-3" style="background:rgba(40,167,69,.08)">
            <div class="d-flex align-items-start gap-2" style="font-size:.85rem;color:#1a7a32">
              <i class="bi bi-cash-coin mt-1"></i>
              <div>
                <strong>Pembayaran Tunai</strong><br>
                Pembayaran dilakukan secara langsung kepada driver saat tiba di tujuan.
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Syarat & Ketentuan --}}
      <div class="p-3 rounded-3 mb-4" style="background:var(--bg-section);font-size:.83rem;color:var(--muted);line-height:1.7"
           data-aos="fade-up">
        <i class="bi bi-shield-check me-1" style="color:var(--primary)"></i>
        Dengan mengkonfirmasi pemesanan, Anda menyetujui syarat dan ketentuan layanan PR GOTRAV Mitra Abadi.
        Kursi tidak dapat diubah setelah konfirmasi dilakukan.
      </div>

      {{-- Action Buttons --}}
      <div class="d-flex gap-3" data-aos="fade-up">
        <a href="{{ route('booking.detail') }}" class="btn-outline-gotrav btn btn-lg-custom flex-fill">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>

        <form method="POST" action="{{ route('booking.proses') }}" class="flex-fill" id="konfirmasiForm">
          @csrf
          <button type="button" class="btn-accent btn btn-lg-custom w-100" id="btnKonfirmasi">
            <i class="bi bi-check-circle me-1"></i> Konfirmasi Pemesanan
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

{{-- Confirm Modal --}}
<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius-lg);border:none;overflow:hidden">
      <div class="modal-body p-0">
        <div style="background:var(--primary);padding:2rem;text-align:center;color:#fff">
          <i class="bi bi-calendar-check fs-1 d-block mb-2"></i>
          <h5 style="font-family:var(--font-heading);font-weight:800;margin:0">Konfirmasi Pemesanan</h5>
        </div>
        <div class="p-4 text-center">
          <p style="color:var(--text-body);font-size:.95rem;margin-bottom:1.5rem">
            Apakah Anda yakin ingin mengkonfirmasi pemesanan ini?<br>
            <strong>Pemesanan tidak dapat diubah setelah dikonfirmasi.</strong>
          </p>
          <div class="d-flex gap-3">
            <button type="button" class="btn-outline-gotrav btn flex-fill" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="button" class="btn-accent btn flex-fill" id="btnProsesAkhir">
              <i class="bi bi-check-circle me-1"></i> Ya, Konfirmasi
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));

  document.getElementById('btnKonfirmasi').addEventListener('click', () => modal.show());

  document.getElementById('btnProsesAkhir').addEventListener('click', function() {
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    this.disabled  = true;
    document.getElementById('konfirmasiForm').submit();
  });
</script>
@endpush
@endsection
