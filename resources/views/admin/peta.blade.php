@extends('layouts.admin')
@section('title', 'Kelola Peta Destinasi')

@section('content')
<div class="container mt-4">
  <h3 class="fw-bold mb-4">Kelola Peta Interaktif</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- PETA --}}
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">Peta Lokasi Destinasi</div>
    <div class="card-body">
      <div id="map" style="height: 500px; border-radius: 8px;"></div>
    </div>
  </div>

  {{-- TABEL DATA DESTINASI --}}
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">Daftar Titik Destinasi</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Latitude</th>
            <th>Longitude</th>
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

{{-- ====== SCRIPT LEAFLET ====== --}}
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Inisialisasi peta di Bengkulu
  var map = L.map('map').setView([-3.7924, 102.2655], 13);

  // Tambahkan tile layer (OpenStreetMap)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>'
  }).addTo(map);

  // Data destinasi dari Laravel
  var destinasi = @json($destinasi);

  // Tambahkan marker untuk setiap destinasi
  destinasi.forEach(function(d) {
    if (d.latitude && d.longitude) {
      var marker = L.marker([d.latitude, d.longitude]).addTo(map);
      marker.bindPopup(`
        <b>${d.nama}</b><br>
        <small>${d.kategori}</small><br>
        <i>${d.deskripsi ? d.deskripsi.substring(0, 80) + '...' : ''}</i>
      `);
    }
  });

  // Jika tidak ada titik, tampilkan pesan
  if (destinasi.length === 0) {
    L.popup()
      .setLatLng([-3.7924, 102.2655])
      .setContent("Belum ada destinasi ditambahkan.")
      .openOn(map);
  }
});
</script>
@endsection
