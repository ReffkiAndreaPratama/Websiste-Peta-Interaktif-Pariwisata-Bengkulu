<?php
namespace App\Http\Controllers;

use App\Models\Destinasi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Top berdasarkan rata-rata rating (minimal 1 ulasan)
        $topByRating = Destinasi::withCount('reviews')
            ->withAvg('reviews','rating')
            ->having('reviews_count', '>', 0)
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->take(3)
            ->get();

        // Top berdasarkan jumlah ulasan
        $topByReviews = Destinasi::withCount('reviews')
            ->withAvg('reviews','rating')
            ->orderByDesc('reviews_count')
            ->orderByDesc('reviews_avg_rating')
            ->take(3)
            ->get();

        return view('home', compact('topByRating','topByReviews'));
    }
}
