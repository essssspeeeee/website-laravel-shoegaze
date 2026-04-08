<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->user()) {
            $request->user()->forceFill([
                'last_seen' => now(),
            ])->save();
        }

        return $response;
    }
}
