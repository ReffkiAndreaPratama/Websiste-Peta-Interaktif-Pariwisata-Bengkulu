@extends('layouts.app')

@section('title', ($d->nama ?? 'Detail Destinasi') . ' | Dinas Pariwisata Kota Bengkulu')

@section('content')
<!-- ===== HERO / HEADER ===== -->
<section class="desc-hero position-relative text-white">
  <div class="desc-hero__bg"></div>
  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <nav aria-label="breadcrumb" class="mb-2">
          <ol class="breadcrumb breadcrumb-light m-0 small">
            <li class="breadcrumb-item">
              <a href="{{ route('destinasi') }}" class="text-white-50 text-decoration-none">Destinasi</a>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">
              {{ $d->nama ?? 'Detail' }}
            </li>
          </ol>
        </nav>

        <h1 class="fw-bold mb-2" style="font-size:clamp(1.6rem,4.6vw,2.8rem);">
          {{ $d->nama ?? '-' }}
        </h1>

        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
          <span class="badge rounded-pill bg-warning text-dark fw-semibold px-3 py-2">
            <i class="bi bi-tags me-1"></i>{{ $d->kategori ?? 'Umum' }}
          </span>
          @if($d->latitude && $d->longitude)
          <span class="badge rounded-pill bg-light text-dark fw-medium px-3 py-2">
            <i class="bi bi-geo-alt me-1"></i>{{ $d->latitude }}, {{ $d->longitude }}
          </span>
          @endif
        </div>

        @if(!empty($d->deskripsi))
          <p class="lead m-0 text-white-90">{{ $d->deskripsi }}</p>
        @endif

        <div class="d-flex flex-wrap gap-2 mt-4">
          <a href="{{ route('peta') }}" class="btn btn-warning text-primary fw-semibold rounded-pill px-4">
            <i class="bi bi-map-fill me-2"></i>Lihat Peta Interaktif
          </a>
          <a href="{{ route('destinasi') }}" class="btn btn-outline-light fw-semibold rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
          </a>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg bg-light-subtle">
          @if(!empty($d->gambar))
            <img src="{{ asset('storage/'.$d->gambar) }}"
                 alt="Gambar {{ $d->nama }}"
                 class="w-100 h-100 object-fit-cover" loading="lazy">
          @else
            <div class="d-flex align-items-center justify-content-center w-100 h-100">
              <div class="text-center text-muted">
                <i class="bi bi-image fs-1 d-block mb-2"></i>
                <small>Gambar belum tersedia</small>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- wave pemisah -->
  <div class="desc-wave">
    <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
      <path fill="#fff" d="M0,120L1440,0L1440,120L0,120Z"></path>
    </svg>
  </div>
</section>

<!-- ===== BODY ===== -->
<section class="py-section bg-white">
  <div class="container">
    <div class="row g-4 g-lg-5">
      <!-- Kolom kiri: konten -->
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Tentang Destinasi</h5>
            <p class="mb-0">{{ $d->deskripsi ?? 'Belum ada deskripsi.' }}</p>
          </div>
        </div>

        <!-- Rating & Komentar (LocalStorage) -->
        <div class="card border-0 shadow-sm rounded-4 mt-4">
          <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
              <h6 class="fw-bold m-0">Bagikan Penilaian</h6>
              <small class="text-muted">Klik bintang untuk memberi rating</small>
            </div>

            <div class="d-flex align-items-center gap-1 rating" data-id="{{ $d->id }}">
              @for($i=1; $i<=5; $i++)
                <i class="bi bi-star fs-4" data-value="{{ $i }}" role="button" aria-label="Beri rating {{ $i }}"></i>
              @endfor
            </div>

            <div class="mt-3">
              <label class="form-label">Tinggalkan Komentar</label>
              <textarea class="form-control comment-box" data-id="{{ $d->id }}" rows="3" placeholder="Tulis komentarmu di sini..."></textarea>
              <div class="d-flex justify-content-end">
                <button class="btn btn-primary mt-2 save-comment" data-id="{{ $d->id }}">
                  <i class="bi bi-send me-1"></i> Simpan Komentar
                </button>
              </div>
            </div>

            <div class="mt-4">
              <h6 class="fw-bold">Komentar</h6>
              <div id="comments-{{ $d->id }}" class="comment-list small"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Kolom kanan: info & peta -->
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Informasi</h5>

            <div class="d-grid gap-2">
              <div class="d-flex align-items-start gap-3">
                <i class="bi bi-geo-alt-fill fs-4 text-primary"></i>
                <div>
                  <div class="fw-semibold">Koordinat</div>
                  <div class="text-muted">
                    {{ $d->latitude ?? '-' }}, {{ $d->longitude ?? '-' }}
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-start gap-3">
                <i class="bi bi-tags fs-4 text-primary"></i>
                <div>
                  <div class="fw-semibold">Kategori</div>
                  <div class="text-muted">{{ $d->kategori ?? '-' }}</div>
                </div>
              </div>
            </div>
          </div>

          @if($d->latitude && $d->longitude)
            <div class="ratio ratio-4x3 rounded-bottom-4 overflow-hidden" id="map"></div>
          @else
            <div class="p-4 pt-0 text-muted">
              <small>Lokasi belum tersedia.</small>
            </div>
          @endif
        </div>

        <div class="d-grid gap-2 mt-3">
          <a href="{{ route('destinasi') }}" class="btn btn-outline-primary rounded-pill">
            <i class="bi bi-grid-3x3-gap me-2"></i> Lihat Semua Destinasi
          </a>
          <a href="{{ route('peta') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-map me-2"></i> Buka Peta Interaktif
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('head')
<style>
  :root{
    --brand-1:#0b66c3;
    --brand-2:#3acfd5;
  }
  .py-section{ padding-block:clamp(2.5rem, 5vw, 4.5rem); }

  /* ===== HERO ===== */
  .desc-hero{ overflow:clip; padding-block:clamp(3.5rem, 6vw, 5.5rem); }
  .desc-hero__bg{
    position:absolute; inset:0;
    background: linear-gradient(135deg, var(--brand-1) 0%, var(--brand-2) 100%);
    z-index:1;
  }
  .desc-wave{ position:absolute; left:0; right:0; bottom:-1px; z-index:1; line-height:0; }
  .desc-wave svg{ width:100%; height:min(16vw, 100px); display:block; }

  .text-white-90{ color:rgba(255,255,255,.92); }

  /* Util image */
  .object-fit-cover{ object-fit:cover; }
</style>
@endpush

@push('scripts')
  {{-- Leaflet Map (hanya jika koordinat ada) --}}
  @if($d->latitude && $d->longitude)
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const lat = parseFloat(@json($d->latitude));
      const lng = parseFloat(@json($d->longitude));
      const map = L.map('map', { zoomControl: true }).setView([lat, lng], 14);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
      }).addTo(map);

      L.marker([lat, lng]).addTo(map).bindPopup({!! json_encode($d->nama) !!});
      setTimeout(() => map.invalidateSize(), 200); // jaga-jaga setelah modal/DOM render
    });
  </script>
  @endif

  {{-- Rating & Komentar LocalStorage (sinkron gaya kamu) --}}
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // === Rating ===
      document.querySelectorAll('.rating').forEach(ratingEl => {
        const id = ratingEl.dataset.id;
        const stars = ratingEl.querySelectorAll('i');
        const saved = localStorage.getItem(`rating_${id}`);
        if (saved) setRating(stars, saved);

        stars.forEach(star => {
          star.addEventListener('click', () => {
            const value = star.dataset.value;
            localStorage.setItem(`rating_${id}`, value);
            setRating(stars, value);
          });
        });
      });

      function setRating(stars, value) {
        stars.forEach(star => {
          const active = Number(star.dataset.value) <= Number(value);
          star.classList.toggle('bi-star-fill', active);
          star.classList.toggle('bi-star', !active);
          star.classList.toggle('text-warning', active);
        });
      }

      // === Komentar ===
      document.querySelectorAll('.save-comment').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.dataset.id;
          const box = document.querySelector(`.comment-box[data-id="${id}"]`);
          const text = (box?.value || '').trim();
          if (!text) return;
          const key = `comments_${id}`;
          const list = JSON.parse(localStorage.getItem(key) || '[]');
          list.push(text);
          localStorage.setItem(key, JSON.stringify(list));
          box.value = '';
          renderComments(id);
        });
      });

      function renderComments(id) {
        const container = document.getElementById(`comments-${id}`);
        const list = JSON.parse(localStorage.getItem(`comments_${id}`) || '[]');
        container.innerHTML = list.map(c =>
          `<div class="border-start ps-3 py-2 my-1">${c}</div>`
        ).join('');
      }

      // Render awal
      document.querySelectorAll('.comment-list').forEach(div => {
        renderComments(div.id.replace('comments-', ''));
      });
    });
  </script>
@endpush
