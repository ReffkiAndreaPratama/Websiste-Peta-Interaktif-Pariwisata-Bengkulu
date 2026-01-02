<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dinas Pariwisata Kota Bengkulu')</title>

  <!-- Bootstrap & Icon -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

  <style>
    html, body { background-color: #f8fafc; overscroll-behavior: none; }
    .navbar {
      background: linear-gradient(90deg, #173f64 0%, #072e57 100%) !important;
      padding-top: .4rem; padding-bottom: .4rem;
    }
    .navbar::after {
      content: ''; position: fixed; top: 0; left: 0; right: 0;
      height: 1px;
      background: linear-gradient(90deg, #173f64 0%, #072e57 100%);
      z-index: 1030;
    }
    .nav-left { display: flex; align-items: center; gap: .5rem; }
    .nav-left img { height: 40px; width: auto; }
    .navbar-brand small { font-size: 12px; line-height: 1; }
    .nav-center .nav-link {
      color: #fff; opacity: .95;
      padding: .5rem 1rem; border-radius: .5rem;
      text-transform: uppercase; font-weight: 600;
      transition: .2s;
    }
    .nav-center .nav-link:hover,
    .nav-center .nav-link.active {
      color: #ffc107 !important; background: rgba(255,255,255,.12);
    }
    .nav-right .btn { border-radius: 999px; }
    main { padding-top: 5rem; }
    @media (max-width: 991.98px){ main { padding-top: 4.6rem; } }
  </style>

  @stack('head')
</head>
<!-- Logout Modal (APK Style, Tengah Layar) -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content text-center">

        <div class="modal-body py-4">
          <i class="bi bi-exclamation-circle text-warning fs-1 mb-3"></i>

          <h5 class="fw-bold mb-2">Keluar Website</h5>
          <p class="text-muted mb-4">
            Apakah Anda yakin ingin logout?
          </p>

          <div class="d-flex gap-2">
            <button type="button"
                    class="btn btn-outline-secondary w-100"
                    data-bs-dismiss="modal">
              Batal
            </button>

            <form method="POST" action="{{ route('logout') }}" class="w-100">
              @csrf
              <button type="submit" class="btn btn-danger w-100">
                Logout
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
  <div class="container-fluid px-3 px-lg-4">

    <!-- Logo & brand -->
    <div class="nav-left me-2 order-0">
      <img src="{{ asset('images/KotaBengkulu.png') }}" alt="Logo 1" class="me-1">
      <img src="{{ asset('images/Kemenparekraf.png') }}" alt="Logo 2" class="me-2">
      <a class="navbar-brand fw-bold text-uppercase d-flex flex-column lh-1 m-0" href="{{ route('home') }}">
        Dinas Pariwisata
        <small class="fw-normal text-light opacity-75">Kota Bengkulu</small>
      </a>
    </div>

    <button class="navbar-toggler border-0 ms-auto order-2" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Auth kanan (desktop) -->
    <div class="nav-right ms-2 order-3 d-none d-lg-flex align-items-center">
      @guest
        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3 me-2">Login</a>
        <a href="{{ route('register') }}" class="btn btn-warning text-primary btn-sm px-3 fw-bold">Sign Up</a>
      @endguest
      @auth
        <div class="dropdown">
          <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle fs-5 me-2"></i>
            <span class="fw-semibold">{{ Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            @if(Auth::user()->role === 'admin')
              <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                  <i class="bi bi-arrow-left-circle me-2 text-primary"></i> Kembali ke Admin
                </a>
              </li>
            @endif

            <!-- Divider -->
            <li><hr class="dropdown-divider"></li>

            <li>
              <form method="POST" action="{{ route('logout') }}" id="logout-form-main" class="d-none">@csrf</form>
              <button type="button"
                      class="dropdown-item text-danger d-flex align-items-center"
                      data-bs-toggle="modal"
                      data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </button>

            </li>
          </ul>
        </div>
      @endauth
    </div>

    <!-- Menu tengah -->
    <div class="collapse navbar-collapse justify-content-center order-1 order-lg-2" id="navMain">
      <ul class="navbar-nav nav-center mb-2 mb-lg-0">
        @if(Auth::check() && Auth::user()->role === 'admin')
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.preview.home') ? 'active' : '' }}" href="{{ route('admin.preview.home') }}">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.preview.peta') ? 'active' : '' }}" href="{{ route('admin.preview.peta') }}">Peta Interaktif</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.preview.destinasi') ? 'active' : '' }}" href="{{ route('admin.preview.destinasi') }}">Destinasi</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.preview.about') ? 'active' : '' }}" href="{{ route('admin.preview.about') }}">About Us</a></li>
        @else
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('peta') ? 'active' : '' }}" href="{{ route('peta') }}">Peta Interaktif</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('destinasi') ? 'active' : '' }}" href="{{ route('destinasi') }}">Destinasi</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a></li>
        @endif

        <!-- Auth (mobile) -->
        <li class="nav-item d-lg-none mt-2">
          @guest
            <div class="d-flex gap-2">
              <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm flex-fill">Login</a>
              <a href="{{ route('register') }}" class="btn btn-warning text-primary btn-sm fw-bold flex-fill">Sign Up</a>
            </div>
          @endguest
          @auth
            <div class="dropdown mt-2">
              <a class="btn btn-outline-light btn-sm w-100 dropdown-toggle" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                @if(Auth::user()->role === 'admin')
                  <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                      <i class="bi bi-arrow-left-circle me-2 text-primary"></i> Kembali ke Admin
                    </a>
                  </li>
                @endif

                <!-- Divider -->
                <li><hr class="dropdown-divider"></li>

                <li>
                  <form method="POST" action="{{ route('logout') }}" id="logout-form-main-sm" class="d-none">@csrf</form>
                  <button type="button"
                          class="dropdown-item text-danger d-flex align-items-center"
                          data-bs-toggle="modal"
                          data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                  </button>

                </li>
              </ul>
            </div>
          @endauth
        </li>
      </ul>
    </div>

  </div>
</nav>

<main class="py-4">@yield('content')</main>

<footer class="bg-dark text-white py-4 mt-5 text-center">
  &copy; {{ date('Y') }} Dinas Pariwisata Kota Bengkulu — Semua hak cipta dilindungi.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>



@stack('scripts')
</body>
</html>
