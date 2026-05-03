{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
@section('title', 'Login - PR GOTRAV')
@section('content')
<section class="auth-section">
  <div class="container">
    <div class="auth-card" data-aos="fade-up">
      <div class="auth-logo">
        <div class="logo-icon"><i class="bi bi-bus-front-fill"></i></div>
        <div class="auth-title">Masuk ke Akun Anda</div>
        <p class="text-muted" style="font-size:.88rem;margin-top:.3rem">Masuk untuk melanjutkan pemesanan</p>
      </div>

      @if($errors->any())
      <div class="alert-gotrav alert-gotrav-danger mb-3 p-3 rounded-2 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label-gotrav">No. WhatsApp</label>
          <div class="input-icon-wrap">
            <i class="bi bi-whatsapp input-icon"></i>
            <input type="text" name="no_whatsapp" class="form-control-gotrav"
                   placeholder="08xxxxxxxxxx" value="{{ old('no_whatsapp') }}" required>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label-gotrav">Password</label>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="form-control-gotrav" placeholder="••••••••" required>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <label class="d-flex align-items-center gap-2" style="font-size:.88rem;cursor:pointer">
            <input type="checkbox" name="remember" class="form-check-input m-0"> Ingat saya
          </label>
          <a href="#" style="font-size:.88rem;color:var(--primary)">Lupa password?</a>
        </div>

        <button type="submit" class="btn-gotrav btn w-100 btn-lg-custom mb-3">
          <i class="bi bi-box-arrow-in-right me-1"></i> Login
        </button>
      </form>

      <div class="text-center" style="font-size:.88rem;color:var(--muted)">
        Belum punya akun?
        <a href="{{ route('register') }}" style="font-weight:700;color:var(--primary)">Daftar dengan WhatsApp</a>
      </div>
    </div>
  </div>
</section>
@endsection


{{-- ============================================================
     resources/views/auth/register.blade.php  (append below)
     ============================================================ --}}
