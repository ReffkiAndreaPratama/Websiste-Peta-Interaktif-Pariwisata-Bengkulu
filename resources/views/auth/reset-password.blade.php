@extends('layouts.app')

@section('title', 'Ganti Password | Dinas Pariwisata Kota Bengkulu')

@section('content')
<section class="d-flex align-items-center justify-content-center" style="min-height: 85vh; background: linear-gradient(180deg, #e8f5ff 0%, #ffffff 100%);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-lg rounded-4">
          <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4 brand-blue">Buat Password Baru</h3>

            <form method="POST" action="{{ route('password.store') }}">
              @csrf

              <input type="hidden" name="token" value="{{ $request->route('token') }}">

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="email" class="form-control rounded-pill">
                @error('email')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password Baru</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control rounded-pill">
                @error('password')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control rounded-pill">
              </div>

              <div class="d-grid">
                <button class="btn btn-primary rounded-pill py-2 fw-semibold">Reset Password</button>
              </div>
            </form>

            <div class="text-center mt-3">
              <a href="{{ route('login') }}" class="text-decoration-none small">Kembali ke Login</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
