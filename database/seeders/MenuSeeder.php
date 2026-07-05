<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // پاک کردن منوهای قبلی
        MenuItem::query()->delete();
        Menu::query()->delete();

        // ── منوی هدر ─────────────────────────────────────────
        $header = Menu::create([
            'name'      => 'منوی اصلی',
            'location'  => 'header',
            'is_active' => true,
        ]);

        // خانه
        MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'خانه',    'en' => 'Home',    'hi' => 'होम',    'it' => 'Home',    'ar' => 'الرئيسية', 'zh' => '首页', 'tr' => 'Ana Sayfa'],
            'route_name' => 'home',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        // محصولات (والد)
        $products = MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'محصولات', 'en' => 'Products', 'hi' => 'उत्पाद', 'it' => 'Prodotti', 'ar' => 'المنتجات', 'zh' => '产品', 'tr' => 'Ürünler'],
            'route_name' => 'products.index',
            'sort_order' => 2,
            'is_active'  => true,
        ]);

        // زیرمنوی محصولات — دسته‌بندی‌های اصلی
        $productChildren = [
            ['fa' => 'تراورتن',    'en' => 'Travertine',    'hi' => 'ट्रैवर्टाइन', 'it' => 'Travertino',    'ar' => 'التراڤرتين',  'zh' => '洞石',       'tr' => 'Traverten',     'slug' => 'travertan',    'sort' => 1],
            ['fa' => 'مرمریت',     'en' => 'Marble',        'hi' => 'संगमरमर',      'it' => 'Marmo',         'ar' => 'الرخام',       'zh' => '大理石',     'tr' => 'Mermer',        'slug' => 'marmorit',     'sort' => 2],
            ['fa' => 'گرانیت',     'en' => 'Granite',       'hi' => 'ग्रेनाइट',     'it' => 'Granito',       'ar' => 'الجرانيت',    'zh' => '花岗岩',     'tr' => 'Granit',        'slug' => 'granite',      'sort' => 3],
            ['fa' => 'سنگ آنتیک',  'en' => 'Antique Stone', 'hi' => 'पुरातन पत्थर', 'it' => 'Pietra Antica', 'ar' => 'الحجر الأنتيك','zh' => '仿古石',     'tr' => 'Antik Taş',     'slug' => 'sang-antique', 'sort' => 4],
            ['fa' => 'سنگ آهک',    'en' => 'Limestone',     'hi' => 'चूना पत्थर',   'it' => 'Calcare',       'ar' => 'الحجر الجيري', 'zh' => '石灰石',     'tr' => 'Kireçtaşı',     'slug' => 'sang-ahak',    'sort' => 5],
            ['fa' => 'اسلیت',      'en' => 'Slate',         'hi' => 'स्लेट',        'it' => 'Ardesia',       'ar' => 'الأردواز',     'zh' => '板岩',       'tr' => 'Arduvaz',       'slug' => 'slate',        'sort' => 6],
            ['fa' => 'همه محصولات','en' => 'All Products',  'hi' => 'सभी उत्पाद',   'it' => 'Tutti',         'ar' => 'كل المنتجات', 'zh' => '所有产品',   'tr' => 'Tüm Ürünler',   'slug' => null,           'sort' => 7, 'route' => 'products.index'],
        ];

        foreach ($productChildren as $child) {
            MenuItem::create([
                'menu_id'      => $header->id,
                'parent_id'    => $products->id,
                'label'        => ['fa' => $child['fa'], 'en' => $child['en'], 'hi' => $child['hi'], 'it' => $child['it'], 'ar' => $child['ar'], 'zh' => $child['zh'], 'tr' => $child['tr']],
                'route_name'   => $child['route'] ?? 'categories.show',
                'route_params' => isset($child['slug']) && $child['slug'] ? ['slug' => $child['slug']] : null,
                'sort_order'   => $child['sort'],
                'is_active'    => true,
            ]);
        }

        // نمایشگاه‌ها — لینک مستقیم بدون زیرمنو
        // (آرشیو حضور شرکت در نمایشگاه‌های گذشته و در‌حال‌برگزاری؛ نیازی به تفکیک آینده/گذشته نیست)
        MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'نمایشگاه‌ها', 'en' => 'Exhibitions', 'hi' => 'प्रदर्शनी', 'it' => 'Fiere', 'ar' => 'المعارض', 'zh' => '展会', 'tr' => 'Fuarlar'],
            'route_name' => 'events.index',
            'sort_order' => 3,
            'is_active'  => true,
        ]);

        // اخبار
        MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'اخبار', 'en' => 'News', 'hi' => 'समाचार', 'it' => 'Notizie', 'ar' => 'الأخبار', 'zh' => '新闻', 'tr' => 'Haberler'],
            'route_name' => 'posts.index',
            'sort_order' => 4,
            'is_active'  => true,
        ]);

        // درباره ما (والد)
        $about = MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'درباره ما', 'en' => 'About Us', 'hi' => 'हमारे बारे में', 'it' => 'Chi Siamo', 'ar' => 'من نحن', 'zh' => '关于我们', 'tr' => 'Hakkımızda'],
            'route_name' => 'pages.show',
            'route_params' => ['slug' => 'about'],
            'sort_order' => 5,
            'is_active'  => true,
        ]);

        // زیرمنوی درباره ما
        $aboutChildren = [
            ['fa' => 'معرفی شرکت',   'en' => 'Company Profile', 'hi' => 'कंपनी प्रोफ़ाइल', 'it' => 'Profilo Aziendale', 'ar' => 'ملف الشركة',    'zh' => '公司简介',   'tr' => 'Şirket Profili',  'slug' => 'about',   'sort' => 1],
            ['fa' => 'گواهینامه‌ها',  'en' => 'Certificates',   'hi' => 'प्रमाण पत्र',      'it' => 'Certificati',      'ar' => 'الشهادات',      'zh' => '证书',       'tr' => 'Sertifikalar',    'slug' => 'certificates', 'sort' => 2],
            ['fa' => 'معادن ما',      'en' => 'Our Mines',      'hi' => 'हमारी खदानें',     'it' => 'Le Nostre Cave',   'ar' => 'مناجمنا',       'zh' => '我们的矿山', 'tr' => 'Ocaklarımız',     'slug' => 'our-mines','sort' => 3],
        ];

        foreach ($aboutChildren as $child) {
            MenuItem::create([
                'menu_id'      => $header->id,
                'parent_id'    => $about->id,
                'label'        => ['fa' => $child['fa'], 'en' => $child['en'], 'hi' => $child['hi'], 'it' => $child['it'], 'ar' => $child['ar'], 'zh' => $child['zh'], 'tr' => $child['tr']],
                'route_name'   => 'pages.show',
                'route_params' => ['slug' => $child['slug']],
                'sort_order'   => $child['sort'],
                'is_active'    => true,
            ]);
        }

        // تماس با ما
        MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'تماس با ما', 'en' => 'Contact Us', 'hi' => 'संपर्क करें', 'it' => 'Contattaci', 'ar' => 'اتصل بنا', 'zh' => '联系我们', 'tr' => 'İletişim'],
            'route_name' => 'contact',
            'sort_order' => 6,
            'is_active'  => true,
        ]);

        // ── منوی فوتر ─────────────────────────────────────────
        $footer = Menu::create([
            'name'      => 'منوی فوتر',
            'location'  => 'footer',
            'is_active' => true,
        ]);

        // ستون ۱ — اطلاعات شرکت
        $col1 = MenuItem::create([
            'menu_id'    => $footer->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'اطلاعات شرکت', 'en' => 'Company Info', 'hi' => 'कंपनी की जानकारी', 'it' => 'Info Azienda', 'ar' => 'معلومات الشركة', 'zh' => '公司信息', 'tr' => 'Şirket Bilgileri'],
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $col1Children = [
            ['fa' => 'درباره ما',      'en' => 'About Us',       'hi' => 'हमारे बारे में',   'it' => 'Chi Siamo',        'ar' => 'من نحن',         'zh' => '关于我们',   'tr' => 'Hakkımızda',      'slug' => 'about',        'sort' => 1],
            ['fa' => 'معادن ما',       'en' => 'Our Mines',      'hi' => 'हमारी खदानें',     'it' => 'Le Nostre Cave',   'ar' => 'مناجمنا',        'zh' => '我们的矿山', 'tr' => 'Ocaklarımız',     'slug' => 'our-mines',    'sort' => 2],
            ['fa' => 'گواهینامه‌ها',   'en' => 'Certificates',   'hi' => 'प्रमाण पत्र',      'it' => 'Certificati',      'ar' => 'الشهادات',       'zh' => '证书',       'tr' => 'Sertifikalar',    'slug' => 'certificates', 'sort' => 3],
            ['fa' => 'نمایشگاه‌ها',    'en' => 'Exhibitions',    'hi' => 'प्रदर्शनी',         'it' => 'Fiere',            'ar' => 'المعارض',        'zh' => '展会',       'tr' => 'Fuarlar',         'route' => 'events.index','sort' => 4],
            ['fa' => 'اخبار',          'en' => 'News',           'hi' => 'समाचार',            'it' => 'Notizie',          'ar' => 'الأخبار',        'zh' => '新闻',       'tr' => 'Haberler',        'route' => 'posts.index', 'sort' => 5],
        ];

        foreach ($col1Children as $child) {
            MenuItem::create([
                'menu_id'      => $footer->id,
                'parent_id'    => $col1->id,
                'label'        => ['fa' => $child['fa'], 'en' => $child['en'], 'hi' => $child['hi'], 'it' => $child['it'], 'ar' => $child['ar'], 'zh' => $child['zh'], 'tr' => $child['tr']],
                'route_name'   => $child['route'] ?? 'pages.show',
                'route_params' => isset($child['slug']) ? ['slug' => $child['slug']] : null,
                'sort_order'   => $child['sort'],
                'is_active'    => true,
            ]);
        }

        // ستون ۲ — خدمات
        $col2 = MenuItem::create([
            'menu_id'    => $footer->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'خدمات', 'en' => 'Services', 'hi' => 'सेवाएं', 'it' => 'Servizi', 'ar' => 'الخدمات', 'zh' => '服务', 'tr' => 'Hizmetler'],
            'sort_order' => 2,
            'is_active'  => true,
        ]);

        $col2Children = [
            ['fa' => 'محصولات',        'en' => 'Products',         'hi' => 'उत्पाद',          'it' => 'Prodotti',        'ar' => 'المنتجات',       'zh' => '产品',       'tr' => 'Ürünler',            'route' => 'products.index', 'sort' => 1],
            ['fa' => 'راهنمای خرید',   'en' => 'Buying Guide',    'hi' => 'खरीदारी गाइड',    'it' => 'Guida Acquisto',  'ar' => 'دليل الشراء',    'zh' => '购买指南',   'tr' => 'Satın Alma Rehberi', 'slug' => 'buying-guide',    'sort' => 2],
            ['fa' => 'روش پرداخت',     'en' => 'Payment Methods', 'hi' => 'भुगतान के तरीके', 'it' => 'Metodi Pagamento','ar' => 'طرق الدفع',      'zh' => '付款方式',   'tr' => 'Ödeme Yöntemleri',   'slug' => 'payment-methods', 'sort' => 3],
            ['fa' => 'حمل و نقل',      'en' => 'Shipping',        'hi' => 'शिपिंग',           'it' => 'Spedizione',      'ar' => 'الشحن',          'zh' => '运输',       'tr' => 'Nakliye',            'slug' => 'shipping',        'sort' => 4],
            ['fa' => 'تماس با ما',     'en' => 'Contact Us',      'hi' => 'संपर्क करें',      'it' => 'Contattaci',      'ar' => 'اتصل بنا',       'zh' => '联系我们',   'tr' => 'İletişim',           'route' => 'contact',        'sort' => 5],
        ];

        foreach ($col2Children as $child) {
            MenuItem::create([
                'menu_id'      => $footer->id,
                'parent_id'    => $col2->id,
                'label'        => ['fa' => $child['fa'], 'en' => $child['en'], 'hi' => $child['hi'], 'it' => $child['it'], 'ar' => $child['ar'], 'zh' => $child['zh'], 'tr' => $child['tr']],
                'route_name'   => $child['route'] ?? 'pages.show',
                'route_params' => isset($child['slug']) ? ['slug' => $child['slug']] : null,
                'sort_order'   => $child['sort'],
                'is_active'    => true,
            ]);
        }
    }
}
