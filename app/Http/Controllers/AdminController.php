<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Destinasi;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Dashboard utama admin.
     */
    public function dashboard()
    {
        $totalDestinasi = Destinasi::count();
        $totalUsers = User::count();
        $totalReview = 0; // tambahkan jika ada tabel review
        $adminAktif = User::where('role', 'admin')->count();

        return view('admin.dashboard', compact('totalDestinasi', 'totalUsers', 'totalReview', 'adminAktif'));
    }

    /**
     * Halaman destinasi admin (CRUD).
     */
    public function destinasi()
    {
        $destinasi = Destinasi::all();
        return view('admin.destinasi', compact('destinasi'));
    }

    /**
     * Halaman kelola peta admin.
     */
    public function peta()
    {
        $destinasi = \App\Models\Destinasi::all(); // ambil data destinasi dari database
        return view('admin.peta', compact('destinasi'));
    }

    /**
     * Halaman daftar pengguna (admin).
     */
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    /**
     * Fungsi Logout Khusus Admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Anda telah logout sebagai admin.');
    }

    // ===========================
    // Preview sebagai User
    // ===========================

    /**
     * Preview halaman home sebagai user.
     */
    public function previewHome()
    {
        // set flag session agar bisa tampil preview (opsional)
        session(['preview_as_user' => true]);

        return view('home'); // view yang sama seperti user
    }

    /**
     * Preview halaman peta interaktif sebagai user.
     */
    public function previewPeta()
{
    session(['preview_as_user' => true]); // opsional

    $destinasi = \App\Models\Destinasi::all();

    // kirim flag isPreview => Blade menampilkan mode preview (tanpa CRUD)
    return view('admin.peta', [
        'destinasi' => $destinasi,
        'isPreview' => true,
    ]);
}

    /**
     * Preview halaman destinasi sebagai user.
     */
    public function previewDestinasi()
    {
        session(['preview_as_user' => true]);

        $destinasi = Destinasi::all();
        return view('destinasi', compact('destinasi'));
    }

    /**
     * Preview halaman about sebagai user.
     */
    public function previewAbout()
    {
        session(['preview_as_user' => true]);

        return view('about');
    }
}
