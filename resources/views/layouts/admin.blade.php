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

    <!-- Custom Style -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: linear-gradient(90deg, #173f64 0%, #072e57 100%);
            color: #fff;
            padding-top: 1rem;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: #e0f7fa;
            margin: 5px 10px;
            border-radius: 8px;
            padding: 10px 15px;
            transition: background 0.3s;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .navbar {
            margin-left: 250px;
            background: #ffffff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .dashboard-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .dashboard-stat i {
            font-size: 2rem;
            color: #00b4d8;
        }

        .dashboard-stat h5 {
            font-weight: 600;
            font-size: 1rem;
        }

        .dashboard-stat span {
            font-size: 1.3rem;
            font-weight: bold;
            color: #0077b6;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column align-items-center">
        <div class="text-center mb-4">
            <div class="container py-2">
                <!-- Logo pertama -->
                <img src="{{ asset('images/KotaBengkulu.png') }}" alt="Logo 1" class="me-2" style="height: 40px; width: auto;">
                <!-- Logo kedua -->
                <img src="{{ asset('images/Kemenparekraf.png') }}" alt="Logo 2" class="me-2" style="height: 40px; width: auto;">
                <!-- Teks -->
                <a class="navbar-brand fw-bold text-uppercase d-flex flex-column lh-1 text-white text-decoration-none mt-2" href="{{ route('home') }}">
                    Dinas Pariwisata
                    <small class="fw-normal text-light opacity-75" style="font-size: 12px;">Kota Bengkulu</small>
                </a>
            </div>
        </div>

        <!-- Navigasi Sidebar -->
        <nav class="nav flex-column w-100">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>

            <a class="nav-link {{ request()->routeIs('admin.peta') ? 'active' : '' }}" href="{{ route('admin.peta') }}">
            <i class="bi bi-geo-alt-fill me-2"></i>Kelola Peta
            </a>

            <a class="nav-link {{ request()->routeIs('admin.destinasi*') ? 'active' : '' }}"
            href="{{ route('admin.destinasi') }}">
            <i class="bi bi-map-fill me-2"></i>Kelola Destinasi
            </a>
            
            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
            <i class="bi bi-people-fill me-2"></i>Kelola Pengguna
            </a>

        </nav>

        <!-- Tombol Logout -->
        <div class="mt-auto p-3 text-center w-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
               <button type="submit" class="btn btn-sm w-100" style="background-color: #ffc107; color: #000;">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>

            </form>
        </div>
    </div>

    <!-- Navbar Atas -->
  <!-- Navbar Atas -->
<nav class="navbar navbar-expand-lg" style="margin-left: 250px; background: linear-gradient(90deg, #173f64 0%, #072e57 100%); box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
    <div class="container-fluid">
        <!-- Teks Dashboard Admin di kiri -->
        <span class="navbar-brand fw-bold text-white fs-5">Panel Admin</span>

        <!-- Bagian user dropdown di kanan -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2 fs-4"></i>
                    {{ Auth::user()->name ?? 'Admin' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="adminDropdown" style="border-radius: 10px;">
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.preview.home') }}">
                            <i class="bi bi-eye me-2"></i> Preview
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>




    <!-- Main Content -->
    <main class="content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
