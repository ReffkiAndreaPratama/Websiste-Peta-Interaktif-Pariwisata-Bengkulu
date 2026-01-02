<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan hanya user dengan role 'admin'
     * yang bisa mengakses route yang dilindungi.
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika belum login, arahkan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika sudah login tapi bukan admin
        if (Auth::user()->role !== 'admin') {
            // Bisa diarahkan ke halaman lain (misalnya dashboard biasa)
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya admin yang bisa mengakses halaman ini.');
        }

        // Jika user adalah admin, lanjutkan request
        return $next($request);
    }
}
