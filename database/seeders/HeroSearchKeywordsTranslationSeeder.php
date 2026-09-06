<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Additive, idempotent seeder for the "Hero Search Keywords" admin field
 * (label + help text) introduced to let admins define the hot-search chips
 * under the homepage hero search box, instead of reusing root categories.
 *
 * Run once with: php artisan db:seed --class=HeroSearchKeywordsTranslationSeeder
 */
class HeroSearchKeywordsTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $help = [
            'hero_search_keywords' => [
                'fa' => 'کلمات کلیدی پرجستجو',
                'en' => 'Hot Search Keywords',
                'ar' => 'كلمات البحث الشائعة',
                'hi' => 'लोकप्रिय खोज कीवर्ड',
                'it' => 'Parole chiave di ricerca popolari',
                'zh' => '热门搜索关键词',
                'tr' => 'Popüler Arama Kelimeleri',
            ],
            'hero_search_keywords_help' => [
                'fa' => 'کلمات کلیدی که به‌صورت تگ زیر کادر جستجوی صفحه اول نمایش داده می‌شوند و کاربر با کلیک روی آن‌ها جستجو می‌کند. با کاما (,) از هم جدا کنید — مثلاً: تراورتن تیتانیوم, تراورتن سیلور دودی, تراورتن خرمایی',
                'en' => 'Keywords shown as clickable chips under the homepage search box; clicking one runs a search for it. Separate with commas — e.g. Titanium Travertine, Silver Smoke Travertine, Walnut Travertine',
                'ar' => 'كلمات مفتاحية تظهر كشرائح قابلة للنقر أسفل مربع البحث في الصفحة الرئيسية؛ النقر عليها يشغّل بحثًا عنها. افصل بينها بفواصل.',
                'hi' => 'होमपेज सर्च बॉक्स के नीचे क्लिक करने योग्य चिप्स के रूप में दिखाए जाने वाले कीवर्ड; क्लिक करने पर उसकी खोज होती है। अल्पविराम से अलग करें।',
                'it' => 'Parole chiave mostrate come chip cliccabili sotto la casella di ricerca della homepage; cliccandole si avvia una ricerca. Separale con virgole.',
                'zh' => '显示在首页搜索框下方、可点击的关键词标签；点击后会执行相应搜索。请用逗号分隔。',
                'tr' => 'Ana sayfa arama kutusunun altında tıklanabilir etiketler olarak gösterilen anahtar kelimeler; tıklandığında arama yapılır. Virgülle ayırın.',
            ],
        ];

        $now = now();
        $touched = [];

        foreach ($help as $key => $locales) {
            foreach ($locales as $locale => $value) {
                DB::table('translations')->updateOrInsert(
                    ['locale' => $locale, 'group' => 'admin', 'key' => $key],
                    ['value' => $value, 'is_auto' => 0, 'created_at' => $now, 'updated_at' => $now]
                );
                $touched[$locale] = true;
            }
        }

        foreach (array_keys($touched) as $locale) {
            Cache::forget("translations.{$locale}.admin");
        }
    }
}
