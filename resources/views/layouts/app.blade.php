<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'GOTRAV Travel')</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
  <link rel="stylesheet" href="{{ asset('css/gotrav.css') }}">

  @push('styles')
  <style>
    .alert-warning-gotrav {
      background: rgba(255,193,7,.12);
      border: 1.5px solid rgba(255,193,7,.4);
      border-radius: var(--radius-sm);
      color: #856404;
      font-size: .88rem;
      padding: .75rem 1rem;
      display: flex;
      align-items: center;
      gap: .6rem;
    }
    .badge-verified {
      display: inline-flex;
      align-items: center;
      gap: .25rem;
      background: rgba(30,126,52,.1);
      color: #1e7e34;
      font-size: .65rem;
      font-weight: 700;
      padding: .15rem .45rem;
      border-radius: 50px;
      letter-spacing: .02em;
    }
    .badge-unverified {
      display: inline-flex;
      align-items: center;
      gap: .25rem;
      background: rgba(255,193,7,.15);
      color: #856404;
      font-size: .65rem;
      font-weight: 700;
      padding: .15rem .45rem;
      border-radius: 50px;
      letter-spacing: .02em;
    }
  </style>
  @endpush

  @stack('styles')
</head>
<body>

  {{-- ── Navbar ──────────────────────────────────────────── --}}
  <nav class="navbar-gotrav">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between w-100">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="navbar-brand text-decoration-none">
          <div class="brand-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
          </div>
          <div class="ms-2">
            <div style="font-size:.7rem;color:var(--muted);font-weight:600;letter-spacing:.05em;line-height:1;">GOTRAV</div>
            <div style="line-height:1.1;font-size:.95rem;">Travel</div>
          </div>
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler border-0 d-lg-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav">
          <i class="bi bi-list fs-4" style="color:var(--primary)"></i>
        </button>

        {{-- Nav links --}}
        <div class="collapse navbar-collapse d-lg-flex align-items-center gap-1" id="mainNav">
          <nav class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-1 mt-3 mt-lg-0 flex-grow-1 justify-content-center">
            <a href="{{ route('home') }}"            class="nav-link @active('home')">Beranda</a>
            <a href="{{ route('home') }}#tentang"    class="nav-link">Tentang Kami</a>
            <a href="{{ route('home') }}#layanan"    class="nav-link">Layanan</a>
            <a href="{{ route('home') }}#cara-pesan" class="nav-link">Cara Pesan</a>
            @auth
            <a href="{{ route('riwayat.index') }}"   class="nav-link @active('riwayat.*')">Riwayat Pesanan</a>
            @endauth
            <a href="{{ route('home') }}#kontak"     class="nav-link">Kontak</a>
          </nav>

          {{-- ── Tombol Kanan ────────────────────────────────── --}}
          <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">

            @guest
              <a href="{{ route('login') }}" class="btn-outline-gotrav btn btn-sm">Masuk</a>
              <a href="{{ route('booking.armada') }}" class="btn-gotrav btn">Booking Sekarang</a>

            @else
              {{-- Tombol Admin --}}
              @if(Auth::user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}"
                 class="btn btn-sm d-flex align-items-center gap-2"
                 style="background:var(--accent);color:#fff;border-radius:var(--radius-sm);
                        font-family:var(--font-heading);font-weight:700;font-size:.85rem;
                        padding:.45rem 1rem;text-decoration:none;transition:all .2s"
                 onmouseover="this.style.opacity='.85'"
                 onmouseout="this.style.opacity='1'">
                <i class="bi bi-speedometer2"></i>
                <span class="d-none d-sm-inline">Dashboard Admin</span>
                <span class="d-inline d-sm-none">Admin</span>
              </a>
              @endif

              {{-- Dropdown User --}}
              <div class="dropdown">
                <button class="btn-outline-gotrav btn btn-sm d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown">
                  <i class="bi bi-person-circle"></i>
                  {{ Str::limit(Auth::user()->nama_lengkap, 12) }}

                  {{-- Badge Admin --}}
                  @if(Auth::user()->isAdmin())
                  <span style="background:var(--accent);color:#fff;font-size:.6rem;
                               padding:.1rem .4rem;border-radius:4px;font-weight:700">
                    ADMIN
                  </span>
                  @endif

                  <i class="bi bi-chevron-down" style="font-size:.65rem"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                    style="border-radius:var(--radius-sm);min-width:220px">

                  {{-- Info User --}}
                  <li>
                    <div style="padding:.75rem 1rem;border-bottom:1px solid var(--border)">
                      <div style="font-size:.85rem;font-weight:700;color:var(--text-dark)">
                        {{ Auth::user()->nama_lengkap }}
                      </div>
                      <div style="font-size:.75rem;color:var(--muted);margin-top:.1rem">
                        {{ Auth::user()->no_whatsapp }}
                      </div>
                      {{-- Status Verifikasi --}}
                      <div class="mt-2">
                        @if(Auth::user()->is_verified)
                          <span class="badge-verified">
                            <i class="bi bi-patch-check-fill"></i> Terverifikasi
                          </span>
                        @else
                          <span class="badge-unverified">
                            <i class="bi bi-exclamation-circle-fill"></i> Belum Terverifikasi
                          </span>
                        @endif
                      </div>
                    </div>
                  </li>

                  {{-- Tombol Verifikasi — hanya jika belum verified --}}
                  @if(! Auth::user()->is_verified)
                  <li>
                    <a class="dropdown-item d-flex align-items-center gap-2"
                       href="{{ route('auth.verifikasi') }}"
                       style="font-size:.85rem;color:#856404;font-weight:600;padding:.55rem 1rem;
                              background:rgba(255,193,7,.08)">
                      <i class="bi bi-shield-exclamation" style="color:#856404"></i>
                      Verifikasi WhatsApp
                    </a>
                  </li>
                  <li><hr class="dropdown-divider my-1"></li>
                  @endif

                  {{-- Menu Admin --}}
                  @if(Auth::user()->isAdmin())
                  <li>
                    <a class="dropdown-item d-flex align-items-center gap-2"
                       href="{{ route('admin.dashboard') }}"
                       style="font-size:.85rem;color:var(--accent);font-weight:600;padding:.55rem 1rem">
                      <i class="bi bi-speedometer2"></i> Dashboard Admin
                    </a>
                  </li>
                  <li><hr class="dropdown-divider my-1"></li>
                  @endif

                  {{-- Riwayat Pesanan --}}
                  <li>
                    <a class="dropdown-item d-flex align-items-center gap-2"
                       href="{{ route('riwayat.index') }}"
                       style="font-size:.85rem;padding:.55rem 1rem">
                      <i class="bi bi-receipt"></i> Riwayat Pesanan
                    </a>
                  </li>
                  <li><hr class="dropdown-divider my-1"></li>

                  {{-- Logout --}}
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit"
                              class="dropdown-item d-flex align-items-center gap-2 text-danger"
                              style="font-size:.85rem;padding:.55rem 1rem">
                        <i class="bi bi-box-arrow-right"></i> Logout
                      </button>
                    </form>
                  </li>
                </ul>
              </div>

              {{-- Tombol Booking --}}
              <a href="{{ route('booking.armada') }}" class="btn-gotrav btn">
                Booking Sekarang
              </a>

            @endguest
          </div>
        </div>
      </div>
    </div>
  </nav>

  {{-- ── Flash Messages ──────────────────────────────────── --}}
  @if(session('success'))
  <div class="container mt-3">
    <div class="alert alert-gotrav alert-gotrav-success d-flex align-items-center gap-2">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
  </div>
  @endif

  @if(session('error') || $errors->any())
  <div class="container mt-3">
    <div class="alert alert-gotrav alert-gotrav-danger d-flex align-items-center gap-2">
      <i class="bi bi-exclamation-circle-fill"></i>
      @if(session('error')){{ session('error') }}@else{{ $errors->first() }}@endif
    </div>
  </div>
  @endif

  {{-- ── Warning Verifikasi ───────────────────────────────── --}}
  @if(session('warning'))
  <div class="container mt-3">
    <div class="alert-warning-gotrav">
      <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;font-size:1.1rem"></i>
      <div class="flex-grow-1">
        {{ session('warning') }}
      </div>
      @auth
      @if(! Auth::user()->is_verified)
      <a href="{{ route('auth.verifikasi') }}"
         class="btn btn-sm fw-bold"
         style="background:rgba(255,193,7,.2);color:#856404;border:1.5px solid rgba(255,193,7,.4);
                border-radius:6px;padding:.25rem .75rem;font-size:.8rem;white-space:nowrap;
                text-decoration:none">
        <i class="bi bi-shield-check me-1"></i>Verifikasi Sekarang
      </a>
      @endif
      @endauth
    </div>
  </div>
  @endif

  {{-- ── Page Content ─────────────────────────────────────── --}}
  @yield('content')

  {{-- ── Footer ──────────────────────────────────────────── --}}
  <footer class="footer-gotrav" id="kontak">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4" data-aos="fade-up">
          <div class="footer-brand">
            <i class="bi bi-bus-front-fill me-2"></i>PR GOTRAV Mitra Abadi
          </div>
          <p style="font-size:.9rem;line-height:1.7;">
            Layanan travel door to door yang nyaman, aman, dan terpercaya.
            Kepuasan pelanggan adalah prioritas kami.
          </p>
          <div class="d-flex gap-2 mt-3">
            <a href="#" class="btn btn-sm"
               style="background:rgba(255,255,255,.1);color:#fff;border-radius:8px;
                      width:36px;height:36px;display:flex;align-items:center;justify-content:center">
              <i class="bi bi-whatsapp"></i>
            </a>
            <a href="#" class="btn btn-sm"
               style="background:rgba(255,255,255,.1);color:#fff;border-radius:8px;
                      width:36px;height:36px;display:flex;align-items:center;justify-content:center">
              <i class="bi bi-instagram"></i>
            </a>
            <a href="#" class="btn btn-sm"
               style="background:rgba(255,255,255,.1);color:#fff;border-radius:8px;
                      width:36px;height:36px;display:flex;align-items:center;justify-content:center">
              <i class="bi bi-facebook"></i>
            </a>
          </div>
        </div>

        <div class="col-sm-6 col-lg-2" data-aos="fade-up" data-aos-delay="100">
          <div class="footer-heading">Navigasi</div>
          <a href="{{ route('home') }}"           class="footer-link">Beranda</a>
          <a href="{{ route('home') }}#tentang"   class="footer-link">Tentang Kami</a>
          <a href="{{ route('home') }}#layanan"   class="footer-link">Layanan</a>
          <a href="{{ route('booking.armada') }}" class="footer-link">Booking</a>
        </div>

        <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
          <div class="footer-heading">Layanan</div>
          <a href="#" class="footer-link">Door to Door Service</a>
          <a href="#" class="footer-link">Armada Nyaman</a>
          <a href="#" class="footer-link">Driver Profesional</a>
          <a href="#" class="footer-link">Harga Terjangkau</a>
        </div>

        <div class="col-lg-3" data-aos="fade-up" data-aos-delay="300">
          <div class="footer-heading">Kontak</div>
          <div class="footer-link d-flex align-items-center gap-2">
            <i class="bi bi-whatsapp text-success"></i> +62 812-xxxx-xxxx
          </div>
          <div class="footer-link d-flex align-items-center gap-2">
            <i class="bi bi-envelope"></i> info@gotrav.id
          </div>
          <div class="footer-link d-flex align-items-start gap-2">
            <i class="bi bi-geo-alt mt-1"></i> Jakarta, Indonesia
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        &copy; {{ date('Y') }} PR GOTRAV Mitra Abadi. Hak cipta dilindungi.
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 700, once: true, offset: 60 });
  </script>
  @stack('scripts')
</body>
</html>