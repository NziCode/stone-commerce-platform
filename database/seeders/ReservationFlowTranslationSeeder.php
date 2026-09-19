<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * UI strings for the WhatsApp button on the product page, the "price on request" cart
 * message, the reservation sales stages (prepayment → final payment) in the admin panel
 * and the owner-notification settings. Additive and safe to re-run (updateOrInsert).
 *
 * Run with: php artisan db:seed --class=ReservationFlowTranslationSeeder
 */
class ReservationFlowTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // group => [ key => [ locale => value ] ]
        $entries = [
            'messages' => [
                'pd_wa_ask' => [
                    'fa' => 'پرسش و رزرو در واتساپ', 'en' => 'Ask & reserve on WhatsApp', 'ar' => 'استفسر واحجز عبر واتساب',
                    'hi' => 'WhatsApp पर पूछें और आरक्षित करें', 'it' => 'Chiedi e prenota su WhatsApp',
                    'zh' => '通过 WhatsApp 咨询并预订', 'tr' => "WhatsApp'tan sorun ve rezerve edin",
                ],
                'pd_wa_message' => [
                    'fa' => 'سلام، درباره سنگ «:name» :code اطلاعات و قیمت می‌خواهم و می‌خواهم آن را رزرو کنم. :url',
                    'en' => 'Hello, I would like the price and details of “:name” :code and to reserve it. :url',
                    'ar' => 'مرحبًا، أودّ معرفة السعر والتفاصيل لحجر «:name» :code وحجزه. :url',
                    'hi' => 'नमस्ते, मुझे “:name” :code की कीमत और विवरण चाहिए और मैं इसे आरक्षित करना चाहता/चाहती हूँ। :url',
                    'it' => 'Buongiorno, vorrei prezzo e dettagli di «:name» :code e prenotarla. :url',
                    'zh' => '您好，我想了解“:name” :code 的价格和详情，并预订该石材。:url',
                    'tr' => 'Merhaba, «:name» :code taşının fiyatını ve ayrıntılarını öğrenmek ve rezerve etmek istiyorum. :url',
                ],
                'pd_wa_code' => [
                    'fa' => '(کد :sku)', 'en' => '(code :sku)', 'ar' => '(الرمز :sku)', 'hi' => '(कोड :sku)',
                    'it' => '(codice :sku)', 'zh' => '（编号 :sku）', 'tr' => '(kod :sku)',
                ],
                'cart_price_on_request' => [
                    'fa' => 'قیمت این سنگ با استعلام است؛ از طریق واتساپ یا تلفن قیمت بگیرید و با پیش‌پرداخت رزرو کنید.',
                    'en' => 'This stone is priced on request — ask for a quote on WhatsApp or by phone, then reserve it with a prepayment.',
                    'ar' => 'سعر هذا الحجر عند الطلب — اطلبوا عرض السعر عبر واتساب أو الهاتف ثم احجزوه بدفعة مقدّمة.',
                    'hi' => 'इस पत्थर की कीमत पूछने पर बताई जाती है — WhatsApp या फ़ोन पर कोटेशन लें और अग्रिम भुगतान देकर आरक्षित करें।',
                    'it' => 'Questa pietra è a prezzo su richiesta: chiedete un preventivo su WhatsApp o per telefono, poi prenotatela con un acconto.',
                    'zh' => '此石材需询价——请通过 WhatsApp 或电话获取报价，并支付预付款进行预订。',
                    'tr' => 'Bu taşın fiyatı talep üzerine verilir — WhatsApp veya telefonla teklif isteyin, ardından ön ödemeyle rezerve edin.',
                ],
            ],

            'admin' => [
                'reservation_stage' => [
                    'fa' => 'مرحله', 'en' => 'Stage', 'ar' => 'المرحلة', 'hi' => 'चरण', 'it' => 'Fase', 'zh' => '阶段', 'tr' => 'Aşama',
                ],
                'reservation_stage_awaiting_deposit' => [
                    'fa' => 'در انتظار پیش‌پرداخت', 'en' => 'Awaiting prepayment', 'ar' => 'بانتظار الدفعة المقدّمة',
                    'hi' => 'अग्रिम भुगतान की प्रतीक्षा', 'it' => "In attesa dell'acconto", 'zh' => '等待预付款', 'tr' => 'Ön ödeme bekleniyor',
                ],
                'reservation_stage_deposit_paid' => [
                    'fa' => 'پیش‌پرداخت دریافت شد', 'en' => 'Prepayment received', 'ar' => 'تم استلام الدفعة المقدّمة',
                    'hi' => 'अग्रिम भुगतान प्राप्त', 'it' => 'Acconto ricevuto', 'zh' => '已收预付款', 'tr' => 'Ön ödeme alındı',
                ],
                'reservation_stage_completed' => [
                    'fa' => 'تسویه شد (فروخته شد)', 'en' => 'Paid in full (sold)', 'ar' => 'تم السداد بالكامل (مباع)',
                    'hi' => 'पूर्ण भुगतान (बिक गया)', 'it' => 'Saldato (venduto)', 'zh' => '已付清（已售出）', 'tr' => 'Tamamen ödendi (satıldı)',
                ],
                'deposit_received_action' => [
                    'fa' => 'ثبت پیش‌پرداخت', 'en' => 'Record prepayment', 'ar' => 'تسجيل الدفعة المقدّمة',
                    'hi' => 'अग्रिम भुगतान दर्ज करें', 'it' => 'Registra acconto', 'zh' => '登记预付款', 'tr' => 'Ön ödemeyi kaydet',
                ],
                'deposit_amount' => [
                    'fa' => 'مبلغ پیش‌پرداخت', 'en' => 'Prepayment amount', 'ar' => 'مبلغ الدفعة المقدّمة',
                    'hi' => 'अग्रिम भुगतान राशि', 'it' => 'Importo acconto', 'zh' => '预付款金额', 'tr' => 'Ön ödeme tutarı',
                ],
                'deposit_currency' => [
                    'fa' => 'واحد پول', 'en' => 'Currency', 'ar' => 'العملة', 'hi' => 'मुद्रा', 'it' => 'Valuta', 'zh' => '币种', 'tr' => 'Para birimi',
                ],
                'final_payment_deadline' => [
                    'fa' => 'مهلت پرداخت نهایی', 'en' => 'Final payment deadline', 'ar' => 'موعد الدفع النهائي',
                    'hi' => 'अंतिम भुगतान की अंतिम तिथि', 'it' => 'Scadenza del saldo', 'zh' => '尾款截止时间', 'tr' => 'Son ödeme tarihi',
                ],
                'deposit_recorded' => [
                    'fa' => 'پیش‌پرداخت ثبت شد؛ سنگ تا پرداخت نهایی رزرو می‌ماند',
                    'en' => 'Prepayment recorded — the stone stays reserved until the final payment',
                    'ar' => 'تم تسجيل الدفعة المقدّمة — يبقى الحجر محجوزًا حتى الدفع النهائي',
                    'hi' => 'अग्रिम भुगतान दर्ज — अंतिम भुगतान तक पत्थर आरक्षित रहेगा',
                    'it' => 'Acconto registrato: la pietra resta prenotata fino al saldo',
                    'zh' => '预付款已登记——在付清尾款前石材将保持预订状态',
                    'tr' => 'Ön ödeme kaydedildi — taş son ödemeye kadar rezerve kalır',
                ],
                'final_paid_action' => [
                    'fa' => 'ثبت پرداخت نهایی', 'en' => 'Record final payment', 'ar' => 'تسجيل الدفع النهائي',
                    'hi' => 'अंतिम भुगतान दर्ज करें', 'it' => 'Registra saldo', 'zh' => '登记尾款', 'tr' => 'Son ödemeyi kaydet',
                ],
                'final_paid_confirm' => [
                    'fa' => 'با ثبت پرداخت نهایی، سنگ «فروخته‌شده» می‌شود. ادامه می‌دهید؟',
                    'en' => 'Recording the final payment marks the stone as sold. Continue?',
                    'ar' => 'تسجيل الدفع النهائي يجعل الحجر «مباعًا». هل تريد المتابعة؟',
                    'hi' => 'अंतिम भुगतान दर्ज करने पर पत्थर “बिका हुआ” हो जाएगा। जारी रखें?',
                    'it' => 'Registrando il saldo la pietra risulterà venduta. Continuare?',
                    'zh' => '登记尾款后，该石材将标记为已售出。是否继续？',
                    'tr' => 'Son ödeme kaydedilince taş “satıldı” olarak işaretlenir. Devam edilsin mi?',
                ],
                'final_paid_recorded' => [
                    'fa' => 'پرداخت نهایی ثبت شد و سنگ فروخته‌شده است', 'en' => 'Final payment recorded — the stone is sold',
                    'ar' => 'تم تسجيل الدفع النهائي — الحجر مباع', 'hi' => 'अंतिम भुगतान दर्ज — पत्थर बिक गया',
                    'it' => 'Saldo registrato: la pietra è venduta', 'zh' => '尾款已登记——石材已售出', 'tr' => 'Son ödeme kaydedildi — taş satıldı',
                ],
                'whatsapp_customer' => [
                    'fa' => 'پیام واتساپ به مشتری', 'en' => 'Message customer on WhatsApp', 'ar' => 'مراسلة العميل عبر واتساب',
                    'hi' => 'ग्राहक को WhatsApp संदेश', 'it' => 'Scrivi al cliente su WhatsApp', 'zh' => '通过 WhatsApp 联系客户', 'tr' => 'Müşteriye WhatsApp mesajı',
                ],
                'call_customer' => [
                    'fa' => 'تماس با مشتری', 'en' => 'Call customer', 'ar' => 'الاتصال بالعميل',
                    'hi' => 'ग्राहक को कॉल करें', 'it' => 'Chiama il cliente', 'zh' => '致电客户', 'tr' => 'Müşteriyi ara',
                ],
                'reservation_payment_section' => [
                    'fa' => 'پرداخت', 'en' => 'Payment', 'ar' => 'الدفع', 'hi' => 'भुगतान', 'it' => 'Pagamento', 'zh' => '付款', 'tr' => 'Ödeme',
                ],
                'reservation_deposit_received_at' => [
                    'fa' => 'تاریخ دریافت پیش‌پرداخت', 'en' => 'Prepayment received on', 'ar' => 'تاريخ استلام الدفعة المقدّمة',
                    'hi' => 'अग्रिम भुगतान प्राप्ति की तिथि', 'it' => 'Acconto ricevuto il', 'zh' => '预付款收款时间', 'tr' => 'Ön ödeme tarihi',
                ],
                'reservation_final_paid_at' => [
                    'fa' => 'تاریخ پرداخت نهایی', 'en' => 'Final payment on', 'ar' => 'تاريخ الدفع النهائي',
                    'hi' => 'अंतिम भुगतान की तिथि', 'it' => 'Saldo ricevuto il', 'zh' => '尾款收款时间', 'tr' => 'Son ödeme tarihi',
                ],

                // ── owner notifications (Settings → Contact) ──
                'notify_bot_token' => [
                    'fa' => 'توکن ربات (تلگرام یا بله)', 'en' => 'Bot token (Telegram or Bale)', 'ar' => 'رمز البوت (تيليجرام أو بلي)',
                    'hi' => 'बॉट टोकन (Telegram या Bale)', 'it' => 'Token del bot (Telegram o Bale)', 'zh' => '机器人令牌（Telegram 或 Bale）', 'tr' => 'Bot belirteci (Telegram veya Bale)',
                ],
                'notify_bot_chat_id' => [
                    'fa' => 'شناسه چت (Chat ID)', 'en' => 'Chat ID', 'ar' => 'معرّف الدردشة (Chat ID)',
                    'hi' => 'चैट आईडी (Chat ID)', 'it' => 'Chat ID', 'zh' => '聊天 ID（Chat ID）', 'tr' => 'Sohbet kimliği (Chat ID)',
                ],
                'notify_bot_api' => [
                    'fa' => 'آدرس API ربات (خالی = تلگرام)', 'en' => 'Bot API address (empty = Telegram)', 'ar' => 'عنوان API للبوت (فارغ = تيليجرام)',
                    'hi' => 'बॉट API पता (खाली = Telegram)', 'it' => 'Indirizzo API del bot (vuoto = Telegram)', 'zh' => '机器人 API 地址（留空 = Telegram）', 'tr' => 'Bot API adresi (boş = Telegram)',
                ],
                'notify_bot_help' => [
                    'fa' => 'برای دریافت اعلان در پیام‌رسان: در BotFather (تلگرام) یا «بابا بله» (بله) یک ربات بسازید، توکن آن را اینجا بگذارید و شناسه چت خودتان را وارد کنید. برای «بله» آدرس API را https://tapi.bale.ai بگذارید.',
                    'en' => 'To get alerts in a messenger: create a bot with BotFather (Telegram) or Bale’s bot father, paste its token here and enter your chat ID. For Bale set the API address to https://tapi.bale.ai.',
                    'ar' => 'لتلقي الإشعارات في تطبيق مراسلة: أنشئوا بوتًا عبر BotFather (تيليجرام) أو بوت الأب في بلي، والصقوا رمزه هنا وأدخلوا معرّف دردشتكم. لبلي اجعلوا عنوان API هو https://tapi.bale.ai.',
                    'hi' => 'मैसेंजर में सूचना पाने के लिए: BotFather (Telegram) या Bale के बॉट फ़ादर से एक बॉट बनाएँ, उसका टोकन यहाँ डालें और अपनी चैट आईडी लिखें। Bale के लिए API पता https://tapi.bale.ai रखें।',
                    'it' => "Per ricevere avvisi in un messenger: create un bot con BotFather (Telegram) o con il bot father di Bale, incollate qui il token e inserite il vostro chat ID. Per Bale impostate l'indirizzo API su https://tapi.bale.ai.",
                    'zh' => '要在即时通讯应用中接收通知：通过 BotFather（Telegram）或 Bale 的机器人之父创建机器人，在此粘贴令牌并填写您的聊天 ID。使用 Bale 时，请将 API 地址设为 https://tapi.bale.ai。',
                    'tr' => 'Mesajlaşma uygulamasında bildirim almak için: BotFather (Telegram) veya Bale’nin bot babası ile bir bot oluşturun, belirtecini buraya yapıştırın ve sohbet kimliğinizi girin. Bale için API adresini https://tapi.bale.ai yapın.',
                ],
                'notify_test_button' => [
                    'fa' => 'ارسال اعلان آزمایشی (بعد از ذخیره)', 'en' => 'Send a test notification (save first)', 'ar' => 'إرسال إشعار تجريبي (بعد الحفظ)',
                    'hi' => 'परीक्षण सूचना भेजें (पहले सहेजें)', 'it' => 'Invia notifica di prova (salva prima)', 'zh' => '发送测试通知（请先保存）', 'tr' => 'Test bildirimi gönder (önce kaydedin)',
                ],
                'notify_test_sent' => [
                    'fa' => 'اعلان آزمایشی ارسال شد', 'en' => 'Test notification sent', 'ar' => 'تم إرسال إشعار تجريبي',
                    'hi' => 'परीक्षण सूचना भेजी गई', 'it' => 'Notifica di prova inviata', 'zh' => '测试通知已发送', 'tr' => 'Test bildirimi gönderildi',
                ],
                'contact_notify_email_help' => [
                    'fa' => 'ایمیلی که اعلان‌های سایت (درخواست رزرو، پیام تماس، سفارش، فیش بانکی) به آن ارسال می‌شود. خالی = ایمیل اصلی سایت. چند ایمیل را با ویرگول جدا کنید.',
                    'en' => 'Email that receives site alerts (reservation requests, contact messages, orders, bank receipts). Empty = the site email. Separate several addresses with commas.',
                    'ar' => 'البريد الذي يستلم تنبيهات الموقع (طلبات الحجز، رسائل التواصل، الطلبات، إيصالات البنك). فارغ = بريد الموقع. افصلوا العناوين المتعددة بفواصل.',
                    'hi' => 'वह ईमेल जिस पर साइट की सूचनाएँ आती हैं (आरक्षण अनुरोध, संपर्क संदेश, ऑर्डर, बैंक रसीद)। खाली = साइट का ईमेल। कई पते कॉमा से अलग करें।',
                    'it' => "Email che riceve gli avvisi del sito (richieste di prenotazione, messaggi, ordini, ricevute bancarie). Vuoto = email del sito. Separate più indirizzi con virgole.",
                    'zh' => '接收网站提醒（预订申请、联系留言、订单、银行回单）的邮箱。留空 = 网站邮箱。多个地址用逗号分隔。',
                    'tr' => 'Site bildirimlerinin (rezervasyon talebi, iletişim mesajı, sipariş, banka dekontu) gönderileceği e-posta. Boş = site e-postası. Birden fazla adresi virgülle ayırın.',
                ],
                'contact_notify_sms_help' => [
                    'fa' => 'شماره موبایل‌هایی که پیامک اعلان می‌گیرند (با ویرگول جدا کنید). نیاز به کلید API در تب «پیامک» دارد.',
                    'en' => 'Mobile numbers that receive alert SMS (comma separated). Needs the API key from the SMS tab.',
                    'ar' => 'أرقام الجوال التي تستلم رسائل التنبيه (مفصولة بفواصل). تتطلب مفتاح API من تبويب الرسائل النصية.',
                    'hi' => 'वे मोबाइल नंबर जिन पर सूचना SMS आएगा (कॉमा से अलग करें)। SMS टैब में API कुंजी ज़रूरी है।',
                    'it' => "Numeri di cellulare che ricevono gli SMS di avviso (separati da virgole). Serve la chiave API della scheda SMS.",
                    'zh' => '接收提醒短信的手机号（用逗号分隔）。需要在“短信”标签页填写 API 密钥。',
                    'tr' => 'Bildirim SMS’i alacak cep telefonu numaraları (virgülle ayırın). SMS sekmesindeki API anahtarı gerekir.',
                ],
            ],
        ];

        $touched = [];

        foreach ($entries as $group => $keys) {
            foreach ($keys as $key => $locales) {
                foreach ($locales as $locale => $value) {
                    DB::table('translations')->updateOrInsert(
                        ['locale' => $locale, 'group' => $group, 'key' => $key],
                        ['value' => $value, 'is_auto' => false, 'updated_at' => now(), 'created_at' => now()]
                    );
                    $touched["{$locale}.{$group}"] = true;
                }
            }
        }

        // The observer-based cache invalidation doesn't fire for raw DB writes.
        foreach (array_keys($touched) as $pair) {
            [$locale, $group] = explode('.', $pair, 2);
            Cache::forget("translations.{$locale}.{$group}");
        }
    }
}
