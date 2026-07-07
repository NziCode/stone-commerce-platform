<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

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
                'email_verified_at'  => now(),
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
