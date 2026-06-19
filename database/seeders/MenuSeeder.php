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
            'label'      => ['fa' => 'خانه',    'en' => 'Home',    'hi' => 'होम',    'it' => 'Home',    'ar' => 'الرئيسية'],
            'route_name' => 'home',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        // محصولات (والد)
        $products = MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'محصولات', 'en' => 'Products', 'hi' => 'उत्पाद', 'it' => 'Prodotti', 'ar' => 'المنتجات'],
            'route_name' => 'products.index',
            'sort_order' => 2,
            'is_active'  => true,
        ]);

        // زیرمنوی محصولات — دسته‌بندی‌های اصلی
        $productChildren = [
            ['fa' => 'تراورتن',    'en' => 'Travertine',    'hi' => 'ट्रैवर्टाइन', 'it' => 'Travertino',    'ar' => 'التراڤرتين',  'slug' => 'travertan',    'sort' => 1],
            ['fa' => 'مرمریت',     'en' => 'Marble',        'hi' => 'संगमरमर',      'it' => 'Marmo',         'ar' => 'الرخام',       'slug' => 'marmorit',     'sort' => 2],
            ['fa' => 'گرانیت',     'en' => 'Granite',       'hi' => 'ग्रेनाइट',     'it' => 'Granito',       'ar' => 'الجرانيت',    'slug' => 'granite',      'sort' => 3],
            ['fa' => 'سنگ آنتیک',  'en' => 'Antique Stone', 'hi' => 'पुरातन पत्थर', 'it' => 'Pietra Antica', 'ar' => 'الحجر الأنتيك','slug' => 'sang-antique', 'sort' => 4],
            ['fa' => 'سنگ آهک',    'en' => 'Limestone',     'hi' => 'चूना पत्थर',   'it' => 'Calcare',       'ar' => 'الحجر الجيري', 'slug' => 'sang-ahak',    'sort' => 5],
            ['fa' => 'اسلیت',      'en' => 'Slate',         'hi' => 'स्लेट',        'it' => 'Ardesia',       'ar' => 'الأردواز',     'slug' => 'slate',        'sort' => 6],
            ['fa' => 'همه محصولات','en' => 'All Products',  'hi' => 'सभी उत्पाद',   'it' => 'Tutti',         'ar' => 'كل المنتجات', 'slug' => null,           'sort' => 7, 'route' => 'products.index'],
        ];

        foreach ($productChildren as $child) {
            MenuItem::create([
                'menu_id'      => $header->id,
                'parent_id'    => $products->id,
                'label'        => ['fa' => $child['fa'], 'en' => $child['en'], 'hi' => $child['hi'], 'it' => $child['it'], 'ar' => $child['ar']],
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
            'label'      => ['fa' => 'نمایشگاه‌ها', 'en' => 'Exhibitions', 'hi' => 'प्रदर्शनी', 'it' => 'Fiere', 'ar' => 'المعارض'],
            'route_name' => 'events.index',
            'sort_order' => 3,
            'is_active'  => true,
        ]);

        // اخبار
        MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'اخبار', 'en' => 'News', 'hi' => 'समाचार', 'it' => 'Notizie', 'ar' => 'الأخبار'],
            'route_name' => 'posts.index',
            'sort_order' => 4,
            'is_active'  => true,
        ]);

        // درباره ما (والد)
        $about = MenuItem::create([
            'menu_id'    => $header->id,
            'parent_id'  => null,
            'label'      => ['fa' => 'درباره ما', 'en' => 'About Us', 'hi' => 'हमारे बारे में', 'it' => 'Chi Siamo', 'ar' => 'من نحن'],
            'route_name' => 'pages.show',
            'route_params' => ['slug' => 'about'],
            'sort_order' => 5,
            'is_active'  => true,
        ]);

        // زیرمنوی درباره ما
        $aboutChildren = [
            ['fa' => 'معرفی شرکت',   'en' => 'Company Profile', 'hi' => 'कंपनी प्रोफ़ाइल', 'it' => 'Profilo Aziendale', 'ar' => 'ملف الشركة',    'slug' => 'about',   'sort' => 1],
            ['fa' => 'گواهینامه‌ها',  'en' => 'Certificates',   'hi' => 'प्रमाण पत्र',      'it' => 'Certificati',      'ar' => 'الشهادات',      'slug' => 'certificates', 'sort' => 2],
            ['fa' => 'معادن ما',      'en' => 'Our Mines',      'hi' => 'हमारी खदानें',     'it' => 'Le Nostre Cave',   'ar' => 'مناجمنا',       'slug' => 'our-mines','sort' => 3],
        ];

        foreach ($aboutChildren as $child) {
            MenuItem::create([
                'menu_id'      => $header->id,
                'parent_id'    => $about->id,
                'label'        => ['fa' => $child['fa'], 'en' => $child['en'], 'hi' => $child['hi'], 'it' => $child['it'], 'ar' => $child['ar']],
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
            'label'      => ['fa' => 'تماس با ما', 'en' => 'Contact Us', 'hi' => 'संपर्क करें', 'it' => 'Contattaci', 'ar' => 'اتصل بنا'],
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
            'label'      => ['fa' => 'اطلاعات شرکت', 'en' => 'Company Info', 'hi' => 'कंपनी की जानकारी', 'it' => 'Info Azienda', 'ar' => 'معلومات الشركة'],
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $col1Children = [
            ['fa' => 'درباره ما',      'en' => 'About Us',       'hi' => 'हमारे बारे में',   'it' => 'Chi Siamo',        'ar' => 'من نحن',         'slug' => 'about',        'sort' => 1],
            ['fa' => 'معادن ما',       'en' => 'Our Mines',      'hi' => 'हमारी खदानें',     'it' => 'Le Nostre Cave',   'ar' => 'مناجمنا',        'slug' => 'our-mines',    'sort' => 2],
            ['fa' => 'گواهینامه‌ها',   'en' => 'Certificates',   'hi' => 'प्रमाण पत्र',      'it' => 'Certificati',      'ar' => 'الشهادات',       'slug' => 'certificates', 'sort' => 3],
            ['fa' => 'نمایشگاه‌ها',    'en' => 'Exhibitions',    'hi' => 'प्रदर्शनी',         'it' => 'Fiere',            'ar' => 'المعارض',        'route' => 'events.index','sort' => 4],
            ['fa' => 'اخبار',          'en' => 'News',           'hi' => 'समाचार',            'it' => 'Notizie',          'ar' => 'الأخبار',        'route' => 'posts.index', 'sort' => 5],
        ];

        foreach ($col1Children as $child) {
            MenuItem::create([
                'menu_id'      => $footer->id,
                'parent_id'    => $col1->id,
                'label'        => ['fa' => $child['fa'], 'en' => $child['en'], 'hi' => $child['hi'], 'it' => $child['it'], 'ar' => $child['ar']],
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
            'label'      => ['fa' => 'خدمات', 'en' => 'Services', 'hi' => 'सेवाएं', 'it' => 'Servizi', 'ar' => 'الخدمات'],
            'sort_order' => 2,
            'is_active'  => true,
        ]);

        $col2Children = [
            ['fa' => 'محصولات',        'en' => 'Products',         'hi' => 'उत्पाद',          'it' => 'Prodotti',        'ar' => 'المنتجات',       'route' => 'products.index', 'sort' => 1],
            ['fa' => 'راهنمای خرید',   'en' => 'Buying Guide',    'hi' => 'खरीदारी गाइड',    'it' => 'Guida Acquisto',  'ar' => 'دليل الشراء',    'slug' => 'buying-guide',    'sort' => 2],
            ['fa' => 'روش پرداخت',     'en' => 'Payment Methods', 'hi' => 'भुगतान के तरीके', 'it' => 'Metodi Pagamento','ar' => 'طرق الدفع',      'slug' => 'payment-methods', 'sort' => 3],
            ['fa' => 'حمل و نقل',      'en' => 'Shipping',        'hi' => 'शिपिंग',           'it' => 'Spedizione',      'ar' => 'الشحن',          'slug' => 'shipping',        'sort' => 4],
            ['fa' => 'تماس با ما',     'en' => 'Contact Us',      'hi' => 'संपर्क करें',      'it' => 'Contattaci',      'ar' => 'اتصل بنا',       'route' => 'contact',        'sort' => 5],
        ];

        foreach ($col2Children as $child) {
            MenuItem::create([
                'menu_id'      => $footer->id,
                'parent_id'    => $col2->id,
                'label'        => ['fa' => $child['fa'], 'en' => $child['en'], 'hi' => $child['hi'], 'it' => $child['it'], 'ar' => $child['ar']],
                'route_name'   => $child['route'] ?? 'pages.show',
                'route_params' => isset($child['slug']) ? ['slug' => $child['slug']] : null,
                'sort_order'   => $child['sort'],
                'is_active'    => true,
            ]);
        }
    }
}
