<?php

namespace App\Http\Middleware;

use App\Support\SuperUser;
use Closure;
use Illuminate\Http\Request;

/**
 * Blocks any attempt to update the SuperUser's password via the profile form.
 * The SuperUser password lives in .env — it must never be changed from the DB.
 */
class EnforceSuperUserPassword
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && SuperUser::is(auth()->user())) {
            // Strip password fields from any request by the SuperUser
            $request->request->remove('password');
            $request->request->remove('password_confirmation');
            $request->request->remove('current_password');
        }

        return $next($request);
    }
}
