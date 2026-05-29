{{-- resources/views/riwayat/faktur.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faktur #{{ $pemesanan->kode_pemesanan }}</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --primary:      #1a6b5a;
      --primary-light:#e8f5f1;
      --accent:       #f5a623;
      --dark:         #0f1c18;
      --muted:        #6b7e78;
      --border:       #d6e4e0;
      --bg:           #f7faf9;
      --white:        #ffffff;
      --font:         'Plus Jakarta Sans', sans-serif;
      --mono:         'DM Mono', monospace;
    }

    html, body {
      background: var(--bg);
      font-family: var(--font);
      color: var(--dark);
      font-size: 14px;
      line-height: 1.6;
    }

    /* ─── Page wrapper ─── */
    .page {
      max-width: 780px;
      margin: 40px auto;
      background: var(--white);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 40px rgba(0,0,0,.08);
    }

    /* ─── Header ─── */
    .header {
      background: var(--primary);
      padding: 36px 48px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .logo-wrap {
      background: var(--white);
      border-radius: 12px;
      padding: 8px 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 80px;
    }

    .logo-wrap img {
      max-height: 48px;
      width: auto;
      display: block;
    }

    .brand-info h1 {
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--white);
      letter-spacing: -.5px;
      line-height: 1.2;
    }

    .brand-info p {
      font-size: .78rem;
      color: rgba(255,255,255,.65);
      margin-top: 2px;
    }

    .header-right {
      text-align: right;
    }

    .faktur-label {
      font-size: .7rem;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(255,255,255,.5);
      margin-bottom: 4px;
    }

    .faktur-number {
      font-family: var(--mono);
      font-size: 1.25rem;
      font-weight: 500;
      color: var(--white);
      background: rgba(255,255,255,.12);
      padding: 6px 14px;
      border-radius: 8px;
      letter-spacing: 1px;
    }

    .faktur-date {
      font-size: .78rem;
      color: rgba(255,255,255,.55);
      margin-top: 6px;
    }

    /* ─── Status banner ─── */
    .status-banner {
      background: var(--primary-light);
      padding: 10px 48px;
      display: flex;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid var(--border);
    }

    .status-dot {
      width: 8px; height: 8px;
      background: var(--primary);
      border-radius: 50%;
    }

    .status-banner span {
      font-size: .8rem;
      font-weight: 600;
      color: var(--primary);
      letter-spacing: .3px;
    }

    /* ─── Body ─── */
    .body {
      padding: 40px 48px;
    }

    /* ─── Info grid ─── */
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 32px;
      margin-bottom: 36px;
    }

    .info-block h4 {
      font-size: .68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: var(--muted);
      margin-bottom: 10px;
    }

    .info-block p {
      font-size: .88rem;
      color: var(--dark);
      line-height: 1.7;
    }

    .info-block strong {
      font-weight: 700;
    }

    /* ─── Divider ─── */
    .divider {
      border: none;
      border-top: 1px solid var(--border);
      margin: 0 0 32px;
    }

    /* ─── Section title ─── */
    .section-title {
      font-size: .68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: var(--muted);
      margin-bottom: 16px;
    }

    /* ─── Journey card ─── */
    .journey-card {
      background: var(--primary-light);
      border-radius: 12px;
      padding: 20px 24px;
      margin-bottom: 28px;
      display: flex;
      align-items: center;
      gap: 0;
    }

    .journey-city {
      flex: 1;
    }

    .journey-city .label {
      font-size: .7rem;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }

    .journey-city .city {
      font-size: 1.15rem;
      font-weight: 800;
      color: var(--primary);
    }

    .journey-arrow {
      flex: 0 0 64px;
      text-align: center;
      color: var(--primary);
    }

    .journey-arrow svg {
      width: 32px;
      height: 32px;
    }

    /* ─── Detail table ─── */
    .detail-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 32px;
    }

    .detail-table th {
      background: var(--dark);
      color: var(--white);
      font-size: .75rem;
      font-weight: 600;
      letter-spacing: .5px;
      padding: 10px 14px;
      text-align: left;
    }

    .detail-table th:first-child { border-radius: 8px 0 0 8px; }
    .detail-table th:last-child  { border-radius: 0 8px 8px 0; text-align: right; }

    .detail-table td {
      padding: 12px 14px;
      font-size: .875rem;
      border-bottom: 1px solid var(--border);
      color: var(--dark);
      vertical-align: middle;
    }

    .detail-table td:last-child { text-align: right; }

    .detail-table tr:last-child td { border-bottom: none; }

    .seat-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: var(--primary-light);
      color: var(--primary);
      font-family: var(--mono);
      font-size: .8rem;
      font-weight: 500;
      padding: 3px 10px;
      border-radius: 6px;
    }

    /* ─── Payment summary ─── */
    .payment-block {
      margin-left: auto;
      width: 280px;
      background: var(--bg);
      border-radius: 12px;
      padding: 20px 22px;
      margin-bottom: 36px;
    }

    .payment-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 6px 0;
      font-size: .875rem;
      color: var(--muted);
    }

    .payment-row.total {
      border-top: 1.5px solid var(--border);
      margin-top: 8px;
      padding-top: 14px;
      color: var(--dark);
      font-weight: 700;
      font-size: 1rem;
    }

    .payment-row.total .amount {
      color: var(--primary);
      font-size: 1.1rem;
      font-family: var(--mono);
    }

    .payment-row .amount {
      font-family: var(--mono);
      font-weight: 500;
      color: var(--dark);
    }

    /* ─── Footer ─── */
    .footer {
      background: var(--dark);
      padding: 28px 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
    }

    .footer-note {
      font-size: .78rem;
      color: rgba(255,255,255,.45);
      max-width: 340px;
      line-height: 1.6;
    }

    .footer-note strong {
      color: rgba(255,255,255,.75);
      font-weight: 600;
    }

    .qr-placeholder {
      width: 64px; height: 64px;
      border-radius: 8px;
      background: rgba(255,255,255,.08);
      border: 1px dashed rgba(255,255,255,.2);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .qr-placeholder svg {
      width: 32px; height: 32px;
      color: rgba(255,255,255,.25);
    }

    /* ─── Print button (screen only) ─── */
    .print-bar {
      max-width: 780px;
      margin: 0 auto 24px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      padding: 0 4px;
    }

    .btn-print, .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 10px 22px;
      border-radius: 10px;
      font-family: var(--font);
      font-size: .875rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: opacity .2s;
    }

    .btn-print { background: var(--primary); color: var(--white); }
    .btn-back  { background: var(--bg); color: var(--dark); border: 1px solid var(--border); }
    .btn-print:hover, .btn-back:hover { opacity: .85; }

    /* ─── Watermark / paid stamp ─── */
    .paid-stamp {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border: 2.5px solid var(--primary);
      border-radius: 8px;
      padding: 6px 16px;
      color: var(--primary);
      font-weight: 800;
      font-size: .85rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      transform: rotate(-3deg);
    }

    /* ─── Print styles ─── */
    @media print {
      body { background: white; font-size: 13px; }
      .page { margin: 0; box-shadow: none; border-radius: 0; }
      .print-bar { display: none !important; }
    }
  </style>
</head>
<body>

  {{-- Action bar (screen only) --}}
  <div class="print-bar">
    <a href="{{ route('riwayat.index') }}" class="btn-back">
      ← Kembali
    </a>
    <button class="btn-print" onclick="window.print()">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
        <rect x="6" y="14" width="12" height="8" rx="1"/>
      </svg>
      Cetak / Download PDF
    </button>
  </div>

  <div class="page">

    {{-- ═══ HEADER ═══ --}}
    <div class="header">
      <div class="header-left">
        <div class="logo-wrap">
          {{-- Ganti src dengan path logo Anda, misal: /images/logo.png --}}
          <img src="{{ asset('images/logo.png') }}"
               alt="GoTrav Logo"
               onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
          {{-- Fallback teks jika logo tidak ditemukan --}}
          <span style="display:none;font-family:var(--font);font-weight:800;color:var(--primary);font-size:1.1rem;">GoTrav</span>
        </div>
        <div class="brand-info">
          <h1>GoTrav</h1>
          <p>Layanan Perjalanan Terpercaya</p>
        </div>
      </div>

      <div class="header-right">
        <div class="faktur-label">Faktur Pembayaran</div>
        <div class="faktur-number">#{{ $pemesanan->kode_pemesanan }}</div>
        <div class="faktur-date">Diterbitkan: {{ now()->format('d M Y') }}</div>
      </div>
    </div>

    {{-- ═══ STATUS BANNER ═══ --}}
    <div class="status-banner">
      <div class="status-dot"></div>
      <span>Pesanan Selesai &amp; Telah Dibayar</span>
      <div style="margin-left:auto">
        <div class="paid-stamp">✓ Lunas</div>
      </div>
    </div>

    {{-- ═══ BODY ═══ --}}
    <div class="body">

      {{-- Info grid: Penumpang & Perusahaan --}}
      <div class="info-grid">
        <div class="info-block">
          <h4>Informasi Penumpang</h4>
          <p>
            <strong>{{ $pemesanan->user->nama_lengkap }}</strong><br>
    <i class="bi bi-whatsapp" style="color:#25d366;font-size:.8rem;"></i>
    {{ $pemesanan->user->no_whatsapp ?? '-' }}<br>
    {{ ucfirst($pemesanan->user->jenis_kelamin ?? '-') }}
          </p>
        </div>
        <div class="info-block" style="text-align:right">
          <h4>Informasi Perusahaan</h4>
          <p>
            <strong>GoTrav</strong><br>
            gotrav.info@gmail.com<br>
            +62 812-8266-1982
          </p>
        </div>
      </div>

      <hr class="divider">

      {{-- Journey ─── Rute --}}
      <div class="section-title">Detail Perjalanan</div>

      <div class="journey-card">
        <div class="journey-city">
          <div class="label">Keberangkatan</div>
          <div class="city">{{ $pemesanan->jadwal->rute->kota_asal }}</div>
        </div>

        <div class="journey-arrow">
          <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 16h24M18 8l8 8-8 8"/>
          </svg>
        </div>

        <div class="journey-city" style="text-align:right">
          <div class="label">Tujuan</div>
          <div class="city">{{ $pemesanan->jadwal->rute->kota_tujuan }}</div>
        </div>
      </div>

      {{-- Detail table --}}
      <table class="detail-table">
        <thead>
          <tr>
            <th>Deskripsi</th>
            <th>Detail</th>
            <th>Harga</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <strong>Tiket Travel — {{ $pemesanan->jadwal->armada->nama }}</strong><br>
              <span style="color:var(--muted);font-size:.8rem;">
                {{ $pemesanan->jadwal->tanggal->format('d M Y') }} · {{ $pemesanan->jadwal->jam_berangkat }}
              </span>
            </td>
            <td>
              @foreach($pemesanan->kursis as $detail)
                <span class="seat-badge">
                  <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="4" y="2" width="16" height="16" rx="2"/><path d="M4 18h16v4H4z"/></svg>
                  Kursi {{ $detail->nomor_kursi }}
                </span>
              @endforeach
              <br>
              <span style="font-size:.78rem;color:var(--muted);">
                {{ $pemesanan->kursis->count() }} penumpang · {{ $pemesanan->jadwal->armada->jumlah_kursi }} seat armada
              </span>
            </td>
            <td>
              <strong>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</strong>
            </td>
          </tr>
        </tbody>
      </table>

      {{-- Payment summary --}}
      <div class="payment-block">
        <div class="payment-row">
          <span>Subtotal</span>
          <span class="amount">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
        </div>
        <div class="payment-row">
          <span>Biaya Layanan</span>
          <span class="amount">Rp 0</span>
        </div>
        <div class="payment-row">
          <span>Diskon</span>
          <span class="amount">– Rp 0</span>
        </div>
        <div class="payment-row total">
          <span>Total Dibayar</span>
          <span class="amount">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
        </div>
      </div>

      {{-- Metode Pembayaran --}}
      <div class="section-title">Metode Pembayaran</div>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:36px;">
        <div style="background:var(--primary-light);border-radius:8px;padding:8px 14px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <rect x="2" y="6" width="20" height="12" rx="2"/>
  <circle cx="12" cy="12" r="2"/>
  <path d="M6 12h.01M18 12h.01"/>
</svg>
        </div>
        <div>
          <div style="font-weight:600;font-size:.9rem;">
            {{ $pemesanan->metode_pembayaran ?? 'Pembayaran Tunai' }}
          </div>
          <div style="font-size:.78rem;color:var(--muted);">
            Dibayar pada {{ $pemesanan->updated_at->format('d M Y, H:i') }} WIB
          </div>
        </div>
      </div>

    </div>{{-- end .body --}}

    {{-- ═══ FOOTER ═══ --}}
    <div class="footer">
      <div class="footer-note">
        <strong>Terima kasih telah menggunakan GoTrav.</strong><br>
        Dokumen ini adalah bukti pembayaran yang sah. Simpan faktur ini sebagai arsip perjalanan Anda.
        Untuk bantuan hubungi <strong>gotrav.info@gmail.com</strong>
      </div>
      
    </div>

  </div>{{-- end .page --}}

</body>
</html>