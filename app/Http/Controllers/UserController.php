<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Destinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * 👥 Tampilkan semua pengguna (admin)
     * Dengan fitur pencarian dan filter berdasarkan role.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // 🔍 Filter pencarian berdasarkan nama atau email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // 🏷️ Filter berdasarkan role (admin/user)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Urutkan dari yang terbaru dan gunakan pagination
        $users = $query->latest()->paginate(10);

        return view('admin.users', compact('users'));
    }

    /**
     * ➕ Tambah pengguna baru (admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return back()->with('success', '✅ Pengguna baru berhasil ditambahkan!');
    }

    /**
     * ✏️ Update data pengguna (admin)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,user',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', '✅ Data pengguna berhasil diperbarui!');
    }

    /**
     * 🗑️ Hapus pengguna (admin)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', '🗑️ Pengguna berhasil dihapus!');
    }

    /* ============================================================
     * 👁️ PREVIEW HALAMAN USER (untuk admin melihat tampilan user)
     * ============================================================ */

    /**
     * 🏠 Halaman beranda user
     */
    public function home()
    {
        return view('home'); // resources/views/home.blade.php
    }

    /**
     * 🧭 Halaman daftar destinasi (user)
     */
    public function destinasi()
    {
        $destinasi = Destinasi::latest()->get();
        return view('destinasi', compact('destinasi'));
    }

    /**
     * 🗺️ Halaman peta (user)
     */
    public function peta()
    {
        $destinasi = Destinasi::latest()->get();
        return view('peta', compact('destinasi'));
    }

    /**
     * ℹ️ Halaman tentang (user)
     */
    public function about()
    {
        return view('about'); // resources/views/about.blade.php
    }
}
