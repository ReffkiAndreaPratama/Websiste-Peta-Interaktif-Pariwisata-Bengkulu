<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dinas Pariwisata Kota Bengkulu')</title>

  <!-- Bootstrap & Icon -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

  <style>
    .hero {
      background: linear-gradient(180deg, #e8f5ff 0%, #ffffff 100%);
    }
    .brand-blue { color: #0b66c3; }
    .navbar {
      padding-top: 0.2rem;
      padding-bottom: 0.2rem;
    }
    .navbar img { height: 30px; }
    .navbar-brand small {
      font-size: 10px; line-height: 1;
    }
  </style>

  @stack('head')
</head>

<body class="bg-light">

<!-- ======== NAVBAR ======== -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm"
     style="background: linear-gradient(90deg, #173f64ff 0%, #072e57ff 100%);">
  <div class="container py-2">

    <!-- Logo kiri -->
    <img src="{{ asset('images/KotaBengkulu.png') }}" alt="Logo 1" class="me-2" style="height: 40px; width: auto;">
    <img src="{{ asset('images/Kemenparekraf.png') }}" alt="Logo 2" class="me-2" style="height: 40px; width: auto;">

    <!-- Brand -->
    <a class="navbar-brand fw-bold text-uppercase d-flex flex-column lh-1" href="{{ route('home') }}">
      Dinas Pariwisata
      <small class="fw-normal text-light opacity-75" style="font-size: 12px;">Kota Bengkulu</small>
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Utama -->
    <div class="collapse navbar-collapse justify-content-center" id="navMain">
      <ul class="navbar-nav mb-2 mb-lg-0 text-uppercase fw-semibold">

        @if(Auth::check() && Auth::user()->role === 'admin')
          <!-- Admin Preview Menu -->
          <li class="nav-item mx-1">
            <a class="nav-link px-3 {{ request()->routeIs('admin.preview.home') ? 'active text-warning' : 'text-white' }}" href="{{ route('admin.preview.home') }}">Dashboard (Preview)</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link px-3 {{ request()->routeIs('admin.preview.peta') ? 'active text-warning' : 'text-white' }}" href="{{ route('admin.preview.peta') }}">Peta Interaktif (Preview)</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link px-3 {{ request()->routeIs('admin.preview.destinasi') ? 'active text-warning' : 'text-white' }}" href="{{ route('admin.preview.destinasi') }}">Destinasi (Preview)</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link px-3 {{ request()->routeIs('admin.preview.about') ? 'active text-warning' : 'text-white' }}" href="{{ route('admin.preview.about') }}">About Us (Preview)</a>
          </li>
        @else
          <!-- Menu User -->
          <li class="nav-item mx-1">
            <a class="nav-link px-3 {{ request()->routeIs('home') ? 'active text-warning' : 'text-white' }}" href="{{ route('home') }}">Dashboard</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link px-3 {{ request()->routeIs('peta') ? 'active text-warning' : 'text-white' }}" href="{{ route('peta') }}">Peta Interaktif</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link px-3 {{ request()->routeIs('destinasi') ? 'active text-warning' : 'text-white' }}" href="{{ route('destinasi') }}">Destinasi</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link px-3 {{ request()->routeIs('about') ? 'active text-warning' : 'text-white' }}" href="{{ route('about') }}">About Us</a>
          </li>
        @endif
      </ul>

      <!-- Tombol kanan -->
      <div class="d-flex align-items-center ms-lg-3">
        @guest
          <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3 me-2 rounded-pill">Login</a>
          <a href="{{ route('register') }}" class="btn btn-warning text-primary btn-sm px-3 rounded-pill fw-bold">Sign Up</a>
        @endguest

        @auth
          <div class="dropdown">
            <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
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
                <li><hr class="dropdown-divider"></li>
              @endif
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @endauth
      </div>
    </div>
  </div>
</nav>

<main class="py-4 mt-5">
  @yield('content')
</main>

<footer class="bg-dark text-white py-4 mt-5">
  <div class="container text-center">
    &copy; {{ date('Y') }} Dinas Pariwisata Kota Bengkulu — Semua hak cipta dilindungi.
  </div>
</footer>

<!-- Scripts -->
<!-- Scripts (load sekali saja, urutan penting) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>

<style>
  /* Pastikan dropdown muncul di atas navbar */
  .navbar .dropdown-menu { z-index: 2000; }
</style>

<script>
  // Navbar shrink on scroll (aman)
  window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  });

  // Inisialisasi paksa semua dropdown Bootstrap (jaga-jaga)
  document.addEventListener('DOMContentLoaded', () => {
    if (window.bootstrap && bootstrap.Dropdown) {
      document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
        try { new bootstrap.Dropdown(el); } catch(e) { /* noop */ }
      });
    }
  });
</script>

@php
  $currentUser = auth()->check()
    ? [
        'id'    => auth()->id(),
        'name'  => auth()->user()->name,
        'email' => auth()->user()->email,
      ]
    : null;
@endphp

<script>
  // Identitas user login (null jika tamu)
  const CURRENT_USER = @json($currentUser);

  document.addEventListener("DOMContentLoaded", () => {
    // ======== UTIL ========
    const esc = (s) => String(s)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#39;');

    // ======== RATING (per user) ========
    document.querySelectorAll('.rating').forEach(ratingEl => {
      if (!ratingEl) return;
      const destinasiId = ratingEl.dataset.id;
      const stars = ratingEl.querySelectorAll('i');

      // Info login di bawah rating
      if (!ratingEl.querySelector('.rating-meta')) {
        const meta = document.createElement('div');
        meta.className = 'rating-meta mt-2 small text-muted';
        meta.innerHTML = CURRENT_USER
          ? `Masuk sebagai <strong>${esc(CURRENT_USER.name)}</strong>.`
          : `Login untuk memberi rating & komentar.`;
        if (ratingEl.parentElement) ratingEl.parentElement.appendChild(meta);
      }

      // Nonaktifkan untuk tamu
      if (!CURRENT_USER) {
        stars.forEach(star => star.setAttribute('disabled', 'true'));
      }

      const key = (uid) => `rating_${destinasiId}_${uid}`;
      const setVisual = (value) => {
        stars.forEach(star => {
          const active = Number(star.dataset.value) <= Number(value);
          star.classList.toggle('bi-star-fill', active);
          star.classList.toggle('bi-star', !active);
          star.classList.toggle('text-warning', active);
        });
      };

      if (CURRENT_USER) {
        const saved = localStorage.getItem(key(CURRENT_USER.id));
        if (saved) setVisual(saved);
      }

      stars.forEach(star => {
        star.addEventListener('click', () => {
          if (!CURRENT_USER) return;
          const value = star.dataset.value;
          localStorage.setItem(key(CURRENT_USER.id), value);
          setVisual(value);

          let lbl = ratingEl.querySelector('.my-rating-label');
          if (!lbl) {
            lbl = document.createElement('div');
            lbl.className = 'my-rating-label small text-muted ms-2';
            ratingEl.appendChild(lbl);
          }
          lbl.textContent = `Rating kamu: ${value}/5`;
        });
      });
    });

    // ======== KOMENTAR (objek: {text, userId, userName, time}) ========
    const readComments = (id) => {
      try { return JSON.parse(localStorage.getItem(`comments_${id}`) || '[]'); }
      catch { return []; }
    };
    const writeComments = (id, list) => {
      localStorage.setItem(`comments_${id}`, JSON.stringify(list));
    };

    const renderComments = (id) => {
      const container = document.getElementById(`comments-${id}`);
      if (!container) return;
      const list = readComments(id);

      if (!list.length) {
        container.innerHTML = `<div class="text-muted">Belum ada komentar.</div>`;
        return;
      }

      container.innerHTML = list.map(c => {
        const when = c.time ? new Date(c.time) : null;
        const timeStr = when ? when.toLocaleString() : '';
        return `
          <div class="border-start ps-3 py-2 my-2">
            <div class="d-flex justify-content-between align-items-center">
              <strong>${esc(c.userName || 'Pengunjung')}</strong>
              <small class="text-muted">${esc(timeStr)}</small>
            </div>
            <div class="mt-1">${esc(c.text)}</div>
          </div>
        `;
      }).join('');
    };

    document.querySelectorAll('.save-comment').forEach(button => {
      button.addEventListener('click', () => {
        const destinasiId = button.dataset.id;
        if (!CURRENT_USER) return; // wajib login

        const textarea = document.querySelector(`.comment-box[data-id="${destinasiId}"]`);
        const text = (textarea?.value || '').trim();
        if (!text) return;

        const list = readComments(destinasiId);
        list.push({
          text,
          userId: CURRENT_USER.id,
          userName: CURRENT_USER.name,
          time: new Date().toISOString()
        });
        writeComments(destinasiId, list);
        textarea.value = '';
        renderComments(destinasiId);
      });
    });

    document.querySelectorAll('.comment-list').forEach(div => {
      const destinasiId = div.id.replace('comments-', '');
      renderComments(destinasiId);

      if (!CURRENT_USER) {
        const box = document.querySelector(`.comment-box[data-id="${destinasiId}"]`);
        const btn = document.querySelector(`.save-comment[data-id="${destinasiId}"]`);
        if (box) {
          box.disabled = true;
          box.placeholder = 'Login untuk menulis komentar...';
        }
        if (btn) {
          btn.disabled = true;
          btn.classList.add('disabled');
          btn.title = 'Login untuk menyimpan komentar';
        }
      }
    });
  });
</script>

@stack('scripts')
</body>
</html>
