<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            SettingSeeder::class,
            CategorySeeder::class,
            MenuSeeder::class,
            SliderSeeder::class,
            AttributeSeeder::class,
            TranslationSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
