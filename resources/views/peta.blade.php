@extends('layouts.app')

@section('title', 'Peta Interaktif | Dinas Pariwisata Kota Bengkulu')

@push('head')
<style>
/* HERO */
.hero-section {
  background: linear-gradient(135deg, #0b66c3 0%, #3acfd5 100%);
  color: #fff;
  text-align: center;
  padding: 5rem 0;
}

/* MAP */
#map {
  height: 60vh; /* default map sebagian layar */
  width: 100%;
  border-radius: 10px;
  z-index: 0;
  transition: height 0.4s ease, width 0.4s ease;
  cursor: pointer;
}

#map.fullscreen {
  height: 100vh;
  width: 100%;
  border-radius: 0;
}

/* Search + Filter bar */
.search-filter {
  position: absolute;
  top: 120px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1100;
  display: flex;
  gap: 10px;
  align-items: center;
  background: rgba(255,255,255,0.95);
  padding: 8px 12px;
  border-radius: 40px;
  box-shadow: 0 6px 20px rgba(10, 40, 80, 0.12);
}

.search-filter input[type="text"] {
  border: none;
  outline: none;
  width: 260px;
  padding: 6px 10px;
  border-radius: 24px;
  background: transparent;
}

.search-filter select {
  border: none;
  outline: none;
  padding: 6px 10px;
  border-radius: 12px;
  background: transparent;
}

/* Detail panel (slide-in dari kanan) */
.detail-panel {
  position: absolute;
  top: 100px;
  right: 20px;
  width: 360px;
  max-width: calc(100% - 24px);
  height: calc(100vh - 140px);
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 10px 40px rgba(10,20,40,0.15);
  transform: translateX(110%);
  opacity: 0;
  transition: transform .32s cubic-bezier(.2,.9,.2,1), opacity .24s ease;
  z-index: 1200;
  overflow-y: auto;
  padding: 16px;
}

.detail-panel.open {
  transform: translateX(0);
  opacity: 1;
}

.detail-panel .close-btn {
  position: absolute;
  top: 10px;
  right: 10px;
}

.detail-panel img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 10px;
}

.detail-panel h4 {
  margin-bottom: 6px;
  color: #0b66c3;
}

.detail-panel .category {
  font-size: 0.85rem;
  color: #666;
  margin-bottom: 10px;
}

.detail-panel .story {
  font-size: 0.95rem;
  color: #333;
  line-height: 1.4;
  margin-bottom: 12px;
}

/* Rating */
.rating i {
  font-size: 1.15rem;
  color: #dcdcdc;
  cursor: pointer;
  margin-right: 4px;
}

.rating i.filled {
  color: #ffb400;
}

/* Comment */
.comment-box {
  width: 100%;
  border-radius: 6px;
  padding: 8px;
  border: 1px solid #e6e6e6;
  font-size: 0.95rem;
  resize: vertical;
  min-height: 60px;
}

.comment-list .comment-item {
  border-left: 3px solid #0b66c3;
  padding-left: 8px;
  margin-top: 8px;
  color: #444;
  font-size: 0.9rem;
  background: #fbfbfb;
  border-radius: 4px;
  padding-top: 6px;
  padding-bottom: 6px;
}

/* responsive */
@media (max-width: 900px) {
  #map { height: 50vh; }
  .detail-panel {
    width: 92%;
    right: 4%;
    top: 140px;
    height: calc(100vh - 180px);
  }
  .search-filter { top: 140px; left: 50%; transform: translateX(-50%); }
}
</style>
@endpush

@section('content')
<!-- HERO -->
<section class="hero-section">
  <div class="container">
    <h1 class="display-5 fw-bold">Peta Interaktif Wisata Pesisir Bengkulu</h1>
    <p class="lead text-white-50">Telusuri destinasi, pelajari sejarah dan asal-usul tempat, beri rating, dan tinggalkan komentar.</p>
  </div>
</section>

<!-- MAP + CONTROL -->
<div class="position-relative">
  <!-- SEARCH + FILTER -->
  <div class="container mt-3">
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-2">
      <input id="searchInput" type="text" class="form-control" placeholder="Cari destinasi wisata..." style="max-width:260px; border-radius:25px; padding:8px 15px;">
      <select id="categorySelect" class="form-select" style="max-width:200px; border-radius:25px; padding:8px 15px;">
        <option value="all">Semua Kategori</option>
        <option value="pantai">Pantai</option>
        <option value="sejarah">Sejarah</option>
        <option value="alam">Alam</option>
        <option value="kuliner">Kuliner</option>
      </select>
    </div>
  </div>

  <!-- map -->
  <div id="map"></div>
  <button id="toggleMapBtn" class="btn btn-primary position-absolute" style="top:10px; right:10px; z-index:1200;">Fullscreen</button>

  <!-- panel detail -->
  <aside id="detailPanel" class="detail-panel" aria-hidden="true">
    <button class="btn btn-sm btn-outline-secondary close-btn" id="closePanelBtn" title="Tutup"><i class="bi bi-x-lg"></i></button>
    <img id="panelImage" src="" alt="Gambar destinasi">
    <h4 id="panelTitle">Judul</h4>
    <div class="category" id="panelCategory">Kategori</div>
    <div class="story" id="panelStory">Sejarah / asal-usul di sini...</div>
    <div class="mb-2">
      <div class="small text-muted">Rating</div>
      <div class="rating" id="panelRating" data-id="">
        <i class="bi bi-star" data-value="1"></i>
        <i class="bi bi-star" data-value="2"></i>
        <i class="bi bi-star" data-value="3"></i>
        <i class="bi bi-star" data-value="4"></i>
        <i class="bi bi-star" data-value="5"></i>
      </div>
    </div>
    <div class="mb-2">
      <div class="small text-muted">Komentar</div>
      <textarea id="panelCommentBox" class="comment-box" placeholder="Tulis komentar..."></textarea>
      <div class="d-flex justify-content-end mt-2">
        <button id="sendCommentBtn" class="btn btn-primary btn-sm">Kirim</button>
      </div>
      <div id="panelComments" class="comment-list mt-3"></div>
    </div>
  </aside>
</div>
@endsection

@push('scripts')
@php
  // 🔁 Deteksi apakah sedang dibuka dari /admin/preview/peta
  $isPreview = request()->is('admin/preview/*');
  $apiChanges = $isPreview
      ? route('admin.preview.api.destinasi.changes')
      : route('api.destinasi.changes');
  $apiGeojson = $isPreview
      ? route('admin.preview.api.destinasi.geojson')
      : route('api.destinasi.geojson');
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
  /* ============= Map setup ============= */
  const mapDiv = document.getElementById('map');
  const map = L.map(mapDiv, { zoomControl: true }).setView([-3.800, 102.265], 12.7);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19, attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  /* ============= Layers & state ============= */
  const markersLayer = L.layerGroup().addTo(map);
  let firstFit = true;
  let currentVersion = null;
  let currentData = []; // {id,name,category,lat,lng,img,short,story,detail_url}
  let markerRefs = new Map(); // id -> Leaflet marker

  /* ============= Utilities ============= */
  function guessCategoryFromName(name='') {
    const s = name.toLowerCase();
    if (s.includes('pantai')) return 'pantai';
    if (s.includes('benteng') || s.includes('rumah') || s.includes('museum')) return 'sejarah';
    if (s.includes('danau') || s.includes('air terjun') || s.includes('hutan')) return 'alam';
    if (s.includes('kuliner') || s.includes('cafe') || s.includes('warung')) return 'kuliner';
    if (s.includes('pulau') || s.includes('pulau tikus') || s.includes('lentera merah')) return 'alam';
    return 'lainnya';
  }
  function iconFor(cat) {
    const size = [36,36];
    const urls = {
      pantai: 'https://cdn-icons-png.flaticon.com/512/861/861060.png',
      sejarah: 'https://cdn-icons-png.flaticon.com/512/3176/3176366.png',
      alam: 'https://cdn-icons-png.flaticon.com/512/2331/2331944.png',
      kuliner: 'https://cdn-icons-png.flaticon.com/512/3480/3480454.png',
      lainnya: 'https://cdn-icons-png.flaticon.com/512/854/854866.png',
    };
    return L.icon({ iconUrl: urls[cat] || urls.lainnya, iconSize: size });
  }

  /* ============= Fetchers ============= */
  async function fetchGeo() {
    const res = await fetch(`{{ $apiGeojson }}`, { cache: 'no-store', credentials: 'same-origin' });
    const geo = await res.json();

    // Map ke struktur internal
    currentData = (geo.features || []).map(f => {
      const p = f.properties || {};
      const [lng, lat] = (f.geometry && f.geometry.coordinates) || [null, null];
      return {
        id: p.id,
        name: p.nama || 'Tanpa Nama',
        category: guessCategoryFromName(p.nama || ''),
        lat, lng,
        img: p.gambar || '',
        short: '', story: '',
        detail_url: p.detail_url || '#'
      };
    });

    renderMarkers();
  }

  async function checkChanges() {
    try {
      const res = await fetch(`{{ route('api.destinasi.changes') }}`, { cache: 'no-store' });
      const json = await res.json();
      if (currentVersion === null) {
        currentVersion = json.version || '';
        await fetchGeo();
      } else if (json.version && json.version !== currentVersion) {
        currentVersion = json.version;
        await fetchGeo();
      }
    } catch(e) { console.error(e); }
  }

  /* ============= Render Markers ============= */
  function renderMarkers() {
    markersLayer.clearLayers();
    markerRefs.clear();

    const bounds = [];
    const q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    const cat = document.getElementById('categorySelect').value;

    currentData.forEach(d => {
      if (!isFinite(d.lat) || !isFinite(d.lng)) return;

      const matchQ = !q || d.name.toLowerCase().includes(q) || d.short.toLowerCase().includes(q) || d.story.toLowerCase().includes(q);
      const matchCat = (cat === 'all') || (d.category === cat);
      if (!matchQ || !matchCat) return;

      const popupHtml = `
        <div style="min-width:200px">
          ${d.img ? `<img src="${d.img}" alt="${d.name}" style="width:100%;max-width:220px;border-radius:10px;margin-bottom:8px;">` : ''}
          <strong>${d.name}</strong><br>
          <a href="${d.detail_url}" class="btn btn-sm btn-primary mt-2">Detail</a>
        </div>
      `;
      const m = L.marker([d.lat, d.lng], { icon: iconFor(d.category) })
        .addTo(markersLayer)
        .bindPopup(popupHtml)
        .on('click', () => showDetailPanel(d));

      markerRefs.set(d.id, m);
      bounds.push([d.lat, d.lng]);
    });

    if (firstFit && bounds.length) {
      map.fitBounds(bounds, { padding: [40, 40] });
      firstFit = false;
    }
  }

  /* ============= Search & Filter ============= */
  document.getElementById('searchInput').addEventListener('input', renderMarkers);
  document.getElementById('categorySelect').addEventListener('change', renderMarkers);

  /* ============= Detail panel ============= */
  const panel = document.getElementById('detailPanel');
  const panelImage = document.getElementById('panelImage');
  const panelTitle = document.getElementById('panelTitle');
  const panelCategory = document.getElementById('panelCategory');
  const panelStory = document.getElementById('panelStory');
  const panelRating = document.getElementById('panelRating');
  const panelCommentBox = document.getElementById('panelCommentBox');
  const panelComments = document.getElementById('panelComments');

  function showDetailPanel(d){
    panelImage.src = d.img || '';
    panelImage.alt = d.name;
    panelTitle.textContent = d.name;
    panelCategory.textContent = d.category.charAt(0).toUpperCase() + d.category.slice(1);
    panelStory.textContent = d.story || '—';
    panelRating.dataset.id = d.id;
    panelCommentBox.dataset.id = d.id;
    renderRating(d.id);
    renderComments(d.id);
    panel.classList.add('open');
    panel.setAttribute('aria-hidden','false');
  }
  function closeDetailPanel(){
    panel.classList.remove('open');
    panel.setAttribute('aria-hidden','true');
  }
  document.getElementById('closePanelBtn').addEventListener('click', closeDetailPanel);
  map.on('click', closeDetailPanel);

  /* ============= Rating (localStorage) ============= */
  panelRating.addEventListener('click', function(e){
    if(e.target && e.target.matches('i[data-value]')){
      const value = Number(e.target.getAttribute('data-value'));
      const id = Number(panelRating.dataset.id);
      if(!id) return;
      localStorage.setItem('rating_'+id, value);
      renderRating(id);
    }
  });
  function renderRating(id){
    const saved = Number(localStorage.getItem('rating_'+id) || 0);
    const stars = panelRating.querySelectorAll('i[data-value]');
    stars.forEach(star => {
      const v = Number(star.getAttribute('data-value'));
      if (v <= saved) { star.classList.add('filled'); star.classList.replace('bi-star', 'bi-star-fill'); }
      else { star.classList.remove('filled'); star.classList.replace('bi-star-fill','bi-star'); }
    });
  }

  /* ============= Comments (localStorage) ============= */
  document.getElementById('sendCommentBtn').addEventListener('click', function(){
    const id = Number(panelCommentBox.dataset.id);
    if(!id) return;
    const text = panelCommentBox.value.trim();
    if(!text) return;
    const key = 'comments_'+id;
    const arr = JSON.parse(localStorage.getItem(key) || '[]');
    arr.push({text, at:new Date().toISOString()});
    localStorage.setItem(key, JSON.stringify(arr));
    panelCommentBox.value = '';
    renderComments(id);
  });
  function renderComments(id){
    const key = 'comments_'+id;
    const arr = JSON.parse(localStorage.getItem(key) || '[]');
    if (!arr.length) { panelComments.innerHTML = '<div class="small text-muted">Belum ada komentar.</div>'; return; }
    panelComments.innerHTML = arr.map(c => {
      const time = new Date(c.at).toLocaleString();
      return `<div class="comment-item"><div class="small text-muted" style="font-size:11px;">${time}</div><div style="margin-top:4px;">${escapeHtml(c.text)}</div></div>`;
    }).join('');
  }
  function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

  /* ============= Fullscreen ============= */
  const toggleBtn = document.getElementById('toggleMapBtn');
  toggleBtn.addEventListener('click', function(e){
    e.stopPropagation();
    mapDiv.classList.toggle('fullscreen');
    map.invalidateSize();
  });
  mapDiv.addEventListener('click', function(){
    mapDiv.classList.toggle('fullscreen');
    map.invalidateSize();
  });

  /* ============= Init ============= */
  checkChanges();                 // muat pertama
  setInterval(checkChanges, 12000); // auto-sync tiap 12 detik
});
</script>
@endpush
