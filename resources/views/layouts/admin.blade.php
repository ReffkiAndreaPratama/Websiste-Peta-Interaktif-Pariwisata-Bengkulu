<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - @yield('title')</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons & Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --sidebar-width: 250px;
      --brand-grad: linear-gradient(90deg, #173f64 0%, #072e57 100%);
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8fafc;
    }

    /* ===== Sidebar ===== */
    .active-clean {
        background: transparent !important;
        color: #000 !important;
        font-weight: normal !important;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: var(--brand-grad);
      color: #fff;
      padding-top: 1rem;
      overflow-y: auto;
      z-index: 1050;
      transform: translateX(0);
      transition: transform .3s ease;
      will-change: transform;
    }
    .sidebar .nav-link {
      color: #e0f7fa;
      margin: 5px 10px;
      border-radius: 8px;
      padding: 10px 15px;
      transition: background .2s ease;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background: rgba(255,255,255,.2);
      color: #fff;
    }

    /* ===== Topbar ===== */
    .topbar {
      position: fixed;
      top: 0;
      left: var(--sidebar-width);
      right: 0;
      z-index: 1030;
      background: #ffffff;
      box-shadow: 0 1px 4px rgba(0,0,0,.1);
      transition: left .3s ease;
    }

    .topbar .brandbar {
      background: var(--brand-grad);
      color: #fff;
      padding: .1rem 1rem;
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .topbar .brandbar .btn-toggle {
      border: 0;
      background: transparent;
      color: #fff;
      padding: .25rem .5rem;
      display: inline-flex;
      align-items: center;
    }

    /* ===== Konten ===== */
    .content {
      margin-left: var(--sidebar-width);
      padding: 90px 30px 30px;
      transition: margin-left .3s ease;
    }

    /* ===== STATE: sidebar collapsed ===== */
    body.sidebar-collapsed .sidebar {
      transform: translateX(calc(-100% + 12px)); /* sisakan strip */
    }

    body.sidebar-collapsed .topbar {
      left: 12px; /* geser sesuai strip */
    }

    body.sidebar-collapsed .content {
      margin-left: 12px;
    }

    /* Handle (strip) simbol */
    .sidebar-collapsed-handle {
      position: fixed;
      top: 60px;
      left: 0;
      width: 12px;
      height: 50px;
      background: rgba(0,0,0,0.2);
      border-radius: 0 6px 6px 0;
      display: none;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      backdrop-filter: blur(2px);
      z-index: 1201;
    }

    body.sidebar-collapsed .sidebar-collapsed-handle {
      display: flex;
    }

    .sidebar-collapsed-handle i {
      font-size: 10px;
      color: #fff;
    }

    /* ===== Responsive ===== */
    @media (max-width: 991.98px) {
      .topbar { left: 0; }
      .content { margin-left: 0; padding-top: 90px; }
      .sidebar { transform: translateX(-100%); }
      body.sidebar-open .sidebar { transform: translateX(0); }
      .sidebar-overlay {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.35);
        z-index: 1040;
        opacity: 0;
        pointer-events: none;
        transition: opacity .2s ease;
      }
      body.sidebar-open .sidebar-overlay {
        opacity: 1;
        pointer-events: auto;
      }
    }
         footer.footer-custom {
          flex-shrink: 0;
          width: 100%;
          font-size: 14px;
          border-top: 1px solid rgba(255,255,255,0.15);
          margin-top: auto !important;
      }

  </style>

  @stack('head')
</head>

<body class="sidebar-collapsed">

  <!-- Strip sidebar (simbol) -->
  <div class="sidebar-collapsed-handle" id="sidebarHandle">
    <i class="bi bi-chevron-right"></i>
  </div>

  <!-- Sidebar -->
  <aside class="sidebar" id="adminSidebar">
    <div class="text-center mb-4">
      <div class="container py-2">
        <img src="{{ asset('images/KotaBengkulu.png') }}" alt="Logo 1" class="me-2" style="height: 40px; width: auto;">
        <img src="{{ asset('images/Kemenparekraf.png') }}" alt="Logo 2" class="me-2" style="height: 40px; width: auto;">
        <a class="navbar-brand fw-bold text-uppercase d-flex flex-column lh-1 text-white text-decoration-none mt-2" href="{{ route('home') }}">
          Dinas Pariwisata
          <small class="fw-normal text-light opacity-75" style="font-size: 12px;">Kota Bengkulu</small>
        </a>
      </div>
    </div>

    <nav class="nav flex-column w-100">
      <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
      </a>
      <a class="nav-link {{ request()->routeIs('admin.peta') ? 'active' : '' }}" href="{{ route('admin.peta') }}">
        <i class="bi bi-geo-alt-fill me-2"></i>Kelola Peta Interaktif
      </a>
      <a class="nav-link {{ request()->routeIs('admin.destinasi*') ? 'active' : '' }}" href="{{ route('admin.destinasi') }}">
        <i class="bi bi-map-fill me-2"></i>Kelola Destinasi
      </a>
      <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
        <i class="bi bi-people-fill me-2"></i>Kelola Pengguna
      </a>
    </nav>

    <div class="mt-auto p-3 text-center w-100">
      <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="btn btn-sm w-100" style="background-color: #ffc107; color: #000;" onclick="confirmLogout()">
          <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
      </form>
    </div>
  </aside>

  <!-- Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay" hidden></div>

  <!-- Topbar -->
  <header class="topbar">
    <div class="brandbar">
      <button class="btn-toggle" id="btnToggleSidebar" aria-label="Toggle sidebar">
        <i class="bi bi-list fs-3"></i>
      </button>
      <span class="navbar-brand fw-bold fs-5 ms-1">Panel Admin</span>

      <ul class="navbar-nav ms-auto flex-row align-items-center">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2 fs-5"></i>
            {{ Auth::user()->name ?? 'Admin' }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="adminDropdown" style="border-radius: 10px;">
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.preview.home') }}">
                <i class="bi bi-eye me-2"></i> Preview
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}" id="logoutFormTop" class="d-none">@csrf</form>
              <button type="button" class="dropdown-item text-danger d-flex align-items-center" onclick="confirmLogout('logoutFormTop')">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </button>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </header>

  <!-- Konten -->
  <main class="content" id="mainContent">
    <div class="container-fluid">
      <div class="row g-4">
        <div class="col-12 col-lg-9">
          @yield('content')
        </div>
        <div class="col-12 col-lg-3">
          <div class="position-sticky" style="top: 88px;">
            <div class="card shadow-sm border-0 rounded-4 mb-3">
              <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold text-primary">
                  <i class="bi bi-lightning-charge-fill me-1 text-primary"></i> Akses Cepat
                </h6>
              </div>
              <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                  <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard
                </a>
                <a href="{{ route('admin.destinasi') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('admin.destinasi') ? 'active-clean' : '' }}">
                  <i class="bi bi-map me-2 text-primary"></i> Kelola Destinasi
                </a>
                <a href="{{ route('admin.peta') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('admin.peta') ? 'active-clean' : '' }}">
                  <i class="bi bi-geo-alt me-2 text-primary"></i> Daftar Peta Interaktif
                </a>
                <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('admin.users') ? 'active-clean' : '' }}">
                  <i class="bi bi-people me-2 text-primary"></i> Kelola Pengguna
                </a>
                <a href="{{ route('home') }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
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
      </div>
    </div>
  </main>
  < <!-- ===== FOOTER PAS DI BAWAH ===== -->
    <footer class="footer-custom bg-dark text-white text-center py-3">
        <div class="container">
            <small>
                &copy; {{ date('Y') }} Dinas Pariwisata Kota Bengkulu — Semua hak cipta dilindungi.
            </small>
        </div>
    </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const btnToggle = document.getElementById('btnToggleSidebar');
    const bodyEl    = document.body;
    const overlay   = document.getElementById('sidebarOverlay');
    const sidebarHandle = document.getElementById('sidebarHandle');

    function isMobile(){
      return window.matchMedia('(max-width: 991.98px)').matches;
    }

    function openSidebarMobile(){
      bodyEl.classList.add('sidebar-open');
      overlay.hidden = false;
    }
    function closeSidebarMobile(){
      bodyEl.classList.remove('sidebar-open');
      overlay.hidden = true;
    }

    btnToggle.addEventListener('click', () => {
      if (isMobile()) {
        if (bodyEl.classList.contains('sidebar-open')) closeSidebarMobile();
        else openSidebarMobile();
      } else {
        bodyEl.classList.toggle('sidebar-collapsed');
      }
    });

    overlay?.addEventListener('click', closeSidebarMobile);

    // klik strip untuk membuka sidebar
    sidebarHandle.addEventListener('click', () => {
      if (!isMobile() && bodyEl.classList.contains('sidebar-collapsed')) {
        bodyEl.classList.remove('sidebar-collapsed');
      }
    });

    function confirmLogout(targetId = 'logoutForm'){
      const ok = confirm('Apakah Anda yakin ingin logout dari Panel Admin?');
      if (ok) document.getElementById(targetId).submit();
    }
    window.confirmLogout = confirmLogout;
  </script>

  @stack('scripts')
</body>
</html>
