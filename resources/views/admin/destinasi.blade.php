@extends('layouts.admin')
@section('title', 'Kelola Destinasi Wisata')

@section('content')
<div class="container mt-4">
  <h3 class="fw-bold mb-4">Kelola Destinasi</h3>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- FORM TAMBAH --}}
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Destinasi Baru</div>
    <div class="card-body">
      <form action="{{ route('admin.destinasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-6 mb-3">
            <label>Nama Destinasi</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label>Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label>Latitude</label>
            <input type="text" name="latitude" class="form-control" placeholder="-3.7924">
          </div>
          <div class="col-md-6 mb-3">
            <label>Longitude</label>
            <input type="text" name="longitude" class="form-control" placeholder="102.2655">
          </div>
        </div>

        <div class="mb-3">
          <label>Gambar</label>
          <input type="file" name="gambar" class="form-control">
        </div>

        <button class="btn btn-success">Tambah</button>
      </form>
    </div>
  </div>

  {{-- TABEL DESTINASI --}}
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">Daftar Destinasi</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Koordinat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($destinasi as $d)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              @if($d->gambar)
                <img src="{{ asset('storage/'.$d->gambar) }}" width="80" class="rounded">
              @else
                <small class="text-muted">Tidak ada</small>
              @endif
            </td>
            <td>{{ $d->nama }}</td>
            <td>{{ $d->kategori }}</td>
            <td>{{ Str::limit($d->deskripsi, 60) }}</td>
            <td>{{ $d->latitude ?? '-' }}, {{ $d->longitude ?? '-' }}</td>
            <td>
              <!-- Tombol Edit -->
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $d->id }}">Edit</button>

              <!-- Tombol Hapus -->
              <form action="{{ route('admin.destinasi.destroy', $d->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus destinasi ini?')">Hapus</button>
              </form>
            </td>
          </tr>

          <!-- Modal Edit -->
          <div class="modal fade" id="edit{{ $d->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form action="{{ route('admin.destinasi.update', $d->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Destinasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label>Nama</label>
                      <input type="text" name="nama" class="form-control" value="{{ $d->nama }}" required>
                    </div>
                    <div class="mb-3">
                      <label>Kategori</label>
                      <input type="text" name="kategori" class="form-control" value="{{ $d->kategori }}" required>
                    </div>
                    <div class="mb-3">
                      <label>Deskripsi</label>
                      <textarea name="deskripsi" class="form-control" rows="4" required>{{ $d->deskripsi }}</textarea>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label>Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ $d->latitude }}">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label>Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ $d->longitude }}">
                      </div>
                    </div>
                    <div class="mb-3">
                      <label>Gambar (Opsional)</label>
                      <input type="file" name="gambar" class="form-control">
                      @if($d->gambar)
                        <img src="{{ asset('storage/'.$d->gambar) }}" class="mt-2 rounded" width="120">
                      @endif
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
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
