<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\UserController;

/* ===========================
 * 🏠 Publik
 * =========================== */
Route::view('/', 'home')->name('home');
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
        ->whereNumber('id') // pastikan {id} numerik
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
            // Route::get('/destinasi/create', 'create')->name('destinasi.create');
            Route::post('/destinasi', 'store')->name('destinasi.store');
            Route::get('/destinasi/{id}/edit', 'edit')->whereNumber('id')->name('destinasi.edit');
            Route::put('/destinasi/{id}', 'update')->whereNumber('id')->name('destinasi.update');
            Route::delete('/destinasi/{id}', 'destroy')->whereNumber('id')->name('destinasi.destroy');
        });

        /* 👤 CRUD User (nama route: admin.user.*) */
        Route::controller(UserController::class)->group(function () {
            // Index daftar user (opsional, berbeda path dari /users agar tidak bentrok)
            Route::get('/user', 'index')->name('user.index');

            // Create (opsional)
            Route::get('/user/create', 'create')->name('user.create');

            // Store ✅ inilah yang biasanya dipanggil form: route('admin.user.store')
            Route::post('/user', 'store')->name('user.store');

            // Edit (opsional)
            Route::get('/user/{id}/edit', 'edit')->whereNumber('id')->name('user.edit');

            // Update
            Route::match(['put', 'patch'], '/user/{id}', 'update')
                ->whereNumber('id')
                ->name('user.update');

            // Destroy
            Route::delete('/user/{id}', 'destroy')->whereNumber('id')->name('user.destroy');
        });

        /* 🗺️ Peta Admin */
        Route::get('/peta', [AdminController::class, 'peta'])->name('peta');

        /* 👁️ Preview (lihat tampilan user dari sisi admin) */
        Route::get('/preview/home', [AdminController::class, 'previewHome'])->name('preview.home');
        Route::get('/preview/peta', [AdminController::class, 'previewPeta'])->name('preview.peta');
        Route::get('/preview/destinasi', [AdminController::class, 'previewDestinasi'])->name('preview.destinasi');
        Route::get('/preview/about', [AdminController::class, 'previewAbout'])->name('preview.about');

        // ❌ HAPUS: Route preview desc khusus admin
        // Route::get('/preview/desc/{id}', [UserController::class, 'desc'])->name('preview.desc');
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

/* ===========================
 * (Opsional) Publikkan API peta
 * ===========================
 * Jika ingin peta & datanya bisa diakses tanpa login,
 * pindahkan 2 route API berikut ke luar middleware user di atas:
 *
 * Route::get('/api/destinasi/changes', [DestinasiController::class, 'changes'])->name('api.destinasi.changes');
 * Route::get('/api/destinasi/geojson',  [DestinasiController::class, 'geojson'])->name('api.destinasi.geojson');
 */
