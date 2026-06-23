<?php

namespace App\Support;

use Illuminate\Support\Facades\Hash;

class SuperUser
{
    /**
     * Check if the given user is the SuperUser.
     * Comparison is done against .env — NOT the database.
     */
    public static function is(\App\Models\User $user): bool
    {
        $envEmail = config('auth.super_user.email');
        $envHash  = config('auth.super_user.password_hash');

        if (!$envEmail || !$envHash) {
            return false;
        }

        return strtolower($user->email) === strtolower($envEmail);
    }

    /**
     * Verify the plain password against the .env hash.
     * Used during login to authenticate the SuperUser.
     */
    public static function verifyPassword(string $plain): bool
    {
        $hash = config('auth.super_user.password_hash');
        if (!$hash) return false;

        return Hash::check($plain, $hash);
    }

    /**
     * Generate a bcrypt hash to paste into .env
     * Run: php artisan tinker --execute="echo App\Support\SuperUser::generateHash('your_password');"
     */
    public static function generateHash(string $plain): string
    {
        return Hash::make($plain, ['rounds' => 12]);
    }

    /**
     * The DB password placeholder — never a real hash.
     */
    public static function dbPasswordPlaceholder(): string
    {
        return 'SUPER_USER_NO_DB_AUTH_' . bin2hex(random_bytes(16));
    }
}
