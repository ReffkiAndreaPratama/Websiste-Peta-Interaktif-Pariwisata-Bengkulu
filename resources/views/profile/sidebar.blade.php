<div class="col-lg-3">
    <div class="position-sticky" style="top: 88px;">

        <div class="card shadow-sm border-0 rounded-4 mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-lightning-charge-fill me-1 text-primary"></i> Akses Cepat
                </h6>
            </div>

            <div class="list-group list-group-flush">

                <a href="{{ route('admin.dashboard') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard
                </a>

                <a href="{{ route('admin.destinasi') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('admin.destinasi') ? 'active-clean' : '' }}">
                    <i class="bi bi-map me-2 text-primary"></i>
                    <span class="text-dark">Kelola Destinasi</span>
                </a>

                <a href="{{ route('admin.peta') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('admin.peta') ? 'active-clean' : '' }}">
                    <i class="bi bi-geo-alt me-2 text-primary"></i> Kelola Peta Interaktif
                </a>

                <a href="{{ route('admin.users') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('admin.users') ? 'active-clean' : '' }}">
                    <i class="bi bi-people me-2 text-primary"></i> Kelola Pengguna
                </a>

                <a href="{{ route('home') }}" target="_blank"
                   class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-eye me-2 text-primary"></i> Preview
                </a>

            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-info-circle-fill me-1"></i> Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0 ps-3 small text-secondary">
                    <li class="mb-2">Gunakan kategori konsisten.</li>
                    <li class="mb-2">Koordinat opsional untuk marker.</li>
                    <li class="mb-2">Gambar ideal 1200×800 px.</li>
                    <li class="mb-2">Deskripsi ±120 karakter.</li>
                    <li>Cek halaman Preview setelah update.</li>
                </ul>
            </div>
        </div>

    </div>
</div>
