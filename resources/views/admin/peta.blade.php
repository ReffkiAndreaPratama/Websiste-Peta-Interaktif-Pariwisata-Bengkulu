@extends('layouts.admin')
@section('title', 'Kelola Peta Destinasi')

@section('content')
<div class="container-fluid mt-3">

  <h3 class="fw-bold mb-3">Daftar Peta Interaktif</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- ===================== FULL WIDTH MAP ===================== --}}
  <div class="card shadow-sm mb-4" id="mapCard">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
      <strong>Peta Lokasi Destinasi</strong>

      <div class="d-flex gap-2">
        <button class="btn btn-light btn-sm" id="btnFitBounds"><i class="bi bi-bounding-box-circles me-1"></i> Fit</button>
        <button class="btn btn-light btn-sm" id="btnCenterBengkulu"><i class="bi bi-crosshair me-1"></i> Fokus</button>
        <button class="btn btn-light btn-sm" id="btnRefreshTiles"><i class="bi bi-arrow-repeat me-1"></i> Refresh</button>
        <button class="btn btn-warning btn-sm" id="btnFullscreen"><i class="bi bi-arrows-fullscreen me-1"></i> Fullscreen</button>
        <button class="btn btn-success btn-sm" id="btnMyLocation"><i class="bi bi-geo-alt me-1"></i> Lokasi Saya</button>
      </div>
    </div>

    <div class="card-body p-2">
      <div id="map" style="height: 650px; border-radius: 8px;"></div>
      <div class="small text-muted mt-1" id="mapMeta">Marker: 0 titik</div>
    </div>
  </div>

  {{-- ===================== FULL WIDTH TABLE ===================== --}}
  <div class="card shadow-sm mb-5">
    <div class="card-header bg-dark text-white py-2">
      <strong>Daftar Titik Destinasi</strong>
    </div>

    <div class="card-body table-responsive p-2">
      <table class="table table-hover table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:60px">#</th>
            <th>Nama</th>
            <th style="width:180px">Kategori</th>
            <th style="width:180px">Latitude</th>
            <th style="width:180px">Longitude</th>
          </tr>
        </thead>
        <tbody>
          @foreach($destinasi as $d)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $d->nama }}</td>
              <td>{{ $d->kategori }}</td>
              <td>{{ $d->latitude ?? '-' }}</td>
              <td>{{ $d->longitude ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>


{{-- ===================== FULLSCREEN MAP CSS ===================== --}}
<style>
  #mapCard.fullscreen {
    position: fixed;
    inset: 60px 0 0 0;
    z-index: 2000;
    border-radius: 0 !important;
  }

  #mapCard.fullscreen #map {
    height: calc(100vh - 80px) !important;
    border-radius: 0 !important;
  }

  @media (max-width: 991px) {
    #mapCard.fullscreen {
      inset: 55px 0 0 0;
    }
    #mapCard.fullscreen #map {
      height: calc(100vh - 70px) !important;
    }
  }
</style>


{{-- ===================== LEAFLET ===================== --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Routing -->
<link rel="stylesheet"
  href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css">
<script
  src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js">
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {

  let userMarker = null;
  let routeControl = null;

  const BENGKULU = [-3.7924, 102.2655];
  const map = L.map('map').setView(BENGKULU, 13);

  const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  const destinasi = @json($destinasi);
  const markersGroup = L.featureGroup().addTo(map);

  // ================= MARKER DESTINASI =================
  destinasi.forEach(d => {
    if (d.latitude && d.longitude) {
      const marker = L.marker([d.latitude, d.longitude]).addTo(markersGroup);

      marker.bindPopup(`
        <b>${d.nama}</b><br>
        <small>${d.kategori}</small><br>
        <button class="btn btn-sm btn-primary mt-2"
          onclick="routeToDestination(${d.latitude}, ${d.longitude})">
          🛣️ Rute ke sini
        </button>
      `);
    }
  });

  if (markersGroup.getLayers().length > 0) {
    map.fitBounds(markersGroup.getBounds().pad(0.2));
  }

  // ================= GPS USER =================
  window.locateUser = function () {
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

  // ================= ROUTING =================
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

  // ================= BUTTON ACTIONS =================
  document.getElementById('btnFitBounds').onclick = () => {
    if (markersGroup.getLayers().length > 0) {
      map.fitBounds(markersGroup.getBounds().pad(0.2));
    }
  };

  document.getElementById('btnCenterBengkulu').onclick = () => {
    map.setView(BENGKULU, 13);
  };

  document.getElementById('btnRefreshTiles').onclick = () => {
    osm.setUrl(`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?t=${Date.now()}`);
  };

  document.getElementById('btnFullscreen').onclick = () => {
    document.getElementById('mapCard').classList.toggle('fullscreen');
    setTimeout(() => map.invalidateSize(), 300);
  };

  document.getElementById('btnMyLocation').onclick = locateUser;

});
</script>


@endsection
