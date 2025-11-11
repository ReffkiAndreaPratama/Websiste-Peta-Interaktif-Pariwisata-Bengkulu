@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Statistik Utama -->
    <div class="row g-4 justify-content-center">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100 bg-gradient-blue text-white">
                <i class="bi bi-geo-alt-fill display-4 mb-3"></i>
                <h6 class="fw-semibold text-uppercase mb-1 opacity-75">Total Destinasi</h6>
                <h3 class="fw-bold">{{ $totalDestinasi ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100 bg-gradient-green text-white">
                <i class="bi bi-people-fill display-4 mb-3"></i>
                <h6 class="fw-semibold text-uppercase mb-1 opacity-75">Pengguna Terdaftar</h6>
                <h3 class="fw-bold">{{ $totalUsers ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100 bg-gradient-orange text-white">
                <i class="bi bi-chat-left-text-fill display-4 mb-3"></i>
                <h6 class="fw-semibold text-uppercase mb-1 opacity-75">Total Review</h6>
                <h3 class="fw-bold">{{ $totalReview ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm rounded-4 p-4 text-center h-100 bg-gradient-purple text-white">
                <i class="bi bi-person-badge-fill display-4 mb-3"></i>
                <h6 class="fw-semibold text-uppercase mb-1 opacity-75">Admin Aktif</h6>
                <h3 class="fw-bold">{{ $adminAktif ?? 1 }}</h3>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-clock-history me-2"></i> Aktivitas Terbaru
                    </h5>
                    <a href="#" class="text-decoration-none small text-primary fw-semibold">Lihat Semua</a>
                </div>

                <ul class="list-group list-group-flush">
                    @forelse($activities ?? [] as $activity)
                        <li class="list-group-item d-flex align-items-center border-0">
                            <div class="icon bg-light-primary text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                            <span class="text-secondary small">{{ $activity }}</span>
                        </li>
                    @empty
                        <li class="list-group-item border-0 text-center text-muted fst-italic py-3">
                            Belum ada aktivitas terbaru.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan gaya khusus -->
<style>
    /* Gradien untuk card */
    .bg-gradient-blue { background: linear-gradient(135deg, #0077b6, #00b4d8); }
    .bg-gradient-green { background: linear-gradient(135deg, #2ec4b6, #0b8457); }
    .bg-gradient-orange { background: linear-gradient(135deg, #ff9f43, #ff6b6b); }
    .bg-gradient-purple { background: linear-gradient(135deg, #6a11cb, #2575fc); }

    /* Card statistik */
    .stat-card {
        transition: all 0.4s ease;
        cursor: pointer;
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }

    /* Aktivitas terbaru */
    .list-group-item:hover {
        background-color: #f1f5f9;
        transition: 0.3s;
    }

    .icon {
        font-size: 1.2rem;
    }
</style>
@endsection
