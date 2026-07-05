<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // ── General ──────────────────────────────────────
            [
                'group'     => 'general',
                'key'       => 'site_name',
                'value'     => 'Stone Commerce',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_name_en',
                'value'     => 'Stone Commerce',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_tagline',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_logo',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_favicon',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_email',
                'value'     => 'info@example.com',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_phone',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_phone_whatsapp',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_address',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_address_en',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_working_hours',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_map_lat',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_map_lng',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_google_map_embed',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],

            // ── SEO ──────────────────────────────────────────
            [
                'group'     => 'seo',
                'key'       => 'meta_title_fa',
                'value'     => 'Stone Commerce | خرید سنگ ساختمانی',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'meta_title_en',
                'value'     => 'Stone Commerce | Natural Stone',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'meta_description_fa',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'meta_description_en',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'og_image',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'google_analytics_id',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'google_tag_manager_id',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'google_search_console',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'robots_txt',
                'value'     => "User-agent: *\nAllow: /\n\nDisallow: /admin\nDisallow: /admin/\nDisallow: /api/\nDisallow: /cart\nDisallow: /checkout\nDisallow: /payment\nDisallow: /orders\nDisallow: /profile\nDisallow: /wishlist\nDisallow: /login\nDisallow: /register\nDisallow: /forgot-password\nDisallow: /reset-password\nDisallow: /email/verify\n\nSitemap: /sitemap.xml",
                'type'      => 'string',
                'is_public' => false,
            ],

            // ── Social ───────────────────────────────────────
            [
                'group'     => 'social',
                'key'       => 'social_instagram',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_linkedin',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_youtube',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_twitter',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_facebook',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_telegram',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_whatsapp',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],

            // ── Payment ──────────────────────────────────────
            [
                'group'     => 'payment',
                'key'       => 'payment_zarinpal_merchant',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_zarinpal_sandbox',
                'value'     => '1',
                'type'      => 'boolean',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_mellat_terminal',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_mellat_username',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_mellat_password',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_bank_name',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_account_number',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_swift',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_iban',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_instructions',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],

            // ── SMTP / Email ──────────────────────────────────
            [
                'group'     => 'smtp',
                'key'       => 'smtp_host',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_port',
                'value'     => '587',
                'type'      => 'integer',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_username',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_password',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_from_address',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_from_name',
                'value'     => 'Stone Commerce',
                'type'      => 'string',
                'is_public' => false,
            ],

            // ── SMS ───────────────────────────────────────────
            [
                'group'     => 'sms',
                'key'       => 'sms_provider',
                'value'     => 'kavenegar',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_api_key',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_sender',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_order_confirmed_template',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_order_shipped_template',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_otp_template',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],

            // ── Shipping ──────────────────────────────────────
            [
                'group'     => 'shipping',
                'key'       => 'shipping_free_above',
                'value'     => '0',
                'type'      => 'integer',
                'is_public' => true,
            ],
            [
                'group'     => 'shipping',
                'key'       => 'shipping_default_cost',
                'value'     => '0',
                'type'      => 'integer',
                'is_public' => true,
            ],

            // ── Contact ───────────────────────────────────────
            [
                'group'     => 'contact',
                'key'       => 'contact_notify_email',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'contact',
                'key'       => 'contact_notify_sms',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'contact',
                'key'       => 'contact_recaptcha_enabled',
                'value'     => '0',
                'type'      => 'boolean',
                'is_public' => false,
            ],
            [
                'group'     => 'contact',
                'key'       => 'contact_recaptcha_site_key',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'contact',
                'key'       => 'contact_recaptcha_secret_key',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],

            // بنر صفحه اصلی
            [
                'group' => 'home',
                'key' => 'banner_1_title',
                'value' => json_encode(['fa' => 'صادرات جهانی',    'en' => 'Global Export',     'ar' => 'تصدير عالمي',    'hi' => 'वैश्विक निर्यात', 'it' => 'Export Globale', 'zh' => '全球出口', 'tr' => 'Küresel İhracat'])
            ],
            [
                'group' => 'home',
                'key' => 'banner_1_desc',
                'value' => json_encode(['fa' => 'صادرات سنگ‌های طبیعی ایران به بیش از ۵۰ کشور جهان', 'en' => 'Exporting Iran natural stones to over 50 countries', 'ar' => 'تصدير الأحجار الطبيعية الإيرانية إلى أكثر من 50 دولة', 'hi' => 'ईरान के प्राकृतिक पत्थर 50 से अधिक देशों में निर्यात', 'it' => 'Esportazione di pietre naturali iraniane in oltre 50 paesi', 'zh' => '将伊朗天然石材出口到50多个国家', 'tr' => "İran doğal taşlarını 50'den fazla ülkeye ihraç ediyoruz"])
            ],
            [
                'group' => 'home',
                'key' => 'banner_2_title',
                'value' => json_encode(['fa' => 'کیفیت برتر',      'en' => 'Premium Quality',   'ar' => 'جودة متميزة',    'hi' => 'प्रीमियम गुणवत्ता', 'it' => 'Qualità Premium', 'zh' => '卓越品质', 'tr' => 'Premium Kalite'])
            ],
            [
                'group' => 'home',
                'key' => 'banner_2_desc',
                'value' => json_encode(['fa' => 'سنگ‌های طبیعی با بالاترین استاندارد کیفی از معادن ایران', 'en' => 'Natural stones with highest quality standards from Iranian mines', 'ar' => 'أحجار طبيعية بأعلى معايير الجودة من مناجم إيران', 'hi' => 'ईरान की खदानों से उच्चतम गुणवत्ता मानकों वाले प्राकृतिक पत्थर', 'it' => 'Pietre naturali con i più alti standard di qualità dalle miniere iraniane', 'zh' => '源自伊朗矿区,具备最高质量标准的天然石材', 'tr' => 'İran madenlerinden en yüksek kalite standartlarına sahip doğal taşlar'])
            ],
            [
                'group' => 'home',
                'key' => 'banner_3_title',
                'value' => json_encode(['fa' => 'تنوع محصول',      'en' => 'Product Variety',   'ar' => 'تنوع المنتجات',  'hi' => 'उत्पाद विविधता', 'it' => 'Varietà di Prodotti', 'zh' => '产品多样性', 'tr' => 'Ürün Çeşitliliği'])
            ],
            [
                'group' => 'home',
                'key' => 'banner_3_desc',
                'value' => json_encode(['fa' => 'تراورتن، مرمریت، گرانیت و انواع سنگ‌های طبیعی ایران', 'en' => 'Travertine, Marble, Granite and all types of Iranian natural stones', 'ar' => 'تراڤرتين، رخام، جرانيت وجميع أنواع الأحجار الطبيعية الإيرانية', 'hi' => 'ट्रैवर्टाइन, मार्बल, ग्रेनाइट और सभी प्रकार के ईरानी प्राकृतिक पत्थर', 'it' => 'Travertino, Marmo, Granito e tutti i tipi di pietre naturali iraniane', 'zh' => '洞石、大理石、花岗岩及各类伊朗天然石材', 'tr' => 'Traverten, Mermer, Granit ve her türlü İran doğal taşı'])
            ],
            // بخش درباره ما
            [
                'group' => 'home',
                'key' => 'about_years',
                'value' => '25'
            ],
            [
                'group' => 'home',
                'key' => 'about_title',
                'value' => json_encode(['fa' => 'بهترین سنگ‌های طبیعی ایران را از ما بخواهید', 'en' => 'Get the Best Natural Stones of Iran from Us', 'ar' => 'احصل على أفضل الأحجار الطبيعية الإيرانية منا', 'hi' => 'हमसे ईरान के सर्वश्रेष्ठ प्राकृतिक पत्थर प्राप्त करें', 'it' => 'Ottieni le Migliori Pietre Naturali dell\'Iran da Noi', 'zh' => '从我们这里获取伊朗最优质的天然石材', 'tr' => "İran'ın En İyi Doğal Taşlarını Bizden Alın"])
            ],
            [
                'group' => 'home',
                'key' => 'about_desc',
                'value' => json_encode(['fa' => 'گروه EN Trading با بیش از ۲۵ سال تجربه در استخراج، فرآوری و صادرات سنگ‌های طبیعی ایران، از جمله تراورتن، مرمریت و گرانیت، یکی از معتبرترین تأمین‌کنندگان سنگ در منطقه است.', 'en' => 'EN Trading Group, with over 25 years of experience in extracting, processing and exporting Iranian natural stones including travertine, marble and granite, is one of the most reputable stone suppliers in the region.', 'ar' => 'مجموعة EN Trading، بخبرة تزيد عن 25 عامًا في استخراج ومعالجة وتصدير الأحجار الطبيعية الإيرانية بما في ذلك التراڤرتين والرخام والجرانيت.', 'hi' => 'EN Trading Group, 25 से अधिक वर्षों के अनुभव के साथ ईरानी प्राकृतिक पत्थरों के निष्कर्षण, प्रसंस्करण और निर्यात में।', 'it' => 'EN Trading Group, con oltre 25 anni di esperienza nell\'estrazione, lavorazione ed esportazione di pietre naturali iraniane.', 'zh' => 'EN贸易集团拥有超过25年在伊朗天然石材开采、加工和出口方面的经验,包括洞石、大理石和花岗岩,是该地区最受信赖的石材供应商之一。', 'tr' => "EN Trading Group, traverten, mermer ve granit dahil olmak üzere İran doğal taşlarının çıkarılması, işlenmesi ve ihracatında 25 yılı aşkın deneyime sahip olup, bölgenin en güvenilir taş tedarikçilerinden biridir."])
            ],
            [
                'group' => 'home',
                'key' => 'about_feature_1',
                'value' => json_encode(['fa' => 'استخراج مستقیم از معادن اختصاصی در ایران', 'en' => 'Direct extraction from dedicated mines in Iran', 'ar' => 'استخراج مباشر من المناجم المخصصة في إيران', 'hi' => 'ईरान में समर्पित खदानों से प्रत्यक्ष निष्कर्षण', 'it' => 'Estrazione diretta da miniere dedicate in Iran', 'zh' => '直接从伊朗自有矿区开采', 'tr' => "İran'daki özel ocaklardan doğrudan çıkarma"])
            ],
            [
                'group' => 'home',
                'key' => 'about_feature_2',
                'value' => json_encode(['fa' => 'صادرات به بیش از ۵۰ کشور در ۵ قاره جهان', 'en' => 'Export to more than 50 countries across 5 continents', 'ar' => 'تصدير إلى أكثر من 50 دولة عبر 5 قارات', 'hi' => '5 महाद्वीपों में 50 से अधिक देशों में निर्यात', 'it' => 'Esportazione in oltre 50 paesi in 5 continenti', 'zh' => '出口至五大洲50多个国家', 'tr' => "5 kıtada 50'den fazla ülkeye ihracat"])
            ],
            [
                'group' => 'home',
                'key' => 'about_feature_3',
                'value' => json_encode(['fa' => 'کنترل کیفیت دقیق در تمام مراحل تولید و ارسال', 'en' => 'Strict quality control at all stages of production and shipping', 'ar' => 'رقابة صارمة على الجودة في جميع مراحل الإنتاج والشحن', 'hi' => 'उत्पादन और शिपिंग के सभी चरणों में सख्त गुणवत्ता नियंत्रण', 'it' => 'Controllo qualità rigoroso in tutte le fasi di produzione e spedizione', 'zh' => '生产和运输各阶段严格的质量控制', 'tr' => 'Üretim ve sevkiyatın tüm aşamalarında sıkı kalite kontrolü'])
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
