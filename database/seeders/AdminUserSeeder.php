<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

/**
 * Env-driven, generic by default — never commit real admin credentials here.
 * Set ADMIN_SEED_EMAIL / ADMIN_SEED_PASSWORD in .env to seed a real account,
 * and change the password immediately after first login.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin',
                'email'    => env('ADMIN_SEED_EMAIL', 'admin@example.com'),
                'password' => bcrypt(env('ADMIN_SEED_PASSWORD', 'ChangeMe123!')),
                'role'     => 'admin',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                $data
            );

            $user->assignRole($role);
        }
    }
}
