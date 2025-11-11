@extends('layouts.app')

@section('title', 'Login | Dinas Pariwisata Kota Bengkulu')

@section('content')
<section class="d-flex align-items-center justify-content-center" style="min-height: 85vh; background: linear-gradient(180deg, #e8f5ff 0%, #ffffff 100%);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-lg rounded-4">
          <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4 brand-blue">Login Akun</h3>

            <!-- Alert untuk pesan error -->
            @if (session('status'))
              <div class="alert alert-info small text-center">{{ session('status') }}</div>
            @endif
            @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
              @csrf

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control rounded-pill">
                @error('email')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <!-- Password -->
              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control rounded-pill">
                @error('password')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <!-- Remember & Forgot -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                  <label class="form-check-label small" for="remember_me">Ingat saya</label>
                </div>
                <a href="{{ route('password.request') }}" class="small text-decoration-none text-primary">Lupa Password?</a>
              </div>

              <!-- Button -->
              <div class="d-grid">
                <button class="btn btn-primary rounded-pill py-2 fw-semibold">Masuk</button>
              </div>
            </form>

            <div class="text-center mt-4">
              <p class="small mb-0">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-decoration-none text-primary fw-semibold">Daftar di sini</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
  setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
      alert.classList.remove('show');
      alert.classList.add('fade');
    }
  }, 4000);
</script>

</section>
@endsection
