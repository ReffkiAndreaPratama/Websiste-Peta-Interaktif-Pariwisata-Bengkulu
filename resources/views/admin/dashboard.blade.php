@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-3 px-3">

    <!-- ====== Header ====== -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary mb-0">Halo, Admin!</h3>
    </div>

    <div class="row g-3">

        <!-- ==================== Statistik ==================== -->
        <div class="col-12">
            <div class="row g-3">

                <!-- Total Destinasi -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ route('admin.destinasi') }}" class="text-decoration-none">
                        <div class="stat-card glass shadow-sm rounded-4 p-4 h-100 d-flex align-items-center">
                            <div class="stat-icon bg-soft-primary text-primary me-3">
                                <i class="bi bi-map-fill"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-semibold text-uppercase">Total Destinasi</div>
                                <div class="h2 mb-0 text-dark fw-bold">{{ $totalDestinasi ?? 0 }}</div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Pengguna Terdaftar -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ route('admin.users') }}" class="text-decoration-none">
                        <div class="stat-card glass shadow-sm rounded-4 p-4 h-100 d-flex align-items-center">
                            <div class="stat-icon bg-soft-success text-success me-3">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-semibold text-uppercase">Pengguna Terdaftar</div>
                                <div class="h2 mb-0 text-dark fw-bold">{{ $totalUsers ?? 0 }}</div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Total Review -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="stat-card glass shadow-sm rounded-4 p-4 h-100 d-flex align-items-center">
                        <div class="stat-icon bg-soft-warning text-warning me-3">
                            <i class="bi bi-chat-left-text-fill"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-semibold text-uppercase">Total Review</div>
                            <div class="h2 mb-0 text-dark fw-bold">{{ $totalReview ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <!-- Admin Aktif -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="stat-card glass shadow-sm rounded-4 p-4 h-100 d-flex align-items-center">
                        <div class="stat-icon bg-soft-purple text-purple me-3">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-semibold text-uppercase">Admin Aktif</div>
                            <div class="h2 mb-0 text-dark fw-bold">{{ $adminAktif ?? 1 }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ==================== Aktivitas Terbaru ==================== -->
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-primary mb-0">
                        <i class="bi bi-clock-history me-2"></i> Aktivitas Terbaru
                    </h5>
                </div>

                <div class="card-body px-4 pb-4 pt-0">

                    @if(($activities ?? collect())->isNotEmpty())
                        <ul class="timeline list-unstyled">

                            @foreach($activities as $activity)
                                @php
                                    $parts = explode('•', $activity, 2);
                                    $text  = trim($parts[0] ?? $activity);
                                    $when  = trim($parts[1] ?? '');
                                @endphp

                                <li class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <div class="fw-semibold">{{ $text }}</div>
                                        @if($when)
                                            <div class="small text-muted">{{ $when }}</div>
                                        @endif
                                    </div>
                                </li>

                            @endforeach

                        </ul>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inboxes mb-3" style="font-size: 2.5rem; color:#9aa7b2"></i>
                            <div class="text-muted">Belum ada aktivitas terbaru.</div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<!-- ==================== Styles ==================== -->
<style>
    body {
        background: #f4f7fb !important;
    }

    .glass {
        background: linear-gradient(135deg, rgba(255, 255, 255, .9), rgba(255, 255, 255, 1));
        border: 1px solid rgba(0, 0, 0, .05);
    }

    .stat-card {
        min-height: 115px;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        font-size: 1.8rem;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-soft-primary { background: rgba(11,102,195,.12); }
    .bg-soft-success { background: rgba(46,196,182,.12); }
    .bg-soft-warning { background: rgba(255,159,67,.12); }
    .bg-soft-purple  { background: rgba(106,17,203,.12); }
    .text-purple     { color: #6a11cb; }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 20px;
    }
    .timeline::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e3e6ea;
    }
    .timeline-item {
        position: relative;
        padding: 15px 0 15px 12px;
    }
    .timeline-item:not(:last-child) {
        border-bottom: 1px dashed #e6e9ed;
    }
    .timeline-dot {
        position: absolute;
        left: 2px;
        top: 20px;
        width: 10px;
        height: 10px;
        background: #0b66c3;
        border-radius: 50%;
        box-shadow: 0 0 0 4px rgba(11,102,195,.15);
    }
</style>

@endsection
