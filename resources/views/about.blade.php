@extends('layouts.app')

@section('title', 'Tentang Kami | Dinas Pariwisata Kota Bengkulu')

@section('content')
<div class="container py-5">

  <div class="bg-light p-5 rounded-4 shadow-sm">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0" data-aos="fade-right">
        <h2 class="fw-bold text-primary mb-3">Tentang Dinas Pariwisata Kota Bengkulu</h2>
        <p class="text-secondary mb-3">
          Dinas Pariwisata Kota Bengkulu merupakan lembaga pemerintah daerah yang bertanggung jawab dalam pengembangan, 
          pengelolaan, dan promosi potensi wisata di Kota Bengkulu. Kami berkomitmen untuk menjadikan kota ini sebagai 
          destinasi wisata unggulan di Indonesia dengan mengedepankan keindahan alam pesisir, kekayaan budaya, dan 
          keramahan masyarakatnya.
        </p>
        <p class="text-secondary mb-3">
          Melalui situs resmi ini, kami menyediakan informasi lengkap seputar destinasi wisata, agenda budaya, 
          kuliner khas, serta panduan perjalanan yang dapat membantu wisatawan dalam merencanakan kunjungan ke Bengkulu.
        </p>
        <blockquote class="fst-italic text-muted border-start ps-3 mt-3">
          “Menjelajahi Bengkulu berarti menemukan harmoni antara alam, budaya, dan kehangatan masyarakat.”
        </blockquote>
      </div>

      <div class="col-md-6 text-center" data-aos="fade-left">
        <img src="https://via.placeholder.com/600x400?text=Dinas+Pariwisata+Kota+Bengkulu" 
             class="img-fluid rounded-4 shadow" 
             alt="Dinas Pariwisata Kota Bengkulu">
      </div>
    </div>
  </div>

  <div class="row text-center mt-5" data-aos="fade-up">
    <div class="col-md-4 mb-4">
      <div class="p-4 bg-white rounded-4 shadow-sm h-100 border">
        <h5 class="fw-bold text-primary mb-2">Visi</h5>
        <p class="text-secondary mb-0">
          Menjadikan Kota Bengkulu sebagai destinasi wisata unggulan yang berdaya saing, berkelanjutan, dan berbudaya.
        </p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="p-4 bg-white rounded-4 shadow-sm h-100 border">
        <h5 class="fw-bold text-primary mb-2">Misi</h5>
        <p class="text-secondary mb-0">
          Mengembangkan potensi wisata berbasis alam, sejarah, dan budaya melalui inovasi serta kolaborasi dengan masyarakat.
        </p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="p-4 bg-white rounded-4 shadow-sm h-100 border">
        <h5 class="fw-bold text-primary mb-2">Tujuan</h5>
        <p class="text-secondary mb-0">
          Meningkatkan kesejahteraan masyarakat melalui pertumbuhan pariwisata yang inklusif dan berkelanjutan.
        </p>
      </div>
    </div>
  </div>

</div>

<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>
@endsection
