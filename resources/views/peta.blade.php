@extends('layouts.app')

@section('title', 'Peta Interaktif | Dinas Pariwisata Kota Bengkulu')

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css">


<style>
:root{
  --primary:#0b66c3;
  --primary-600:#0754a3;
  --muted:#6b7b8a;
  --glass-bg: rgba(255,255,255,0.92);
  --card-radius:14px;
  --soft-shadow: 0 8px 28px rgba(10,30,60,0.08);
}

/* HERO */
.hero-section{
  background: linear-gradient(135deg, var(--primary) 0%, #3acfd5 100%);
  color:#fff;
  text-align:center;
  padding:4.25rem 0;
}
.hero-section .lead { color: rgba(255,255,255,0.9); }

/* MAP */
.map-wrapper {
  position: relative;
  margin-top: -40px; /* pull up under hero */
  padding: 20px;
}
#map {
  height: 66vh;
  width: 100%;
  border-radius: 16px;
  box-shadow: var(--soft-shadow);
  transition: height .36s ease, border-radius .3s ease;
  cursor: grab;
}
#map.fullscreen {
  height: calc(100vh - 10px);
  border-radius: 0;
}

/* MAP TOPBAR - rapi, sejajar */
.map-topbar{ width:100%; max-width:1100px; margin: 0 auto 12px; }
.topbar-inner{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  background: rgba(255,255,255,0.98);
  padding:10px;
  border-radius:12px;
  box-shadow: 0 8px 28px rgba(8,20,48,0.08);
}

/* LEFT: search area */
.topbar-left{ display:flex; flex-direction:column; gap:6px; flex:1; min-width:240px; position:relative; }
.search-pill{
  display:flex; align-items:center; gap:8px;
  background:var(--glass-bg); padding:8px 10px; border-radius:999px;
  border:1px solid rgba(11,102,195,0.04);
}
.search-pill i{ color:var(--muted); font-size:1.05rem; margin-left:4px; }
.search-pill .form-control{ border:none; background:transparent; padding:0 6px; min-width:220px; box-shadow:none; }
.search-pill .form-select{ border:none; background:transparent; width:150px; padding:0 6px; box-shadow:none; }
.btn-clear{ border-radius:999px; background:transparent; border:none; color:var(--muted); }

/* suggestion dropdown relative to search-pill */
.search-suggestions{
  position:absolute; left:8px; top:calc(100% + 8px);
  width:clamp(260px, 46%, 420px);
  background:#fff; border-radius:10px; box-shadow:0 12px 34px rgba(8,20,48,0.12);
  max-height:300px; overflow:auto; z-index:1700; display:none;
}
.search-suggestions.open{ display:block; }
.search-suggestions .item{ padding:10px 12px; cursor:pointer; }
.search-suggestions .item:hover{ background:#f6fbff; }

/* CENTER: compact buttons */
.topbar-center{ display:flex; gap:8px; align-items:center; justify-content:center; flex:0 0 auto; }
.btn.compact{ display:flex; align-items:center; gap:8px; padding:8px 12px; border-radius:8px; background:white; border:1px solid rgba(11,102,195,0.06); color:var(--primary); font-weight:600; }
.btn.compact .label{ font-size:0.9rem; }

/* RIGHT */
.topbar-right{ flex:0 0 auto; display:flex; gap:8px; align-items:center; }

/* responsive */
@media (max-width:880px){
  .topbar-inner{ flex-wrap:wrap; padding:8px; gap:8px; }
  .search-pill .form-select{ display:none; }
  .btn.compact .label{ display:none; }
  .search-suggestions{ left:8px; right:8px; width:auto; top:calc(100% + 8px); }
  .search-pill .form-control{ min-width:120px; }
}

/* detail panel */
.detail-panel {
  position: absolute;
  top: 24px;
  right: 24px;
  width: 380px;
  max-width: calc(100% - 32px);
  height: calc(100vh - 48px);
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 18px 50px rgba(8,20,48,0.16);
  transform: translateX(120%);
  opacity: 0;
  transition: transform .36s cubic-bezier(.2,.9,.3,1), opacity .28s ease;
  z-index: 1600;
  overflow-y: auto;
  padding-bottom: 18px;
}
.detail-panel.open {
  transform: translateX(0);
  opacity: 1;
}
.detail-panel .panel-header {
  position: sticky; top:0; background:linear-gradient(180deg,rgba(255,255,255,0.95),rgba(255,255,255,0.85));
  z-index:10; padding:12px 14px;
  display:flex; gap:8px; align-items:center; justify-content:space-between;
  border-bottom: 1px solid #f1f5f9;
}
.detail-panel img {
  width: calc(100% - 28px); margin: 12px 14px; height: 190px; object-fit: cover; border-radius: 10px;
}
.detail-panel .content { padding: 0 14px 12px 14px; color: #223344; }

/* badges */
.badge-cat { padding:6px 10px; border-radius: 999px; font-weight:600; font-size:0.82rem; }
.badge-pantai { background:linear-gradient(90deg,#ffd89b,#ffb347); color:#5a3b00; }
.badge-sejarah { background:linear-gradient(90deg,#c7f0ff,#9ad8ff); color:#033a6b; }
.badge-alam { background:linear-gradient(90deg,#c7ffd3,#79f29b); color:#064b1a; }
.badge-kuliner { background:linear-gradient(90deg,#ffd6e0,#ff9bb3); color:#62102a; }
.badge-lainnya { background:#f2f4f7; color:var(--muted); }

/* rating stars */
.rating i { font-size:1.1rem; color:#e6e6e6; margin-right:6px; cursor:pointer; transition: color .12s ease, transform .08s ease; }
.rating i.filled { color:#ffb400; transform:translateY(-2px); }

/* comment box */
.comment-box { width:100%; min-height:66px; border-radius:8px; padding:8px 10px; border:1px solid #e9eef6; }

/* comment item */
.comment-list .comment-item { background:#fbfbff; border-radius:8px; padding:8px 10px; margin-top:10px; border-left:3px solid var(--primary); color:#333; }

/* review unified style: stars + comment together */
.review-item {
  display:flex;
  gap:12px;
  padding:10px;
  background:#fff;
  border-radius:10px;
  box-shadow: 0 6px 18px rgba(8,20,48,0.04);
  margin-bottom:12px;
  align-items:flex-start;
}
.review-avatar { width:46px; height:46px; border-radius:10px; overflow:hidden; flex:0 0 46px;
  display:inline-flex; align-items:center; justify-content:center; background:#e9f2ff; color:var(--primary); font-weight:700;
}
.review-meta { flex:1; min-width:0; }
.review-meta .header { display:flex; justify-content:space-between; align-items:center; gap:8px; }
.review-meta .name { font-weight:700; color:#0b66c3; font-size:0.95rem; }
.review-meta .time { font-size:0.78rem; color:#6b7b8a; }
.review-stars-row { display:flex; align-items:center; gap:8px; margin-top:6px; }
.review-stars-row .stars { display:flex; gap:4px; }
.review-stars-row .stars i { font-size:0.98rem; color:#e6e6e6; }
.review-stars-row .stars i.filled { color:#ffb400; transform:translateY(-1px); }
.review-stars-row .rating-num { font-size:0.85rem; color:#6b7b8a; }
.review-text { margin-top:8px; color:#223344; font-size:0.95rem; line-height:1.4; white-space:pre-wrap; }


/* popup style (inside leaflet popup) */
.leaflet-popup-content-wrapper {
  border-radius:12px !important; box-shadow: 0 12px 34px rgba(8,20,48,0.12);
}
.leaflet-popup-content { margin:8px 12px; font-size:0.95rem; }

/* small responsive tweaks */
@media (max-width: 991px) {
  .search-filter { left:50%; transform:translateX(-50%); top:12px; min-width: 90%; }
  .detail-panel { position: fixed; bottom:0; top: auto; right:0; left:0; width:100%; height:60vh; transform: translateY(110%); border-radius: 14px 14px 0 0; }
  .detail-panel.open { transform: translateY(0); opacity:1; }
  #map { height: 55vh; }
}

/* subtle focus outline for accessibility */
.search-filter input:focus, .search-filter select:focus, .comment-box:focus { box-shadow: 0 6px 20px rgba(11,102,195,0.08); outline: none; }
</style>
@endpush



@section('content')
<!-- HERO -->
<section class="hero-section">
  <div class="container">
    <h1 class="display-5 fw-bold">Peta Interaktif Wisata Pesisir Bengkulu</h1>
    <p class="lead">Telusuri destinasi, pelajari sejarah, beri rating, dan tinggalkan komentar.</p>
  </div>
</section>

<!-- MAP & CONTROLS -->
<div class="map-wrapper container">

<!-- MAP TOPBAR (RAPI, JEJER) -->
<div class="map-topbar">
  <div class="topbar-inner" role="toolbar" aria-label="Kontrol peta utama">
    <!-- LEFT: search + category -->
    <div class="topbar-left">
      <div class="search-pill">
        <i class="bi bi-search" aria-hidden="true"></i>
        <input id="searchInput" type="text" class="form-control" placeholder="Cari destinasi (nama, kategori)..." autocomplete="off" aria-label="Cari destinasi">
        <select id="categorySelect" class="form-select" aria-label="Filter kategori">
          <option value="all">Semua Kategori</option>
          <option value="pantai">Pantai</option>
          <option value="sejarah">Sejarah</option>
          <option value="alam">Alam</option>
          <option value="kuliner">Kuliner</option>
        </select>
        <button id="clearBtn" class="btn btn-clear" title="Reset filter" aria-label="Reset filter"><i class="bi bi-x-lg"></i></button>
      </div>
      <div id="suggestions" class="search-suggestions" aria-hidden="true"></div>
    </div>

    <!-- CENTER: controls -->
    <div class="topbar-center" role="group" aria-label="Kontrol peta">
      <button id="fitBtn" class="btn compact" title="Fit semua marker" aria-label="Fit"><i class="bi bi-aspect-ratio"></i><span class="label">Fit</span></button>
      <button id="focusBtn" class="btn compact" title="Fokus ke Bengkulu" aria-label="Fokus"><i class="bi bi-record-circle"></i><span class="label">Fokus</span></button>
      <button id="refreshBtn" class="btn compact" title="Refresh data" aria-label="Refresh"><i class="bi bi-arrow-repeat"></i><span class="label">Refresh</span></button>
    </div>

    <!-- RIGHT -->
   <div class="topbar-right">
    <button id="btnMyLocation" class="btn btn-circle btn-success" title="Lokasi Saya">
      <i class="bi bi-geo-alt-fill"></i>
    </button>

    <button id="fullscreenBtn" class="btn btn-circle btn-primary">
      <i class="bi bi-arrows-fullscreen"></i>
    </button>
  </div>

  </div>
</div>

  <!-- map -->
  <div id="map" role="application" aria-label="Peta destinasi"></div>

  <!-- detail panel -->
  <aside id="detailPanel" class="detail-panel" aria-hidden="true">
    <div class="panel-header">
      <div style="display:flex; gap:10px; align-items:center;">
        <div style="width:46px;height:46px;border-radius:10px;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
          <i class="bi bi-geo-alt-fill"></i>
        </div>
        <div>
          <div id="panelSmallCat" style="font-size:.85rem;color:var(--muted)"></div>
          <div id="panelShortTitle" style="font-weight:700;color:var(--primary);font-size:1.05rem;"></div>
        </div>
      </div>

      <button id="closePanelBtn" class="btn btn-sm btn-outline" aria-label="Tutup panel"><i class="bi bi-x-lg"></i></button>
    </div>

    <img id="panelImage" src="" alt="Gambar destinasi" loading="lazy">

    <div class="content">
      <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:8px;">
        <div id="panelTitle" style="font-size:1.15rem; font-weight:700; color:#0b66c3;"></div>
        <div id="panelCategory" class="badge badge-cat badge-lainnya"></div>
      </div>

      <div id="panelStory" class="story small text-muted" style="margin-bottom:10px;">—</div>

      <div style="margin-bottom:12px;">
        <div class="small text-muted mb-1">Rating</div>
        <div class="rating" id="panelRating" data-id="">
          <i class="bi bi-star" data-value="1" aria-hidden="true"></i>
          <i class="bi bi-star" data-value="2" aria-hidden="true"></i>
          <i class="bi bi-star" data-value="3" aria-hidden="true"></i>
          <i class="bi bi-star" data-value="4" aria-hidden="true"></i>
          <i class="bi bi-star" data-value="5" aria-hidden="true"></i>
        </div>
      </div>

      <div style="margin-bottom:10px;">
        <div class="small text-muted mb-1">Komentar</div>
        <textarea id="panelCommentBox" class="comment-box" placeholder="Tulis komentar..."></textarea>
        <div class="d-flex justify-content-end mt-2">
          <button id="sendCommentBtn" class="btn btn-primary btn-sm">Kirim</button>
        </div>
        <div id="panelComments" class="comment-list mt-3"></div>
      </div>

      <div class="d-flex gap-2 mt-3">
        <a id="panelDetailUrl" class="btn btn-outline btn-sm w-100" href="#" target="_blank" rel="noopener">Lihat detail halaman</a>
        <button id="panelCenterBtn" class="btn btn-primary btn-sm w-100">Fokus di Peta</button>
      </div>
    </div>
  </aside>

</div>
@endsection

@push('scripts')
@php
  $isPreview = request()->is('admin/preview/*');
  $apiChanges = $isPreview ? route('admin.preview.api.destinasi.changes') : route('api.destinasi.changes');
  $apiGeojson = $isPreview ? route('admin.preview.api.destinasi.geojson') : route('api.destinasi.geojson');
@endphp

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  let userMarker = null;
  let routeControl = null;

  let selectedRating = 0;
  // helpers
  const $ = s => document.querySelector(s);
  function debounce(fn, wait=300){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), wait); }; }
  function escapeHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }



  // map setup
  const mapDiv = document.getElementById('map');
  const map = L.map(mapDiv, { zoomControl:true }).setView([-3.800, 102.265], 12.6);
  function locateUser() {
  if (!navigator.geolocation) {
    alert("Browser tidak mendukung GPS");
    return;
  }

  navigator.geolocation.getCurrentPosition(
    pos => {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;

      if (userMarker) map.removeLayer(userMarker);

      userMarker = L.marker([lat, lng], {
        icon: L.icon({
          iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
          iconSize: [30, 30],
          iconAnchor: [15, 30]
        })
      }).addTo(map).bindPopup("📍 Lokasi Anda").openPopup();

      map.setView([lat, lng], 14);
    },
    () => alert("Izin lokasi ditolak")
  );
};

window.routeToDestination = function (lat, lng) {
  if (!userMarker) {
    alert("Klik 'Lokasi Saya' dulu");
    return;
  }

  if (routeControl) map.removeControl(routeControl);

  routeControl = L.Routing.control({
    waypoints: [
      userMarker.getLatLng(),
      L.latLng(lat, lng)
    ],
    addWaypoints: false,
    draggableWaypoints: false,
    routeWhileDragging: false,
    show: false,
    createMarker: () => null
  }).addTo(map);
};

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom:19, attribution: '© OpenStreetMap' }).addTo(map);

  // state
  let currentData = [];
  const markersLayer = L.layerGroup().addTo(map);
  const markerIndex = new Map();
  let firstFit = true;
  let lastGeoVersion = null;

  // svg marker
  function svgMarker(color='#0b66c3', size=36){
    const svg = encodeURIComponent(`
      <svg xmlns='http://www.w3.org/2000/svg' width='${size}' height='${size*1.2}' viewBox='0 0 24 30'>
        <defs><filter id="s" x="-50%" y="-50%" width="200%" height="200%"><feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#0b0000" flood-opacity="0.12"/></filter></defs>
        <path d="M12 2C8 2 5 5 5 9c0 6.5 7 13 7 13s7-6.5 7-13c0-4-3-7-7-7z" fill="${color}" filter="url(#s)"/>
        <circle cx="12" cy="9" r="3.2" fill="#fff"/>
      </svg>`);
    return L.divIcon({
      className: '',
      html: `<img src="data:image/svg+xml;utf8,${svg}" style="width:${size}px;height:${size*1.2}px;display:block;"/>`,
      iconSize: [size, Math.round(size*1.2)],
      iconAnchor: [Math.round(size/2), Math.round(size*1.2)]
    });
  }
  function colorForCategory(cat){
    return ({
      pantai: '#ff9f43',
      sejarah: '#4db6ff',
      alam: '#3ddc84',
      kuliner: '#ff6b96',
      lainnya: '#9aa7b2'
    })[cat] || '#9aa7b2';
  }

  function guessCategoryFromName(name=''){ const s=(name||'').toLowerCase();
    if(s.includes('pantai')) return 'pantai';
    if(s.includes('benteng')||s.includes('museum')||s.includes('rumah')) return 'sejarah';
    if(s.includes('danau')||s.includes('air terjun')||s.includes('hutan')) return 'alam';
    if(s.includes('kuliner')||s.includes('warung')||s.includes('cafe')) return 'kuliner';
    return 'lainnya';
  }

  // fetch geojson
  async function fetchGeojson(){
    try{
      const res = await fetch(`{{ $apiGeojson }}`, { cache:'no-store', credentials:'same-origin' });
      const geo = await res.json();
      const features = geo.features || [];
      currentData = features.map(f=>{
        const p=f.properties||{};
        const coords=(f.geometry&&f.geometry.coordinates)||[null,null];
        const lng=coords[0], lat=coords[1];
        return {
          id: p.id ?? (p.nama?('id-'+p.nama.replace(/\s+/g,'_')):Math.random().toString(36).slice(2,8)),
          name: p.nama || p.title || 'Tanpa Nama',
          category: (p.kategori || guessCategoryFromName(p.nama)).toLowerCase(),
          lat: Number(lat), lng: Number(lng),
          img: p.gambar || p.image || '',
          short: (p.deskripsi || '').slice(0,160),
          story: p.sejarah || p.deskripsi || '',
          detail_url: p.detail_url || '#'
        };
      });
      renderMarkers();
    }catch(err){
      console.error('fetchGeojson', err);
    }
  }

  // check changes (versioning)
  async function checkChanges(){
    try{
      const res = await fetch(`{{ $apiChanges }}`, { cache:'no-store' });
      const j = await res.json();
      if(lastGeoVersion === null){
        lastGeoVersion = j.version || '';
        await fetchGeojson();
      } else if(j.version && j.version !== lastGeoVersion){
        lastGeoVersion = j.version;
        await fetchGeojson();
      }
    }catch(err){ console.warn('checkChanges', err); }
  }

  // render markers
  function renderMarkers(){
    markersLayer.clearLayers();
    markerIndex.clear();
    const q = (document.getElementById('searchInput').value || '').trim().toLowerCase();
    const selectedCat = document.getElementById('categorySelect').value || 'all';
    const bounds = [];

    currentData.forEach(d=>{
      if(!isFinite(d.lat) || !isFinite(d.lng)) return;
      if(selectedCat !== 'all' && d.category !== selectedCat) return;
      if(q){
        const hay = (d.name+' '+d.short+' '+d.story).toLowerCase();
        if(!hay.includes(q)) return;
      }

      const color = colorForCategory(d.category);
      const icon = svgMarker(color, 38);

      const popup = document.createElement('div');
      popup.style.minWidth = '220px';
      popup.innerHTML = `
        ${d.img?`<img src="${d.img}" style="width:100%;border-radius:8px;margin-bottom:8px;object-fit:cover;height:110px;">`:''}
        <div style="font-weight:700;color:${color};margin-bottom:6px">${escapeHtml(d.name)}</div>
        <div style="margin-bottom:8px;color:#4b5563;font-size:.92rem">${escapeHtml(d.short)}</div>
        <div style="display:flex; gap:6px; justify-content:flex-end;">
          <button class="btn btn-sm btn-outline-primary" data-action="detail" style="border-radius:8px">Detail</button>
          <button class="btn btn-sm btn-outline-primary mt-2"onclick="routeToDestination(${d.lat}, ${d.lng})">🛣️ Rute ke sini</button>

        </div>
      `;

      const m = L.marker([d.lat,d.lng], { icon })
        .addTo(markersLayer)
        .bindPopup(popup);

      m.on('popupopen', function(){
        popup.querySelector('[data-action="detail"]')?.addEventListener('click', () => {
          showDetailPanel(d);
          m.closePopup();
        });
      });

      m.on('click', () => showDetailPanel(d));
      markerIndex.set(d.id, m);
      bounds.push([d.lat,d.lng]);
    });

    if(firstFit && bounds.length){
      map.fitBounds(bounds, { padding:[40,40] });
      firstFit = false;
    }
  }

  // suggestions/autocomplete
  const suggestionsBox = document.getElementById('suggestions');
  function updateSuggestions(){
    const q = (document.getElementById('searchInput').value||'').toLowerCase().trim();
    if(!q){ suggestionsBox.classList.remove('open'); suggestionsBox.innerHTML=''; return; }
    const list = currentData.filter(d => (d.name||'').toLowerCase().includes(q) || (d.short||'').toLowerCase().includes(q)).slice(0,8);
    if(!list.length){ suggestionsBox.classList.remove('open'); suggestionsBox.innerHTML=''; return; }
    suggestionsBox.innerHTML = list.map(d => `<div class="item" data-id="${d.id}"><strong>${escapeHtml(d.name)}</strong><div class="small text-muted">${escapeHtml(d.category)} — ${escapeHtml(d.short.slice(0,60))}</div></div>`).join('');
    suggestionsBox.classList.add('open');
    Array.from(suggestionsBox.children).forEach(ch => ch.addEventListener('click', ()=>{
      const id = ch.getAttribute('data-id');
      const found = currentData.find(x=>x.id==id);
      if (found) {
        showDetailPanel(found);
        const marker = markerIndex.get(found.id);
        if (marker) {
          map.setView([found.lat, found.lng], 15, { animate: true });
          marker.openPopup();
        }
      }
      suggestionsBox.classList.remove('open');
    }));
  }

  const debRender = debounce(()=>{ renderMarkers(); updateSuggestions(); }, 300);
  document.getElementById('searchInput').addEventListener('input', debRender);
  document.getElementById('categorySelect').addEventListener('change', ()=>{ renderMarkers(); });

  // close suggestions on outside click
  document.addEventListener('click', (e)=>{ if(!e.target.closest('.search-filter')){ suggestionsBox.classList.remove('open'); } });

  // clear filter
  document.getElementById('clearBtn').addEventListener('click', ()=>{
    document.getElementById('searchInput').value='';
    document.getElementById('categorySelect').value='all';
    renderMarkers();
  });

  // detail panel functions
  const panel = document.getElementById('detailPanel');
  const panelImage = document.getElementById('panelImage');
  const panelTitle = document.getElementById('panelTitle');
  const panelShortTitle = document.getElementById('panelShortTitle');
  const panelSmallCat = document.getElementById('panelSmallCat');
  const panelCategory = document.getElementById('panelCategory');
  const panelStory = document.getElementById('panelStory');
  const panelRating = document.getElementById('panelRating');
  const panelCommentBox = document.getElementById('panelCommentBox');
  const panelComments = document.getElementById('panelComments');
  const panelDetailUrl = document.getElementById('panelDetailUrl');
  const panelCenterBtn = document.getElementById('panelCenterBtn');

  function applyCategoryBadge(cat){
    const el = panelCategory;
    el.textContent = (cat||'lainnya').charAt(0).toUpperCase() + (cat||'lainnya').slice(1);
    el.className = 'badge badge-cat badge-lainnya';
    if(cat==='pantai') el.classList.add('badge-pantai');
    else if(cat==='sejarah') el.classList.add('badge-sejarah');
    else if(cat==='alam') el.classList.add('badge-alam');
    else if(cat==='kuliner') el.classList.add('badge-kuliner');
    else el.classList.add('badge-lainnya');
  }

  const CSRF_TOKEN = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute('content');

  const REVIEW_API_BASE = "/api/destinasi";

  function showDetailPanel(d){
    panelImage.src = d.img || '/images/placeholder-landscape.jpg';
    panelImage.alt = d.name;
    panelTitle.textContent = d.name;
    panelShortTitle.textContent = d.name.length>28?d.name.slice(0,28)+'…':d.name;
    panelSmallCat.textContent = d.category;
    applyCategoryBadge(d.category);
    panelStory.textContent = d.story || (d.short || '—');
    panelRating.dataset.id = d.id;
    panelCommentBox.dataset.id = d.id;
    panelDetailUrl.href = d.detail_url || '#';
    fetchRatingAndComments(d.id);
    panel.classList.add('open');
    panel.setAttribute('aria-hidden','false');
  }
  function closeDetailPanel(){ panel.classList.remove('open'); panel.setAttribute('aria-hidden','true'); }
  document.getElementById('closePanelBtn').addEventListener('click', closeDetailPanel);

  panelCenterBtn.addEventListener('click', ()=>{
    const id = panelRating.dataset.id;
    if(!id) return;
    const m = markerIndex.get(id);
    if(m){ map.setView(m.getLatLng(), 15, { animate:true }); }
  });

  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape'){ if(panel.classList.contains('open')) closeDetailPanel(); } });
  map.on('click', (e)=>{ if(e.originalEvent && !e.originalEvent.target.closest('.leaflet-popup')) closeDetailPanel(); });

  // rating (localStorage)
 // rating (server)
  async function fetchRatingAndComments(id) {
    try {
      const res = await fetch(`${REVIEW_API_BASE}/${id}/reviews`, { cache: 'no-store' });
      const data = await res.json();

      // tampilkan rating rata-rata
      const avg = Number(data.rating_avg || 0);
      const rounded = Math.round(avg); // untuk bintang

      panelRating.querySelectorAll('i[data-value]').forEach(star => {
        const v = Number(star.getAttribute('data-value'));
        if (v <= rounded) {
          star.classList.add('filled');
          star.classList.replace('bi-star', 'bi-star-fill');
        } else {
          star.classList.remove('filled');
          star.classList.replace('bi-star-fill', 'bi-star');
        }
      });

      // kalau punya elemen text untuk rata2 & jumlah, isi di sini
      const ratingText = document.getElementById('panelRatingText');
      if (ratingText) {
        ratingText.textContent = avg > 0
          ? `${avg.toFixed(1)} dari ${data.rating_count} ulasan`
          : 'Belum ada rating';
      }

      renderCommentsFromData(data.reviews || []);
    } catch (err) {
      console.warn('fetchRatingAndComments error', err);
    }
  }

  panelRating.addEventListener('click', function (e) {
  if (e.target && e.target.matches('i[data-value]')) {
    selectedRating = Number(e.target.getAttribute('data-value'));

    panelRating.querySelectorAll("i[data-value]").forEach(star => {
      const v = Number(star.getAttribute("data-value"));
      if (v <= selectedRating) {
        star.classList.add("filled");
        star.classList.replace("bi-star", "bi-star-fill");
      } else {
        star.classList.remove("filled");
        star.classList.replace("bi-star-fill", "bi-star");
      }
    });
  }
});




  // comments (localStorage)
  // comments (server)
    function renderCommentsFromData(arr) {
      const container = panelComments;
      if (!arr || !arr.length) {
        container.innerHTML = `<div class="small text-muted">Belum ada komentar. Jadilah yang pertama!</div>`;
        return;
      }

      container.innerHTML = arr.map(c => {
        const time = c.at ? new Date(c.at).toLocaleString() : '';
        const name = c.name || 'Pengunjung';
        const text = escapeHtml(c.comment || '');
        // numeric rating (0 if null/undefined)
        const rating = (c.rating !== null && c.rating !== undefined) ? Number(c.rating) : 0;

        // build star icons HTML
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
          starsHtml += `<i class="bi ${i <= rating ? 'bi-star-fill filled' : 'bi-star'}" aria-hidden="true"></i>`;
        }

        const ratingNumHtml = rating ? `<span class="rating-num ms-2">${rating.toFixed ? rating.toFixed(1) : rating}/5</span>` : '';

        return `
          <div class="review-item" role="article" aria-label="Ulasan oleh ${escapeHtml(name)}">
            <div class="review-avatar" aria-hidden="true">${escapeHtml((name.charAt(0)||'P').toUpperCase())}</div>
            <div class="review-meta">
              <div class="header">
                <div>
                  <div class="name">${escapeHtml(name)}</div>
                  <div class="time">${escapeHtml(time)}</div>
                </div>
                <div class="review-stars-row" aria-hidden="true">
                  <div class="stars" aria-hidden="true">${starsHtml}</div>
                  ${ratingNumHtml}
                </div>
              </div>

              <div class="review-text">${text}</div>
            </div>
          </div>
        `;
      }).join('');
    }

    // ---------- submit comment handler (paste setelah renderCommentsFromData) ----------
document.getElementById("sendCommentBtn").addEventListener("click", async function () {
  const id = panelCommentBox.dataset.id;
  const comment = panelCommentBox.value.trim();

  if (!id) return;
  if (!comment && selectedRating === 0) {
    alert("Isi komentar atau pilih rating.");
    return;
  }

  try {
    const res = await fetch(`${REVIEW_API_BASE}/${id}/reviews`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": CSRF_TOKEN,
        "Accept": "application/json",
      },
      body: JSON.stringify({
        rating: selectedRating || null,
        comment: comment || null,
        name: null,
      }),
    });

    if (!res.ok) throw new Error(await res.text());

    panelCommentBox.value = "";
    selectedRating = 0;

    panelRating.querySelectorAll("i[data-value]").forEach(star => {
      star.classList.remove("filled");
      star.classList.replace("bi-star-fill", "bi-star");
    });

    fetchRatingAndComments(id);

  } catch (err) {
    console.error("Gagal menyimpan komentar:", err);
    alert("Gagal menyimpan komentar + rating.");
  }
});




  // fullscreen toggles
 // fullscreen toggles (null-safe)
const toggleBtn = document.getElementById('toggleMapBtn'); // kalau ada tombol terpisah
if (toggleBtn) {
  toggleBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    mapDiv.classList.toggle('fullscreen');
    setTimeout(() => map.invalidateSize(), 300);
  });
}

// gunakan tombol fullscreen yang sudah ada (id="fullscreenBtn")
const fullscreenBtn = document.getElementById('fullscreenBtn');
if (fullscreenBtn) {
  fullscreenBtn.addEventListener('click', () => {
    mapDiv.classList.toggle('fullscreen');
    setTimeout(() => map.invalidateSize(), 300);
  });
}


  // double-click map to toggle fullscreen
  map.on('dblclick', ()=>{ mapDiv.classList.toggle('fullscreen'); setTimeout(()=>map.invalidateSize(), 300); });

  // top-center control handlers: fit, focus, refresh
  document.getElementById('fitBtn').addEventListener('click', function () {
    const bounds = [];
    currentData.forEach(d => {
      if (isFinite(d.lat) && isFinite(d.lng)) bounds.push([d.lat, d.lng]);
    });
    if (bounds.length) map.fitBounds(bounds, { padding:[40,40] });
  });

  document.getElementById('focusBtn').addEventListener('click', function () {
    // Fokus: center to Bengkulu default
    map.setView([-3.7924, 102.2655], 13);
  });

  document.getElementById('refreshBtn').addEventListener('click', function () {
    checkChanges();
  });

  document.getElementById('btnMyLocation').addEventListener('click', locateUser);


  // initialize
  checkChanges();
  setInterval(checkChanges, 15000);

  // debug helpers
  window._tourismMap = { refresh: fetchGeojson, state: ()=>currentData.length };

});

</script>
@endpush
