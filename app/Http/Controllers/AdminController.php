<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Destinasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    /* ============================================================
    |  1. DASHBOARD
    |============================================================ */
    public function dashboard()
    {
        $totalDestinasi = Destinasi::count();
        $totalUsers     = User::count();
        $adminAktif     = User::where('role', 'admin')->count();

        $totalReview = 0;
        if (Schema::hasTable('reviews')) {
            try {
                $totalReview = DB::table('reviews')->count();
            } catch (\Throwable $e) {
                $totalReview = 0;
            }
        }

        $activities = $this->buildRecentActivities();

        return view('admin.dashboard', compact(
            'totalDestinasi',
            'totalUsers',
            'adminAktif',
            'totalReview',
            'activities'
        ));
    }

    /** Build activity timeline */
    private function buildRecentActivities(): Collection
    {
        $items = collect();

        /* === Destinasi === */
        $recentDest = Destinasi::orderByDesc('updated_at')
            ->take(8)
            ->get()
            ->map(function ($d) {
                $when = $d->updated_at ?: $d->created_at;
                $isNew = $d->created_at->eq($d->updated_at);
                $label = $isNew ? 'Ditambahkan destinasi' : 'Diperbarui destinasi';

                return [
                    'when' => $when,
                    'text' => "$label: {$d->nama}",
                ];
            });

        /* === User === */
        $recentUsers = User::orderByDesc('created_at')
            ->take(8)
            ->get()
            ->map(fn($u) => [
                'when' => $u->created_at,
                'text' => "Pengguna baru: {$u->name}",
            ]);

        /* === Review === */
        $recentReviews = collect();
        if (Schema::hasTable('reviews')) {
            try {
                $recentReviews = DB::table('reviews')
                    ->orderByDesc('created_at')
                    ->take(8)
                    ->get()
                    ->map(function ($r) {
                        $user = User::find($r->user_id);
                        $dest = Destinasi::find($r->destinasi_id);

                        return [
                            'when' => Carbon::parse($r->created_at),
                            'text' => "Review baru oleh " .
                                ($user->name ?? 'Pengguna') .
                                " di " .
                                ($dest->nama ?? 'Destinasi'),
                        ];
                    });
            } catch (\Throwable $e) {
                $recentReviews = collect();
            }
        }

        return $items
            ->merge($recentDest)
            ->merge($recentUsers)
            ->merge($recentReviews)
            ->sortByDesc('when')
            ->take(10)
            ->map(fn($row) =>
                $row['text'] . ' • ' . Carbon::parse($row['when'])->diffForHumans()
            )
            ->values();
    }

    /* ============================================================
    |  2. CRUD ADMIN
    |============================================================ */

    public function destinasi()
    {
        $destinasi = Destinasi::all();
        return view('admin.destinasi', compact('destinasi'));
    }

    public function peta()
    {
        $destinasi = Destinasi::all();
        return view('admin.peta', compact('destinasi'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    /* ============================================================
    |  3. LOGOUT
    |============================================================ */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('status', 'Anda telah logout sebagai admin.');
    }

    /* ============================================================
    |  4. PREVIEW SEBAGAI USER
    |============================================================ */

    /** Home */
    public function previewHome()
    {
        // 🔥 TOP 3 DESTINASI BERDASARKAN RATING
        $topByRating = Destinasi::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(3)
            ->get();

        return view('home', compact('topByRating'));
    }


    /** Peta user */
    public function previewPeta()
    {
        session(['preview_as_user' => true]);

        $destinasi = Destinasi::all();

        return view('peta', [
            'destinasi' => $destinasi,
            'isPreview' => true,
        ]);
    }

    /** Daftar destinasi user */
    public function previewDestinasi()
    {
        session(['preview_as_user' => true]);

        $destinasi = Destinasi::all();

        return view('destinasi', [
            'destinasi' => $destinasi,
            'isPreview' => true,
        ]);
    }

    /** About user */
    public function previewAbout()
    {
        session(['preview_as_user' => true]);
        return view('about', ['isPreview' => true]);
    }

    /** Deskripsi destinasi user */
    public function previewDesc($id)
    {
        session(['preview_as_user' => true]);

        $destinasi = Destinasi::findOrFail($id);

        return view('user.desc', [
            'data' => $destinasi,
            'isPreview' => true,
        ]);
    }

    /** ============================================================
    |  PREVIEW ADMIN → TAMPILAN USER
    |============================================================ */
    public function previewAdminPeta()
    {
        session(['preview_as_user' => true]);

        $destinasi = Destinasi::all();

        return view('peta', [
            'destinasi' => $destinasi,
            'isPreview' => true,
        ]);
    }
}
