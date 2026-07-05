<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Remove previous categories
        Category::query()->forceDelete();

        $categories = [

            // ── 1. Travertine ──────────────────────────────────
            [
                'name' => [
                    'fa' => 'تراورتن',
                    'en' => 'Travertine',
                    'hi' => 'ट्रैवर्टाइन',
                    'it' => 'Travertino',
                    'ar' => 'التراڤرتين',
                    'zh' => '洞石',
                    'tr' => 'Traverten',
                ],
                'slug' => [
                    'fa' => 'travertan',
                    'en' => 'travertine',
                    'hi' => 'travertine',
                    'it' => 'travertino',
                    'ar' => 'travertine',
                    'zh' => 'travertine',
                    'tr' => 'travertine',
                ],
                'description' => [
                    'fa' => 'تراورتن یکی از پرکاربردترین سنگ‌های ساختمانی ایران است که از معادن مختلف کشور استخراج می‌شود.',
                    'en' => 'Travertine is one of the most widely used building stones in Iran, extracted from various mines across the country.',
                    'hi' => null,
                    'it' => 'Il travertino è una delle pietre da costruzione più utilizzate in Iran.',
                    'ar' => 'التراڤرتين هو أحد أكثر أحجار البناء استخداماً في إيران.',
                    'zh' => '洞石是伊朗使用最广泛的建筑石材之一,产自全国各地的多个矿区。',
                    'tr' => "Traverten, ülkenin çeşitli ocaklarından çıkarılan, İran'da en yaygın kullanılan yapı taşlarından biridir.",
                ],
                'sort_order' => 1,
                'children' => [
                    ['fa' => 'تراورتن عباس‌آباد',  'en' => 'Abbasabad Travertine',  'slug_fa' => 'travertan-abbasabad',  'slug_en' => 'abbasabad-travertine',  'sort' => 1],
                    ['fa' => 'تراورتن آتشکوه',     'en' => 'Atashkuh Travertine',   'slug_fa' => 'travertan-atashkuh',   'slug_en' => 'atashkuh-travertine',   'sort' => 2],
                    ['fa' => 'تراورتن هرسین',      'en' => 'Harsin Travertine',     'slug_fa' => 'travertan-harsin',     'slug_en' => 'harsin-travertine',     'sort' => 3],
                    ['fa' => 'تراورتن محلات',      'en' => 'Mahallat Travertine',   'slug_fa' => 'travertan-mahallat',   'slug_en' => 'mahallat-travertine',   'sort' => 4],
                    ['fa' => 'تراورتن سفید',       'en' => 'White Travertine',      'slug_fa' => 'travertan-sefid',      'slug_en' => 'white-travertine',      'sort' => 5],
                    ['fa' => 'تراورتن عسلی',       'en' => 'Honey Travertine',      'slug_fa' => 'travertan-asali',      'slug_en' => 'honey-travertine',      'sort' => 6],
                    ['fa' => 'تراورتن اکباتان',    'en' => 'Ekbatan Travertine',    'slug_fa' => 'travertan-ekbatan',    'slug_en' => 'ekbatan-travertine',    'sort' => 7],
                ],
            ],

            // ── 2. Marble ──────────────────────────────────────
            [
                'name' => [
                    'fa' => 'مرمریت',
                    'en' => 'Marble',
                    'hi' => 'संगमरमर',
                    'it' => 'Marmo',
                    'ar' => 'الرخام',
                    'zh' => '大理石',
                    'tr' => 'Mermer',
                ],
                'slug' => [
                    'fa' => 'marmorit',
                    'en' => 'marble',
                    'hi' => 'marble',
                    'it' => 'marmo',
                    'ar' => 'marble',
                    'zh' => 'marble',
                    'tr' => 'marble',
                ],
                'description' => [
                    'fa' => 'مرمریت سنگی با زیبایی بی‌نظیر برای کاربردهای لوکس در ساختمان‌سازی.',
                    'en' => 'Marble is a stone of unparalleled beauty for luxury applications in construction.',
                    'hi' => null,
                    'it' => 'Il marmo è una pietra di bellezza senza pari per applicazioni di lusso.',
                    'ar' => 'الرخام حجر ذو جمال لا مثيل له للتطبيقات الفاخرة.',
                    'zh' => '大理石以其无与伦比的美感,广泛用于高端建筑装饰。',
                    'tr' => 'Mermer, inşaatta lüks uygulamalar için eşsiz güzellikte bir taştır.',
                ],
                'sort_order' => 2,
                'children' => [
                    ['fa' => 'مرمریت سفید',    'en' => 'White Marble',  'slug_fa' => 'marmorit-sefid',   'slug_en' => 'white-marble',  'sort' => 1],
                    ['fa' => 'مرمریت کرم',     'en' => 'Cream Marble',  'slug_fa' => 'marmorit-kerm',    'slug_en' => 'cream-marble',  'sort' => 2],
                    ['fa' => 'مرمریت مشکی',    'en' => 'Black Marble',  'slug_fa' => 'marmorit-meshki',  'slug_en' => 'black-marble',  'sort' => 3],
                    ['fa' => 'مرمریت صورتی',   'en' => 'Pink Marble',   'slug_fa' => 'marmorit-sorati',  'slug_en' => 'pink-marble',   'sort' => 4],
                    ['fa' => 'مرمریت طلایی',   'en' => 'Gold Marble',   'slug_fa' => 'marmorit-talayi',  'slug_en' => 'gold-marble',   'sort' => 5],
                ],
            ],

            // ── 3. Granite ─────────────────────────────────────
            [
                'name' => [
                    'fa' => 'گرانیت',
                    'en' => 'Granite',
                    'hi' => 'ग्रेनाइट',
                    'it' => 'Granito',
                    'ar' => 'الجرانيت',
                    'zh' => '花岗岩',
                    'tr' => 'Granit',
                ],
                'slug' => [
                    'fa' => 'granite',
                    'en' => 'granite',
                    'hi' => 'granite',
                    'it' => 'granito',
                    'ar' => 'granite',
                    'zh' => 'granite',
                    'tr' => 'granite',
                ],
                'description' => [
                    'fa' => 'گرانیت سخت‌ترین سنگ ساختمانی با مقاومت بالا در برابر سایش و رطوبت.',
                    'en' => 'Granite is the hardest building stone with high resistance to abrasion and moisture.',
                    'hi' => null,
                    'it' => 'Il granito è la pietra da costruzione più dura con alta resistenza.',
                    'ar' => 'الجرانيت هو أصلب حجر بناء مع مقاومة عالية.',
                    'zh' => '花岗岩是最坚硬的建筑石材,具有极高的耐磨性和防潮性。',
                    'tr' => 'Granit, aşınmaya ve neme karşı yüksek dirence sahip en sert yapı taşıdır.',
                ],
                'sort_order' => 3,
                'children' => [
                    ['fa' => 'گرانیت مشکی',   'en' => 'Black Granite',  'slug_fa' => 'granite-meshki',  'slug_en' => 'black-granite',  'sort' => 1],
                    ['fa' => 'گرانیت صورتی',  'en' => 'Pink Granite',   'slug_fa' => 'granite-sorati',  'slug_en' => 'pink-granite',   'sort' => 2],
                    ['fa' => 'گرانیت طوسی',   'en' => 'Grey Granite',   'slug_fa' => 'granite-tosi',    'slug_en' => 'grey-granite',   'sort' => 3],
                    ['fa' => 'گرانیت قرمز',   'en' => 'Red Granite',    'slug_fa' => 'granite-ghermez', 'slug_en' => 'red-granite',    'sort' => 4],
                ],
            ],

            // ── 4. Antique Stone ───────────────────────────────
            [
                'name' => [
                    'fa' => 'سنگ آنتیک',
                    'en' => 'Antique Stone',
                    'hi' => 'पुरातन पत्थर',
                    'it' => 'Pietra Antica',
                    'ar' => 'الحجر الأنتيك',
                    'zh' => '复古石材',
                    'tr' => 'Antik Taş',
                ],
                'slug' => [
                    'fa' => 'sang-antique',
                    'en' => 'antique-stone',
                    'hi' => 'antique-stone',
                    'it' => 'pietra-antica',
                    'ar' => 'antique-stone',
                    'zh' => 'antique-stone',
                    'tr' => 'antique-stone',
                ],
                'description' => [
                    'fa' => 'سنگ‌های آنتیک با بافت و ظاهر قدیمی، مناسب برای طراحی‌های کلاسیک و روستیک.',
                    'en' => 'Antique stones with aged texture and appearance, suitable for classic and rustic designs.',
                    'hi' => null,
                    'it' => 'Pietre antiche con texture invecchiata, adatte per design classici e rustici.',
                    'ar' => 'الأحجار الأنتيكية ذات الملمس القديم، مناسبة للتصاميم الكلاسيكية.',
                    'zh' => '复古石材具有做旧的质感与外观,适用于古典与乡村风格的设计。',
                    'tr' => 'Eskitilmiş doku ve görünüme sahip antik taşlar, klasik ve rustik tasarımlar için idealdir.',
                ],
                'sort_order' => 4,
                'children' => [
                    ['fa' => 'آنتیک روستیک',   'en' => 'Rustic Antique',  'slug_fa' => 'antique-rustic',   'slug_en' => 'rustic-antique',  'sort' => 1],
                    ['fa' => 'آنتیک قلوه‌ای',  'en' => 'Pebble Antique',  'slug_fa' => 'antique-ghalve',   'slug_en' => 'pebble-antique',  'sort' => 2],
                    ['fa' => 'آنتیک کوبیده',   'en' => 'Hammered Antique','slug_fa' => 'antique-kubide',   'slug_en' => 'hammered-antique','sort' => 3],
                ],
            ],

            // ── 5. Limestone ───────────────────────────────────
            [
                'name' => [
                    'fa' => 'سنگ آهک',
                    'en' => 'Limestone',
                    'hi' => 'चूना पत्थर',
                    'it' => 'Calcare',
                    'ar' => 'الحجر الجيري',
                    'zh' => '石灰石',
                    'tr' => 'Kireçtaşı',
                ],
                'slug' => [
                    'fa' => 'sang-ahak',
                    'en' => 'limestone',
                    'hi' => 'limestone',
                    'it' => 'calcare',
                    'ar' => 'limestone',
                    'zh' => 'limestone',
                    'tr' => 'limestone',
                ],
                'description' => [
                    'fa' => 'سنگ آهک سنگی سبک و کاربردی برای نماکاری و فضاهای خارجی.',
                    'en' => 'Limestone is a light and practical stone for facades and exterior spaces.',
                    'hi' => null,
                    'it' => 'Il calcare è una pietra leggera e pratica per facciate e spazi esterni.',
                    'ar' => 'الحجر الجيري حجر خفيف وعملي للواجهات والمساحات الخارجية.',
                    'zh' => '石灰石是一种轻质实用的石材,适用于外墙装饰和户外空间。',
                    'tr' => 'Kireçtaşı, cepheler ve dış mekanlar için hafif ve pratik bir taştır.',
                ],
                'sort_order' => 5,
                'children' => [
                    ['fa' => 'سنگ آهک سفید',  'en' => 'White Limestone', 'slug_fa' => 'ahak-sefid', 'slug_en' => 'white-limestone', 'sort' => 1],
                    ['fa' => 'سنگ آهک کرم',   'en' => 'Cream Limestone', 'slug_fa' => 'ahak-kerm',  'slug_en' => 'cream-limestone', 'sort' => 2],
                ],
            ],

            // ── 6. Slate ───────────────────────────────────────
            [
                'name' => [
                    'fa' => 'اسلیت',
                    'en' => 'Slate',
                    'hi' => 'स्लेट',
                    'it' => 'Ardesia',
                    'ar' => 'الأردواز',
                    'zh' => '板岩',
                    'tr' => 'Arduvaz',
                ],
                'slug' => [
                    'fa' => 'slate',
                    'en' => 'slate',
                    'hi' => 'slate',
                    'it' => 'ardesia',
                    'ar' => 'slate',
                    'zh' => 'slate',
                    'tr' => 'slate',
                ],
                'description' => [
                    'fa' => 'اسلیت سنگی با لایه‌های طبیعی، مناسب برای کف‌پوش و نمای خارجی.',
                    'en' => 'Slate is a natural layered stone, suitable for flooring and exterior cladding.',
                    'hi' => null,
                    'it' => 'L\'ardesia è una pietra a strati naturali, adatta per pavimenti e rivestimenti esterni.',
                    'ar' => 'الأردواز حجر طبيعي متعدد الطبقات، مناسب للأرضيات والواجهات الخارجية.',
                    'zh' => '板岩是一种天然层状石材,适用于地面铺装和外墙装饰。',
                    'tr' => 'Arduvaz, zemin kaplamaları ve dış cephe kaplamaları için uygun doğal katmanlı bir taştır.',
                ],
                'sort_order' => 6,
                'children' => [
                    ['fa' => 'اسلیت مشکی', 'en' => 'Black Slate', 'slug_fa' => 'slate-meshki', 'slug_en' => 'black-slate', 'sort' => 1],
                    ['fa' => 'اسلیت سبز',  'en' => 'Green Slate', 'slug_fa' => 'slate-sabz',   'slug_en' => 'green-slate', 'sort' => 2],
                    ['fa' => 'اسلیت قرمز', 'en' => 'Red Slate',   'slug_fa' => 'slate-ghermez','slug_en' => 'red-slate',   'sort' => 3],
                ],
            ],

        ];

        foreach ($categories as $catData) {
            $children = $catData['children'] ?? [];
            unset($catData['children']);

            $parent = Category::create([
                'name'        => $catData['name'],
                'slug'        => $catData['slug'],
                'description' => $catData['description'],
                'is_active'   => true,
                'sort_order'  => $catData['sort_order'],
            ]);

            foreach ($children as $child) {
                Category::create([
                    'parent_id'  => $parent->id,
                    'name'       => [
                        'fa' => $child['fa'],
                        'en' => $child['en'],
                        'hi' => $child['en'],
                        'it' => $child['en'],
                        'ar' => $child['en'],
                        'zh' => $child['en'],
                        'tr' => $child['en'],
                    ],
                    'slug'       => [
                        'fa' => $child['slug_fa'],
                        'en' => $child['slug_en'],
                        'hi' => $child['slug_en'],
                        'it' => $child['slug_en'],
                        'ar' => $child['slug_en'],
                        'zh' => $child['slug_en'],
                        'tr' => $child['slug_en'],
                    ],
                    'is_active'  => true,
                    'sort_order' => $child['sort'],
                ]);
            }
        }

        // Rebuild nested set tree
        Category::fixTree();
    }
}
