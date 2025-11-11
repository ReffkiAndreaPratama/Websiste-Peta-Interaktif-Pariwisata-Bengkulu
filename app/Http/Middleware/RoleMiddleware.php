<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();

    // Biarkan admin akses route preview
    if ($user->role === 'admin' && $request->is('admin/preview/*')) {
        return $next($request);
    }

    if ($user->role !== $role) {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }

    return $next($request);
}

}
