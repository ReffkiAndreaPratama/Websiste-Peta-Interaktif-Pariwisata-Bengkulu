<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\DestinasiReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestinasiReviewController extends Controller
{
    /**
     * GET /api/destinasi/{id}/reviews
     * Mengembalikan rating_avg, rating_count, dan list reviews terbaru.
     */
    public function index($id)
    {
        $dest = Destinasi::findOrFail($id);

        $reviews = $dest->reviews()->latest()->get()->map(function($r){
            return [
                'id'      => $r->id,
                'name'    => $r->name ?? 'Pengunjung',
                'rating'  => $r->rating !== null ? (float) $r->rating : null,
                'comment' => $r->comment,
                'at'      => optional($r->created_at)->toIso8601String(),
            ];
        });

        $ratingAvg = $dest->reviews()->avg('rating') ?? 0;
        $ratingCount = $dest->reviews()->count();

        return response()->json([
            'rating_avg'   => round((float)$ratingAvg, 2),
            'rating_count' => (int)$ratingCount,
            'reviews'      => $reviews,
        ]);
    }

    /**
     * POST /api/destinasi/{id}/reviews
     * Simpan review baru ke tabel destinasi_reviews dan kembalikan payload segar.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating'  => 'nullable|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'name'    => 'nullable|string|max:100',
        ]);

        $dest = Destinasi::findOrFail($id);

        DB::transaction(function() use ($request, $dest, &$review) {
            $review = $dest->reviews()->create([
                'name'    => $request->input('name') ?? optional($request->user())->name ?? 'Pengunjung',
                'rating'  => $request->input('rating'),
                'comment' => $request->input('comment'),
                'user_id' => optional($request->user())->id,
            ]);
        });

        // fresh stats + single review payload
        $ratingAvg = round($dest->reviews()->avg('rating') ?? 0, 2);
        $ratingCount = $dest->reviews()->count();

        return response()->json([
            'success'      => true,
            'rating_avg'   => $ratingAvg,
            'rating_count' => $ratingCount,
            'review'       => [
                'id'      => $review->id,
                'name'    => $review->name ?? 'Pengunjung',
                'rating'  => $review->rating !== null ? (float)$review->rating : null,
                'comment' => $review->comment,
                'at'      => optional($review->created_at)->toIso8601String(),
            ],
        ], 201);
    }
}
