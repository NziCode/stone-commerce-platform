<?php

namespace App\Console\Commands\Support;

use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * Regenerates SettingValuesSeeder.php — the gitignored companion to
 * SettingSeeder.php that holds the real `key => value` content (site name,
 * contact info, translations, etc.) so it never reaches source control.
 */
class SettingValuesSeederSync implements SeederSync
{
    protected const GROUP_LABELS = [
        'general'  => 'General',
        'seo'      => 'SEO',
        'social'   => 'Social',
        'payment'  => 'Payment',
        'smtp'     => 'SMTP / Email',
        'sms'      => 'SMS',
        'shipping' => 'Shipping',
        'contact'  => 'Contact',
        'home'     => 'Home',
    ];

    /**
     * Credentials that must never end up in a seeder file, gitignored or
     * not — set these via the admin panel, not through seeding.
     */
    protected const SENSITIVE_KEYS = [
        'smtp_password',
        'sms_api_key',
        'contact_recaptcha_secret_key',
        'payment_zarinpal_merchant',
        'payment_mellat_username',
        'payment_mellat_password',
    ];

    public function seederPath(): string
    {
        return database_path('seeders/SettingValuesSeeder.php');
    }

    public function generateBlock(): string
    {
        $groups = $this->rows()->groupBy('group');

        $lines = [''];

        foreach ($groups as $group => $rows) {
            $label = self::GROUP_LABELS[$group] ?? ucfirst($group);
            $lines[] = '            // ── ' . $label . ' ' . str_repeat('─', max(3, 50 - strlen($label)));

            foreach ($rows as $row) {
                $lines[] = '            ' . var_export($row->key, true) . ' => ' . var_export((string) $row->value, true) . ',';
            }

            $lines[] = '';
        }

        array_pop($lines);

        return implode("\n", $lines);
    }

    public function describe(): string
    {
        $rows = $this->rows();

        return $rows->count() . ' setting values across ' . $rows->pluck('group')->unique()->count() . ' groups';
    }

    public function template(): string
    {
        return <<<'PHP'
        <?php

        namespace Database\Seeders;

        use Illuminate\Database\Seeder;
        use App\Models\Setting;
        use Illuminate\Support\Facades\Cache;

        /**
         * Real content for every setting defined in SettingSeeder — site name,
         * contact info, translations, etc. Gitignored on purpose: this file holds
         * actual site/client data and must never be committed.
         *
         * Regenerate it any time after editing Settings in the admin panel:
         *   php artisan db:sync-seeders SettingValuesSeeder
         */
        class SettingValuesSeeder extends Seeder
        {
            public function run(): void
            {
                $values = [
                    // BEGIN: db-sync — run `php artisan db:sync-seeders SettingValuesSeeder` to refresh this block from the database; don't hand-edit it, your changes will be overwritten on the next sync.
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

        PHP;
    }

    /**
     * @return Collection<int, Setting>
     */
    protected function rows(): Collection
    {
        return Setting::orderBy('id')
            ->get(['group', 'key', 'value'])
            ->reject(fn (Setting $row) => in_array($row->key, self::SENSITIVE_KEYS, true))
            ->values();
    }
}
