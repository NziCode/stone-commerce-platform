<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin*')) {
            return $next($request);
        }

        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->last_login_at || $user->last_login_at->diffInMinutes(now()) > 30) {
                $user->recordLogin($request->ip());
            }
        }

        return $response;
    }
}
