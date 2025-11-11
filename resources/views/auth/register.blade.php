@extends('layouts.app')

@section('title', 'Register | Dinas Pariwisata Kota Bengkulu')

@section('content')
<section class="py-5" style="min-height: 100vh; background: linear-gradient(180deg, #e8f5ff 0%, #ffffff 100%);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">
        <div class="card border-0 shadow-lg rounded-4 mt-4 mb-5">
          <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4 brand-blue">Buat Akun Baru</h3>

            <form method="POST" action="{{ route('register') }}">
              @csrf

              <!-- Nama Lengkap -->
              <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control rounded-pill">
                @error('name')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control rounded-pill">
                @error('email')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <!-- Password -->
              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control rounded-pill">
                @error('password')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <!-- Konfirmasi Password -->
              <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control rounded-pill">
              </div>

              <!-- Tombol -->
              <div class="d-grid">
                <button class="btn btn-primary rounded-pill py-2 fw-semibold">Daftar</button>
              </div>
            </form>

            <div class="text-center mt-4">
              <p class="small mb-0">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-semibold">Login di sini</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
