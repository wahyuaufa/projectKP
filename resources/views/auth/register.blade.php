{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title', 'Daftar Akun - PR GOTRAV')
@section('content')
<section class="auth-section">
  <div class="container">
    <div class="auth-card" style="max-width:500px" data-aos="fade-up">
      <div class="auth-logo">
        <div class="logo-icon"><i class="bi bi-person-plus-fill"></i></div>
        <div class="auth-title">Buat Akun Baru</div>
        <p class="text-muted" style="font-size:.88rem;margin-top:.3rem">Registrasi mudah dan cepat menggunakan No. WhatsApp</p>
      </div>

      @if($errors->any())
      <div class="alert-gotrav alert-gotrav-danger mb-3 p-3 rounded-2">
        <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label-gotrav">Nama Lengkap</label>
          <div class="input-icon-wrap">
            <i class="bi bi-person input-icon"></i>
            <input type="text" name="nama_lengkap" class="form-control-gotrav"
                   placeholder="Masukkan nama lengkap" value="{{ old('nama_lengkap') }}" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label-gotrav">Jenis Kelamin</label>
          <div class="d-flex gap-3">
            <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.9rem">
              <input type="radio" name="jenis_kelamin" value="laki-laki"
                     {{ old('jenis_kelamin', 'laki-laki') === 'laki-laki' ? 'checked' : '' }}>
              <i class="bi bi-gender-male" style="color:var(--primary)"></i> Laki-laki
            </label>
            <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.9rem">
              <input type="radio" name="jenis_kelamin" value="perempuan"
                     {{ old('jenis_kelamin') === 'perempuan' ? 'checked' : '' }}>
              <i class="bi bi-gender-female" style="color:var(--danger)"></i> Perempuan
            </label>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label-gotrav">No. WhatsApp</label>
          <div class="input-icon-wrap">
            <i class="bi bi-whatsapp input-icon"></i>
            <input type="text" name="no_whatsapp" class="form-control-gotrav"
                   placeholder="08xxxxxxxxxx" value="{{ old('no_whatsapp') }}" required>
          </div>
          <div style="font-size:.78rem;color:var(--muted);margin-top:.3rem">
            Nomor WhatsApp akan digunakan untuk login dan komunikasi
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label-gotrav">Password</label>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="form-control-gotrav"
                   placeholder="Minimal 6 karakter" required>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label-gotrav">Konfirmasi Password</label>
          <div class="input-icon-wrap">
            <i class="bi bi-lock-fill input-icon"></i>
            <input type="password" name="password_confirmation" class="form-control-gotrav"
                   placeholder="Ulangi password" required>
          </div>
        </div>

        <button type="submit" class="btn-whatsapp mb-3">
          <i class="bi bi-whatsapp"></i> Daftar dengan WhatsApp
        </button>

        <div class="text-center" style="font-size:.88rem;color:var(--muted)">
          Sudah punya akun?
          <a href="{{ route('login') }}" style="font-weight:700;color:var(--primary)">Masuk di sini</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
