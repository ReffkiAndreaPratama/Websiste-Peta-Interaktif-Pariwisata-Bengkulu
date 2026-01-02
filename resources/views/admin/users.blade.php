@extends('layouts.admin')
@section('title', 'Kelola Pengguna')

@section('content')
<div class="container mt-4">

  <h3 class="fw-bold mb-4">Kelola Pengguna</h3>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif


  {{-- ================= FORM TAMBAH ================= --}}
  <div class="card mb-4 shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white">Tambah Pengguna Baru</div>
    <div class="card-body">
      <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="fw-semibold">Nama</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="fw-semibold">Role</label>
            <select name="role" class="form-control" required>
              <option value="">-- Pilih Role --</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
        </div>

        <button class="btn btn-success px-4">Tambah</button>
      </form>
    </div>
  </div>




  {{-- ================= FILTER PENCARIAN ================= --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <form action="{{ route('admin.user.index') }}" method="GET" class="d-flex w-100 gap-2">
      <input type="text" name="search" class="form-control"
             placeholder="Cari nama atau email..."
             value="{{ request('search') }}">

      <select name="role" class="form-select w-auto">
        <option value="">Semua Role</option>
        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
      </select>

      <button class="btn btn-dark">Filter</button>
    </form>
  </div>




  {{-- ================= DAFTAR PENGGUNA (CARD GRID) ================= --}}
  <div class="card shadow-sm border-0 rounded-4 mt-4">
    <div class="card-header bg-dark text-white fw-semibold">
      Daftar Pengguna
    </div>

    <div class="card-body">
      <div class="row g-4">

        @forelse($users as $u)
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0 rounded-4">

            <div class="card-body text-center">

              <!-- Avatar -->
              <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                   style="width: 80px; height: 80px; background: #e3f2fd; font-size: 34px; font-weight: bold;">
                {{ strtoupper(substr($u->name, 0, 1)) }}
              </div>

              <h5 class="fw-bold mb-0">{{ $u->name }}</h5>
              <small class="text-muted d-block">{{ $u->email }}</small>

              <span class="badge mt-2 px-3 py-2
                {{ $u->role == 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                {{ ucfirst($u->role) }}
              </span>

              <p class="text-muted mt-2 mb-0" style="font-size: 0.85rem;">
                Dibuat: {{ $u->created_at->format('d M Y') }}
              </p>

            </div>

            <div class="card-footer bg-white text-center border-0 pb-3">

              <!-- Tombol Edit -->
              <button class="btn btn-warning btn-sm px-3"
                      data-bs-toggle="modal"
                      data-bs-target="#edit{{ $u->id }}">
                Edit
              </button>

              <!-- Tombol Hapus -->
              <form action="{{ route('admin.user.destroy', $u->id) }}"
                    method="POST"
                    class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm px-3"
                        onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                  Hapus
                </button>
              </form>

            </div>

          </div>
        </div>



        {{-- =============== MODAL EDIT =============== --}}
        <div class="modal fade" id="edit{{ $u->id }}" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">

              <form action="{{ route('admin.user.update', $u->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title">Edit Pengguna</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                  <div class="mb-3">
                    <label class="fw-semibold">Nama</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ $u->name }}" required>
                  </div>

                  <div class="mb-3">
                    <label class="fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ $u->email }}" required>
                  </div>

                  <div class="mb-3">
                    <label class="fw-semibold">Role</label>
                    <select name="role" class="form-control" required>
                      <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                      <option value="user" {{ $u->role == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="fw-semibold">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="Kosongkan jika tidak diubah">
                  </div>

                </div>

                <div class="modal-footer">
                  <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button class="btn btn-success">Simpan Perubahan</button>
                </div>

              </form>

            </div>
          </div>
        </div>

        @empty

        <p class="text-center text-muted mt-3">Belum ada pengguna.</p>

        @endforelse

      </div>
    </div>
  </div>

</div>
@endsection
