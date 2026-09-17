<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\SuperUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // The SuperUser's password lives only in .env — never let the public
        // password-reset flow set a real, DB-authenticatable password on it.
        $envEmail = config('auth.super_user.email');
        if ($envEmail && strtolower($request->string('email')) === strtolower($envEmail)) {
            return back()->with('status', __(\Illuminate\Auth\Passwords\PasswordBroker::RESET_LINK_SENT));
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
