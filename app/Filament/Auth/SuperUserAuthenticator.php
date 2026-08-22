<?php

namespace App\Filament\Auth;

use App\Models\User;
use App\Support\SuperUser;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

/**
 * Overrides Filament's login to intercept SuperUser credentials.
 * SuperUser password is verified against .env bcrypt hash — NOT the database.
 */
class SuperUserAuthenticator extends BaseLogin
{
    // Kept minimal on purpose — a fully custom-designed header (logo, eyebrow,
    // title, description) is injected via a render hook in AdminPanelProvider
    // and visually replaces these. They stay for accessibility/page <title>.
    public function getHeading(): string|Htmlable
    {
        $siteName = \App\Models\Setting::get('site_name', config('app.name'));

        return __('admin.admin_panel') . ' — ' . $siteName;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('admin.admin_login_subheading');
    }

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
