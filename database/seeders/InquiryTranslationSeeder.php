<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * UI strings of the "Request a quote" pop-up (price inquiries on stones).
 * Additive and safe to re-run: uses updateOrInsert per locale/key.
 *
 * Run with: php artisan db:seed --class=InquiryTranslationSeeder
 */
class InquiryTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // key => [ locale => value ]  (all in the `messages` group)
        $entries = [
            'inquiry_intro' => [
                'fa' => 'برای دریافت قیمت این سنگ همین حالا در واتساپ بپرسید، یا شماره‌تان را بگذارید تا کارشناس فروش با شما تماس بگیرد.',
                'en' => 'Ask about this stone right now on WhatsApp, or leave your number and our sales team will call you back.',
                'ar' => 'اسأل عن سعر هذا الحجر الآن عبر واتساب، أو اترك رقمك ليتصل بك فريق المبيعات.',
                'hi' => 'इस पत्थर की कीमत अभी WhatsApp पर पूछें, या अपना नंबर छोड़ें — हमारी सेल्स टीम आपको कॉल करेगी।',
                'it' => 'Chiedi subito il prezzo di questa pietra su WhatsApp, oppure lascia il tuo numero e il nostro team vendite ti richiamerà.',
                'zh' => '立即通过 WhatsApp 询问这块石材的价格，或留下您的号码，销售团队会回电给您。',
                'tr' => 'Bu taşın fiyatını hemen WhatsApp’tan sorun ya da numaranızı bırakın, satış ekibimiz sizi arasın.',
            ],
            'inquiry_or_leave' => [
                'fa' => 'یا شماره‌تان را بگذارید تا با شما تماس بگیریم',
                'en' => 'Or leave your number and we will contact you',
                'ar' => 'أو اترك رقمك وسنتواصل معك',
                'hi' => 'या अपना नंबर छोड़ें, हम आपसे संपर्क करेंगे',
                'it' => 'Oppure lascia il tuo numero e ti contatteremo',
                'zh' => '或留下您的号码，我们会联系您',
                'tr' => 'Ya da numaranızı bırakın, sizinle iletişime geçelim',
            ],
            'inquiry_submit' => [
                'fa' => 'ثبت درخواست', 'en' => 'Send request', 'ar' => 'إرسال الطلب', 'hi' => 'अनुरोध भेजें',
                'it' => 'Invia richiesta', 'zh' => '提交询价', 'tr' => 'Talebi gönder',
            ],
            'inquiry_sending' => [
                'fa' => 'در حال ارسال…', 'en' => 'Sending…', 'ar' => 'جارٍ الإرسال…', 'hi' => 'भेजा जा रहा है…',
                'it' => 'Invio in corso…', 'zh' => '发送中…', 'tr' => 'Gönderiliyor…',
            ],
            'inquiry_sent' => [
                'fa' => 'درخواست شما ثبت شد. کارشناس فروش به‌زودی با شما تماس می‌گیرد.',
                'en' => 'Your request has been received. Our sales team will contact you shortly.',
                'ar' => 'تم استلام طلبك. سيتواصل معك فريق المبيعات قريبًا.',
                'hi' => 'आपका अनुरोध प्राप्त हो गया है। हमारी सेल्स टीम जल्द ही आपसे संपर्क करेगी।',
                'it' => 'Abbiamo ricevuto la tua richiesta. Il nostro team vendite ti contatterà a breve.',
                'zh' => '已收到您的询价，销售团队将尽快与您联系。',
                'tr' => 'Talebiniz alındı. Satış ekibimiz kısa süre içinde sizinle iletişime geçecek.',
            ],
            'inquiry_phone_invalid' => [
                'fa' => 'شمارهٔ تماس را درست وارد کنید.', 'en' => 'Please enter a valid phone number.', 'ar' => 'يرجى إدخال رقم هاتف صحيح.',
                'hi' => 'कृपया सही फ़ोन नंबर दर्ज करें।', 'it' => 'Inserisci un numero di telefono valido.', 'zh' => '请输入有效的电话号码。', 'tr' => 'Lütfen geçerli bir telefon numarası girin.',
            ],
            'inquiry_error' => [
                'fa' => 'ثبت درخواست انجام نشد. لطفاً دوباره تلاش کنید یا با ما تماس بگیرید.',
                'en' => 'The request could not be sent. Please try again or contact us.',
                'ar' => 'تعذّر إرسال الطلب. يرجى المحاولة مرة أخرى أو التواصل معنا.',
                'hi' => 'अनुरोध भेजा नहीं जा सका। कृपया फिर से प्रयास करें या हमसे संपर्क करें।',
                'it' => 'Impossibile inviare la richiesta. Riprova o contattaci.',
                'zh' => '提交失败，请重试或直接联系我们。',
                'tr' => 'Talep gönderilemedi. Lütfen tekrar deneyin veya bizimle iletişime geçin.',
            ],
            'inquiry_unavailable' => [
                'fa' => 'این سنگ در حال حاضر قابل استعلام نیست.',
                'en' => 'This stone cannot be asked about right now.',
                'ar' => 'لا يمكن الاستفسار عن هذا الحجر حاليًا.',
                'hi' => 'इस पत्थर के बारे में अभी पूछताछ संभव नहीं है।',
                'it' => 'Al momento non è possibile richiedere informazioni su questa pietra.',
                'zh' => '这块石材目前无法询价。',
                'tr' => 'Bu taş için şu anda fiyat sorulamıyor.',
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
