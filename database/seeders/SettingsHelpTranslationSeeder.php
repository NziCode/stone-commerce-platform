<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Additive, idempotent seeder for the settings-page "field help" strings
 * (small grey hint text under each field in Admin > Settings) that were
 * never present in TranslationSeeder.php — a pre-existing gap in the
 * original project, unrelated to the reservation feature. Safe to re-run.
 *
 * Run once with: php artisan db:seed --class=SettingsHelpTranslationSeeder
 */
class SettingsHelpTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // key => [locale => value]
        $help = [
            'social_networks_intro' => [
                'fa' => 'لینک صفحات شبکه‌های اجتماعی خود را وارد کنید. فیلدهای خالی در فوتر سایت نمایش داده نمی‌شوند.',
                'en' => 'Enter your social media page links. Empty fields are not shown in the site footer.',
                'ar' => 'أدخل روابط صفحاتك على وسائل التواصل. الحقول الفارغة لا تظهر في تذييل الموقع.',
                'hi' => 'अपने सोशल मीडिया पेज लिंक दर्ज करें। खाली फ़ील्ड फ़ुटर में नहीं दिखतीं।',
                'it' => 'Inserisci i link ai tuoi social. I campi vuoti non vengono mostrati nel footer.',
                'zh' => '请输入您的社交媒体页面链接。空白字段不会显示在页脚。',
                'tr' => 'Sosyal medya sayfa bağlantılarınızı girin. Boş alanlar site altbilgisinde gösterilmez.',
            ],
            'social_instagram_help' => ['fa'=>'آدرس کامل صفحه اینستاگرام.','en'=>'Full Instagram page URL.','ar'=>'الرابط الكامل لصفحة إنستغرام.','hi'=>'इंस्टाग्राम पेज का पूरा लिंक।','it'=>'URL completo della pagina Instagram.','zh'=>'完整的 Instagram 页面链接。','tr'=>'Instagram sayfasının tam bağlantısı.'],
            'social_telegram_help' => ['fa'=>'آدرس کامل کانال یا صفحه تلگرام.','en'=>'Full Telegram channel/page URL.','ar'=>'الرابط الكامل لقناة أو صفحة تيليجرام.','hi'=>'टेलीग्राम चैनल/पेज का पूरा लिंक।','it'=>'URL completo del canale/pagina Telegram.','zh'=>'完整的 Telegram 频道/页面链接。','tr'=>'Telegram kanalı/sayfasının tam bağlantısı.'],
            'social_whatsapp_help' => ['fa'=>'فقط شماره تلفن با کد کشور، بدون + یا صفر (مثال: 989123456789).','en'=>'Phone number with country code only, no + or leading zero (e.g. 989123456789).','ar'=>'رقم الهاتف مع رمز الدولة فقط، بدون + أو صفر بداية (مثال: 989123456789).','hi'=>'केवल देश कोड सहित फ़ोन नंबर, + या शुरुआती शून्य नहीं (उदा: 989123456789)।','it'=>'Solo numero con prefisso internazionale, senza + o zero iniziale (es. 989123456789).','zh'=>'仅电话号码含国家代码，不含+号或前导零（例：989123456789）。','tr'=>'Sadece ülke koduyla telefon numarası, + veya baştaki sıfır olmadan (örn. 989123456789).'],
            'social_linkedin_help' => ['fa'=>'آدرس کامل صفحه لینکدین شرکت.','en'=>'Full company LinkedIn page URL.','ar'=>'الرابط الكامل لصفحة الشركة على لينكد إن.','hi'=>'कंपनी के लिंक्डइन पेज का पूरा लिंक।','it'=>'URL completo della pagina aziendale LinkedIn.','zh'=>'完整的公司 LinkedIn 页面链接。','tr'=>'Şirketin LinkedIn sayfasının tam bağlantısı.'],
            'social_youtube_help' => ['fa'=>'آدرس کامل کانال یوتیوب.','en'=>'Full YouTube channel URL.','ar'=>'الرابط الكامل لقناة يوتيوب.','hi'=>'यूट्यूब चैनल का पूरा लिंक।','it'=>'URL completo del canale YouTube.','zh'=>'完整的 YouTube 频道链接。','tr'=>'YouTube kanalının tam bağlantısı.'],
            'social_twitter_help' => ['fa'=>'آدرس کامل صفحه X (توییتر).','en'=>'Full X (Twitter) page URL.','ar'=>'الرابط الكامل لصفحة X (تويتر).','hi'=>'X (ट्विटर) पेज का पूरा लिंक।','it'=>'URL completo della pagina X (Twitter).','zh'=>'完整的 X（推特）页面链接。','tr'=>'X (Twitter) sayfasının tam bağlantısı.'],
            'social_facebook_help' => ['fa'=>'آدرس کامل صفحه فیسبوک.','en'=>'Full Facebook page URL.','ar'=>'الرابط الكامل لصفحة فيسبوك.','hi'=>'फ़ेसबुक पेज का पूरा लिंक।','it'=>'URL completo della pagina Facebook.','zh'=>'完整的 Facebook 页面链接。','tr'=>'Facebook sayfasının tam bağlantısı.'],

            'site_name_help' => ['fa'=>'نام سایت که در هدر، فوتر و عنوان صفحات نمایش داده می‌شود.','en'=>'Site name shown in the header, footer, and page titles.','ar'=>'اسم الموقع الذي يظهر في الترويسة والتذييل وعناوين الصفحات.','hi'=>'साइट का नाम जो हेडर, फ़ुटर और पेज शीर्षकों में दिखता है।','it'=>'Nome del sito mostrato in header, footer e titoli pagina.','zh'=>'显示在页眉、页脚和页面标题中的网站名称。','tr'=>'Üstbilgi, altbilgi ve sayfa başlıklarında görünen site adı.'],
            'site_tagline_help' => ['fa'=>'شعار یا توضیح کوتاه درباره سایت.','en'=>'A short slogan or description of the site.','ar'=>'شعار أو وصف قصير للموقع.','hi'=>'साइट का संक्षिप्त नारा या विवरण।','it'=>'Uno slogan o breve descrizione del sito.','zh'=>'网站的简短口号或说明。','tr'=>'Site için kısa bir slogan veya açıklama.'],
            'site_email_help' => ['fa'=>'ایمیل اصلی برای تماس مشتریان.','en'=>'Primary contact email for customers.','ar'=>'البريد الإلكتروني الأساسي لتواصل العملاء.','hi'=>'ग्राहकों के लिए मुख्य संपर्क ईमेल।','it'=>'Email principale di contatto per i clienti.','zh'=>'客户联系的主要邮箱。','tr'=>'Müşteriler için birincil iletişim e-postası.'],
            'site_phone_help' => ['fa'=>'شماره تماس اصلی، همراه با کد کشور نمایش داده می‌شود.','en'=>'Primary phone number, displayed with country code.','ar'=>'رقم الهاتف الأساسي، يُعرض مع رمز الدولة.','hi'=>'मुख्य फ़ोन नंबर, देश कोड सहित दिखाया जाता है।','it'=>'Numero di telefono principale, mostrato con prefisso.','zh'=>'主要电话号码，显示时带国家代码。','tr'=>'Birincil telefon numarası, ülke koduyla gösterilir.'],
            'site_address_help' => ['fa'=>'آدرس دفتر یا کارخانه که در فوتر و صفحه تماس نمایش داده می‌شود.','en'=>'Office/factory address shown in the footer and contact page.','ar'=>'عنوان المكتب/المصنع الذي يظهر في التذييل وصفحة التواصل.','hi'=>'कार्यालय/फ़ैक्टरी पता जो फ़ुटर और संपर्क पेज पर दिखता है।','it'=>'Indirizzo ufficio/fabbrica mostrato in footer e contatti.','zh'=>'显示在页脚和联系页面的办公室/工厂地址。','tr'=>'Altbilgide ve iletişim sayfasında görünen ofis/fabrika adresi.'],
            'site_working_hours_help' => ['fa'=>'ساعات کاری که در فوتر و صفحه تماس نمایش داده می‌شود.','en'=>'Working hours shown in the footer and contact page.','ar'=>'ساعات العمل التي تظهر في التذييل وصفحة التواصل.','hi'=>'कार्य समय जो फ़ुटर और संपर्क पेज पर दिखता है।','it'=>'Orari di lavoro mostrati in footer e contatti.','zh'=>'显示在页脚和联系页面的工作时间。','tr'=>'Altbilgide ve iletişim sayfasında görünen çalışma saatleri.'],
            'site_map_lat_help' => ['fa'=>'مختصات جغرافیایی عرض (latitude) برای نمایش نقشه.','en'=>'Latitude coordinate for the map display.','ar'=>'إحداثية خط العرض لعرض الخريطة.','hi'=>'नक्शे के लिए अक्षांश निर्देशांक।','it'=>'Coordinata di latitudine per la mappa.','zh'=>'用于地图显示的纬度坐标。','tr'=>'Harita gösterimi için enlem koordinatı.'],
            'site_map_lng_help' => ['fa'=>'مختصات جغرافیایی طول (longitude) برای نمایش نقشه.','en'=>'Longitude coordinate for the map display.','ar'=>'إحداثية خط الطول لعرض الخريطة.','hi'=>'नक्शे के लिए देशांतर निर्देशांक।','it'=>'Coordinata di longitudine per la mappa.','zh'=>'用于地图显示的经度坐标。','tr'=>'Harita gösterimi için boylam koordinatı.'],
            'map_picker_help' => ['fa'=>'روی نقشه کلیک کنید تا موقعیت را انتخاب کنید.','en'=>'Click on the map to select the location.','ar'=>'انقر على الخريطة لتحديد الموقع.','hi'=>'स्थान चुनने के लिए नक्शे पर क्लिक करें।','it'=>'Clicca sulla mappa per selezionare la posizione.','zh'=>'点击地图以选择位置。','tr'=>'Konumu seçmek için haritaya tıklayın.'],

            'meta_title_help' => ['fa'=>'عنوان صفحه که در نتایج گوگل و تب مرورگر نمایش داده می‌شود.','en'=>'Page title shown in Google results and the browser tab.','ar'=>'عنوان الصفحة الذي يظهر في نتائج جوجل وعلامة تبويب المتصفح.','hi'=>'पेज शीर्षक जो गूगल परिणामों और ब्राउज़र टैब में दिखता है।','it'=>'Titolo pagina mostrato nei risultati Google e nella scheda browser.','zh'=>'显示在 Google 搜索结果和浏览器标签中的页面标题。','tr'=>'Google sonuçlarında ve tarayıcı sekmesinde görünen sayfa başlığı.'],
            'meta_description_help' => ['fa'=>'توضیح کوتاه صفحه که زیر عنوان در نتایج گوگل نمایش داده می‌شود.','en'=>'Short page summary shown under the title in Google results.','ar'=>'ملخص قصير للصفحة يظهر تحت العنوان في نتائج جوجل.','hi'=>'पेज का संक्षिप्त सारांश जो गूगल परिणामों में शीर्षक के नीचे दिखता है।','it'=>'Breve riepilogo mostrato sotto il titolo nei risultati Google.','zh'=>'显示在 Google 搜索结果标题下方的页面简介。','tr'=>'Google sonuçlarında başlığın altında görünen kısa sayfa özeti.'],
            'og_image_help' => ['fa'=>'تصویری که هنگام اشتراک‌گذاری لینک سایت در شبکه‌های اجتماعی نمایش داده می‌شود.','en'=>'Image shown when the site link is shared on social media.','ar'=>'الصورة التي تظهر عند مشاركة رابط الموقع على وسائل التواصل.','hi'=>'सोशल मीडिया पर साइट लिंक शेयर करते समय दिखने वाली छवि।','it'=>'Immagine mostrata quando il link del sito viene condiviso sui social.','zh'=>'在社交媒体上分享网站链接时显示的图片。','tr'=>'Site bağlantısı sosyal medyada paylaşıldığında gösterilen görsel.'],
            'google_analytics_id_help' => ['fa'=>'شناسه Google Analytics (مثال: G-XXXXXXXXXX).','en'=>'Google Analytics ID (e.g. G-XXXXXXXXXX).','ar'=>'معرّف Google Analytics (مثال: G-XXXXXXXXXX).','hi'=>'गूगल एनालिटिक्स आईडी (उदा: G-XXXXXXXXXX)।','it'=>'ID Google Analytics (es. G-XXXXXXXXXX).','zh'=>'Google Analytics 编号（例：G-XXXXXXXXXX）。','tr'=>'Google Analytics kimliği (örn. G-XXXXXXXXXX).'],
            'google_tag_manager_id_help' => ['fa'=>'شناسه Google Tag Manager (مثال: GTM-XXXXXXX).','en'=>'Google Tag Manager ID (e.g. GTM-XXXXXXX).','ar'=>'معرّف Google Tag Manager (مثال: GTM-XXXXXXX).','hi'=>'गूगल टैग मैनेजर आईडी (उदा: GTM-XXXXXXX)।','it'=>'ID Google Tag Manager (es. GTM-XXXXXXX).','zh'=>'Google Tag Manager 编号（例：GTM-XXXXXXX）。','tr'=>'Google Tag Manager kimliği (örn. GTM-XXXXXXX).'],
            'google_search_console_help' => ['fa'=>'کد تایید مالکیت Google Search Console.','en'=>'Google Search Console verification code.','ar'=>'رمز التحقق من Google Search Console.','hi'=>'गूगल सर्च कंसोल सत्यापन कोड।','it'=>'Codice di verifica Google Search Console.','zh'=>'Google Search Console 验证代码。','tr'=>'Google Search Console doğrulama kodu.'],
            'robots_txt_help' => ['fa'=>'محتوای فایل robots.txt که به موتورهای جستجو دستور می‌دهد کدام صفحات را ایندکس کنند.','en'=>'Content of robots.txt, telling search engines which pages to index.','ar'=>'محتوى ملف robots.txt الذي يخبر محركات البحث بالصفحات التي يجب فهرستتها.','hi'=>'robots.txt की सामग्री, जो सर्च इंजनों को बताती है कौन-से पेज इंडेक्स करें।','it'=>'Contenuto di robots.txt che indica ai motori di ricerca quali pagine indicizzare.','zh'=>'robots.txt 内容，告知搜索引擎应索引哪些页面。','tr'=>'Arama motorlarına hangi sayfaların dizinleneceğini bildiren robots.txt içeriği.'],

            'payment_zarinpal_merchant_help' => ['fa'=>'کد مرچنت درگاه زرین‌پال.','en'=>'ZarinPal merchant code.','ar'=>'رمز التاجر في زرين‌بال.','hi'=>'ज़रीनपाल मर्चेंट कोड।','it'=>'Codice merchant ZarinPal.','zh'=>'ZarinPal 商户代码。','tr'=>'ZarinPal üye işyeri kodu.'],
            'payment_zarinpal_sandbox_help' => ['fa'=>'فعال‌سازی حالت تست (Sandbox) برای درگاه زرین‌پال — بدون تراکنش واقعی.','en'=>'Enable ZarinPal sandbox/test mode — no real transactions.','ar'=>'تفعيل وضع الاختبار في زرين‌بال — بدون معاملات حقيقية.','hi'=>'ज़रीनपाल टेस्ट मोड सक्षम करें — कोई वास्तविक लेन-देन नहीं।','it'=>'Attiva la modalità sandbox/test ZarinPal — nessuna transazione reale.','zh'=>'启用 ZarinPal 沙盒/测试模式——无真实交易。','tr'=>'ZarinPal test modunu etkinleştir — gerçek işlem yapılmaz.'],
            'payment_receipt_account_number_help' => ['fa'=>'شماره حساب بانکی برای پرداخت با فیش/کارت به کارت.','en'=>'Bank account number for receipt/manual bank transfer payments.','ar'=>'رقم الحساب المصرفي للدفع عبر إيصال/تحويل بنكي يدوي.','hi'=>'रसीद/मैनुअल बैंक ट्रांसफ़र भुगतान के लिए बैंक खाता संख्या।','it'=>'Numero conto bancario per pagamenti tramite ricevuta/bonifico.','zh'=>'用于收据/手动银行转账付款的银行账号。','tr'=>'Makbuz/manuel banka havalesi ödemeleri için banka hesap numarası.'],
            'payment_receipt_iban_help' => ['fa'=>'شماره شبا (IBAN) حساب بانکی.','en'=>'IBAN of the bank account.','ar'=>'رقم الآيبان (IBAN) للحساب المصرفي.','hi'=>'बैंक खाते का IBAN।','it'=>'IBAN del conto bancario.','zh'=>'银行账户的 IBAN。','tr'=>'Banka hesabının IBAN numarası.'],
            'payment_receipt_swift_help' => ['fa'=>'کد سوئیفت (SWIFT) بانک برای انتقال ارزی.','en'=>'SWIFT code of the bank for international transfers.','ar'=>'رمز السويفت (SWIFT) للبنك للتحويلات الدولية.','hi'=>'अंतरराष्ट्रीय ट्रांसफ़र के लिए बैंक का SWIFT कोड।','it'=>'Codice SWIFT della banca per bonifici internazionali.','zh'=>'用于国际转账的银行 SWIFT 代码。','tr'=>'Uluslararası transferler için bankanın SWIFT kodu.'],
            'payment_receipt_bank_name_help' => ['fa'=>'نام بانک برای پرداخت با فیش.','en'=>'Bank name for receipt payments.','ar'=>'اسم البنك للدفع عبر الإيصال.','hi'=>'रसीद भुगतान के लिए बैंक का नाम।','it'=>'Nome della banca per pagamenti con ricevuta.','zh'=>'收据付款所用的银行名称。','tr'=>'Makbuz ödemeleri için banka adı.'],
            'payment_receipt_instructions_help' => ['fa'=>'توضیحات و راهنمای پرداخت با فیش که به مشتری نمایش داده می‌شود.','en'=>'Receipt payment instructions shown to the customer.','ar'=>'تعليمات الدفع بالإيصال التي تُعرض للعميل.','hi'=>'ग्राहक को दिखाई जाने वाली रसीद भुगतान निर्देश।','it'=>'Istruzioni di pagamento con ricevuta mostrate al cliente.','zh'=>'向客户显示的收据付款说明。','tr'=>'Müşteriye gösterilen makbuz ödeme talimatları.'],

            'smtp_host_help' => ['fa'=>'آدرس سرور SMTP برای ارسال ایمیل.','en'=>'SMTP server host for sending emails.','ar'=>'مضيف خادم SMTP لإرسال البريد الإلكتروني.','hi'=>'ईमेल भेजने के लिए SMTP सर्वर होस्ट।','it'=>'Host del server SMTP per l\'invio di email.','zh'=>'用于发送邮件的 SMTP 服务器地址。','tr'=>'E-posta göndermek için SMTP sunucu adresi.'],
            'smtp_port_help' => ['fa'=>'پورت سرور SMTP (معمولاً 587 یا 465).','en'=>'SMTP server port (usually 587 or 465).','ar'=>'منفذ خادم SMTP (عادة 587 أو 465).','hi'=>'SMTP सर्वर पोर्ट (आमतौर पर 587 या 465)।','it'=>'Porta del server SMTP (di solito 587 o 465).','zh'=>'SMTP 服务器端口（通常为 587 或 465）。','tr'=>'SMTP sunucu portu (genellikle 587 veya 465).'],
            'smtp_username_help' => ['fa'=>'نام کاربری حساب SMTP.','en'=>'SMTP account username.','ar'=>'اسم مستخدم حساب SMTP.','hi'=>'SMTP खाते का उपयोगकर्ता नाम।','it'=>'Nome utente dell\'account SMTP.','zh'=>'SMTP 账户用户名。','tr'=>'SMTP hesabı kullanıcı adı.'],
            'smtp_password_help' => ['fa'=>'رمز عبور حساب SMTP.','en'=>'SMTP account password.','ar'=>'كلمة مرور حساب SMTP.','hi'=>'SMTP खाते का पासवर्ड।','it'=>'Password dell\'account SMTP.','zh'=>'SMTP 账户密码。','tr'=>'SMTP hesabı parolası.'],
            'smtp_from_address_help' => ['fa'=>'آدرس ایمیل فرستنده در ایمیل‌های ارسالی.','en'=>'Sender email address on outgoing emails.','ar'=>'عنوان البريد الإلكتروني للمرسل في الرسائل الصادرة.','hi'=>'भेजी गई ईमेल में प्रेषक का ईमेल पता।','it'=>'Indirizzo email del mittente nelle email in uscita.','zh'=>'发出邮件中的发件人邮箱地址。','tr'=>'Giden e-postalarda gönderen e-posta adresi.'],
            'smtp_from_name_help' => ['fa'=>'نام فرستنده در ایمیل‌های ارسالی.','en'=>'Sender name on outgoing emails.','ar'=>'اسم المرسل في الرسائل الصادرة.','hi'=>'भेजी गई ईमेल में प्रेषक का नाम।','it'=>'Nome del mittente nelle email in uscita.','zh'=>'发出邮件中的发件人名称。','tr'=>'Giden e-postalarda gönderen adı.'],

            'sms_provider_help' => ['fa'=>'سرویس‌دهنده پیامک (مثلاً کاوه‌نگار).','en'=>'SMS provider (e.g. Kavenegar).','ar'=>'مزود خدمة الرسائل النصية (مثل Kavenegar).','hi'=>'एसएमएस प्रदाता (जैसे Kavenegar)।','it'=>'Fornitore SMS (es. Kavenegar).','zh'=>'短信服务商（如 Kavenegar）。','tr'=>'SMS sağlayıcısı (örn. Kavenegar).'],
            'sms_api_key_help' => ['fa'=>'کلید API سرویس پیامک.','en'=>'API key of the SMS service.','ar'=>'مفتاح API لخدمة الرسائل النصية.','hi'=>'एसएमएस सेवा की API कुंजी।','it'=>'Chiave API del servizio SMS.','zh'=>'短信服务的 API 密钥。','tr'=>'SMS servisinin API anahtarı.'],
            'sms_sender_help' => ['fa'=>'شماره فرستنده پیامک.','en'=>'Sender number for SMS.','ar'=>'رقم المرسل للرسائل النصية.','hi'=>'एसएमएस के लिए प्रेषक नंबर।','it'=>'Numero mittente per gli SMS.','zh'=>'短信发送号码。','tr'=>'SMS gönderici numarası.'],
            'sms_otp_template_help' => ['fa'=>'متن پیامک کد تایید. از {code} برای درج کد استفاده کنید.','en'=>'OTP SMS text. Use {code} to insert the code.','ar'=>'نص رسالة رمز التحقق. استخدم {code} لإدراج الرمز.','hi'=>'OTP एसएमएस टेक्स्ट। कोड डालने के लिए {code} का उपयोग करें।','it'=>'Testo SMS OTP. Usa {code} per inserire il codice.','zh'=>'验证码短信内容。使用 {code} 插入验证码。','tr'=>'OTP SMS metni. Kodu eklemek için {code} kullanın.'],
            'sms_order_confirmed_template_help' => ['fa'=>'متن پیامک تایید سفارش.','en'=>'SMS text for order confirmation.','ar'=>'نص رسالة تأكيد الطلب.','hi'=>'ऑर्डर पुष्टि के लिए एसएमएस टेक्स्ट।','it'=>'Testo SMS per la conferma dell\'ordine.','zh'=>'订单确认短信内容。','tr'=>'Sipariş onayı SMS metni.'],
            'sms_order_shipped_template_help' => ['fa'=>'متن پیامک اطلاع‌رسانی ارسال سفارش.','en'=>'SMS text for shipping notification.','ar'=>'نص رسالة إشعار الشحن.','hi'=>'शिपिंग सूचना के लिए एसएमएस टेक्स्ट।','it'=>'Testo SMS per la notifica di spedizione.','zh'=>'发货通知短信内容。','tr'=>'Kargo bildirimi SMS metni.'],

            'contact_notify_email_help' => ['fa'=>'ایمیلی که پیام‌های فرم تماس با ما به آن ارسال می‌شود.','en'=>'Email address that receives contact form submissions.','ar'=>'البريد الإلكتروني الذي يستلم رسائل نموذج التواصل.','hi'=>'ईमेल पता जो संपर्क फ़ॉर्म सबमिशन प्राप्त करता है।','it'=>'Indirizzo email che riceve gli invii del modulo contatti.','zh'=>'接收联系表单提交的邮箱地址。','tr'=>'İletişim formu gönderimlerini alan e-posta adresi.'],
            'contact_notify_sms_help' => ['fa'=>'شماره‌ای که پیامک اطلاع‌رسانی پیام جدید به آن ارسال می‌شود.','en'=>'Phone number notified via SMS on new messages.','ar'=>'الرقم الذي يتم إشعاره برسالة نصية عند وصول رسالة جديدة.','hi'=>'नए संदेश पर एसएमएस से सूचित किया जाने वाला फ़ोन नंबर।','it'=>'Numero avvisato via SMS per i nuovi messaggi.','zh'=>'新消息通过短信通知的电话号码。','tr'=>'Yeni mesajlarda SMS ile bilgilendirilecek telefon numarası.'],
            'contact_recaptcha_site_key_help' => ['fa'=>'کلید سایت (Site Key) گوگل ری‌کپچا.','en'=>'Google reCAPTCHA site key.','ar'=>'مفتاح الموقع لـ Google reCAPTCHA.','hi'=>'गूगल reCAPTCHA साइट कुंजी।','it'=>'Site key di Google reCAPTCHA.','zh'=>'Google reCAPTCHA 网站密钥。','tr'=>'Google reCAPTCHA site anahtarı.'],
            'contact_recaptcha_secret_key_help' => ['fa'=>'کلید محرمانه (Secret Key) گوگل ری‌کپچا.','en'=>'Google reCAPTCHA secret key.','ar'=>'المفتاح السري لـ Google reCAPTCHA.','hi'=>'गूगल reCAPTCHA गुप्त कुंजी।','it'=>'Secret key di Google reCAPTCHA.','zh'=>'Google reCAPTCHA 密钥。','tr'=>'Google reCAPTCHA gizli anahtarı.'],
            'contact_recaptcha_enabled_help' => ['fa'=>'فعال‌سازی محافظت ری‌کپچا برای فرم تماس با ما.','en'=>'Enable reCAPTCHA protection on the contact form.','ar'=>'تفعيل حماية reCAPTCHA لنموذج التواصل.','hi'=>'संपर्क फ़ॉर्म पर reCAPTCHA सुरक्षा सक्षम करें।','it'=>'Attiva la protezione reCAPTCHA sul modulo contatti.','zh'=>'为联系表单启用 reCAPTCHA 保护。','tr'=>'İletişim formunda reCAPTCHA korumasını etkinleştir.'],

            'about_years_help' => ['fa'=>'تعداد سال‌های تجربه که در صفحه اصلی نمایش داده می‌شود.','en'=>'Years of experience shown on the homepage.','ar'=>'سنوات الخبرة التي تظهر في الصفحة الرئيسية.','hi'=>'होमपेज पर दिखाए जाने वाले अनुभव के वर्ष।','it'=>'Anni di esperienza mostrati in homepage.','zh'=>'首页显示的从业年数。','tr'=>'Ana sayfada gösterilen deneyim yılı.'],
            'about_title_help' => ['fa'=>'عنوان بخش «درباره ما» در صفحه اصلی.','en'=>'"About Us" section title on the homepage.','ar'=>'عنوان قسم "من نحن" في الصفحة الرئيسية.','hi'=>'होमपेज पर "हमारे बारे में" अनुभाग का शीर्षक।','it'=>'Titolo della sezione "Chi siamo" in homepage.','zh'=>'首页"关于我们"部分的标题。','tr'=>'Ana sayfadaki "Hakkımızda" bölümü başlığı.'],
            'about_desc_help' => ['fa'=>'متن توضیحی بخش «درباره ما».','en'=>'Descriptive text for the "About Us" section.','ar'=>'النص الوصفي لقسم "من نحن".','hi'=>'"हमारे बारे में" अनुभाग का विवरण पाठ।','it'=>'Testo descrittivo della sezione "Chi siamo".','zh'=>'"关于我们"部分的描述文字。','tr'=>'"Hakkımızda" bölümü açıklama metni.'],
            'about_feature_1_help' => ['fa'=>'ویژگی اول نمایش داده شده در بخش درباره ما.','en'=>'First feature shown in the About section.','ar'=>'الميزة الأولى المعروضة في قسم من نحن.','hi'=>'अबाउट सेक्शन में दिखाई गई पहली विशेषता।','it'=>'Prima caratteristica mostrata nella sezione About.','zh'=>'关于我们部分显示的第一项特色。','tr'=>'Hakkımızda bölümünde gösterilen ilk özellik.'],
            'about_feature_2_help' => ['fa'=>'ویژگی دوم نمایش داده شده در بخش درباره ما.','en'=>'Second feature shown in the About section.','ar'=>'الميزة الثانية المعروضة في قسم من نحن.','hi'=>'अबाउट सेक्शन में दिखाई गई दूसरी विशेषता।','it'=>'Seconda caratteristica mostrata nella sezione About.','zh'=>'关于我们部分显示的第二项特色。','tr'=>'Hakkımızda bölümünde gösterilen ikinci özellik.'],
            'about_feature_3_help' => ['fa'=>'ویژگی سوم نمایش داده شده در بخش درباره ما.','en'=>'Third feature shown in the About section.','ar'=>'الميزة الثالثة المعروضة في قسم من نحن.','hi'=>'अबाउट सेक्शन में दिखाई गई तीसरी विशेषता।','it'=>'Terza caratteristica mostrata nella sezione About.','zh'=>'关于我们部分显示的第三项特色。','tr'=>'Hakkımızda bölümünde gösterilen üçüncü özellik.'],
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
