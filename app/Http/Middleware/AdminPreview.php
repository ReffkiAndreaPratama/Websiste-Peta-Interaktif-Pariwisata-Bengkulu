<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AdminPreview
{
    public function handle(Request $request, Closure $next)
    {
        // Hanya admin yang bisa preview
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Cek apakah admin klik tombol preview
        if (!Session::get('preview_mode', false)) {
            abort(403, 'Preview mode is not enabled.');
        }

        return $next($request);
    }
}
