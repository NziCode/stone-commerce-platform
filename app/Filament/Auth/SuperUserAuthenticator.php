<?php

namespace App\Filament\Auth;

use App\Models\User;
use App\Support\SuperUser;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;

/**
 * Overrides Filament's login to intercept SuperUser credentials.
 * SuperUser password is verified against .env bcrypt hash — NOT the database.
 */
class SuperUserAuthenticator extends BaseLogin
{
    public function authenticate(): LoginResponse
    {
        $data     = $this->form->getState();
        $envEmail = config('auth.super_user.email');

        // ── SuperUser path ───────────────────────────────────────
        if ($envEmail && strtolower($data['email']) === strtolower($envEmail)) {

            if (!SuperUser::verifyPassword($data['password'])) {
                $this->throwFailureValidationException();
            }

            $user = User::where('email', $envEmail)->firstOrFail();
            Auth::login($user, $data['remember'] ?? false);

            return app(LoginResponse::class);
        }

        // ── Normal admin path ────────────────────────────────────
        return parent::authenticate();
    }
}
