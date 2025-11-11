<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dinas Pariwisata Kota Bengkulu')</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Leaflet CSS (dipakai di halaman peta) -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <style>
    /* sedikit styling cepat sesuai mockup (biru laut, besar) */
    .hero {
      background: linear-gradient(180deg, #e8f5ff 0%, #ffffff 100%);
    }
    .brand-blue { color: #0b66c3; } /* warna biru utama */
  </style>

  @stack('head')
</head>
<body class="bg-light">

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#0b66c3;">
  <div class="container-fluid">
    <!-- Brand / Logo -->
    <a class="navbar-brand fw-bold" href="{{ route('home') }}">
      DINAS PARIWISATA 
      <small class="d-block" style="font-size:10px">KOTA BENGKULU</small>
    </a>

    <!-- Tombol toggle (untuk mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu kanan -->
    <div class="collapse navbar-collapse d-flex justify-content-end" id="navMain">
      <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('peta') ? 'active' : '' }}" href="{{ route('peta') }}">Peta Interaktif</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('destinasi') ? 'active' : '' }}" href="{{ route('destinasi') }}">Destinasi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
        </li>

        @guest
          <!-- Jika belum login -->
          <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
            <a class="btn btn-outline-light px-3 w-100 w-lg-auto" href="{{ route('login') }}">Login</a>
          </li>
          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <a class="btn btn-light text-primary px-3 w-100 w-lg-auto" href="{{ route('register') }}">Sign Up</a>
          </li>
        @endguest

        @auth
          <!-- Jika sudah login -->
          <li class="nav-item dropdown ms-lg-4 mt-2 mt-lg-0 me-0 ms-auto">
            <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-end text-end" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
              <li>
                <a class="dropdown-item" href="{{ route('dashboard') }}">
                  <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
              </li>

              @if(Auth::user()->role === 'admin')
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="{{ route('admin.destinasi') }}">
                    <i class="bi bi-geo-alt-fill me-2"></i>Kelola Destinasi
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('admin.users') }}">
                    <i class="bi bi-people-fill me-2"></i>Kelola Pengguna
                  </a>
                </li>
              @endif

              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                  </button>
                </form>
              </li>
            </ul>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
<!-- Konten utama -->
<main class="py-4">
  @yield('content')
</main>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
  <div class="container text-center">
    &copy; {{ date('Y') }} Dinas Pariwisata Kota Bengkulu — Semua hak cipta dilindungi.
  </div>
</footer>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="{{ asset('js/custom.js') }}"></script>
@stack('scripts')

</body>
</html>
