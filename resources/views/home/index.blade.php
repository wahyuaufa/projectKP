{{-- resources/views/home/index.blade.php --}}
@extends('layouts.app')

@section('title', 'GOTRAV Travel')

@section('content')

{{-- ── HERO ────────────────────────────────────────────────── --}}
<section class="hero-section">
  <div class="container hero-content">
    <div class="row align-items-center">
      <div class="col-lg-6" data-aos="fade-right">
        <h1 class="hero-title">
          Travel Door to Door<br>
          <span class="accent-line">Nyaman, Aman,</span><br>
          Terpercaya
        </h1>
        <p class="hero-subtitle">
          PR GOTRAV Mitra Abadi siap menemani perjalanan Anda dengan layanan travel door to door yang nyaman dan terpercaya.
        </p>
        <div class="hero-features">
          <div class="hero-feature-item"><i class="bi bi-door-open"></i> Door to Door Service</div>
          <div class="hero-feature-item"><i class="bi bi-car-front"></i> Armada Nyaman</div>
          <div class="hero-feature-item"><i class="bi bi-person-badge"></i> Driver Profesional</div>
          <div class="hero-feature-item"><i class="bi bi-tag"></i> Harga Terjangkau</div>
        </div>
        <div class="d-flex flex-wrap gap-3">
          <a href="{{ route('booking.armada') }}" class="btn-accent btn btn-lg-custom">
            <i class="bi bi-calendar-check"></i> Booking Sekarang
          </a>
          <a href="#tentang" class="btn-outline-gotrav btn btn-lg-custom" style="border-color:rgba(255,255,255,.5);color:#fff;">
            Pelajari Lebih
          </a>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
        <div class="hero-image-wrap">
          {{-- Placeholder SVG car illustration --}}
          <svg viewBox="0 0 500 280" xmlns="http://www.w3.org/2000/svg" style="max-width:100%;filter:drop-shadow(0 20px 40px rgba(0,0,0,.3))">
            <rect x="30" y="130" width="440" height="100" rx="20" fill="white" opacity=".95"/>
            <rect x="60" y="80" width="300" height="90" rx="16" fill="white" opacity=".9"/>
            <circle cx="120" cy="235" r="30" fill="#1A3A5C"/>
            <circle cx="120" cy="235" r="18" fill="#eee"/>
            <circle cx="380" cy="235" r="30" fill="#1A3A5C"/>
            <circle cx="380" cy="235" r="18" fill="#eee"/>
            <rect x="62" y="82" width="296" height="55" rx="12" fill="#3A6FA0" opacity=".6"/>
            <rect x="80" y="86" width="80" height="45" rx="8" fill="rgba(255,255,255,.5)"/>
            <rect x="170" y="86" width="80" height="45" rx="8" fill="rgba(255,255,255,.5)"/>
            <rect x="260" y="86" width="80" height="45" rx="8" fill="rgba(255,255,255,.5)"/>
            <rect x="30" y="175" width="30" height="20" rx="5" fill="#F4A020" opacity=".8"/>
            <rect x="440" y="175" width="30" height="20" rx="5" fill="#F4A020" opacity=".8"/>
            <text x="250" y="165" text-anchor="middle" fill="white" font-size="12" font-weight="bold" opacity=".9">GOTRAV</text>
          </svg>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── STATS ───────────────────────────────────────────────── --}}
<section class="stats-section">
  <div class="container">
    <div class="row g-4 justify-content-center">
      @php $stats = [
        ['10+',   'Tahun Pengalaman',  'bi-clock-history'],
        ['50+',   'Armada Tersedia',   'bi-truck'],
        ['1000+', 'Pelanggan Puas',    'bi-people'],
        ['24/7',  'Layanan Customer',  'bi-headset'],
      ]; @endphp
      @foreach($stats as $i => $s)
      <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
        <div class="stat-card">
          <i class="bi {{ $s[2] }} fs-2 mb-2" style="color:var(--accent)"></i>
          <div class="stat-number">{{ $s[0] }}</div>
          <div class="stat-label">{{ $s[1] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── TENTANG ─────────────────────────────────────────────── --}}
<section id="tentang" class="py-5">
  <div class="container py-3">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="section-tag">Tentang Kami</div>
        <h2 class="section-title">PR GOTRAV Mitra Abadi</h2>
        <div class="section-divider"></div>
        <p style="line-height:1.8;">
          PR GOTRAV Mitra Abadi merupakan perusahaan transportasi yang berkomitmen memberikan layanan perjalanan terbaik dengan sistem door to door. Keselamatan, kenyamanan dan kepuasan pelanggan adalah prioritas kami.
        </p>
        <p style="line-height:1.8;">
          Dengan lebih dari 10 tahun pengalaman dan armada yang terawat, kami melayani rute antar kota dan bandara dengan penuh profesionalisme.
        </p>
        <div class="d-flex flex-wrap gap-3 mt-3">
          @foreach(['Keselamatan', 'Kenyamanan', 'Ketepatan Waktu', 'Kepercayaan'] as $v)
          <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background:var(--bg-section)">
            <i class="bi bi-check-circle-fill" style="color:var(--primary)"></i>
            <span style="font-size:.88rem;font-weight:600;">{{ $v }}</span>
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <div class="row g-3">
          @foreach([
            ['bi-shield-check',   'Keamanan Terjamin',    'Setiap perjalanan dipantau untuk memastikan keamanan penumpang'],
            ['bi-person-badge',   'Driver Berpengalaman',  'Driver terlatih dan berlisensi resmi dengan track record terbaik'],
            ['bi-door-open',      'Door to Door',          'Penjemputan dan pengantaran langsung ke tujuan Anda'],
            ['bi-currency-exchange','Harga Transparan',    'Tarif jelas tanpa biaya tersembunyi'],
          ] as $idx => $f)
          <div class="col-6" data-aos="zoom-in" data-aos-delay="{{ $idx * 80 }}">
            <div class="card-gotrav p-3 h-100">
              <div class="d-flex align-items-center justify-content-center mb-3" style="width:48px;height:48px;background:rgba(26,58,92,.08);border-radius:12px;">
                <i class="bi {{ $f[0] }} fs-4" style="color:var(--primary)"></i>
              </div>
              <div style="font-weight:700;font-size:.92rem;color:var(--text-dark);">{{ $f[1] }}</div>
              <div style="font-size:.82rem;color:var(--muted);margin-top:.3rem;line-height:1.5;">{{ $f[2] }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── ARMADA ──────────────────────────────────────────────── --}}
<section id="layanan" class="armada-section bg-section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-tag">Armada Kami</div>
      <h2 class="section-title">Pilih Kendaraan Anda</h2>
      <div class="section-divider mx-auto"></div>
      <p class="text-muted" style="max-width:500px;margin:0 auto;">Armada terawat dengan kenyamanan maksimal untuk setiap perjalanan Anda</p>
    </div>

    <div class="row g-4 justify-content-center">
      @foreach($armadas as $idx => $armada)
      <div class="col-md-6 col-lg-5" data-aos="fade-up" data-aos-delay="{{ $idx * 100 }}">
        <div class="armada-card">
          <div class="armada-card-image">
            @if($armada->foto)
              <img src="{{ $armada->foto_url }}" alt="{{ $armada->nama }}">
            @else
              {{-- Placeholder --}}
              <svg viewBox="0 0 240 120" xmlns="http://www.w3.org/2000/svg" style="max-width:200px">
                <rect x="10" y="40" width="220" height="60" rx="12" fill="#1A3A5C" opacity=".15"/>
                <rect x="30" y="20" width="150" height="50" rx="10" fill="#1A3A5C" opacity=".2"/>
                <circle cx="60"  cy="105" r="16" fill="#1A3A5C" opacity=".3"/>
                <circle cx="180" cy="105" r="16" fill="#1A3A5C" opacity=".3"/>
                <text x="120" y="65" text-anchor="middle" fill="#1A3A5C" font-size="11" font-weight="700" opacity=".7">{{ $armada->nama }}</text>
              </svg>
            @endif
          </div>
          <div class="armada-card-body">
            <div class="armada-card-title">{{ $armada->nama }}</div>
            <div style="font-size:.82rem;color:var(--muted);">{{ $armada->tipe }}</div>
            <div class="armada-meta">
              <div class="armada-meta-item"><i class="bi bi-people"></i> {{ $armada->jumlah_kursi }} Kursi</div>
              <div class="armada-meta-item"><i class="bi bi-person"></i> {{ $armada->jumlah_supir }} Supir</div>
              @if($armada->has_ac)
              <div class="armada-meta-item"><i class="bi bi-thermometer-snow"></i> AC</div>
              @endif
            </div>
            <div class="bagasi-info-box">
              <i class="bi bi-bag-check"></i>
              <div class="bagasi-info-text">
                Gratis {{ $armada->bagasi_gratis }} tas dan {{ $armada->kardus_gratis }} kardus per penumpang.
                Biaya tambahan: Rp {{ number_format($armada->biaya_bagasi_tambahan, 0, ',', '.') }} / item.
              </div>
            </div>
            <div class="mt-3">
              <a href="{{ route('booking.armada') }}" class="btn-gotrav btn w-100">
                <i class="bi bi-calendar-plus"></i> Booking {{ $armada->nama }}
              </a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── CARA PESAN ──────────────────────────────────────────── --}}
<section id="cara-pesan" class="py-5">
  <div class="container py-3">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-tag">Cara Pesan</div>
      <h2 class="section-title">Mudah & Cepat</h2>
      <div class="section-divider mx-auto"></div>
    </div>

    <div class="row g-4">
      @foreach([
        ['1', 'bi-person-plus',   'Daftar / Login',      'Buat akun atau masuk menggunakan No. WhatsApp Anda'],
        ['2', 'bi-car-front',     'Pilih Armada',         'Tentukan jenis kendaraan sesuai kebutuhan'],
        ['3', 'bi-map',           'Pilih Rute & Jadwal',  'Tentukan rute dan tanggal keberangkatan'],
        ['4', 'bi-ticket-perforated', 'Konfirmasi',       'Review pesanan dan dapatkan tiket booking Anda'],
      ] as $idx => $step)
      <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $idx * 100 }}">
        <div class="text-center p-3">
          <div class="d-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px;height:64px;background:var(--primary);border-radius:50%;color:#fff;font-size:1.4rem;">
            <i class="bi {{ $step[1] }}"></i>
          </div>
          <div style="font-family:var(--font-heading);font-weight:800;font-size:2.5rem;color:var(--bg-section);line-height:1;margin-bottom:.5rem;">0{{ $step[0] }}</div>
          <h5 style="font-family:var(--font-heading);font-weight:700;color:var(--text-dark);">{{ $step[2] }}</h5>
          <p style="font-size:.88rem;color:var(--muted);line-height:1.6;">{{ $step[3] }}</p>
        </div>
      </div>
      @endforeach
    </div>

    <div class="text-center mt-4" data-aos="fade-up">
      <a href="{{ route('booking.armada') }}" class="btn-accent btn btn-lg-custom">
        <i class="bi bi-calendar-check me-2"></i>Mulai Booking Sekarang
      </a>
    </div>
  </div>
</section>

@endsection
