<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * UI strings of the mobile layout (filters drawer on the products page, generic "close").
 * Additive and safe to re-run: uses updateOrInsert per locale/key.
 *
 * Run with: php artisan db:seed --class=MobileUxTranslationSeeder
 */
class MobileUxTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // key => [ locale => value ]  (all in the `messages` group)
        $entries = [
            'filters' => [
                'fa' => 'فیلترها', 'en' => 'Filters', 'ar' => 'التصفية', 'hi' => 'फ़िल्टर',
                'it' => 'Filtri', 'zh' => '筛选', 'tr' => 'Filtreler',
            ],
            'show_results' => [
                'fa' => 'نمایش نتایج', 'en' => 'Show results', 'ar' => 'عرض النتائج', 'hi' => 'परिणाम दिखाएँ',
                'it' => 'Mostra risultati', 'zh' => '查看结果', 'tr' => 'Sonuçları göster',
            ],
            'close' => [
                'fa' => 'بستن', 'en' => 'Close', 'ar' => 'إغلاق', 'hi' => 'बंद करें',
                'it' => 'Chiudi', 'zh' => '关闭', 'tr' => 'Kapat',
            ],
        ];

        $now = now();
        $touched = [];

        foreach ($entries as $key => $locales) {
            foreach ($locales as $locale => $value) {
                DB::table('translations')->updateOrInsert(
                    ['locale' => $locale, 'group' => 'messages', 'key' => $key],
                    ['value' => $value, 'is_auto' => 0, 'created_at' => $now, 'updated_at' => $now]
                );
                $touched[$locale] = true;
            }
        }

        // Query-builder writes skip the model's cache invalidation.
        foreach (array_keys($touched) as $locale) {
            Cache::forget("translations.{$locale}.messages");
        }
    }
}
