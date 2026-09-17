<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds real site/client
 * content (name, contact info, addresses, gateway config, etc.). Every
 * Setting::get() call in the app already has a code-level fallback default,
 * so the site works fine with no rows; fill in real values from the admin
 * panel's Settings page after a fresh install.
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->delete();
    }
}
