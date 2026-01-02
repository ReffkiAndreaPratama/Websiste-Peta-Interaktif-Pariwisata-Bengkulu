@extends('layouts.app')

@section('title', 'Lupa Password | Dinas Pariwisata Kota Bengkulu')

@section('content')
<section class="d-flex align-items-center justify-content-center" style="min-height: 85vh; background: linear-gradient(180deg, #e8f5ff 0%, #ffffff 100%);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-lg rounded-4">
          <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4 brand-blue">Reset Password</h3>

            @if (session('status'))
              <div class="alert alert-success small text-center">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="form-control rounded-pill">
                @error('email')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="d-grid">
                <button class="btn btn-primary rounded-pill py-2 fw-semibold">Kirim Link Reset</button>
              </div>
            </form>

            <div class="text-center mt-4">
              <a href="{{ route('login') }}" class="text-decoration-none small">Kembali ke Login</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
