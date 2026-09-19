<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * UI strings of the "guide" page template (the "talk to our team" panel).
 * Additive and safe to re-run: uses updateOrInsert per locale/key.
 *
 * Run with: php artisan db:seed --class=GuidePageTranslationSeeder
 */
class GuidePageTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // key => [ locale => value ]  (all in the `messages` group)
        $entries = [
            'gd_talk_title' => [
                'fa' => 'با تیم فروش صحبت کنید', 'en' => 'Talk to our sales team', 'ar' => 'تحدّث مع فريق المبيعات',
                'hi' => 'हमारी सेल्स टीम से बात करें', 'it' => 'Parla con il nostro team commerciale',
                'zh' => '联系我们的销售团队', 'tr' => 'Satış ekibimizle görüşün',
            ],
            'gd_talk_text' => [
                'fa' => 'کد سنگ انتخابی‌تان را برایمان بفرستید؛ در واتساپ و تلفن پاسخگوی شما هستیم.',
                'en' => 'Send us the code of the stone you chose — we are available on WhatsApp and by phone.',
                'ar' => 'أرسلوا لنا رمز الحجر الذي اخترتموه — نحن متاحون عبر واتساب وبالهاتف.',
                'hi' => 'अपने चुने हुए पत्थर का कोड हमें भेजें — हम WhatsApp और फ़ोन पर उपलब्ध हैं।',
                'it' => 'Inviateci il codice della pietra scelta: siamo disponibili su WhatsApp e per telefono.',
                'zh' => '请把您选中的石材编号发给我们——我们可通过 WhatsApp 和电话为您解答。',
                'tr' => 'Seçtiğiniz taşın kodunu bize gönderin — WhatsApp\'ta ve telefonda yanıtlıyoruz.',
            ],
            'gd_whatsapp' => [
                'fa' => 'گفت‌وگو در واتساپ', 'en' => 'Chat on WhatsApp', 'ar' => 'تواصل عبر واتساب',
                'hi' => 'WhatsApp पर चैट करें', 'it' => 'Scrivici su WhatsApp', 'zh' => '通过 WhatsApp 联系', 'tr' => 'WhatsApp\'tan yazın',
            ],
            'gd_wa_message' => [
                'fa' => 'سلام، سنگ مورد نظرم را انتخاب کرده‌ام و می‌خواهم درباره جزئیات و رزرو آن اطلاعات بگیرم.',
                'en' => 'Hello, I have chosen a stone and would like details and to reserve it.',
                'ar' => 'مرحبًا، لقد اخترت حجرًا وأود معرفة التفاصيل وحجزه.',
                'hi' => 'नमस्ते, मैंने एक पत्थर चुना है और उसके विवरण तथा आरक्षण के बारे में जानना चाहता/चाहती हूँ।',
                'it' => 'Buongiorno, ho scelto una pietra e vorrei avere dettagli e prenotarla.',
                'zh' => '您好，我已选好一块石材，想了解详情并预订。',
                'tr' => 'Merhaba, bir taş seçtim; ayrıntıları öğrenmek ve rezerve etmek istiyorum.',
            ],
            'gd_reserve_hint' => [
                'fa' => 'در صفحه هر سنگ موجود، دکمه «درخواست رزرو» هم هست.',
                'en' => 'Every available stone also has a “Reserve Product” button on its own page.',
                'ar' => 'كل حجر متاح له أيضًا زر «طلب حجز المنتج» في صفحته.',
                'hi' => 'हर उपलब्ध पत्थर के पेज पर “उत्पाद आरक्षित करें” बटन भी है।',
                'it' => 'Ogni pietra disponibile ha anche un pulsante «Prenota prodotto» nella sua pagina.',
                'zh' => '每块在售石材的页面上也有“预订产品”按钮。',
                'tr' => 'Satıştaki her taşın sayfasında ayrıca «Ürünü Rezerve Et» düğmesi vardır.',
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
