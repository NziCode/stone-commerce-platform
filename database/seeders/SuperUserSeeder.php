<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\SuperUser;
use Illuminate\Database\Seeder;

/**
 * Creates the SuperUser record in the database.
 *
 * IMPORTANT:
 *  - The DB password is intentionally set to a random unusable string.
 *  - Authentication is done via .env SUPER_USER_PASSWORD_HASH.
 *  - Never set a real bcrypt password in the DB for this user.
 *
 * Before running, set in .env:
 *   SUPER_USER_EMAIL=super@yourdomain.com
 *   SUPER_USER_PASSWORD_HASH=<run: php artisan superuser:hash>
 */
class SuperUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('auth.super_user.email');

        if (!$email) {
            $this->command->warn('SUPER_USER_EMAIL not set in .env — skipping SuperUserSeeder.');
            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'       => 'Super User',
                'password'   => SuperUser::dbPasswordPlaceholder(), // NOT a real hash
                'is_active'  => true,
                'locale'     => 'fa',
            ]
        );

        // Assign the 'admin' role so Filament panel access works
        // (canAccessPanel also checks isSuperUser, so this is just for completeness)
        if (!\Spatie\Permission\Models\Role::where('name', 'super_user')->exists()) {
            \Spatie\Permission\Models\Role::create([
                'name'       => 'super_user',
                'guard_name' => 'web',
            ]);
        }

        $user->syncRoles(['super_user', 'admin']);

        $this->command->info("SuperUser created: {$email}");
        $this->command->warn('Remember: password is stored in .env, NOT in the database.');
    }
}
