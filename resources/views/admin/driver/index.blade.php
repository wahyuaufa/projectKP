{{-- resources/views/admin/driver/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Data Driver')
@section('page-title', 'Data Driver / PIC')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <p style="color:var(--muted);font-size:.88rem;margin:0">Kelola daftar driver dan PIC yang bertugas</p>
  <button class="abtn abtn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-lg"></i> Tambah Driver
  </button>
</div>

<div class="acard">
  <div style="overflow-x:auto">
    <table class="atable">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Driver</th>
          <th>No. WhatsApp</th>
          <th>No. Kendaraan</th>
          <th>Jenis Kendaraan</th>
          <th>Total Jadwal</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($drivers as $i => $d)
        <tr>
          <td style="color:var(--muted)">{{ $i+1 }}</td>
          <td><strong>{{ $d->nama }}</strong></td>
          <td>
            <a href="{{ $d->waLink() }}" target="_blank" class="abtn abtn-wa abtn-sm">
              <i class="bi bi-whatsapp"></i> {{ $d->no_whatsapp }}
            </a>
          </td>
          <td>{{ $d->no_kendaraan ?? '-' }}</td>
          <td>{{ $d->jenis_kendaraan ?? '-' }}</td>
          <td>{{ $d->jadwals_count }} jadwal</td>
          <td>
            @if($d->is_active)
            <span class="abadge abadge-selesai"><i class="bi bi-circle-fill" style="font-size:.5rem"></i>Aktif</span>
            @else
            <span class="abadge abadge-batal"><i class="bi bi-circle-fill" style="font-size:.5rem"></i>Nonaktif</span>
            @endif
          </td>
          <td>
            <button class="abtn abtn-outline abtn-sm" data-bs-toggle="modal"
                    data-bs-target="#modalEdit{{ $d->id }}">
              <i class="bi bi-pencil"></i>
            </button>
            <form method="POST" action="{{ route('admin.driver.destroy', $d->id) }}"
                  style="display:inline" onsubmit="return confirm('Hapus driver {{ $d->nama }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="abtn abtn-danger abtn-sm">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>

        {{-- Modal Edit --}}
        <div class="modal fade" id="modalEdit{{ $d->id }}" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:var(--radius);border:none">
              <form method="POST" action="{{ route('admin.driver.update', $d->id) }}">
                @csrf @method('PUT')
                <div class="modal-header">
                  <h6 class="modal-title" style="font-family:var(--font-head);font-weight:700">Edit Driver</h6>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="display:grid;gap:1rem">
                  <div>
                    <label class="aform-label">Nama</label>
                    <input type="text" name="nama" class="aform-control" value="{{ $d->nama }}" required>
                  </div>
                  <div>
                    <label class="aform-label">No. WhatsApp</label>
                    <input type="text" name="no_whatsapp" class="aform-control" value="{{ $d->no_whatsapp }}" required>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="aform-label">No. Kendaraan</label>
                      <input type="text" name="no_kendaraan" class="aform-control" value="{{ $d->no_kendaraan }}">
                    </div>
                    <div class="col-6">
                      <label class="aform-label">Jenis Kendaraan</label>
                      <input type="text" name="jenis_kendaraan" class="aform-control" value="{{ $d->jenis_kendaraan }}">
                    </div>
                  </div>
                  <div>
                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.88rem">
                      <input type="checkbox" name="is_active" value="1" class="form-check-input m-0"
                             {{ $d->is_active ? 'checked' : '' }}>
                      Driver aktif
                    </label>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="abtn abtn-outline" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="abtn abtn-primary">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        @empty
        <tr><td colspan="8" class="text-center" style="color:var(--muted);padding:3rem">Belum ada driver</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius);border:none">
      <form method="POST" action="{{ route('admin.driver.store') }}">
        @csrf
        <div class="modal-header" style="background:var(--primary);color:#fff;border:none">
          <h6 class="modal-title" style="font-family:var(--font-head);font-weight:700">
            <i class="bi bi-person-plus me-2"></i>Tambah Driver Baru
          </h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="display:grid;gap:1rem">
          <div>
            <label class="aform-label">Nama Lengkap *</label>
            <input type="text" name="nama" class="aform-control" placeholder="Budi Santoso" required>
          </div>
          <div>
            <label class="aform-label">No. WhatsApp *</label>
            <input type="text" name="no_whatsapp" class="aform-control" placeholder="08123456789" required>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <label class="aform-label">No. Kendaraan</label>
              <input type="text" name="no_kendaraan" class="aform-control" placeholder="B 1234 XYZ">
            </div>
            <div class="col-6">
              <label class="aform-label">Jenis Kendaraan</label>
              <input type="text" name="jenis_kendaraan" class="aform-control" placeholder="Toyota Innova">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="abtn abtn-outline" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="abtn abtn-primary"><i class="bi bi-check-lg"></i> Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
