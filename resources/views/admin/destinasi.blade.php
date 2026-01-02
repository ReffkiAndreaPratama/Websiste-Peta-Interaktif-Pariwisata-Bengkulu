@extends('layouts.admin')
@section('title', 'Kelola Destinasi Wisata')

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .active-clean {
        background: transparent !important;
        color: #000 !important;
        font-weight: normal !important;
    }

    .list-group-item {
        padding: 10px 14px;
        border: none !important;
    }

    .list-group-item:hover {
        background: #f5faff;
    }

    /* Membuat tabel lebih lebar */
    table.fullwide {
        min-width: 1400px;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="fw-bold mb-0">Kelola Destinasi</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">

        {{-- ======================================= --}}
        {{-- KONTEN KANAN FULL WIDTH --}}
        {{-- ======================================= --}}
        <div class="col-lg-12">

            {{-- ================= FORM TAMBAH ================= --}}
            <div id="formTambah" class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                    <span>Tambah Destinasi Baru</span>
                    <a href="#formTambah" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-down-circle me-1"></i> Fokus ke Form
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.destinasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Destinasi</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="kategori" class="form-control" placeholder="pantai, alam, kuliner, sejarah" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar</label>
                            <input type="file" name="gambar" class="form-control">
                        </div>

                        <button class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Tambah
                        </button>

                    </form>
                </div>
            </div>

            {{-- ================= TABEL DESTINASI ================= --}}
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Daftar Destinasi</div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle fullwide">

                            <thead class="table-dark">
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th style="width:140px">Gambar</th>
                                    <th style="width:200px">Nama</th>
                                    <th style="width:160px">Kategori</th>
                                    <th>Deskripsi</th>
                                    <th style="width:200px">Koordinat</th>
                                    <th style="width:180px">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($destinasi as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        @if($d->gambar)
                                            <img src="{{ asset('storage/'.$d->gambar) }}"
                                                 width="120"
                                                 class="rounded shadow-sm"
                                                 alt="Gambar {{ $d->nama }}">
                                        @else
                                            <small class="text-muted">Tidak ada</small>
                                        @endif
                                    </td>

                                    <td class="fw-semibold">{{ $d->nama }}</td>

                                    <td>
                                        <span class="badge bg-info text-dark">{{ $d->kategori }}</span>
                                    </td>

                                    <td style="max-width:420px">
                                        {{ Str::limit($d->deskripsi, 120) }}
                                    </td>

                                    <td>
                                        <b>Lat:</b> {{ $d->latitude ?? '-' }} <br>
                                        <b>Long:</b> {{ $d->longitude ?? '-' }}
                                    </td>

                                    <td class="text-center">

                                        {{-- Tombol edit --}}
                                        <button class="btn btn-warning btn-sm mb-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $d->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        {{-- Tombol hapus --}}
                                        <form action="{{ route('admin.destinasi.destroy', $d->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin hapus destinasi ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- ================= MODAL EDIT ================= --}}
                                <div class="modal fade" id="edit{{ $d->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <form action="{{ route('admin.destinasi.update', $d->id) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Edit Destinasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">

                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label>
                                                        <input type="text" name="nama" class="form-control" value="{{ $d->nama }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Kategori</label>
                                                        <input type="text" name="kategori" class="form-control" value="{{ $d->kategori }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi</label>
                                                        <textarea name="deskripsi" class="form-control" rows="4" required>{{ $d->deskripsi }}</textarea>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Latitude</label>
                                                            <input type="text" name="latitude" class="form-control" value="{{ $d->latitude }}">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Longitude</label>
                                                            <input type="text" name="longitude" class="form-control" value="{{ $d->longitude }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Gambar (Opsional)</label>
                                                        <input type="file" name="gambar" class="form-control">

                                                        @if($d->gambar)
                                                            <img src="{{ asset('storage/'.$d->gambar) }}" class="mt-2 rounded" width="120">
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                                                    <button class="btn btn-success">
                                                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                                                    </button>
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

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Tooltip
        const triggerList = [].slice.call(document.querySelectorAll('[data-bs-title]'));
        triggerList.forEach(el => new bootstrap.Tooltip(el));

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

    });
</script>
@endpush
