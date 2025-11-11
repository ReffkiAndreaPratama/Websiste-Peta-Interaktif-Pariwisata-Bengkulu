<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Destinasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function preview(Request $request): View
    {
        return view('profile.preview', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Tampilkan halaman detail destinasi (dipakai user & admin).
     * Menentukan URL "kembali" yang sesuai asal (admin/user) secara otomatis.
     */
    public function desc(Request $request, int $id): View
    {
        $d = Destinasi::findOrFail($id);

        // Deteksi asal navigasi dan role
        $fromAdmin = Auth::check() && Auth::user()->role === 'admin';
        $cameFromAdminArea = Str::contains(url()->previous(), '/admin');

        // Tentukan URL back default
        $back = route('destinasi'); // default ke daftar destinasi user

        if ($fromAdmin || $cameFromAdminArea) {
            // Jika punya rute preview destinasi admin, pakai itu; kalau tidak, fallback ke halaman kelola destinasi admin
            if (RouteFacade::has('admin.preview.destinasi')) {
                $back = route('admin.preview.destinasi');
            } elseif (RouteFacade::has('admin.destinasi')) {
                $back = route('admin.destinasi');
            } else {
                // Fallback terakhir tetap ke daftar destinasi user
                $back = route('destinasi');
            }
        }

        return view('profile.desc', [
            'd' => $d,
            'fromAdmin' => $fromAdmin || $cameFromAdminArea,
            'back' => $back,
        ]);
    }
}
