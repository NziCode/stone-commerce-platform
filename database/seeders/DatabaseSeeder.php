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
            SuperUserSeeder::class,
            SettingSeeder::class,
        ]);

        // Gitignored — only present where someone has generated it locally via
        // `php artisan db:sync-seeders SettingValuesSeeder`. Absent on a fresh
        // checkout, so Settings just keep the empty placeholders from SettingSeeder.
        if (class_exists(SettingValuesSeeder::class)) {
            $this->call(SettingValuesSeeder::class);
        }

        $this->call([
            CategorySeeder::class,
            MenuSeeder::class,
            SliderSeeder::class,
            AttributeSeeder::class,
            TranslationSeeder::class,
            ProductSeeder::class,
            EventSeeder::class,
            PostSeeder::class,
            PageSeeder::class,
        ]);
    }
}
