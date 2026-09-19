<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * UI strings of the "About us" page template (headings, labels, closing call to
 * action). Additive and safe to re-run: uses updateOrInsert per locale/key.
 *
 * Run with: php artisan db:seed --class=AboutPageTranslationSeeder
 */
class AboutPageTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // key => [ locale => value ]  (all in the `messages` group)
        $entries = [
            'ab_cta_products' => [
                'fa' => 'مشاهده سنگ‌های ما', 'en' => 'Explore our stones', 'ar' => 'استكشف أحجارنا',
                'hi' => 'हमारे पत्थर देखें', 'it' => 'Scopri le nostre pietre', 'zh' => '浏览我们的石材', 'tr' => 'Taşlarımızı keşfedin',
            ],
            'ab_years_label' => [
                'fa' => 'سال تجربه میدانی', 'en' => 'years of hands-on experience', 'ar' => 'سنة من الخبرة الميدانية',
                'hi' => 'वर्षों का व्यावहारिक अनुभव', 'it' => 'anni di esperienza sul campo', 'zh' => '年实地经验', 'tr' => 'yıllık saha deneyimi',
            ],
            'ab_story_kicker' => [
                'fa' => 'داستان ما', 'en' => 'Our story', 'ar' => 'قصتنا',
                'hi' => 'हमारी कहानी', 'it' => 'La nostra storia', 'zh' => '我们的故事', 'tr' => 'Hikâyemiz',
            ],
            'ab_stat_products' => [
                'fa' => 'محصول آماده عرضه', 'en' => 'Products on offer', 'ar' => 'منتج معروض',
                'hi' => 'उपलब्ध उत्पाद', 'it' => 'Prodotti disponibili', 'zh' => '在售产品', 'tr' => 'Satışta ürün',
            ],
            'ab_stat_families' => [
                'fa' => 'خانواده سنگ', 'en' => 'Stone families', 'ar' => 'فئات الحجر',
                'hi' => 'पत्थर की श्रेणियाँ', 'it' => 'Famiglie di pietra', 'zh' => '石材系列', 'tr' => 'Taş ailesi',
            ],
            'ab_stat_offices' => [
                'fa' => 'دفتر و انبار', 'en' => 'Warehouse offices', 'ar' => 'مكاتب ومستودعات',
                'hi' => 'गोदाम कार्यालय', 'it' => 'Sedi e magazzini', 'zh' => '仓库办公室', 'tr' => 'Depo ofisi',
            ],
            'ab_founder_kicker' => [
                'fa' => 'آشنایی با مالک گروه', 'en' => 'Meet the owner', 'ar' => 'تعرّف على مالك المجموعة',
                'hi' => 'समूह के मालिक से मिलिए', 'it' => 'Il titolare', 'zh' => '认识集团所有者', 'tr' => 'Grubun sahiyle tanışın',
            ],
            'ab_why_kicker' => [
                'fa' => 'چرا EN؟', 'en' => 'Why EN', 'ar' => 'لماذا EN؟',
                'hi' => 'EN क्यों?', 'it' => 'Perché EN', 'zh' => '为什么选择 EN', 'tr' => 'Neden EN?',
            ],
            'ab_why_title' => [
                'fa' => 'آنچه ما را متمایز می‌کند', 'en' => 'What sets us apart', 'ar' => 'ما يميّزنا',
                'hi' => 'हमें क्या अलग बनाता है', 'it' => 'Ciò che ci distingue', 'zh' => '我们的与众不同之处', 'tr' => 'Bizi farklı kılan',
            ],
            'ab_stones_kicker' => [
                'fa' => 'سنگ‌های ما', 'en' => 'Our stones', 'ar' => 'أحجارنا',
                'hi' => 'हमारे पत्थर', 'it' => 'Le nostre pietre', 'zh' => '我们的石材', 'tr' => 'Taşlarımız',
            ],
            'ab_stones_title' => [
                'fa' => 'بلوک‌های واقعی، همان‌گونه که هستند', 'en' => 'Real blocks, photographed as they stand',
                'ar' => 'كتل حقيقية، كما هي على أرض الواقع', 'hi' => 'असली ब्लॉक, जैसे वे हैं',
                'it' => 'Blocchi reali, fotografati così come sono', 'zh' => '真实荒料，原貌呈现', 'tr' => 'Gerçek bloklar, olduğu gibi fotoğraflandı',
            ],
            'ab_stones_lead' => [
                'fa' => 'هر بلوک با ابعاد مشخص‌شده و مستقیم از انبار ما به نمایش گذاشته شده است.',
                'en' => 'Each block is shown with its marked dimensions, straight from our yard.',
                'ar' => 'تُعرض كل كتلة بأبعادها الموضّحة مباشرةً من ساحتنا.',
                'hi' => 'हर ब्लॉक को उसके अंकित आयामों के साथ सीधे हमारे यार्ड से दिखाया गया है।',
                'it' => 'Ogni blocco è mostrato con le dimensioni indicate, direttamente dal nostro piazzale.',
                'zh' => '每块荒料均标注尺寸，直接来自我们的堆场。',
                'tr' => 'Her blok, ölçüleri işaretli olarak doğrudan sahamızdan gösterilmektedir.',
            ],
            'ab_field_kicker' => [
                'fa' => 'در میدان', 'en' => 'In the field', 'ar' => 'في الميدان',
                'hi' => 'मैदान में', 'it' => 'Sul campo', 'zh' => '行业现场', 'tr' => 'Sahada',
            ],
            'ab_field_title' => [
                'fa' => 'شریکان تجاری‌مان را رودررو می‌بینیم', 'en' => 'We meet our partners face to face',
                'ar' => 'نلتقي شركاءنا وجهًا لوجه', 'hi' => 'हम अपने साझेदारों से आमने-सामने मिलते हैं',
                'it' => 'Incontriamo i nostri partner di persona', 'zh' => '我们与合作伙伴面对面交流', 'tr' => 'Ortaklarımızla yüz yüze görüşüyoruz',
            ],
            'ab_field_lead' => [
                'fa' => 'نمایشگاه‌ها جایی است که معدن، کارخانه و بازرگان با هم روبه‌رو می‌شوند. نگاهی به حضور ما در «:event».',
                'en' => 'Trade fairs are where quarries, factories and traders meet. A look at our presence at “:event”.',
                'ar' => 'المعارض هي المكان الذي يلتقي فيه أصحاب المحاجر والمصانع والتجار. لمحة عن حضورنا في «:event».',
                'hi' => 'व्यापार मेले वह जगह हैं जहाँ खदानें, कारखाने और व्यापारी मिलते हैं। “:event” में हमारी उपस्थिति की एक झलक।',
                'it' => 'Le fiere sono il luogo in cui cave, fabbriche e commercianti si incontrano. Uno sguardo alla nostra presenza a «:event».',
                'zh' => '展会是矿山、工厂与贸易商相聚之地。一览我们在“:event”的参展风采。',
                'tr' => 'Fuarlar; ocakların, fabrikaların ve tüccarların buluştuğu yerlerdir. “:event” fuarındaki katılımımıza bir bakış.',
            ],
            'ab_where_title' => [
                'fa' => 'ما را کجا پیدا کنید؟', 'en' => 'Where to find us', 'ar' => 'أين تجدوننا؟',
                'hi' => 'हमें कहाँ खोजें', 'it' => 'Dove trovarci', 'zh' => '我们在哪里', 'tr' => 'Bizi nerede bulabilirsiniz',
            ],
            'ab_hours' => [
                'fa' => 'ساعات کاری', 'en' => 'Working hours', 'ar' => 'ساعات العمل',
                'hi' => 'कार्य समय', 'it' => 'Orari di lavoro', 'zh' => '工作时间', 'tr' => 'Çalışma saatleri',
            ],
            'ab_call' => [
                'fa' => 'تماس تلفنی', 'en' => 'Call us', 'ar' => 'اتصل بنا',
                'hi' => 'हमें कॉल करें', 'it' => 'Chiamaci', 'zh' => '致电我们', 'tr' => 'Bizi arayın',
            ],
            'ab_email_us' => [
                'fa' => 'ایمیل', 'en' => 'Email', 'ar' => 'البريد الإلكتروني',
                'hi' => 'ईमेल', 'it' => 'Email', 'zh' => '电子邮件', 'tr' => 'E-posta',
            ],
            'ab_open_map' => [
                'fa' => 'مشاهده روی نقشه', 'en' => 'Open in Maps', 'ar' => 'افتح في الخرائط',
                'hi' => 'मानचित्र में खोलें', 'it' => 'Apri nelle mappe', 'zh' => '在地图中打开', 'tr' => 'Haritada aç',
            ],
            'ab_cta_title' => [
                'fa' => 'به دنبال بلوک مناسب پروژه خود هستید؟', 'en' => 'Looking for the right block for your project?',
                'ar' => 'هل تبحثون عن الكتلة المناسبة لمشروعكم؟', 'hi' => 'क्या आप अपनी परियोजना के लिए सही ब्लॉक ढूँढ रहे हैं?',
                'it' => 'Cercate il blocco giusto per il vostro progetto?', 'zh' => '正在为您的项目寻找合适的荒料？', 'tr' => 'Projeniz için doğru bloğu mu arıyorsunuz?',
            ],
            'ab_cta_text' => [
                'fa' => 'رنگ، ابعاد و تناژ مورد نیازتان را بگویید؛ ما بلوک‌های مناسب را به شما معرفی می‌کنیم.',
                'en' => 'Tell us what you need — colour, dimensions and tonnage — and we will get back to you with suitable blocks.',
                'ar' => 'أخبرونا بما تحتاجونه — اللون والأبعاد والكمية — وسنعود إليكم بالكتل المناسبة.',
                'hi' => 'हमें अपनी ज़रूरत बताइए — रंग, आयाम और टनेज — हम उपयुक्त ब्लॉकों के साथ आपसे संपर्क करेंगे।',
                'it' => 'Diteci di cosa avete bisogno — colore, dimensioni e tonnellaggio — vi risponderemo con i blocchi più adatti.',
                'zh' => '告诉我们您需要的颜色、规格和吨位，我们将为您推荐合适的荒料。',
                'tr' => 'İhtiyacınızı — renk, ebat ve tonaj — bize iletin; size uygun bloklarla dönüş yapalım.',
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
