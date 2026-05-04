@extends('layouts.app')
@section('title', 'Verifikasi Nomor WhatsApp')

@push('styles')
<style>
.otp-input-wrap {
  display: flex;
  gap: .6rem;
  justify-content: center;
  margin: 1.5rem 0;
}
.otp-box {
  width: 52px;
  height: 60px;
  border: 2.5px solid var(--border);
  border-radius: var(--radius-sm);
  text-align: center;
  font-size: 1.6rem;
  font-weight: 800;
  font-family: var(--font-heading);
  color: var(--primary);
  background: #fff;
  transition: all .2s;
  outline: none;
  caret-color: transparent;
}
.otp-box:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(26,58,92,.1);
  background: rgba(26,58,92,.02);
}
.otp-box.filled {
  border-color: var(--primary);
  background: rgba(26,58,92,.04);
}
.otp-box.error {
  border-color: var(--danger);
  background: rgba(220,53,69,.04);
  animation: shake .3s ease;
}
@keyframes shake {
  0%,100% { transform: translateX(0); }
  25%      { transform: translateX(-6px); }
  75%      { transform: translateX(6px); }
}

.countdown-ring {
  position: relative;
  width: 64px;
  height: 64px;
  margin: 0 auto 1rem;
}
.countdown-ring svg {
  transform: rotate(-90deg);
}
.countdown-ring circle {
  fill: none;
  stroke-width: 4;
}
.countdown-ring .track  { stroke: var(--border); }
.countdown-ring .timer  {
  stroke: var(--primary);
  stroke-linecap: round;
  transition: stroke-dashoffset .9s linear;
}
.countdown-number {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--font-heading);
  font-weight: 800;
  font-size: 0.85rem;
  color: var(--primary);
}

.wa-number-display {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  background: rgba(37,211,102,.1);
  border: 1.5px solid rgba(37,211,102,.3);
  border-radius: 50px;
  padding: .35rem 1rem;
  font-weight: 700;
  color: #1a7a32;
  font-size: .9rem;
}
</style>
@endpush

@section('content')
<section class="auth-section">
  <div class="container">
    <div class="auth-card" style="max-width:440px" data-aos="fade-up">

      {{-- Header --}}
      <div class="auth-logo">
        <div class="logo-icon" style="background:#25D366">
          <i class="bi bi-whatsapp"></i>
        </div>
        <div class="auth-title">Verifikasi WhatsApp</div>
        <p class="text-muted" style="font-size:.88rem;margin-top:.4rem;line-height:1.6">
          Kode OTP 6 digit telah dikirim ke nomor
        </p>
        <div class="wa-number-display mt-2">
          <i class="bi bi-whatsapp"></i>
          {{ $noWhatsapp }}
        </div>
      </div>

      {{-- Error --}}
      @if(session('error'))
      <div class="alert-gotrav alert-gotrav-danger p-3 rounded-2 mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
      </div>
      @endif

      @if($errors->any())
      <div class="alert-gotrav alert-gotrav-danger p-3 rounded-2 mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
      </div>
      @endif

      {{-- Success resend --}}
      @if(session('success'))
      <div class="alert-gotrav alert-gotrav-success p-3 rounded-2 mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      </div>
      @endif

      {{-- Countdown Timer --}}
      <div class="text-center mb-2">
        <div class="countdown-ring">
          <svg viewBox="0 0 64 64" width="64" height="64">
            <circle class="track" cx="32" cy="32" r="28"/>
            <circle class="timer" id="timerCircle" cx="32" cy="32" r="28"
                    stroke-dasharray="175.93"
                    stroke-dashoffset="0"/>
          </svg>
          <div class="countdown-number" id="countdownNumber">{{ $expireSeconds }}</div>
        </div>
        <div style="font-size:.78rem;color:var(--muted)">detik tersisa</div>
      </div>

      {{-- Form OTP --}}
      <form method="POST" action="{{ route('auth.verifikasi.submit') }}" id="otpForm">
        @csrf
        <input type="hidden" name="otp" id="otpHidden">

        <div class="otp-input-wrap" id="otpWrap">
          @for($i = 0; $i < 6; $i++)
          <input type="text" class="otp-box"
                 inputmode="numeric"
                 maxlength="1"
                 data-index="{{ $i }}"
                 autocomplete="off">
          @endfor
        </div>

        <div id="otpError" style="display:none;text-align:center;font-size:.82rem;color:var(--danger);margin-bottom:.8rem">
          <i class="bi bi-exclamation-circle me-1"></i>
          <span id="otpErrorMsg">Masukkan 6 digit kode OTP</span>
        </div>

        <button type="submit" class="btn-gotrav btn w-100 btn-lg-custom" id="btnVerif">
          <i class="bi bi-shield-check me-2"></i>Verifikasi
        </button>
      </form>

      {{-- Divider --}}
      <div class="d-flex align-items-center gap-2 my-3">
        <hr style="flex:1;border-color:var(--border)">
        <span style="font-size:.78rem;color:var(--muted)">Tidak menerima kode?</span>
        <hr style="flex:1;border-color:var(--border)">
      </div>

      {{-- Kirim Ulang --}}
      <form method="POST" action="{{ route('auth.verifikasi.resend') }}" id="resendForm">
        @csrf
        <button type="submit" class="btn w-100 btn-whatsapp" id="btnResend" disabled>
          <!-- <i class="bi bi-arrow-clockwise me-2"></i> -->
          @php
    $menit  = floor($expireSeconds / 60);
    $detik  = $expireSeconds % 60;
    $format = $menit > 0 ? "{$menit}:" . str_pad($detik, 2, '0', STR_PAD_LEFT) : "{$detik}";
@endphp
<span id="resendText">Kirim Ulang (tunggu <span id="resendCountdown">{{ $format }}</span>)</span>
        </button>
      </form>

      {{-- Ganti Nomor --}}
      <div class="text-center mt-3">
        <a href="{{ route('register') }}"
           style="font-size:.83rem;color:var(--muted);text-decoration:underline">
          <i class="bi bi-arrow-left me-1"></i>Ganti nomor WhatsApp
        </a>
      </div>

      {{-- Info --}}
      <div class="mt-4 p-3 rounded-3" style="background:var(--bg-section);font-size:.78rem;color:var(--muted);line-height:1.7">
        <i class="bi bi-info-circle me-1" style="color:var(--primary)"></i>
        @php
    $menitInfo = floor($expireSeconds / 60);
    $detikInfo = $expireSeconds % 60;
@endphp
Kode OTP berlaku selama
<strong>
    {{ $menitInfo > 0 ? $menitInfo . ' menit' : '' }}
    {{ $detikInfo > 0 ? $detikInfo . ' detik' : '' }}
</strong>.
        Jika tidak menerima pesan, pastikan nomor WhatsApp Anda aktif dan coba kirim ulang.
      </div>

    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
// ════════════════════════════════════════════════════════════
// 1. OTP INPUT — navigasi antar kotak otomatis
// ════════════════════════════════════════════════════════════
const boxes     = document.querySelectorAll('.otp-box');
const otpHidden = document.getElementById('otpHidden');
const otpError  = document.getElementById('otpError');
const otpErrMsg = document.getElementById('otpErrorMsg');

boxes.forEach((box, idx) => {
  // Input angka
  box.addEventListener('input', function () {
    const val = this.value.replace(/\D/g, '');
    this.value = val ? val[0] : '';
    this.classList.toggle('filled', !!this.value);
    otpError.style.display = 'none';

    if (this.value && idx < 5) boxes[idx + 1].focus();

    updateHidden();
    if (getOTP().length === 6) submitOTP();
  });

  // Backspace
  box.addEventListener('keydown', function (e) {
    if (e.key === 'Backspace' && !this.value && idx > 0) {
      boxes[idx - 1].value = '';
      boxes[idx - 1].classList.remove('filled');
      boxes[idx - 1].focus();
      updateHidden();
    }
  });

  // Paste seluruh kode
  box.addEventListener('paste', function (e) {
    e.preventDefault();
    const text = (e.clipboardData || window.clipboardData)
      .getData('text').replace(/\D/g, '').slice(0, 6);
    text.split('').forEach((ch, i) => {
      if (boxes[i]) {
        boxes[i].value = ch;
        boxes[i].classList.add('filled');
      }
    });
    updateHidden();
    if (text.length === 6) {
      boxes[5].focus();
      submitOTP();
    }
  });
});

function getOTP() {
  return [...boxes].map(b => b.value).join('');
}

function updateHidden() {
  otpHidden.value = getOTP();
}

function submitOTP() {
  const otp = getOTP();
  if (otp.length < 6) {
    showOTPError('Masukkan 6 digit kode OTP');
    return;
  }
  const btn = document.getElementById('btnVerif');
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...';
  btn.disabled  = true;
  document.getElementById('otpForm').submit();
}

function showOTPError(msg) {
  boxes.forEach(b => b.classList.add('error'));
  otpErrMsg.textContent  = msg;
  otpError.style.display = '';
  setTimeout(() => boxes.forEach(b => b.classList.remove('error')), 600);
}

// Validasi manual saat tombol diklik
document.getElementById('btnVerif').addEventListener('click', function (e) {
  e.preventDefault();
  const otp = getOTP();
  if (otp.length < 6) {
    showOTPError('Masukkan 6 digit kode OTP terlebih dahulu');
    return;
  }
  updateHidden();
  submitOTP();
});

// Fokus ke box pertama yang kosong saat halaman load
boxes[0].focus();

// ════════════════════════════════════════════════════════════
// 2. COUNTDOWN TIMER
// ════════════════════════════════════════════════════════════
const TOTAL_DETIK  = {{ $expireSeconds }};
const circumference = 2 * Math.PI * 28; // r=28
const timerCircle   = document.getElementById('timerCircle');
const countNum      = document.getElementById('countdownNumber');
const resendBtn     = document.getElementById('btnResend');
const resendText    = document.getElementById('resendText');
const resendCount   = document.getElementById('resendCountdown');

let detikSisa = TOTAL_DETIK;

timerCircle.style.strokeDasharray  = circumference;
timerCircle.style.strokeDashoffset = 0;

const interval = setInterval(() => {
  detikSisa--;

  // Update angka
  // Update angka dalam format menit:detik
function formatWaktu(detik) {
    const m = Math.floor(detik / 60);
    const s = detik % 60;
    return m > 0
        ? `${m}:${String(s).padStart(2, '0')}`
        : `${s}`;
}

countNum.textContent    = detikSisa > 0 ? formatWaktu(detikSisa) : '0';
resendCount.textContent = detikSisa > 0 ? formatWaktu(detikSisa) : '0';

  // Update lingkaran SVG
  const progress = detikSisa / TOTAL_DETIK;
  timerCircle.style.strokeDashoffset = circumference * (1 - progress);

  // Warna berubah saat mendekati habis
  if (detikSisa <= 10) {
    timerCircle.style.stroke = 'var(--danger)';
    countNum.style.color     = 'var(--danger)';
  }

  if (detikSisa <= 0) {
    clearInterval(interval);
    countNum.textContent = '0';
    timerCircle.style.strokeDashoffset = circumference;

    // Aktifkan tombol kirim ulang
    resendBtn.disabled = false;
    resendText.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Kirim Ulang Kode OTP';
    resendBtn.style.opacity = '1';
  }
}, 1000);

// Nonaktifkan tombol kirim ulang selama timer masih jalan
resendBtn.style.opacity = '.5';
</script>
@endpush