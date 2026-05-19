<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\DestinasiReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\HomeController;


/* ===========================
 * 🏠 Publik
 * =========================== */

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');

/* ===========================
 * 🔐 Auth (Breeze/Fortify)
 * =========================== */
require __DIR__ . '/auth.php';

/* ===========================
 * 🌍 USER (wajib login, role:user)
 * =========================== */
Route::middleware(['auth', 'role:user'])->group(function () {

    // Halaman user
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Destinasi & Peta (khusus setelah login)
    Route::get('/destinasi', [DestinasiController::class, 'layoutIndex'])->name('destinasi');
    Route::get('/destinasi/{id}', [DestinasiController::class, 'show'])
        ->whereNumber('id')
        ->name('destinasi.detail');

    Route::get('/peta', [DestinasiController::class, 'layoutPeta'])->name('peta');

    /* === API ringan untuk peta (auto-sync CRUD admin) === */
    Route::get('/api/destinasi/changes', [DestinasiController::class, 'changes'])->name('api.destinasi.changes');
    Route::get('/api/destinasi/geojson',  [DestinasiController::class, 'geojson'])->name('api.destinasi.geojson');

    // Profil (edit/update/destroy)
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

/* ===========================
 * 🧭 DETAIL DESTINASI (dipakai user & admin)
 * =========================== */
Route::middleware(['auth'])->group(function () {
    // Detail deskripsi (desc.blade.php) — dipakai bersama
    Route::get('/profile/desc/{id}', [ProfileController::class, 'desc'])
        ->whereNumber('id')
        ->name('profile.desc');
});

/* ===========================
 * ⚙️ ADMIN (wajib login, role:admin)
 * =========================== */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        // Dashboard & umum
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Halaman daftar user versi AdminController (tetap dipertahankan)
        Route::get('/users', [AdminController::class, 'users'])->name('users');

        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        /* 🧭 CRUD Destinasi */
        Route::controller(DestinasiController::class)->group(function () {
            Route::get('/destinasi', 'index')->name('destinasi');
            Route::post('/destinasi', 'store')->name('destinasi.store');
            Route::get('/destinasi/{id}/edit', 'edit')->whereNumber('id')->name('destinasi.edit');
            Route::put('/destinasi/{id}', 'update')->whereNumber('id')->name('destinasi.update');
            Route::delete('/destinasi/{id}', 'destroy')->whereNumber('id')->name('destinasi.destroy');
        });

        /* 👤 CRUD User (nama route: admin.user.*) */
        Route::controller(UserController::class)->group(function () {
            Route::get('/user', 'index')->name('user.index');
            Route::get('/user/create', 'create')->name('user.create');
            Route::post('/user', 'store')->name('user.store');
            Route::get('/user/{id}/edit', 'edit')->whereNumber('id')->name('user.edit');
            Route::match(['put', 'patch'], '/user/{id}', 'update')
                ->whereNumber('id')
                ->name('user.update');
            Route::delete('/user/{id}', 'destroy')->whereNumber('id')->name('user.destroy');
        });

        /* 🗺️ Peta Admin */
        Route::get('/peta', [AdminController::class, 'peta'])->name('peta');

        /* 👁️ Preview (lihat tampilan user dari sisi admin) */
        Route::get('/preview/home', [AdminController::class, 'previewHome'])->name('preview.home');
        Route::get('/preview/peta', [AdminController::class, 'previewPeta'])->name('preview.peta');
        Route::get('/preview/destinasi', [AdminController::class, 'previewDestinasi'])->name('preview.destinasi');
        Route::get('/preview/about', [AdminController::class, 'previewAbout'])->name('preview.about');

        /* ⭐ Fitur Baru — Preview Admin Peta (versi user tapi untuk admin) */
        Route::get('/preview/admin-peta', [AdminController::class, 'previewAdminPeta'])->name('preview.adminPeta');
    });

/* ===========================
 * 📡 API MIRROR untuk Preview Admin (tanpa mengubah API lama)
 * =========================== */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/preview')
    ->as('admin.preview.')
    ->group(function () {
        Route::get('/api/destinasi/changes', [DestinasiController::class, 'changes'])
            ->name('api.destinasi.changes');

        Route::get('/api/destinasi/geojson', [DestinasiController::class, 'geojson'])
            ->name('api.destinasi.geojson');
    });

    Route::prefix('api/destinasi')->group(function () {
    Route::get('{destinasi}/reviews', [DestinasiReviewController::class, 'index'])
        ->name('api.destinasi.reviews.index');

    Route::post('{destinasi}/reviews', [DestinasiReviewController::class, 'store'])
        ->name('api.destinasi.reviews.store')
        ->middleware('throttle:20,1'); // batasi spam
});

// API untuk reviews (single source, PUBLIC — sesuaikan middleware jika ingin auth)
Route::prefix('api/destinasi')->group(function () {
    // GET /api/destinasi/{id}/reviews
    Route::get('{id}/reviews', [DestinasiReviewController::class, 'index'])
        ->name('api.destinasi.reviews.index');

    // POST /api/destinasi/{id}/reviews
    Route::post('{id}/reviews', [DestinasiReviewController::class, 'store'])
        ->name('api.destinasi.reviews.store')
        ->middleware('throttle:20,1'); // batasi spam
});


Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

/* ===========================
 * 🚀 SETUP ROUTES (untuk deploy tanpa SSH)
 * Hapus setelah setup selesai!
 * =========================== */
Route::prefix('setup')->group(function () {
    // Jalankan migration
    Route::get('/migrate', function () {
        if (app()->environment('production') && request('key') !== 'petainteraktif2025') {
            abort(403);
        }
        \Artisan::call('migrate', ['--force' => true]);
        return '<pre>' . \Artisan::output() . '</pre><p>✅ Migration selesai!</p>';
    });

    // Jalankan seeder
    Route::get('/seed', function () {
        if (app()->environment('production') && request('key') !== 'petainteraktif2025') {
            abort(403);
        }
        \Artisan::call('db:seed', ['--force' => true]);
        return '<pre>' . \Artisan::output() . '</pre><p>✅ Seeder selesai!</p>';
    });

    // Buat storage symlink
    Route::get('/storage-link', function () {
        if (app()->environment('production') && request('key') !== 'petainteraktif2025') {
            abort(403);
        }
        \Artisan::call('storage:link');
        return '<pre>' . \Artisan::output() . '</pre><p>✅ Storage link dibuat!</p>';
    });

    // Clear cache
    Route::get('/clear-cache', function () {
        if (app()->environment('production') && request('key') !== 'petainteraktif2025') {
            abort(403);
        }
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        return '<p>✅ Semua cache dibersihkan!</p>';
    });
});


