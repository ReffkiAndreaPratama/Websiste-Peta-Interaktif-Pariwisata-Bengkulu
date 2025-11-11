@extends('layouts.admin')
@section('title', 'Kelola Pengguna')

@section('content')
<div class="container mt-4">
  <h3 class="fw-bold mb-4">Kelola Pengguna</h3>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- FORM TAMBAH --}}
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Pengguna Baru</div>
    <div class="card-body">
      <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6 mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
              <option value="">-- Pilih Role --</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
        </div>

        <button class="btn btn-success">Tambah</button>
      </form>
    </div>
  </div>

  {{-- FILTER & PENCARIAN --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <form action="{{ route('admin.user.index') }}" method="GET" class="d-flex w-100 gap-2">
      <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
      <select name="role" class="form-select w-auto">
        <option value="">Semua Role</option>
        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
      </select>
      <button class="btn btn-dark">Filter</button>
    </form>
  </div>

  {{-- TABEL PENGGUNA --}}
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">Daftar Pengguna</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Dibuat Pada</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $u)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>
              <span class="badge {{ $u->role == 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                {{ ucfirst($u->role) }}
              </span>
            </td>
            <td>{{ $u->created_at->format('d M Y') }}</td>
            <td>
              <!-- Tombol Edit -->
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $u->id }}">Edit</button>

              <!-- Tombol Hapus -->
              <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus pengguna ini?')">Hapus</button>
              </form>
            </td>
          </tr>

          <!-- Modal Edit -->
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
                      <label>Nama</label>
                      <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label>Email</label>
                      <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                    </div>
                    <div class="mb-3">
                      <label>Role</label>
                      <select name="role" class="form-control" required>
                        <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ $u->role == 'user' ? 'selected' : '' }}>User</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label>Password Baru (Opsional)</label>
                      <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
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
          <tr>
            <td colspan="6" class="text-center text-muted">Belum ada pengguna</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
