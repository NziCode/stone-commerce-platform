<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Base key => legacy companion key holding the English value, if any. */
    protected const COMPANIONS = [
        'site_name' => 'site_name_en',
        'site_address' => 'site_address_en',
    ];

    /** Keys with no preexisting base row — only the two locale-suffixed rows. */
    protected const SPLIT_ONLY = [
        'meta_title' => ['fa' => 'meta_title_fa', 'en' => 'meta_title_en'],
        'meta_description' => ['fa' => 'meta_description_fa', 'en' => 'meta_description_en'],
    ];

    /** Keys with a single existing value and no companion — just get wrapped. */
    protected const SINGLE_VALUE = [
        'site_tagline', 'site_working_hours',
        'payment_receipt_bank_name', 'payment_receipt_instructions',
        'sms_otp_template', 'sms_order_confirmed_template', 'sms_order_shipped_template',
    ];

    /** Already locale-keyed JSON from the seeder — just tag the type. */
    protected const ALREADY_JSON = [
        'about_title', 'about_desc', 'about_feature_1', 'about_feature_2', 'about_feature_3',
    ];

    public function up(): void
    {
        DB::transaction(function () {
            $defaultLocale = DB::table('languages')->where('is_default', true)->value('code') ?? 'fa';

            $rows = DB::table('settings')->pluck('value', 'key');

            foreach (self::COMPANIONS as $baseKey => $companionKey) {
                if (! $rows->has($baseKey)) {
                    continue;
                }

                $merged = [$defaultLocale => $rows->get($baseKey)];

                if ($rows->has($companionKey) && $defaultLocale !== 'en') {
                    $merged['en'] = $rows->get($companionKey);
                }

                DB::table('settings')->where('key', $baseKey)->update([
                    'value' => json_encode($merged, JSON_UNESCAPED_UNICODE),
                    'type' => 'translatable',
                ]);

                DB::table('settings')->where('key', $companionKey)->delete();
            }

            foreach (self::SPLIT_ONLY as $baseKey => $locales) {
                $faValue = $rows->get($locales['fa']);
                $enValue = $rows->get($locales['en']);

                if ($faValue === null && $enValue === null) {
                    continue;
                }

                $merged = array_filter([
                    $defaultLocale => $faValue,
                    'en' => $enValue,
                ], fn ($v) => $v !== null);

                $template = DB::table('settings')->where('key', $locales['fa'])->first()
                    ?? DB::table('settings')->where('key', $locales['en'])->first();

                DB::table('settings')->updateOrInsert(
                    ['key' => $baseKey],
                    [
                        'group' => $template->group ?? 'seo',
                        'value' => json_encode($merged, JSON_UNESCAPED_UNICODE),
                        'type' => 'translatable',
                        'is_public' => $template->is_public ?? false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                DB::table('settings')->whereIn('key', [$locales['fa'], $locales['en']])->delete();
            }

            foreach (self::SINGLE_VALUE as $key) {
                if (! $rows->has($key)) {
                    continue;
                }

                $current = $rows->get($key);
                $decoded = json_decode((string) $current, true);

                // Idempotent: skip if this already looks like locale-keyed JSON.
                if (is_array($decoded)) {
                    DB::table('settings')->where('key', $key)->update(['type' => 'translatable']);
                    continue;
                }

                DB::table('settings')->where('key', $key)->update([
                    'value' => json_encode([$defaultLocale => $current], JSON_UNESCAPED_UNICODE),
                    'type' => 'translatable',
                ]);
            }

            foreach (self::ALREADY_JSON as $key) {
                if (! $rows->has($key)) {
                    continue;
                }

                DB::table('settings')->where('key', $key)->update(['type' => 'translatable']);
            }
        });

        \Illuminate\Support\Facades\Cache::forget(Setting::CACHE_KEY);
        \Illuminate\Support\Facades\Cache::forget('site_settings_public');
    }

    public function down(): void
    {
        DB::transaction(function () {
            foreach (self::COMPANIONS as $baseKey => $companionKey) {
                $row = DB::table('settings')->where('key', $baseKey)->first();
                if (! $row) {
                    continue;
                }

                $decoded = json_decode($row->value, true) ?: [];
                $defaultLocale = DB::table('languages')->where('is_default', true)->value('code') ?? 'fa';

                DB::table('settings')->where('key', $baseKey)->update([
                    'value' => $decoded[$defaultLocale] ?? '',
                    'type' => 'string',
                ]);

                if (isset($decoded['en'])) {
                    DB::table('settings')->updateOrInsert(
                        ['key' => $companionKey],
                        ['group' => $row->group, 'value' => $decoded['en'], 'type' => 'string', 'is_public' => $row->is_public, 'created_at' => now(), 'updated_at' => now()]
                    );
                }
            }

            foreach (self::SPLIT_ONLY as $baseKey => $locales) {
                $row = DB::table('settings')->where('key', $baseKey)->first();
                if (! $row) {
                    continue;
                }

                $decoded = json_decode($row->value, true) ?: [];
                $defaultLocale = DB::table('languages')->where('is_default', true)->value('code') ?? 'fa';

                DB::table('settings')->updateOrInsert(
                    ['key' => $locales['fa']],
                    ['group' => $row->group, 'value' => $decoded[$defaultLocale] ?? '', 'type' => 'string', 'is_public' => $row->is_public, 'created_at' => now(), 'updated_at' => now()]
                );
                DB::table('settings')->updateOrInsert(
                    ['key' => $locales['en']],
                    ['group' => $row->group, 'value' => $decoded['en'] ?? '', 'type' => 'string', 'is_public' => $row->is_public, 'created_at' => now(), 'updated_at' => now()]
                );

                DB::table('settings')->where('key', $baseKey)->delete();
            }

            foreach (self::SINGLE_VALUE as $key) {
                $row = DB::table('settings')->where('key', $key)->first();
                if (! $row) {
                    continue;
                }

                $decoded = json_decode($row->value, true);
                if (! is_array($decoded)) {
                    continue;
                }

                $defaultLocale = DB::table('languages')->where('is_default', true)->value('code') ?? 'fa';

                DB::table('settings')->where('key', $key)->update([
                    'value' => $decoded[$defaultLocale] ?? reset($decoded) ?: '',
                    'type' => 'string',
                ]);
            }

            foreach (self::ALREADY_JSON as $key) {
                DB::table('settings')->where('key', $key)->update(['type' => 'json']);
            }
        });

        \Illuminate\Support\Facades\Cache::forget(Setting::CACHE_KEY);
        \Illuminate\Support\Facades\Cache::forget('site_settings_public');
    }
};
