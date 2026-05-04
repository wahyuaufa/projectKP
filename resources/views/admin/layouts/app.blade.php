{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') — GOTRAV Dashboard</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">

  <style>
  :root {
    --primary:      #1A3A5C;
    --primary-dark: #102540;
    --accent:       #F4A020;
    --success:      #28A745;
    --danger:       #DC3545;
    --warning:      #FFC107;
    --sidebar-w:    260px;
    --topbar-h:     60px;
    --bg:           #F0F4F8;
    --card:         #ffffff;
    --border:       #DEE2E6;
    --muted:        #6C757D;
    --text:         #1A2332;
    --font-head:    'Plus Jakarta Sans', sans-serif;
    --font-body:    'DM Sans', sans-serif;
    --radius:       12px;
    --shadow:       0 2px 16px rgba(26,58,92,.08);
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: var(--font-body); background: var(--bg); color: var(--text); }

  /* ── Sidebar ─────────────────────────────── */
  .admin-sidebar {
    position: fixed; top: 0; left: 0; bottom: 0;
    width: var(--sidebar-w);
    background: var(--primary-dark);
    z-index: 1040;
    display: flex; flex-direction: column;
    transition: transform .3s ease;
  }
  .sidebar-brand {
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,.08);
    display: flex; align-items: center; gap: .8rem;
  }
  .sidebar-brand-icon {
    width: 38px; height: 38px;
    background: var(--accent);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.1rem;
  }
  .sidebar-brand-text { color: #fff; font-family: var(--font-head); font-weight: 800; font-size: .95rem; line-height: 1.2; }
  .sidebar-brand-sub  { color: rgba(255,255,255,.5); font-size: .72rem; font-weight: 400; }

  .sidebar-nav { flex: 1; overflow-y: auto; padding: 1rem 0; }
  .sidebar-section {
    padding: .5rem 1.5rem .3rem;
    font-size: .68rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: rgba(255,255,255,.35);
  }
  .sidebar-link {
    display: flex; align-items: center; gap: .75rem;
    padding: .6rem 1.5rem;
    color: rgba(255,255,255,.65);
    font-size: .88rem; font-weight: 500;
    border-left: 3px solid transparent;
    transition: all .15s;
    text-decoration: none;
  }
  .sidebar-link i { font-size: 1.05rem; width: 20px; text-align: center; }
  .sidebar-link:hover { color: #fff; background: rgba(255,255,255,.06); }
  .sidebar-link.active {
    color: #fff;
    background: rgba(255,255,255,.08);
    border-left-color: var(--accent);
  }

  .sidebar-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(255,255,255,.08);
    font-size: .8rem; color: rgba(255,255,255,.4);
  }

  /* ── Topbar ──────────────────────────────── */
  .admin-topbar {
    position: fixed; top: 0; left: var(--sidebar-w); right: 0;
    height: var(--topbar-h);
    background: var(--card);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 1.5rem;
    z-index: 1030;
    box-shadow: 0 1px 8px rgba(0,0,0,.05);
  }
  .topbar-title {
    font-family: var(--font-head); font-weight: 700;
    font-size: 1rem; color: var(--text);
  }
  .topbar-right { display: flex; align-items: center; gap: .8rem; }
  .topbar-admin {
    display: flex; align-items: center; gap: .5rem;
    font-size: .85rem; font-weight: 600; color: var(--text);
  }
  .avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: var(--primary);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .85rem; font-weight: 700;
  }

  /* ── Main Content ────────────────────────── */
  .admin-main {
    margin-left: var(--sidebar-w);
    padding-top: var(--topbar-h);
    min-height: 100vh;
  }
  .admin-content { padding: 1.5rem; }

  /* ── Cards ───────────────────────────────── */
  .acard {
    background: var(--card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: none;
  }
  .acard-header {
    padding: 1rem 1.3rem;
    border-bottom: 1px solid var(--border);
    font-family: var(--font-head);
    font-weight: 700; font-size: .92rem;
    display: flex; align-items: center; justify-content: space-between;
    color: var(--text);
  }
  .acard-body { padding: 1.3rem; }

  /* ── Stat Cards ──────────────────────────── */
  .stat-card {
    background: var(--card);
    border-radius: var(--radius);
    padding: 1.3rem;
    box-shadow: var(--shadow);
    display: flex; align-items: center; gap: 1rem;
  }
  .stat-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
  }
  .stat-label { font-size: .78rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
  .stat-value { font-family: var(--font-head); font-weight: 800; font-size: 1.6rem; color: var(--text); line-height: 1.1; }
  .stat-sub   { font-size: .75rem; color: var(--muted); margin-top: .2rem; }

  /* ── Table ───────────────────────────────── */
  .atable { width: 100%; border-collapse: collapse; }
  .atable th {
    background: var(--bg); font-family: var(--font-head);
    font-size: .75rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .06em;
    padding: .7rem 1rem; border-bottom: 1px solid var(--border);
  }
  .atable td {
    padding: .85rem 1rem; font-size: .88rem;
    border-bottom: 1px solid var(--border); vertical-align: middle;
  }
  .atable tbody tr:last-child td { border-bottom: none; }
  .atable tbody tr:hover { background: rgba(26,58,92,.02); }

  /* ── Badge ───────────────────────────────── */
  .abadge {
    padding: .3rem .75rem; border-radius: 50px;
    font-size: .75rem; font-weight: 700; font-family: var(--font-head);
    display: inline-flex; align-items: center; gap: .3rem;
  }
  .abadge-akan   { background: rgba(26,58,92,.1);  color: var(--primary); }
  .abadge-selesai{ background: rgba(40,167,69,.1); color: #1a7a32; }
  .abadge-batal  { background: rgba(220,53,69,.1); color: var(--danger); }
  .abadge-assign { background: rgba(244,160,32,.1);color: #b07000; }

  /* ── Form ────────────────────────────────── */
  .aform-control {
    border: 1.5px solid var(--border); border-radius: 8px;
    padding: .55rem .9rem; font-size: .88rem;
    width: 100%; color: var(--text);
    transition: border-color .15s, box-shadow .15s;
    background: #fff; font-family: var(--font-body);
  }
  .aform-control:focus {
    outline: none; border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,58,92,.1);
  }
  .aform-label { font-size: .82rem; font-weight: 600; color: var(--text); margin-bottom: .35rem; display: block; }

  /* ── Buttons ─────────────────────────────── */
  .abtn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1.1rem; border-radius: 8px; border: none;
    font-size: .85rem; font-weight: 600; font-family: var(--font-head);
    cursor: pointer; transition: all .15s;
  }
  .abtn-primary { background: var(--primary); color: #fff; }
  .abtn-primary:hover { background: var(--primary-dark); color: #fff; transform: translateY(-1px); }
  .abtn-accent  { background: var(--accent); color: #fff; }
  .abtn-accent:hover  { background: #d4880a; color: #fff; }
  .abtn-outline { background: #fff; color: var(--text); border: 1.5px solid var(--border); }
  .abtn-outline:hover { border-color: var(--primary); color: var(--primary); }
  .abtn-success { background: var(--success); color: #fff; }
  .abtn-danger  { background: var(--danger);  color: #fff; }
  .abtn-wa      { background: #25D366; color: #fff; }
  .abtn-wa:hover{ background: #1eb85a; color: #fff; }
  .abtn-sm      { padding: .35rem .75rem; font-size: .78rem; }

  /* ── Mobile sidebar ──────────────────────── */
  @media (max-width: 991px) {
    .admin-sidebar { transform: translateX(-100%); }
    .admin-sidebar.open { transform: translateX(0); }
    .admin-topbar, .admin-main { margin-left: 0; left: 0; }
    .sidebar-overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,.4); z-index: 1039;
    }
    .sidebar-overlay.show { display: block; }
  }

  /* ── Misc ────────────────────────────────── */
  .page-title { font-family: var(--font-head); font-weight: 800; font-size: 1.3rem; color: var(--text); }
  .text-muted-sm { font-size: .78rem; color: var(--muted); }
  .divider { border-color: var(--border); margin: 1rem 0; }
  </style>

  @stack('styles')
</head>
<body>

{{-- Overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ── SIDEBAR ────────────────────────────────────────── --}}
<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-brand">
    <div class="sidebar-brand-icon"><img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100%; height: auto;"></i></div>
    <div>
      <div class="sidebar-brand-text">GOTRAV</div>
      <div class="sidebar-brand-sub">Admin Dashboard</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="sidebar-section">Utama</div>
    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-link @if(request()->routeIs('admin.dashboard')) active @endif">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-section mt-2">Operasional</div>
    <a href="{{ route('admin.jadwal.index') }}"
       class="sidebar-link @if(request()->routeIs('admin.jadwal.*')) active @endif">
      <i class="bi bi-calendar-week"></i> Jadwal Keberangkatan
    </a>
    <a href="{{ route('admin.pemesanan.index') }}"
       class="sidebar-link @if(request()->routeIs('admin.pemesanan.*')) active @endif">
      <i class="bi bi-receipt"></i> Data Pemesanan
    </a>
    <a href="{{ route('admin.pemesanan.cetak-harian') }}"
       class="sidebar-link @if(request()->routeIs('admin.pemesanan.cetak-harian')) active @endif">
      <i class="bi bi-printer"></i> Cetak Harian
    </a>

    <div class="sidebar-section mt-2">Master Data</div>
    <a href="{{ route('admin.driver.index') }}"
       class="sidebar-link @if(request()->routeIs('admin.driver.*')) active @endif">
      <i class="bi bi-person-badge"></i> Data Driver / PIC
    </a>

    <div class="sidebar-section mt-2">Lainnya</div>
    <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
      <i class="bi bi-globe"></i> Lihat Website
    </a>
  </nav>

  <div class="sidebar-footer">
    <div style="font-size:.75rem;color:rgba(255,255,255,.3)">v1.0 · PR GOTRAV Mitra Abadi</div>
  </div>
</aside>

{{-- ── TOPBAR ─────────────────────────────────────────── --}}
<header class="admin-topbar">
  <div class="d-flex align-items-center gap-3">
    <button class="btn p-0 d-lg-none" onclick="toggleSidebar()" style="background:none;border:none;font-size:1.3rem;color:var(--text)">
      <i class="bi bi-list"></i>
    </button>
    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
  </div>

  <div class="topbar-right">
    {{-- Tanggal hari ini --}}
    <div style="font-size:.8rem;color:var(--muted)" class="d-none d-sm-block">
      <i class="bi bi-calendar3 me-1"></i>
      {{ now()->translatedFormat('l, d F Y') }}
    </div>

    {{-- Admin dropdown --}}
    <div class="dropdown">
      <button class="btn p-0 topbar-admin" data-bs-toggle="dropdown">
        <div class="avatar">{{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}</div>
        <span class="d-none d-sm-block">{{ Str::limit(Auth::user()->nama_lengkap, 14) }}</span>
        <i class="bi bi-chevron-down" style="font-size:.65rem"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius:10px;min-width:180px">
        <li><div class="dropdown-item-text" style="font-size:.78rem;color:var(--muted)">{{ Auth::user()->no_whatsapp }}</div></li>
        <li><hr class="dropdown-divider my-1"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item" style="color:var(--danger);font-size:.85rem">
              <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</header>

{{-- ── MAIN ────────────────────────────────────────────── --}}
<main class="admin-main">
  <div class="admin-content">

    @if(session('success'))
    <div class="alert border-0 mb-3 d-flex align-items-center gap-2"
         style="background:#d4edda;color:#1a7a32;border-radius:10px;font-size:.88rem">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert border-0 mb-3 d-flex align-items-center gap-2"
         style="background:#f8d7da;color:#842029;border-radius:10px;font-size:.88rem">
      <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
    </div>
    @endif

    @yield('content')
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
  document.getElementById('adminSidebar').classList.toggle('open');
  document.getElementById('sidebarOverlay').classList.toggle('show');
}
function closeSidebar() {
  document.getElementById('adminSidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('show');
}
</script>
@stack('scripts')
</body>
</html>
