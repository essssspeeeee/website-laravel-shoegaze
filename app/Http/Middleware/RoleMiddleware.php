<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Mengecek apakah user sudah login dan apakah role-nya sesuai
        if (Auth::check()) {
            $userRole = strtolower($request->user()->role);
            $allowed = array_map(fn($r) => strtolower($r), $roles);
            if (in_array($userRole, $allowed)) {
                return $next($request);
            }
        }

        // Jika tidak punya akses, lempar ke halaman 403 (Forbidden)
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}