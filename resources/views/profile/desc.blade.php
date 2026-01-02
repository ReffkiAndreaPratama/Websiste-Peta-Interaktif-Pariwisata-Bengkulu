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
              <a href="{{ route('admin.preview.destinasi') }}" class="text-white-50 text-decoration-none">Destinasi</a>
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
           @if(Auth::check() && Auth::user()->role === 'admin')
              <a href="{{ route('admin.preview.peta') }}" class="btn btn-warning text-primary fw-semibold rounded-pill px-4">
                <i class="bi bi-map-fill me-2"></i>Lihat Peta Interaktif
              </a>
              <a href="{{ route('admin.preview.destinasi') }}" class="btn btn-outline-light fw-semibold rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
              </a>
          @else
              <a href="{{ route('peta') }}" class="btn btn-warning text-primary fw-semibold rounded-pill px-4">
                <i class="bi bi-map-fill me-2"></i>Lihat Peta Interaktif
              </a>
              <a href="{{ route('destinasi') }}" class="btn btn-outline-light fw-semibold rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
              </a>
           @endif
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

        <!-- ===== RATING & KOMENTAR (SERVER-BACKED) ===== -->
        <div class="card border-0 shadow-sm rounded-4 mt-4">
          <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
              <h6 class="fw-bold m-0">Bagikan Penilaian</h6>
              <small class="text-muted">Klik bintang untuk memberi rating</small>
            </div>

            {{-- Stars (interactive) --}}
            <div id="rating-widget-{{ $d->id }}" class="d-flex align-items-center gap-1 rating-widget" data-id="{{ $d->id }}">
              @for($i=1;$i<=5;$i++)
                <button type="button" class="btn btn-sm btn-icon star-btn" data-value="{{ $i }}" aria-label="Beri rating {{ $i }}">
                  <i class="bi bi-star" data-value="{{ $i }}"></i>
                </button>
              @endfor

              <div class="ms-3 small text-muted" id="rating-summary-{{ $d->id }}">
                @php
                  $avg = $rating_avg ?? ($d->reviews()->avg('rating') ?? 0);
                  $count = $rating_count ?? $d->reviews->count();
                @endphp
                {{ $count > 0 ? number_format($avg,1).' dari '.$count.' ulasan' : 'Belum ada ulasan' }}
              </div>
            </div>

            <div class="mt-3">
              <label class="form-label">Tinggalkan Komentar</label>
              <textarea id="comment-box-{{ $d->id }}" class="form-control" rows="3" placeholder="Tulis komentarmu di sini..."></textarea>
              <div class="d-flex justify-content-end">
                <button id="save-comment-{{ $d->id }}" class="btn btn-primary mt-2">
                  <i class="bi bi-send me-1"></i> Simpan Komentar
                </button>
              </div>
            </div>

            <div class="mt-4">
              <h6 class="fw-bold">Komentar</h6>
              <div id="comments-container-{{ $d->id }}" class="comment-list small">
                {{-- server-side render existing reviews --}}
                @foreach($d->reviews as $r)
                  <div class="border-start ps-3 py-2 my-1">
                    <div class="d-flex justify-content-between">
                      <div><strong>{{ $r->name ?? 'Pengunjung' }}</strong></div>
                      <div class="small text-muted">{{ optional($r->created_at)->format('d M Y H:i') }}</div>
                    </div>
                    <div class="mt-1 small">
                      @for($i=1;$i<=5;$i++)
                        @if($r->rating !== null && $i <= $r->rating)
                          <i class="bi bi-star-fill text-warning"></i>
                        @else
                          <i class="bi bi-star text-muted"></i>
                        @endif
                      @endfor
                    </div>
                    @if($r->comment)
                      <p class="mt-2 mb-0">{{ $r->comment }}</p>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>

          </div>
        </div>
        <!-- ===== END RATING & KOMENTAR ===== -->

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

        <!-- TOMBOL ADMIN PREVIEW -->
        <div class="d-grid gap-2 mt-3">
          @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('admin.preview.destinasi') }}" class="btn btn-outline-primary rounded-pill">
              <i class="bi bi-grid-3x3-gap me-2"></i> Lihat Semua Destinasi
            </a>
            <a href="{{ route('admin.preview.peta') }}" class="btn btn-primary rounded-pill">
              <i class="bi bi-map me-2"></i> Buka Peta Interaktif
            </a>
          @else
            <a href="{{ route('destinasi') }}" class="btn btn-outline-primary rounded-pill">
              <i class="bi bi-grid-3x3-gap me-2"></i> Lihat Semua Destinasi
            </a>
            <a href="{{ route('peta') }}" class="btn btn-primary rounded-pill">
              <i class="bi bi-map me-2"></i> Buka Peta Interaktif
            </a>
           @endif 
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
  {{-- Leaflet Map --}}
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
      setTimeout(() => map.invalidateSize(), 200);
    });
  </script>
  @endif

  {{-- Rating & Komentar JS --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const destId = {{ $d->id }};
      const reviewApi = '/api/destinasi/' + destId + '/reviews';
      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const CSRF_TOKEN = csrfMeta ? csrfMeta.getAttribute('content') : '';

      function escapeHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

      function buildReviewHTML(r){
        const time = r.at ? new Date(r.at).toLocaleString() : '';
        let stars = '';
        const rating = r.rating ? Number(r.rating) : 0;
        for(let i=1;i<=5;i++){
          stars += i<=rating ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>';
        }
        return `
          <div class="border-start ps-3 py-2 my-1">
            <div class="d-flex justify-content-between">
              <div><strong>${escapeHtml(r.name || 'Pengunjung')}</strong></div>
              <div class="small text-muted">${escapeHtml(time)}</div>
            </div>
            <div class="mt-1 small">${stars}</div>
            ${r.comment ? `<p class="mt-2 mb-0">${escapeHtml(r.comment)}</p>` : ''}
          </div>
        `;
      }

      (function setupStars(){
        const widget = document.getElementById('rating-widget-' + destId);
        if(!widget) return;
        let selected = 0;
        widget.querySelectorAll('.star-btn').forEach(btn => {
          btn.addEventListener('click', () => {
            selected = Number(btn.getAttribute('data-value'));
            widget.querySelectorAll('.star-btn i').forEach(i=>{
              const val = Number(i.getAttribute('data-value'));
              if(val <= selected) { i.classList.remove('bi-star'); i.classList.add('bi-star-fill','text-warning'); }
              else { i.classList.remove('bi-star-fill','text-warning'); i.classList.add('bi-star'); }
            });
          });
        });

        const saveBtn = document.getElementById('save-comment-' + destId);
        if(!saveBtn) return;

        saveBtn.addEventListener('click', async () => {
          const box = document.getElementById('comment-box-' + destId);
          const comment = (box?.value || '').trim();
          if(!comment && selected === 0){
            alert('Isi komentar atau pilih rating.');
            return;
          }

          saveBtn.disabled = true;
          try {
            const res = await fetch(reviewApi, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
              },
              body: JSON.stringify({ rating: selected || null, comment: comment || null, name: null })
            });
            if(!res.ok) throw new Error(await res.text());
            const data = await res.json();

            // update summary
            const summary = document.getElementById('rating-summary-' + destId);
            if(summary && data.rating_avg !== undefined){
              summary.textContent = data.rating_count > 0 ? `${Number(data.rating_avg).toFixed(1)} dari ${data.rating_count} ulasan` : 'Belum ada ulasan';
            }

            const container = document.getElementById('comments-container-' + destId);
            if(container && data.review){
              container.insertAdjacentHTML('afterbegin', buildReviewHTML(data.review));
            } else if(container && Array.isArray(data.reviews)){
              container.insertAdjacentHTML('afterbegin', buildReviewHTML(data.reviews[0]));
            } else {
              fetch(reviewApi).then(r=>r.json()).then(j=>{
                if(j.reviews){
                  container.innerHTML = j.reviews.map(buildReviewHTML).join('');
                  const sum = document.getElementById('rating-summary-' + destId);
                  if(sum) sum.textContent = j.rating_count>0 ? `${Number(j.rating_avg).toFixed(1)} dari ${j.rating_count} ulasan` : 'Belum ada ulasan';
                }
              });
            }

            box.value = '';
            selected = 0;
            widget.querySelectorAll('.star-btn i').forEach(i=>{
              i.classList.remove('bi-star-fill','text-warning');
              i.classList.add('bi-star');
            });

          } catch(err){
            console.error('Gagal menyimpan review', err);
            alert('Gagal menyimpan. Cek console/network.');
          } finally {
            saveBtn.disabled = false;
          }
        });
      })();
    });
  </script>
@endpush
