<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;

class HandleRedirects
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET') && !$request->is('admin*')) {
            $path     = '/' . ltrim($request->path(), '/');
            $redirect = Redirect::active()
                ->where('from_url', $path)
                ->first();

            if ($redirect) {
                $redirect->incrementHits();
                return redirect($redirect->to_url, $redirect->status_code);
            }
        }

        return $next($request);
    }
}
