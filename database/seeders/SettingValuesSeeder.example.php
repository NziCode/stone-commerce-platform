<?php

/**
 * Copy this file to SettingValuesSeeder.php (gitignored — see .gitignore)
 * to seed real Settings content locally. It's created automatically the
 * first time you run:
 *
 *   php artisan db:sync-seeders SettingValuesSeeder
 *
 * which fills it in from whatever is currently in your database. Re-run
 * the same command any time after editing Settings in the admin panel to
 * refresh it. Never commit the real SettingValuesSeeder.php — it holds
 * actual site/client content (name, contact info, translations, etc.).
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingValuesSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            // BEGIN: db-sync — run `php artisan db:sync-seeders SettingValuesSeeder` to refresh this block from the database; don't hand-edit it, your changes will be overwritten on the next sync.
            'site_name' => '{"fa":"Your Company","en":"Your Company"}',
            // END: db-sync
        ];

        foreach ($values as $key => $value) {
            // Query-builder update() doesn't fire model events, so it won't
            // invalidate Setting's cache on its own — cleared once below instead.
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget(Setting::CACHE_KEY);
        Cache::forget('site_settings_public');
    }
}
