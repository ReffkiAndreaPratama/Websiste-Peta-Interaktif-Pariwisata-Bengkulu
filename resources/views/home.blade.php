@extends('layouts.app')

@section('title', 'Dashboard | Dinas Pariwisata Kota Bengkulu')

@section('content')

<!-- === DASHBOARD SECTION === -->
<section class="hero-section text-white d-flex align-items-start justify-content-center position-relative" style="
  background: linear-gradient(135deg, #0b66c3 0%, #3acfd5 100%);
  min-height: 100vh;
  padding-top: 4rem; /* naikkan konten */
  padding-bottom: 4rem;
  overflow: visible;
">
  <div class="container px-lg-5 position-relative" style="z-index: 2;">
    <div class="row align-items-start justify-content-between">
      <!-- Teks & Tombol -->
      <div class="col-lg-6 mb-5 mb-lg-0 animate__animated animate__fadeInLeft">
        <h1 class="fw-bold mb-3 lh-base" style="font-size: clamp(1.8rem, 4vw, 3rem);">
          Halo! Selamat datang di<span class="text-warning"> Website Peta Interaktif wisata Pesisir</span>
        </h1>
        <p class="lead mb-4 text-light" style="font-size: clamp(0.9rem, 2.5vw, 1.2rem);">
          Temukan info destinasi wisata favoritmu dan kelola data dengan mudah.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="{{ route('peta') }}" class="btn btn-warning text-primary fw-semibold px-4 py-2 shadow">Lihat Peta</a>
          <a href="{{ route('destinasi') }}" class="btn btn-destinasi fw-semibold px-4 py-2 shadow">Destinasi</a>
        </div>
      </div>

      <div class="col-lg-5 text-center animate__animated animate__fadeInRight">
        <img src="https://cdn-icons-png.flaticon.com/512/854/854894.png"
             alt="Dashboard Icon"
             class="img-fluid hero-img shadow-lg rounded-4"
             style="max-width: 460px; background: rgba(255,255,255,0.1); backdrop-filter: blur(6px); padding: 20px;">
      </div>
    </div>
  </div>

  <!-- Ombak bawah -->
  <div class="position-absolute bottom-0 w-100" style="z-index: 1;">
    <svg viewBox="0 0 1440 320" width="100%" height="auto">
      <path fill="#fff" fill-opacity="1" 
        d="M0,256L80,245.3C160,235,320,213,480,181.3C640,149,800,107,960,85.3C1120,64,1280,64,1360,64L1440,64L1440,320L1360,320
        C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z">
      </path>
    </svg>
  </div>
</section>

<!-- === DESTINASI UNGGULAN === -->
<section class="py-6 bg-light position-relative">
  <div class="container px-lg-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary mb-3">Destinasi Unggulan</h2>
      <p class="text-muted">Jelajahi wisata favorit yang wajib kamu kunjungi di Bengkulu.</p>
    </div>

    <div class="row g-5">
      @foreach([
        ['Pantai Panjang', 'Pantai terkenal dengan garis pantai panjang dan sunset indah.', 'https://www.garudacitizen.com/wp-content/uploads/2015/04/Pantai-Panjang-Bengkulu-Tujuan-Surfing-Baru.jpg', 'Pantai ini merupakan ikon wisata Kota Bengkulu dengan panorama laut yang memukau dan suasana santai.'],
        ['Benteng Marlborough', 'Situs bersejarah peninggalan kolonial Inggris.', 'https://tse3.mm.bing.net/th/id/OIP.3hweuZvgR9X2uKDj7s75nAHaEK?cb=12&rs=1&pid=ImgDetMain&o=7&rm=3', 'Benteng terbesar di Asia Tenggara dari masa kolonial Inggris, menyimpan sejarah panjang Bengkulu.'],
        ['Kampoeng China', 'Kawasan bersejarah dan pusat perdagangan masa lalu.', 'https://th.bing.com/th/id/R.cf4cdd3c17218c56bdddc622a1d0cb6c?rik=X7E8y1zG0GJ2XQ&riu=http%3a%2f%2fphotos.wikimapia.org%2fp%2f00%2f04%2f28%2f66%2f36_full.jpg&ehk=TM437TpbFOvY7ocuW0P9nTlfKRQr95OGUzzcw9%2ftsEU%3d&risl=&pid=ImgRaw&r=0', 'Saksi bisu sejarah kolonial dan interaksi budaya Tionghoa yang masih lestari hingga kini.']
      ] as $index => $destinasi)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-lg hover-card rounded-4 overflow-hidden bg-white">
          <div class="position-relative">
            <img src="{{ $destinasi[2] }}" class="card-img-top" alt="{{ $destinasi[0] }}" style="height:250px;object-fit:cover;">
            <div class="overlay"></div>
          </div>
          <div class="card-body text-center p-4">
            <h5 class="card-title fw-semibold text-primary mb-2">{{ $destinasi[0] }}</h5>
            <p class="text-muted small mb-3">{{ $destinasi[1] }}</p>
            <button class="btn btn-primary btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#destinasiModal{{ $index }}">
              Detail
            </button>
          </div>
        </div>
      </div>

      <!-- Modal Detail -->
      <div class="modal fade" id="destinasiModal{{ $index }}" tabindex="-1" aria-labelledby="destinasiModalLabel{{ $index }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
              <h5 class="modal-title fw-semibold" id="destinasiModalLabel{{ $index }}">{{ $destinasi[0] }}</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
              <img src="{{ $destinasi[2] }}" class="img-fluid rounded mb-3" alt="{{ $destinasi[0] }}">
              <p class="text-muted">{{ $destinasi[3] }}</p>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- === STYLING TAMBAHAN === -->
<style>
  /* === Scroll & Layout Fixes === */
    .hero-section {
    min-height: auto; /* biarkan tinggi menyesuaikan konten */
    padding-top: 6rem; /* ruang atas lebih lega */
    padding-bottom: 6rem; /* ruang bawah lebih lega */
    overflow: visible; /* pastikan scroll terlihat */
  }

  html, body {
  scroll-behavior: smooth;
  height: 100%;
  margin: 0;
  overflow-x: hidden;
}


  /* Tombol destinasi custom */
  .btn-destinasi {
    background: linear-gradient(90deg, #3acfd5, #0b66c3);
    color: #fff !important;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  .btn-destinasi:hover {
    background: linear-gradient(90deg, #34b5c0, #0958a5);
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.25);
  }

  /* Card hover */
  .hover-card {
    transition: all 0.35s ease;
  }
  .hover-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 2rem rgba(0,0,0,0.1);
  }

  .overlay {
    position:absolute;
    inset:0;
    background:linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,0.25) 100%);
  }

  .btn-primary {
    background: linear-gradient(90deg, #0b66c3, #3acfd5);
    border: none;
  }
  .btn-primary:hover {
    background: linear-gradient(90deg, #0958a5, #34b5c0);
  }

  .py-6 {
    padding-top: 5rem !important;
    padding-bottom: 5rem !important;
  }

  footer {
    font-size: 0.9rem;
    letter-spacing: 0.3px;
  }
  

  /* Responsive Hero Image */
  @media (max-width: 768px) {
    .hero-section {
      text-align: center;
    }
    .hero-section img {
      max-width: 320px;
      margin-top: 2rem;
    }
  }
</style>
@endsection
