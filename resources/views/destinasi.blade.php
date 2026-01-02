@extends('layouts.app')
@section('title', 'Destinasi Wisata | Dinas Pariwisata Kota Bengkulu')

@section('content')
<!-- === HERO SECTION === -->
<section class="hero-section mb-5">
  <div class="container text-center">
    <h1 class="fw-bold display-5 mb-3">Destinasi Wisata Pesisir Kota Bengkulu</h1>
    <p class="fs-5 text-white-50 mx-auto" style="max-width: 750px;">
      Temukan pesona wisata alam, sejarah, dan budaya di Kota Bengkulu. Dari keindahan pesisir hingga warisan kolonial,
      setiap sudut Bengkulu menyimpan cerita dan pengalaman berharga untuk dijelajahi.
    </p>
  </div>
</section>

<!-- === DESTINASI GRID (DINAMIS) === -->
<section class="container py-5">
  <div class="row g-4 justify-content-center">
    @forelse($destinasi as $index => $d)
      <div class="col-lg-4 col-md-6 col-sm-12 d-flex" data-aos="zoom-in-up" data-aos-delay="{{ $index * 100 }}">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden w-100 destination-card d-flex flex-column">
          <div class="position-relative overflow-hidden">
            @if($d->gambar)
              <img src="{{ asset('storage/'.$d->gambar) }}" class="card-img-top" alt="{{ $d->nama }}">
            @else
              <img src="{{ asset('images/no-image.jpg') }}" class="card-img-top" alt="No Image">
            @endif
            <span class="badge bg-primary position-absolute top-0 start-0 m-3 text-uppercase fw-bold">
              {{ $d->kategori }}
            </span>
          </div>
          <div class="card-body d-flex flex-column flex-grow-1">
              <h5 class="fw-bold text-primary mb-3">{{ $d->nama }}</h5>

              {{-- ⭐ RATING DESTINASI --}}
              @php
                  $avg = $d->rating_avg ?? 0;
                  $rounded = round($avg);
              @endphp
              <div class="rating mb-2">
                  @for ($i = 1; $i <= 5; $i++)
                      <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                  @endfor
                  <span class="small text-muted ms-1">
                      @if ($avg)
                          {{ number_format($avg, 1) }} ({{ $d->review_count }} ulasan)
                      @else
                          Belum ada ulasan
                      @endif
                  </span>
              </div>
              {{-- ⭐ END RATING --}}

              <p class="text-secondary small mb-4 flex-grow-1">{{ Str::limit($d->deskripsi, 120) }}</p>

              <a href="{{ route('profile.desc', $d->id) }}" class="btn-detail mt-auto">
                  Lihat Detail <span class="arrow">→</span>
              </a>
          </div>

        </div>
      </div>
    @empty
      <div class="text-center py-5">
        <p class="text-muted">Belum ada destinasi wisata yang tersedia.</p>
      </div>
    @endforelse
  </div>
</section>


<!-- === AOS Animation === -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>

<!-- === CUSTOM STYLES === -->
<style>
.hero-section {
  background: linear-gradient(135deg, #0b66c3 0%, #3acfd5 100%);
  color: #fff;
  text-align: center;
  padding: 5rem 1rem;
  position: relative;
  overflow: hidden;
}
.hero-section::before {
  content: '';
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.25);
  z-index: 0;
}
.hero-section .container {
  position: relative;
  z-index: 1;
}
.destination-card {
  transition: all 0.4s ease;
  display: flex;
  flex-direction: column;
  height: 100%;
  border-radius: 12px;
}
.destination-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 35px rgba(0, 0, 0, 0.2);
}
.destination-card img {
  height: 220px;
  object-fit: cover;
  transition: transform 0.5s ease;
}
.destination-card:hover img {
  transform: scale(1.05);
}
.badge {
  font-size: 0.75rem;
  padding: 0.4em 0.7em;
  border-radius: 0.5rem;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}
.card-body {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}
.card-body h5 {
  font-size: 1.25rem;
  line-height: 1.3;
}
.card-body p {
  line-height: 1.5;
}
.btn-detail {
  background: linear-gradient(90deg, #3acfd5 0%, #0b66c3 100%);
  color: #fff !important;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  padding: 10px 26px;
  font-size: 1rem;
  transition: all 0.4s ease;
  box-shadow: 0 6px 16px rgba(11, 102, 195, 0.3);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  text-decoration: none;
  align-self: flex-start;
}
.btn-detail:hover {
  background: linear-gradient(90deg, #34b5c0 0%, #0958a5 100%);
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(11, 102, 195, 0.4);
}
.btn-detail .arrow {
  font-weight: 700;
  transition: transform 0.3s ease;
}
.btn-detail:hover .arrow {
  transform: translateX(5px);
}
@media (max-width: 768px) {
  .hero-section h1 { font-size: 2.25rem; }
  .hero-section p { font-size: 1rem; }
  .destination-card img { height: 160px; }
  .card-body { padding: 1rem; }
  .card-body h5 { font-size: 1.1rem; }
  .card-body p { font-size: 0.9rem; }
  .btn-detail { padding: 8px 20px; font-size: 0.9rem; }
}
</style>
@endsection
