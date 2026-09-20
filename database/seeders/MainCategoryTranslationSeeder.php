<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * UI strings of the home-page main-category slider and the main-category filter (export / saw-cut / top-cut).
 * Additive and safe to re-run: uses updateOrInsert per locale/key.
 *
 * Run with: php artisan db:seed --class=MainCategoryTranslationSeeder
 */
class MainCategoryTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // key => [ locale => value ]  (all in the `messages` group)
        $entries = [
            'shop_by_main_category' => [
                'fa' => 'دسته‌بندی اصلی سنگ‌ها', 'en' => 'Shop by main category', 'ar' => 'تسوّق حسب الفئة الرئيسية', 'hi' => 'मुख्य श्रेणी के अनुसार देखें',
                'it' => 'Acquista per categoria principale', 'zh' => '按主分类选购', 'tr' => 'Ana kategoriye göre keşfedin',
            ],
            'view_stones' => [
                'fa' => 'مشاهده سنگ‌ها', 'en' => 'View stones', 'ar' => 'عرض الأحجار', 'hi' => 'पत्थर देखें',
                'it' => 'Vedi le pietre', 'zh' => '查看石材', 'tr' => 'Taşları gör',
            ],
            'stones_count' => [
                'fa' => ':count سنگ', 'en' => ':count stones', 'ar' => ':count حجر', 'hi' => ':count पत्थर',
                'it' => ':count pietre', 'zh' => ':count 种石材', 'tr' => ':count taş',
            ],
            'main_category' => [
                'fa' => 'دسته‌بندی اصلی', 'en' => 'Main category', 'ar' => 'الفئة الرئيسية', 'hi' => 'मुख्य श्रेणी',
                'it' => 'Categoria principale', 'zh' => '主分类', 'tr' => 'Ana kategori',
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
