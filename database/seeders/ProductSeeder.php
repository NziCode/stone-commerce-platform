<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\ProductAttribute;

class ProductSeeder extends Seeder
{
    /**
     * Base path where seeder source images are stored.
     * Copy the extracted contents of product-seeder-images.zip here:
     *   storage/app/seeders/products/{1..9}/main.*  and  gallery-*.*
     */
    private string $imagesBasePath = 'seeders/products';

    public function run(): void
    {
        // ── Resolve categories ──────────────────────────────────
        $silverCategory = Category::query()
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(slug, '$.en')) = ?", ['white-travertine'])
            ->first();

        $honeyCategory = Category::query()
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(slug, '$.en')) = ?", ['honey-travertine'])
            ->first();

        // ── Resolve attributes by key (from the 20-attribute seeder) ──
        $attr = Attribute::query()
            ->whereIn('key', [
                'color', 'surface_finish', 'length', 'width', 'thickness', 'weight',
                'block_volume', 'quarry_origin', 'country_of_origin', 'quality_grade',
                'pattern', 'water_absorption', 'compressive_strength', 'density',
                'frost_resistant', 'usage', 'usage_environment', 'slip_resistance',
                'edge_profile', 'packaging_type',
            ])
            ->get()
            ->keyBy('key');

        // ══════════════════════════════════════════════════════════
        //  PRODUCT DEFINITIONS
        // ══════════════════════════════════════════════════════════
        $products = [

            // ── Product 1 (images: 1,2,3,4.jpg) ─────────────────
            [
                'folder'   => '1',
                'main'     => 'main.jpg',
                'gallery'  => ['gallery-1.jpg', 'gallery-2.jpg', 'gallery-3.jpg'],
                'category' => $silverCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن سیلور',
                    'en' => 'Silver Travertine Block',
                    'ar' => 'كتلة ترافرتين فضي',
                    'hi' => 'सिल्वर ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Argento',
                ],
                'slug' => [
                    'fa' => 'travertan-silver-1',
                    'en' => 'silver-travertine-block-1',
                    'ar' => 'silver-travertine-block-1',
                    'hi' => 'silver-travertine-block-1',
                    'it' => 'silver-travertine-block-1',
                ],
                'short_description' => [
                    'fa' => 'مجموعه بلوک‌های تراورتن سیلور با رگه‌های طبیعی طوسی-کرم، آماده‌سازی شده در انبار جهت بارگیری.',
                    'en' => 'A lot of silver travertine blocks with natural grey-cream veining, prepared in the warehouse for loading.',
                    'ar' => 'مجموعة من كتل الترافرتين الفضي بعروق طبيعية رمادية-كريمية، مُجهزة في المستودع للشحن.',
                    'hi' => 'प्राकृतिक धूसर-क्रीम धारियों वाला सिल्वर ट्रैवर्टाइन ब्लॉक्स का एक लॉट, लोडिंग के लिए गोदाम में तैयार।',
                    'it' => 'Un lotto di blocchi di travertino argento con venature naturali grigio-crema, preparati nel magazzino per il carico.',
                ],
                'description' => [
                    'fa' => '<p>این مجموعه بلوک‌های تراورتن سیلور به‌صورت یکجا در محوطه انبار EN Trading Group نگهداری می‌شوند و آماده بازرسی نهایی و بارگیری هستند. رنگ غالب طوسی روشن با رگه‌های موجی تیره، الگوی کلاسیک تراورتن سیلور را به نمایش می‌گذارد. ساختار یکپارچه و عاری از شکستگی این بلوک‌ها، آن‌ها را برای برش به اسلب و تایل با کیفیت بالا مناسب کرده است.</p>',
                    'en' => '<p>This lot of silver travertine blocks is stored together at the EN Trading Group warehouse yard, ready for final inspection and loading. The dominant light grey color with dark wavy veining showcases the classic silver travertine pattern. The solid, crack-free structure of these blocks makes them suitable for cutting high-quality slabs and tiles.</p>',
                    'ar' => '<p>يتم تخزين هذه الدفعة من كتل الترافرتين الفضي معاً في ساحة مستودع EN Trading Group، جاهزة للفحص النهائي والشحن. اللون الرمادي الفاتح السائد مع العروق المتموجة الداكنة يُظهر النمط الكلاسيكي للترافرتين الفضي. البنية المتماسكة والخالية من الشقوق لهذه الكتل تجعلها مناسبة لتقطيع ألواح وبلاط عالي الجودة.</p>',
                    'hi' => '<p>सिल्वर ट्रैवर्टाइन ब्लॉक्स का यह लॉट EN Trading Group के गोदाम यार्ड में एक साथ संग्रहीत है, अंतिम निरीक्षण और लोडिंग के लिए तैयार। गहरी लहरदार धारियों के साथ प्रमुख हल्का धूसर रंग क्लासिक सिल्वर ट्रैवर्टाइन पैटर्न प्रदर्शित करता है। इन ब्लॉक्स की ठोस, दरार-मुक्त संरचना उन्हें उच्च-गुणवत्ता वाले स्लैब और टाइल काटने के लिए उपयुक्त बनाती है।</p>',
                    'it' => '<p>Questo lotto di blocchi di travertino argento è conservato insieme nel piazzale del magazzino di EN Trading Group, pronto per l\'ispezione finale e il carico. Il colore grigio chiaro dominante con venature scure ondulate mostra il motivo classico del travertino argento. La struttura solida e priva di crepe di questi blocchi li rende adatti per il taglio di lastre e piastrelle di alta qualità.</p>',
                ],
                'sku' => 'EN-TRV-SLV-LOT1',
                'price' => 24000000,
                'price_usd' => 480,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'available',
                'is_featured' => true,
                'is_active' => true,
                'is_new' => true,
                'sort_order' => 1,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن سیلور EN - مجموعه ۱ | EN Trading Group',
                    'en' => 'Buy EN Silver Travertine Block - Lot 1 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين فضي EN - الدفعة ١ | EN Trading Group',
                    'hi' => 'EN सिल्वर ट्रैवर्टाइन ब्लॉक खरीदें - लॉट 1 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Argento EN - Lotto 1 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن سیلور درجه یک، آماده بارگیری از انبار. استعلام قیمت از EN Trading Group.',
                    'en' => 'Premium grade silver travertine block, ready for loading from the warehouse. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين فضي من الدرجة الممتازة، جاهزة للشحن من المستودع. اطلب عرض سعر من EN Trading Group.',
                    'hi' => 'प्रीमियम ग्रेड सिल्वर ट्रैवर्टाइन ब्लॉक, गोदाम से लोडिंग के लिए तैयार। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Blocco di travertino argento di grado premium, pronto per il carico dal magazzino. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن سیلور, بلوک تراورتن, تراورتن طوسی, صادرات سنگ',
                    'en' => 'silver travertine, travertine block, grey travertine, stone export',
                    'ar' => 'ترافرتين فضي, كتلة ترافرتين, ترافرتين رمادي, تصدير الحجر',
                    'hi' => 'सिल्वर ट्रैवर्टाइन, ट्रैवर्टाइन ब्लॉक, धूसर ट्रैवर्टाइन, पत्थर निर्यात',
                    'it' => 'travertino argento, blocco travertino, travertino grigio, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'gray',
                    'surface_finish'        => 'natural_split',
                    'length'                => 320,
                    'width'                 => 190,
                    'thickness'             => 175,
                    'weight'                => 24500,
                    'block_volume'          => 10.64,
                    'quarry_origin'         => [
                        'fa' => 'معدن آتشکوه، محلات',
                        'en' => 'Atashkuh Mine, Mahallat',
                        'ar' => 'محجر آتشكوه، محلات',
                        'hi' => 'आतशकूह खदान, महल्लात',
                        'it' => 'Miniera di Atashkuh, Mahallat',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'premium',
                    'pattern'               => 'veined',
                    'water_absorption'      => 2.1,
                    'compressive_strength'  => 75,
                    'density'               => 2.4,
                    'frost_resistant'       => true,
                    'usage'                 => 'flooring',
                    'usage_environment'     => 'both',
                    'slip_resistance'       => 'medium',
                    'edge_profile'          => 'straight',
                    'packaging_type'        => 'loose',
                ],
            ],

            // ── Product 2 (images: 11,12.jpeg) ──────────────────
            [
                'folder'   => '2',
                'main'     => 'main.jpeg',
                'gallery'  => ['gallery-1.jpeg'],
                'category' => $silverCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن طوسی',
                    'en' => 'Grey Travertine Block',
                    'ar' => 'كتلة ترافرتين رمادي',
                    'hi' => 'ग्रे ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Grigio',
                ],
                'slug' => [
                    'fa' => 'travertan-tosi-2',
                    'en' => 'grey-travertine-block-2',
                    'ar' => 'grey-travertine-block-2',
                    'hi' => 'grey-travertine-block-2',
                    'it' => 'grey-travertine-block-2',
                ],
                'short_description' => [
                    'fa' => 'بلوک تراورتن طوسی با علامت تجاری EN1 و ابعاد ثبت‌شده روی بلوک، آماده حمل با جرثقیل.',
                    'en' => 'Grey travertine block marked "EN1" with recorded dimensions on the surface, ready for crane loading.',
                    'ar' => 'كتلة ترافرتين رمادي مُعلَّمة بـ "EN1" وبأبعاد مُسجلة على السطح، جاهزة للرفع بالرافعة.',
                    'hi' => '"EN1" चिह्नित और सतह पर दर्ज आयामों वाला ग्रे ट्रैवर्टाइन ब्लॉक, क्रेन लोडिंग के लिए तैयार।',
                    'it' => 'Blocco di travertino grigio contrassegnato "EN1" con dimensioni registrate sulla superficie, pronto per il carico con la grua.',
                ],
                'description' => [
                    'fa' => '<p>این بلوک با علامت تجاری "EN1" و ابعاد ثبت‌شده با رنگ قرمز روی سطح آن، در فضای باز کارگاه برش قرار دارد و توسط جرثقیل برای انتقال آماده می‌شود. رنگ طوسی متمایل به قهوه‌ای با رگه‌های افقی، تنوع رنگی این بلوک را به نمایش می‌گذارد. این محصول گزینه مناسبی برای پروژه‌هایی است که به ترکیب رنگ طوسی-قهوه‌ای نیاز دارند.</p>',
                    'en' => '<p>Marked with the trade name "EN1" and dimensions recorded in red on its surface, this block sits in the open yard of the cutting workshop, being prepared for crane transport. Its grey-to-brown tone with horizontal veining showcases this block\'s color variation. This product is a suitable option for projects requiring a grey-brown color combination.</p>',
                    'ar' => '<p>مُعلَّمة بالاسم التجاري "EN1" وبأبعاد مُسجلة بالأحمر على سطحها، تقع هذه الكتلة في الساحة المفتوحة لورشة التقطيع، ويتم تجهيزها للنقل بالرافعة. لونها الرمادي المائل إلى البني مع العروق الأفقية يُظهر تنوع اللون في هذه الكتلة. هذا المنتج خيار مناسب للمشاريع التي تتطلب مزيجاً من اللون الرمادي والبني.</p>',
                    'hi' => '<p>व्यापारिक नाम "EN1" से चिह्नित और सतह पर लाल रंग में दर्ज आयामों के साथ, यह ब्लॉक कटिंग वर्कशॉप के खुले यार्ड में स्थित है, क्रेन परिवहन के लिए तैयार किया जा रहा है। इसका धूसर-से-भूरा रंग क्षैतिज धारियों के साथ इस ब्लॉक की रंग विविधता प्रदर्शित करता है। यह उत्पाद उन प्रोजेक्ट्स के लिए एक उपयुक्त विकल्प है जिन्हें धूसर-भूरे रंग के संयोजन की आवश्यकता होती है।</p>',
                    'it' => '<p>Contrassegnato con il nome commerciale "EN1" e dimensioni registrate in rosso sulla sua superficie, questo blocco si trova nel piazzale aperto dell\'officina di taglio, in preparazione per il trasporto con la grua. La sua tonalità che va dal grigio al marrone con venature orizzontali mostra la variazione cromatica di questo blocco. Questo prodotto è un\'opzione adatta per progetti che richiedono una combinazione di colori grigio-marrone.</p>',
                ],
                'sku' => 'EN-TRV-GRY-002',
                'price' => 22750000,
                'price_usd' => 455,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'available',
                'is_featured' => true,
                'is_active' => true,
                'is_new' => false,
                'sort_order' => 2,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن طوسی EN1 - کد ۲ | EN Trading Group',
                    'en' => 'Buy EN1 Grey Travertine Block - Code 2 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين رمادي EN1 - كود ٢ | EN Trading Group',
                    'hi' => 'EN1 ग्रे ट्रैवर्टाइन ब्लॉक खरीदें - कोड 2 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Grigio EN1 - Codice 2 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن طوسی-قهوه‌ای با علامت EN1، آماده حمل با جرثقیل. استعلام قیمت از EN Trading Group.',
                    'en' => 'Grey-brown travertine block marked EN1, ready for crane transport. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين رمادي-بني مُعلَّمة EN1، جاهزة للنقل بالرافعة. اطلب عرض سعر من EN Trading Group.',
                    'hi' => 'EN1 चिह्नित धूसर-भूरा ट्रैवर्टाइन ब्लॉक, क्रेन परिवहन के लिए तैयार। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Blocco di travertino grigio-marrone contrassegnato EN1, pronto per il trasporto con la grua. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن طوسی, بلوک EN1, تراورتن قهوه‌ای, صادرات سنگ',
                    'en' => 'grey travertine, EN1 block, brown travertine, stone export',
                    'ar' => 'ترافرتين رمادي, كتلة EN1, ترافرتين بني, تصدير الحجر',
                    'hi' => 'ग्रे ट्रैवर्टाइन, EN1 ब्लॉक, भूरा ट्रैवर्टाइन, पत्थर निर्यात',
                    'it' => 'travertino grigio, blocco EN1, travertino marrone, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'gray',
                    'surface_finish'        => 'natural_split',
                    'length'                => 300,
                    'width'                 => 170,
                    'thickness'             => 198,
                    'weight'                => 21500,
                    'block_volume'          => 10.10,
                    'quarry_origin'         => [
                        'fa' => 'معدن هرسین',
                        'en' => 'Harsin Mine',
                        'ar' => 'محجر هرسين',
                        'hi' => 'हरसिन खदान',
                        'it' => 'Miniera di Harsin',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'standard',
                    'pattern'               => 'veined',
                    'water_absorption'      => 2.4,
                    'compressive_strength'  => 70,
                    'density'               => 2.37,
                    'frost_resistant'       => true,
                    'usage'                 => 'wall_cladding',
                    'usage_environment'     => 'both',
                    'slip_resistance'       => 'medium',
                    'edge_profile'          => 'straight',
                    'packaging_type'        => 'loose',
                ],
            ],

            // ── Product 3 (images: 21,22,23,24.jpeg) ────────────
            [
                'folder'   => '3',
                'main'     => 'main.jpeg',
                'gallery'  => ['gallery-1.jpeg', 'gallery-2.jpeg', 'gallery-3.jpeg'],
                'category' => $silverCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن سیلور',
                    'en' => 'Silver Travertine Block',
                    'ar' => 'كتلة ترافرتين فضي',
                    'hi' => 'सिल्वर ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Argento',
                ],
                'slug' => [
                    'fa' => 'travertan-silver-3',
                    'en' => 'silver-travertine-block-3',
                    'ar' => 'silver-travertine-block-3',
                    'hi' => 'silver-travertine-block-3',
                    'it' => 'silver-travertine-block-3',
                ],
                'short_description' => [
                    'fa' => 'بلوک تراورتن سیلور با ابعاد بزرگ ۳۲۰×۱۹۰×۲۰۰ و رگه‌های موجی پررنگ، در محوطه معدن.',
                    'en' => 'Large silver travertine block sized 320x190x200 cm with bold wavy veining, located at the quarry site.',
                    'ar' => 'كتلة ترافرتين فضي كبيرة بمقاس 320×190×200 سم بعروق متموجة بارزة، في موقع المحجر.',
                    'hi' => 'घनी लहरदार धारियों वाला 320x190x200 सेमी आकार का बड़ा सिल्वर ट्रैवर्टाइन ब्लॉक, खदान स्थल पर स्थित।',
                    'it' => 'Grande blocco di travertino argento di dimensioni 320x190x200 cm con marcate venature ondulate, situato presso il sito della cava.',
                ],
                'description' => [
                    'fa' => '<p>این بلوک بزرگ با علامت "EN/8" و ابعاد دقیق ۳۲۰×۱۹۰×۲۰۰ سانتی‌متر، مستقیماً از معدن استخراج شده و در کنار سایر بلوک‌های خام در محوطه معدن قرار دارد. رگه‌های موجی پررنگ و عمیق روی زمینه سفید-طوسی، الگوی بصری منحصربه‌فردی به این بلوک می‌بخشد که پس از برش، برای پروژه‌های دکوراتیو با طراحی خاص بسیار مناسب است.</p>',
                    'en' => '<p>This large block, marked "EN/8" with precise dimensions of 320x190x200 cm, has been extracted directly from the quarry and sits alongside other raw blocks in the quarry yard. Its bold, deep wavy veining over a white-grey background gives this block a unique visual pattern that, once cut, is highly suitable for decorative projects with a distinctive design.</p>',
                    'ar' => '<p>هذه الكتلة الكبيرة، المُعلَّمة بـ "EN/8" وبأبعاد دقيقة 320×190×200 سم، تم استخراجها مباشرة من المحجر وتقع بجانب كتل خام أخرى في ساحة المحجر. عروقها المتموجة العميقة والبارزة على خلفية بيضاء-رمادية تعطي هذه الكتلة نمطاً بصرياً فريداً يكون، بعد التقطيع، مناسباً جداً للمشاريع الزخرفية ذات التصميم المميز.</p>',
                    'hi' => '<p>"EN/8" से चिह्नित और 320x190x200 सेमी के सटीक आयामों वाला यह बड़ा ब्लॉक सीधे खदान से निकाला गया है और खदान यार्ड में अन्य कच्चे ब्लॉक्स के साथ स्थित है। सफेद-धूसर पृष्ठभूमि पर इसकी गहरी, बोल्ड लहरदार धारियाँ इस ब्लॉक को एक अनूठा विज़ुअल पैटर्न देती हैं जो काटने के बाद विशिष्ट डिज़ाइन वाले सजावटी प्रोजेक्ट्स के लिए बहुत उपयुक्त है।</p>',
                    'it' => '<p>Questo grande blocco, contrassegnato "EN/8" con dimensioni precise di 320x190x200 cm, è stato estratto direttamente dalla cava e si trova insieme ad altri blocchi grezzi nel piazzale della cava. Le sue venature ondulate marcate e profonde su uno sfondo bianco-grigio conferiscono a questo blocco un motivo visivo unico che, una volta tagliato, è molto adatto per progetti decorativi con un design distintivo.</p>',
                ],
                'sku' => 'EN-TRV-SLV-003',
                'price' => 30500000,
                'price_usd' => 610,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'available',
                'is_featured' => true,
                'is_active' => true,
                'is_new' => false,
                'sort_order' => 3,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن سیلور EN/8 - کد ۳ | EN Trading Group',
                    'en' => 'Buy EN/8 Silver Travertine Block - Code 3 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين فضي EN/8 - كود ٣ | EN Trading Group',
                    'hi' => 'EN/8 सिल्वर ट्रैवर्टाइन ब्लॉक खरीदें - कोड 3 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Argento EN/8 - Codice 3 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن سیلور بزرگ با ابعاد ۳۲۰×۱۹۰×۲۰۰، رگه‌های پررنگ. استعلام قیمت از EN Trading Group.',
                    'en' => 'Large silver travertine block sized 320x190x200 with bold veining. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين فضي كبيرة بمقاس 320×190×200 بعروق بارزة. اطلب عرض سعر من EN Trading Group.',
                    'hi' => 'बोल्ड धारियों के साथ 320x190x200 आकार का बड़ा सिल्वर ट्रैवर्टाइन ब्लॉक। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Grande blocco di travertino argento di dimensioni 320x190x200 con venature marcate. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن سیلور, بلوک EN8, رگه پررنگ, صادرات سنگ',
                    'en' => 'silver travertine, EN8 block, bold veining, stone export',
                    'ar' => 'ترافرتين فضي, كتلة EN8, عروق بارزة, تصدير الحجر',
                    'hi' => 'सिल्वर ट्रैवर्टाइन, EN8 ब्लॉक, बोल्ड वेनिंग, पत्थर निर्यात',
                    'it' => 'travertino argento, blocco EN8, venature marcate, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'gray',
                    'surface_finish'        => 'natural_split',
                    'length'                => 320,
                    'width'                 => 190,
                    'thickness'             => 200,
                    'weight'                => 28600,
                    'block_volume'          => 12.16,
                    'quarry_origin'         => [
                        'fa' => 'معدن آتشکوه، محلات',
                        'en' => 'Atashkuh Mine, Mahallat',
                        'ar' => 'محجر آتشكوه، محلات',
                        'hi' => 'आतशकूह खदान, महल्लात',
                        'it' => 'Miniera di Atashkuh, Mahallat',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'premium',
                    'pattern'               => 'veined',
                    'water_absorption'      => 2.0,
                    'compressive_strength'  => 77,
                    'density'               => 2.41,
                    'frost_resistant'       => true,
                    'usage'                 => 'facade',
                    'usage_environment'     => 'both',
                    'slip_resistance'       => 'medium',
                    'edge_profile'          => 'straight',
                    'packaging_type'        => 'loose',
                ],
            ],

            // ── Product 4 (image: 31.jpeg, single) ──────────────
            [
                'folder'   => '4',
                'main'     => 'main.jpeg',
                'gallery'  => [],
                'category' => $silverCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن طوسی',
                    'en' => 'Grey Travertine Block',
                    'ar' => 'كتلة ترافرتين رمادي',
                    'hi' => 'ग्रे ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Grigio',
                ],
                'slug' => [
                    'fa' => 'travertan-tosi-4',
                    'en' => 'grey-travertine-block-4',
                    'ar' => 'grey-travertine-block-4',
                    'hi' => 'grey-travertine-block-4',
                    'it' => 'grey-travertine-block-4',
                ],
                'short_description' => [
                    'fa' => 'بلوک تراورتن با تن رنگی طوسی-بنفش کمیاب، تازه استخراج شده در محوطه معدن.',
                    'en' => 'Travertine block with a rare grey-purple color tone, freshly extracted at the quarry site.',
                    'ar' => 'كتلة ترافرتين بدرجة لون رمادي-بنفسجي نادرة، مستخرجة حديثاً في موقع المحجر.',
                    'hi' => 'दुर्लभ ग्रे-पर्पल रंग टोन वाला ट्रैवर्टाइन ब्लॉक, खदान स्थल पर ताज़ा निकाला गया।',
                    'it' => 'Blocco di travertino con una rara tonalità grigio-viola, appena estratto sul sito della cava.',
                ],
                'description' => [
                    'fa' => '<p>این بلوک با تن رنگی طوسی متمایل به بنفش که در میان محصولات تراورتن کمتر دیده می‌شود، به‌تازگی از دیواره معدن جدا شده و با علامت آبی "EN" نشانه‌گذاری شده است. رگه‌های افقی متراکم و یکدست، این بلوک را برای کاربردهایی که به ظاهری متمایز و خاص نیاز دارند، گزینه‌ای جذاب می‌سازد. این رنگ کمیاب می‌تواند برای پروژه‌های نمایشی و نمونه‌کاری مورد استفاده قرار گیرد.</p>',
                    'en' => '<p>This block, with its grey-to-purple tone rarely seen among travertine products, has just been separated from the quarry face and marked with a blue "EN" symbol. Its dense, uniform horizontal veining makes this block an attractive option for applications requiring a distinctive, special appearance. This rare color can be used for showcase projects and sample work.</p>',
                    'ar' => '<p>هذه الكتلة، بدرجة لونها الرمادي المائل إلى البنفسجي والتي يندر رؤيتها بين منتجات الترافرتين، تم فصلها للتو عن واجهة المحجر وتم تعليمها برمز "EN" أزرق. عروقها الأفقية الكثيفة والمتجانسة تجعل هذه الكتلة خياراً جذاباً للتطبيقات التي تتطلب مظهراً مميزاً وخاصاً. يمكن استخدام هذا اللون النادر لمشاريع العرض وأعمال النماذج.</p>',
                    'hi' => '<p>यह ब्लॉक, ट्रैवर्टाइन उत्पादों में दुर्लभ रूप से देखे जाने वाले अपने ग्रे-से-पर्पल टोन के साथ, खदान की दीवार से अलग किया गया है और नीले "EN" प्रतीक से चिह्नित है। इसकी घनी, समान क्षैतिज धारियाँ इस ब्लॉक को विशिष्ट, खास दिखावट की आवश्यकता वाले एप्लिकेशन के लिए एक आकर्षक विकल्प बनाती हैं। इस दुर्लभ रंग का उपयोग शोकेस प्रोजेक्ट्स और सैंपल कार्य के लिए किया जा सकता है।</p>',
                    'it' => '<p>Questo blocco, con la sua tonalità che va dal grigio al viola raramente vista tra i prodotti di travertino, è stato appena separato dalla parete della cava e contrassegnato con un simbolo blu "EN". Le sue venature orizzontali dense e uniformi rendono questo blocco un\'opzione attraente per applicazioni che richiedono un aspetto distintivo e speciale. Questo colore raro può essere utilizzato per progetti dimostrativi e lavori campione.</p>',
                ],
                'sku' => 'EN-TRV-GRP-004',
                'price' => 21500000,
                'price_usd' => 430,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'available',
                'is_featured' => false,
                'is_active' => true,
                'is_new' => true,
                'sort_order' => 4,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن طوسی-بنفش EN - کد ۴ | EN Trading Group',
                    'en' => 'Buy EN Grey-Purple Travertine Block - Code 4 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين رمادي-بنفسجي EN - كود ٤ | EN Trading Group',
                    'hi' => 'EN ग्रे-पर्पल ट्रैवर्टाइन ब्लॉक खरीदें - कोड 4 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Grigio-Viola EN - Codice 4 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن با رنگ کمیاب طوسی-بنفش، تازه از معدن. استعلام قیمت از EN Trading Group.',
                    'en' => 'Travertine block with a rare grey-purple color, fresh from the quarry. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين بلون رمادي-بنفسجي نادر، طازجة من المحجر. اطلب عرض سعر من EN Trading Group.',
                    'hi' => 'दुर्लभ ग्रे-पर्पल रंग वाला ट्रैवर्टाइन ब्लॉक, खदान से ताज़ा। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Blocco di travertino con un raro colore grigio-viola, fresco dalla cava. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن بنفش, تراورتن کمیاب, بلوک معدن, صادرات سنگ',
                    'en' => 'purple travertine, rare travertine, quarry block, stone export',
                    'ar' => 'ترافرتين بنفسجي, ترافرتين نادر, كتلة محجر, تصدير الحجر',
                    'hi' => 'पर्पल ट्रैवर्टाइन, दुर्लभ ट्रैवर्टाइन, खदान ब्लॉक, पत्थर निर्यात',
                    'it' => 'travertino viola, travertino raro, blocco di cava, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'gray',
                    'surface_finish'        => 'natural_split',
                    'length'                => 290,
                    'width'                 => 180,
                    'thickness'             => 170,
                    'weight'                => 19400,
                    'block_volume'          => 8.88,
                    'quarry_origin'         => [
                        'fa' => 'معدن آتشکوه، محلات',
                        'en' => 'Atashkuh Mine, Mahallat',
                        'ar' => 'محجر آتشكوه، محلات',
                        'hi' => 'आतशकूह खदान, महल्लात',
                        'it' => 'Miniera di Atashkuh, Mahallat',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'standard',
                    'pattern'               => 'veined',
                    'water_absorption'      => 2.3,
                    'compressive_strength'  => 71,
                    'density'               => 2.38,
                    'frost_resistant'       => true,
                    'usage'                 => 'wall_cladding',
                    'usage_environment'     => 'indoor',
                    'slip_resistance'       => 'medium',
                    'edge_profile'          => 'straight',
                    'packaging_type'        => 'loose',
                ],
            ],

            // ── Product 5 (image: 41.jpeg, single) ──────────────
            [
                'folder'   => '5',
                'main'     => 'main.jpeg',
                'gallery'  => [],
                'category' => $silverCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن کرم',
                    'en' => 'Cream Travertine Block',
                    'ar' => 'كتلة ترافرتين كريمي',
                    'hi' => 'क्रीम ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Crema',
                ],
                'slug' => [
                    'fa' => 'travertan-kerem-5',
                    'en' => 'cream-travertine-block-5',
                    'ar' => 'cream-travertine-block-5',
                    'hi' => 'cream-travertine-block-5',
                    'it' => 'cream-travertine-block-5',
                ],
                'short_description' => [
                    'fa' => 'بلوک تراورتن کرم با سطح برش‌خورده صاف و رگه‌های ریز، با علامت EN3 مشخص شده.',
                    'en' => 'Cream travertine block with a smooth cut surface and fine veining, marked with EN3.',
                    'ar' => 'كتلة ترافرتين كريمي بسطح مقطوع ناعم وعروق دقيقة، مُعلَّمة بـ EN3.',
                    'hi' => 'चिकनी कटी सतह और बारीक धारियों वाला क्रीम ट्रैवर्टाइन ब्लॉक, EN3 से चिह्नित।',
                    'it' => 'Blocco di travertino crema con superficie di taglio liscia e venature fini, contrassegnato con EN3.',
                ],
                'description' => [
                    'fa' => '<p>این بلوک تراورتن با رنگ کرم روشن و سطح برش‌خورده صاف، نمونه‌ای از کیفیت یکنواخت محصولات معدنی است. علامت قرمز "EN3" روی سطح بلوک برای ردیابی و شناسایی محموله ثبت شده است. رگه‌های ریز و منظم این بلوک، آن را برای تولید کاشی‌های با ظاهر مدرن و مینیمال بسیار مناسب می‌سازد و گزینه‌ای عالی برای پروژه‌های با تقاضای رنگ روشن و یکدست است.</p>',
                    'en' => '<p>This travertine block, with its light cream color and smooth cut surface, exemplifies the consistent quality of the quarry\'s products. The red "EN3" mark on the block surface is recorded for shipment tracking and identification. Its fine, regular veining makes this block highly suitable for producing tiles with a modern, minimal look, and an excellent option for projects requiring a light, uniform color.</p>',
                    'ar' => '<p>تُمثل هذه الكتلة من الترافرتين، بلونها الكريمي الفاتح وسطحها المقطوع الناعم، الجودة المتسقة لمنتجات المحجر. تم تسجيل علامة "EN3" الحمراء على سطح الكتلة لتتبع وتحديد الشحنة. عروقها الدقيقة والمنتظمة تجعل هذه الكتلة مناسبة جداً لإنتاج بلاط بمظهر عصري ومينيمال، وهي خيار ممتاز للمشاريع التي تتطلب لوناً فاتحاً ومتجانساً.</p>',
                    'hi' => '<p>यह ट्रैवर्टाइन ब्लॉक, अपने हल्के क्रीम रंग और चिकनी कटी सतह के साथ, खदान के उत्पादों की सुसंगत गुणवत्ता का उदाहरण है। ब्लॉक की सतह पर लाल "EN3" निशान शिपमेंट ट्रैकिंग और पहचान के लिए दर्ज किया गया है। इसकी बारीक, नियमित धारियाँ इस ब्लॉक को आधुनिक, मिनिमल लुक वाली टाइलें बनाने के लिए अत्यंत उपयुक्त बनाती हैं, और हल्के, समान रंग की आवश्यकता वाले प्रोजेक्ट्स के लिए एक उत्कृष्ट विकल्प है।</p>',
                    'it' => '<p>Questo blocco di travertino, con il suo colore crema chiaro e la superficie di taglio liscia, esemplifica la qualità costante dei prodotti della cava. Il segno rosso "EN3" sulla superficie del blocco è registrato per il tracciamento e l\'identificazione della spedizione. Le sue venature fini e regolari rendono questo blocco molto adatto per la produzione di piastrelle con un aspetto moderno e minimale, ed è un\'ottima opzione per progetti che richiedono un colore chiaro e uniforme.</p>',
                ],
                'sku' => 'EN-TRV-CRM-005',
                'price' => 23250000,
                'price_usd' => 465,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'available',
                'is_featured' => false,
                'is_active' => true,
                'is_new' => false,
                'sort_order' => 5,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن کرم EN3 - کد ۵ | EN Trading Group',
                    'en' => 'Buy EN3 Cream Travertine Block - Code 5 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين كريمي EN3 - كود ٥ | EN Trading Group',
                    'hi' => 'EN3 क्रीम ट्रैवर्टाइन ब्लॉक खरीदें - कोड 5 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Crema EN3 - Codice 5 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن کرم با رگه‌های ریز و رنگ یکدست، مناسب کاشی مدرن. استعلام قیمت از EN Trading Group.',
                    'en' => 'Cream travertine block with fine veining and uniform color, suitable for modern tiles. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين كريمي بعروق دقيقة ولون متجانس، مناسبة للبلاط العصري. اطلب عرض سعر من EN Trading Group.',
                    'hi' => 'बारीक धारियों और समान रंग वाला क्रीम ट्रैवर्टाइन ब्लॉक, आधुनिक टाइल्स के लिए उपयुक्त। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Blocco di travertino crema con venature fini e colore uniforme, adatto per piastrelle moderne. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن کرم, بلوک EN3, رگه ریز, صادرات سنگ',
                    'en' => 'cream travertine, EN3 block, fine veining, stone export',
                    'ar' => 'ترافرتين كريمي, كتلة EN3, عروق دقيقة, تصدير الحجر',
                    'hi' => 'क्रीम ट्रैवर्टाइन, EN3 ब्लॉक, बारीक धारियाँ, पत्थर निर्यात',
                    'it' => 'travertino crema, blocco EN3, venature fini, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'cream',
                    'surface_finish'        => 'honed',
                    'length'                => 280,
                    'width'                 => 170,
                    'thickness'             => 165,
                    'weight'                => 17800,
                    'block_volume'          => 7.85,
                    'quarry_origin'         => [
                        'fa' => 'معدن محلات',
                        'en' => 'Mahallat Mine',
                        'ar' => 'محجر محلات',
                        'hi' => 'महल्लात खदान',
                        'it' => 'Miniera di Mahallat',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'premium',
                    'pattern'               => 'uniform',
                    'water_absorption'      => 1.7,
                    'compressive_strength'  => 82,
                    'density'               => 2.46,
                    'frost_resistant'       => true,
                    'usage'                 => 'flooring',
                    'usage_environment'     => 'indoor',
                    'slip_resistance'       => 'medium',
                    'edge_profile'          => 'straight',
                    'packaging_type'        => 'loose',
                ],
            ],

            // ── Product 6 (images: 51,52,53,54.jpg) ─────────────
            [
                'folder'   => '6',
                'main'     => 'main.jpg',
                'gallery'  => ['gallery-1.jpg', 'gallery-2.jpg', 'gallery-3.jpg'],
                'category' => $honeyCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن عسلی',
                    'en' => 'Honey Travertine Block',
                    'ar' => 'كتلة ترافرتين عسلي',
                    'hi' => 'हनी ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Miele',
                ],
                'slug' => [
                    'fa' => 'travertan-asali-6',
                    'en' => 'honey-travertine-block-6',
                    'ar' => 'honey-travertine-block-6',
                    'hi' => 'honey-travertine-block-6',
                    'it' => 'honey-travertine-block-6',
                ],
                'short_description' => [
                    'fa' => 'مجموعه بلوک‌های تراورتن عسلی با رنگ قهوه‌ای گرم، تازه استخراج شده در محوطه باز معدن.',
                    'en' => 'A lot of honey travertine blocks with a warm brown color, freshly extracted in the open quarry yard.',
                    'ar' => 'مجموعة من كتل الترافرتين العسلي بلون بني دافئ، مستخرجة حديثاً في ساحة المحجر المفتوحة.',
                    'hi' => 'गर्म भूरे रंग के साथ हनी ट्रैवर्टाइन ब्लॉक्स का एक लॉट, खुले खदान यार्ड में ताज़ा निकाला गया।',
                    'it' => 'Un lotto di blocchi di travertino miele con un colore marrone caldo, appena estratti nel piazzale aperto della cava.',
                ],
                'description' => [
                    'fa' => '<p>این مجموعه بلوک‌های تراورتن عسلی به‌تازگی از معدن استخراج شده و در محوطه باز برای بازرسی نهایی و آماده‌سازی برای حمل قرار گرفته‌اند. رنگ قهوه‌ای گرم با رگه‌های موجی، نمونه‌ای کلاسیک از تراورتن‌های معروف منطقه عباس‌آباد است. این بلوک‌ها برای دیوارپوش‌های دکوراتیو و فضاهای داخلی لوکس بسیار محبوب هستند.</p>',
                    'en' => '<p>This lot of honey travertine blocks has just been extracted from the quarry and placed in the open yard for final inspection and shipping preparation. The warm brown color with wavy veining is a classic example of the renowned travertine from the Abbasabad region. These blocks are highly popular for decorative wall cladding and luxury interior spaces.</p>',
                    'ar' => '<p>تم استخراج هذه الدفعة من كتل الترافرتين العسلي للتو من المحجر ووضعها في الساحة المفتوحة للفحص النهائي والتحضير للشحن. اللون البني الدافئ بعروق متموجة هو مثال كلاسيكي للترافرتين المشهور من منطقة عباس آباد. تُعد هذه الكتل شائعة جداً لتكسية الجدران الزخرفية والمساحات الداخلية الفاخرة.</p>',
                    'hi' => '<p>हनी ट्रैवर्टाइन ब्लॉक्स के इस लॉट को खदान से ताज़ा निकाला गया है और अंतिम निरीक्षण और शिपिंग तैयारी के लिए खुले यार्ड में रखा गया है। लहरदार धारियों के साथ गर्म भूरा रंग अब्बासाबाद क्षेत्र के प्रसिद्ध ट्रैवर्टाइन का एक क्लासिक उदाहरण है। ये ब्लॉक सजावटी वॉल क्लैडिंग और लक्जरी इंटीरियर स्पेस के लिए बहुत लोकप्रिय हैं।</p>',
                    'it' => '<p>Questo lotto di blocchi di travertino miele è stato appena estratto dalla cava e collocato nel piazzale aperto per l\'ispezione finale e la preparazione alla spedizione. Il colore marrone caldo con venature ondulate è un esempio classico del rinomato travertino della regione di Abbasabad. Questi blocchi sono molto popolari per rivestimenti murali decorativi e spazi interni di lusso.</p>',
                ],
                'sku' => 'EN-TRV-HNY-LOT6',
                'price' => 27000000,
                'price_usd' => 540,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'available',
                'is_featured' => true,
                'is_active' => true,
                'is_new' => true,
                'sort_order' => 6,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن عسلی EN - مجموعه ۶ | EN Trading Group',
                    'en' => 'Buy EN Honey Travertine Block - Lot 6 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين عسلي EN - الدفعة ٦ | EN Trading Group',
                    'hi' => 'EN हनी ट्रैवर्टाइन ब्लॉक खरीदें - लॉट 6 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Miele EN - Lotto 6 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن عسلی با رنگ گرم قهوه‌ای، تازه از معدن. مناسب دکوراسیون لوکس. استعلام قیمت از EN Trading Group.',
                    'en' => 'Honey travertine block with warm brown color, fresh from the quarry. Suitable for luxury decor. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين عسلي بلون بني دافئ، طازجة من المحجر. مناسبة للديكور الفاخر. اطلب عرض سعر من EN Trading Group.',
                    'hi' => 'गर्म भूरे रंग वाला हनी ट्रैवर्टाइन ब्लॉक, खदान से ताज़ा। लक्जरी डेकोर के लिए उपयुक्त। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Blocco di travertino miele con colore marrone caldo, fresco dalla cava. Adatto per arredamento di lusso. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن عسلی, بلوک قهوه‌ای, دیوارپوش لوکس, صادرات سنگ',
                    'en' => 'honey travertine, brown block, luxury wall cladding, stone export',
                    'ar' => 'ترافرتين عسلي, كتلة بنية, تكسية جدران فاخرة, تصدير الحجر',
                    'hi' => 'हनी ट्रैवर्टाइन, भूरा ब्लॉक, लक्जरी वॉल क्लैडिंग, पत्थर निर्यात',
                    'it' => 'travertino miele, blocco marrone, rivestimento murale di lusso, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'brown',
                    'surface_finish'        => 'natural_split',
                    'length'                => 280,
                    'width'                 => 175,
                    'thickness'             => 160,
                    'weight'                => 18800,
                    'block_volume'          => 7.84,
                    'quarry_origin'         => [
                        'fa' => 'معدن عباس‌آباد',
                        'en' => 'Abbasabad Mine',
                        'ar' => 'محجر عباس آباد',
                        'hi' => 'अब्बासाबाद खदान',
                        'it' => 'Miniera di Abbasabad',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'premium',
                    'pattern'               => 'veined',
                    'water_absorption'      => 2.5,
                    'compressive_strength'  => 68,
                    'density'               => 2.35,
                    'frost_resistant'       => false,
                    'usage'                 => 'wall_cladding',
                    'usage_environment'     => 'indoor',
                    'slip_resistance'       => 'low',
                    'edge_profile'          => 'straight',
                    'packaging_type'        => 'loose',
                ],
            ],

            // ── Product 7 (images: 61,62,63.jpeg) ───────────────
            [
                'folder'   => '7',
                'main'     => 'main.jpeg',
                'gallery'  => ['gallery-1.jpeg', 'gallery-2.jpeg'],
                'category' => $honeyCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن عسلی',
                    'en' => 'Honey Travertine Block',
                    'ar' => 'كتلة ترافرتين عسلي',
                    'hi' => 'हनी ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Miele',
                ],
                'slug' => [
                    'fa' => 'travertan-asali-7',
                    'en' => 'honey-travertine-block-7',
                    'ar' => 'honey-travertine-block-7',
                    'hi' => 'honey-travertine-block-7',
                    'it' => 'honey-travertine-block-7',
                ],
                'short_description' => [
                    'fa' => 'بلوک تراورتن عسلی با ابعاد ثبت‌شده ۳۱۰×۱۷۵×۱۹۰ سانتی‌متر، آماده برای حمل با جرثقیل.',
                    'en' => 'Honey travertine block with recorded dimensions of 310x175x190 cm, ready for crane transport.',
                    'ar' => 'كتلة ترافرتين عسلي بأبعاد مسجلة 310×175×190 سم، جاهزة للنقل بالرافعة.',
                    'hi' => '310x175x190 सेमी के दर्ज आयामों वाला हनी ट्रैवर्टाइन ब्लॉक, क्रेन परिवहन के लिए तैयार।',
                    'it' => 'Blocco di travertino miele con dimensioni registrate di 310x175x190 cm, pronto per il trasporto con la grua.',
                ],
                'description' => [
                    'fa' => '<p>این بلوک با ابعاد دقیق ۳۱۰×۱۷۵×۱۹۰ سانتی‌متر و علامت تجاری "EN 2" که با رنگ آبی روی سطح آن ثبت شده، توسط جرثقیل برای بارگیری بلند شده است. رنگ قهوه‌ای گرم همراه با رگه‌های موجی متراکم، الگوی کلاسیک تراورتن عسلی را به نمایش می‌گذارد. این بلوک گزینه‌ای عالی برای پروژه‌های نمای ساختمان و دیوارپوش است.</p>',
                    'en' => '<p>With its precise dimensions of 310x175x190 cm and the trade mark "EN 2" recorded in blue on its surface, this block has been lifted by crane for loading. The warm brown color with dense wavy veining showcases the classic honey travertine pattern. This block is an excellent option for building facade and wall cladding projects.</p>',
                    'ar' => '<p>بأبعادها الدقيقة 310×175×190 سم والعلامة التجارية "EN 2" المُسجلة بالأزرق على سطحها، تم رفع هذه الكتلة بالرافعة للشحن. اللون البني الدافئ مع العروق المتموجة الكثيفة يُظهر النمط الكلاسيكي للترافرتين العسلي. هذه الكتلة خيار ممتاز لمشاريع واجهات المباني وتكسية الجدران.</p>',
                    'hi' => '<p>310x175x190 सेमी के सटीक आयामों और सतह पर नीले रंग में दर्ज "EN 2" व्यापारिक चिह्न के साथ, इस ब्लॉक को लोडिंग के लिए क्रेन द्वारा उठाया गया है। घनी लहरदार धारियों के साथ गर्म भूरा रंग क्लासिक हनी ट्रैवर्टाइन पैटर्न प्रदर्शित करता है। यह ब्लॉक बिल्डिंग फ़साड और वॉल क्लैडिंग प्रोजेक्ट्स के लिए एक उत्कृष्ट विकल्प है।</p>',
                    'it' => '<p>Con le sue dimensioni precise di 310x175x190 cm e il marchio commerciale "EN 2" registrato in blu sulla sua superficie, questo blocco è stato sollevato con la grua per il carico. Il colore marrone caldo con dense venature ondulate mostra il motivo classico del travertino miele. Questo blocco è un\'ottima opzione per progetti di facciate di edifici e rivestimenti murali.</p>',
                ],
                'sku' => 'EN-TRV-HNY-007',
                'price' => 28250000,
                'price_usd' => 565,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'available',
                'is_featured' => false,
                'is_active' => true,
                'is_new' => false,
                'sort_order' => 7,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن عسلی EN - کد ۷ | EN Trading Group',
                    'en' => 'Buy EN Honey Travertine Block - Code 7 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين عسلي EN - كود ٧ | EN Trading Group',
                    'hi' => 'EN हनी ट्रैवर्टाइन ब्लॉक खरीदें - कोड 7 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Miele EN - Codice 7 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن عسلی با ابعاد ۳۱۰×۱۷۵×۱۹۰، مناسب نمای ساختمان. استعلام قیمت از EN Trading Group.',
                    'en' => 'Honey travertine block sized 310x175x190 cm, suitable for building facades. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين عسلي بمقاس 310×175×190 سم، مناسبة لواجهات المباني. اطلب عرض سعر من EN Trading Group.',
                    'hi' => '310x175x190 सेमी आकार का हनी ट्रैवर्टाइन ब्लॉक, बिल्डिंग फ़साड के लिए उपयुक्त। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Blocco di travertino miele di dimensioni 310x175x190 cm, adatto per facciate di edifici. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن عسلی, بلوک ۳۱۰x۱۷۵x۱۹۰, نمای ساختمان, صادرات سنگ',
                    'en' => 'honey travertine, 310x175x190 block, building facade, stone export',
                    'ar' => 'ترافرتين عسلي, كتلة 310x175x190, واجهة مبنى, تصدير الحجر',
                    'hi' => 'हनी ट्रैवर्टाइन, 310x175x190 ब्लॉक, बिल्डिंग फ़साड, पत्थर निर्यात',
                    'it' => 'travertino miele, blocco 310x175x190, facciata edificio, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'brown',
                    'surface_finish'        => 'natural_split',
                    'length'                => 310,
                    'width'                 => 175,
                    'thickness'             => 190,
                    'weight'                => 24700,
                    'block_volume'          => 10.31,
                    'quarry_origin'         => [
                        'fa' => 'معدن عباس‌آباد',
                        'en' => 'Abbasabad Mine',
                        'ar' => 'محجر عباس آباد',
                        'hi' => 'अब्बासाबाद खदान',
                        'it' => 'Miniera di Abbasabad',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'premium',
                    'pattern'               => 'veined',
                    'water_absorption'      => 2.4,
                    'compressive_strength'  => 70,
                    'density'               => 2.36,
                    'frost_resistant'       => false,
                    'usage'                 => 'facade',
                    'usage_environment'     => 'both',
                    'slip_resistance'       => 'medium',
                    'edge_profile'          => 'straight',
                    'packaging_type'        => 'loose',
                ],
            ],

            // ── Product 8 (images: 71,72,73,74.jpeg) ────────────
            [
                'folder'   => '8',
                'main'     => 'main.jpeg',
                'gallery'  => ['gallery-1.jpeg', 'gallery-2.jpeg', 'gallery-3.jpeg'],
                'category' => $honeyCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن عسلی',
                    'en' => 'Honey Travertine Block',
                    'ar' => 'كتلة ترافرتين عسلي',
                    'hi' => 'हनी ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Miele',
                ],
                'slug' => [
                    'fa' => 'travertan-asali-8',
                    'en' => 'honey-travertine-block-8',
                    'ar' => 'honey-travertine-block-8',
                    'hi' => 'honey-travertine-block-8',
                    'it' => 'honey-travertine-block-8',
                ],
                'short_description' => [
                    'fa' => 'بلوک تراورتن عسلی تیره (اسمر) با ابعاد ۲۷۰×۱۸۰×۱۸۵، در حال آماده‌سازی برای حمل با جرثقیل.',
                    'en' => 'Dark honey "Asmar" travertine block sized 270x180x185 cm, being prepared for crane handling.',
                    'ar' => 'كتلة ترافرتين عسلي غامق "أسمر" بمقاس 270×180×185 سم، يتم تجهيزها للرفع بالرافعة.',
                    'hi' => 'गहरे हनी "अस्मर" ट्रैवर्टाइन ब्लॉक का आकार 270x180x185 सेमी, क्रेन हैंडलिंग के लिए तैयार किया जा रहा है।',
                    'it' => 'Blocco di travertino miele scuro "Asmar" di dimensioni 270x180x185 cm, in preparazione per la movimentazione con la grua.',
                ],
                'description' => [
                    'fa' => '<p>این بلوک با برند تجاری «اسمر» که به‌معنای رنگ تیره و گرم اشاره دارد، نمونه‌ای از تراورتن‌های عسلی پررنگ منطقه است. ابعاد ۲۷۰×۱۸۰×۱۸۵ سانتی‌متر روی سطح بلوک با رنگ قرمز ثبت شده و توسط جرثقیل برای بارگیری آماده می‌شود. این بلوک با رگه‌های افقی منظم، گزینه‌ای مناسب برای کف‌سازی فضاهای باز و پروژه‌های محوطه‌سازی است.</p>',
                    'en' => '<p>Marked with the trade name "Asmar" — meaning a deep, warm tone — this block represents the region\'s richly-colored honey travertine. Its dimensions of 270x180x185 cm are marked in red on the block surface, and it is being prepared for crane loading. With its regular horizontal veining, this block is a great choice for outdoor flooring and landscaping projects.</p>',
                    'ar' => '<p>تحت الاسم التجاري "أسمر" — الذي يشير إلى لون غامق ودافئ — تمثل هذه الكتلة الترافرتين العسلي الغني بالألوان في المنطقة. أبعادها 270×180×185 سم مُسجلة بالأحمر على سطح الكتلة، ويتم تجهيزها للتحميل بالرافعة. بعروقها الأفقية المنتظمة، تُعد هذه الكتلة خياراً رائعاً لأرضيات المساحات الخارجية ومشاريع تنسيق الحدائق.</p>',
                    'hi' => '<p>व्यापारिक नाम "अस्मर" — जिसका अर्थ है गहरा, गर्म टोन — से चिह्नित, यह ब्लॉक क्षेत्र के समृद्ध रंग वाले हनी ट्रैवर्टाइन का प्रतिनिधित्व करता है। इसके 270x180x185 सेमी आयाम ब्लॉक की सतह पर लाल रंग में चिह्नित हैं, और इसे क्रेन लोडिंग के लिए तैयार किया जा रहा है। नियमित क्षैतिज धारियों के साथ, यह ब्लॉक आउटडोर फ़्लोरिंग और लैंडस्केपिंग प्रोजेक्ट्स के लिए एक बेहतरीन विकल्प है।</p>',
                    'it' => '<p>Contrassegnato con il nome commerciale "Asmar" — che significa tonalità profonda e calda — questo blocco rappresenta il travertino miele ricco di colore della regione. Le sue dimensioni di 270x180x185 cm sono segnate in rosso sulla superficie del blocco, ed è in preparazione per il carico con la grua. Con le sue venature orizzontali regolari, questo blocco è un\'ottima scelta per pavimentazioni esterne e progetti di paesaggistica.</p>',
                ],
                'sku' => 'EN-TRV-HNY-008',
                'price' => 24750000,
                'price_usd' => 495,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'available',
                'is_featured' => true,
                'is_active' => true,
                'is_new' => true,
                'sort_order' => 8,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن عسلی اسمر EN - کد ۸ | EN Trading Group',
                    'en' => 'Buy EN Asmar Honey Travertine Block - Code 8 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين عسلي أسمر EN - كود ٨ | EN Trading Group',
                    'hi' => 'EN अस्मर हनी ट्रैवर्टाइन ब्लॉक खरीदें - कोड 8 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Miele Asmar EN - Codice 8 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن عسلی تیره اسمر، ابعاد ۲۷۰×۱۸۰×۱۸۵، مناسب محوطه‌سازی. استعلام قیمت از EN Trading Group.',
                    'en' => 'Dark honey Asmar travertine block, dimensions 270x180x185, suitable for landscaping. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين عسلي غامق أسمر، أبعاد 270×180×185، مناسبة لتنسيق الحدائق. اطلب عرض سعر من EN Trading Group.',
                    'hi' => 'गहरा हनी अस्मर ट्रैवर्टाइन ब्लॉक, आयाम 270x180x185, लैंडस्केपिंग के लिए उपयुक्त। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Blocco di travertino miele scuro Asmar, dimensioni 270x180x185, adatto per paesaggistica. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن عسلی اسمر, بلوک ۲۷۰x۱۸۰x۱۸۵, محوطه‌سازی, صادرات سنگ',
                    'en' => 'asmar honey travertine, 270x180x185 block, landscaping, stone export',
                    'ar' => 'ترافرتين عسلي أسمر, كتلة 270x180x185, تنسيق حدائق, تصدير الحجر',
                    'hi' => 'अस्मर हनी ट्रैवर्टाइन, 270x180x185 ब्लॉक, लैंडस्केपिंग, पत्थर निर्यात',
                    'it' => 'travertino miele asmar, blocco 270x180x185, paesaggistica, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'brown',
                    'surface_finish'        => 'natural_split',
                    'length'                => 270,
                    'width'                 => 180,
                    'thickness'             => 185,
                    'weight'                => 21000,
                    'block_volume'          => 8.99,
                    'quarry_origin'         => [
                        'fa' => 'معدن عباس‌آباد',
                        'en' => 'Abbasabad Mine',
                        'ar' => 'محجر عباس آباد',
                        'hi' => 'अब्बासाबाद खदान',
                        'it' => 'Miniera di Abbasabad',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'standard',
                    'pattern'               => 'veined',
                    'water_absorption'      => 2.6,
                    'compressive_strength'  => 66,
                    'density'               => 2.33,
                    'frost_resistant'       => false,
                    'usage'                 => 'landscape',
                    'usage_environment'     => 'outdoor',
                    'slip_resistance'       => 'medium',
                    'edge_profile'          => 'chiseled',
                    'packaging_type'        => 'loose',
                ],
            ],

            // ── Product 9 (images: 81,82,83,84.jpeg) ────────────
            [
                'folder'   => '9',
                'main'     => 'main.jpeg',
                'gallery'  => ['gallery-1.jpeg', 'gallery-2.jpeg', 'gallery-3.jpeg'],
                'category' => $honeyCategory,
                'name' => [
                    'fa' => 'بلوک تراورتن عسلی',
                    'en' => 'Honey Travertine Block',
                    'ar' => 'كتلة ترافرتين عسلي',
                    'hi' => 'हनी ट्रैवर्टाइन ब्लॉक',
                    'it' => 'Blocco di Travertino Miele',
                ],
                'slug' => [
                    'fa' => 'travertan-asali-9',
                    'en' => 'honey-travertine-block-9',
                    'ar' => 'honey-travertine-block-9',
                    'hi' => 'honey-travertine-block-9',
                    'it' => 'honey-travertine-block-9',
                ],
                'short_description' => [
                    'fa' => 'بلوک تراورتن عسلی روشن با ابعاد علامت‌گذاری‌شده ۳۰۰×۱۵۵، در حال بارگیری در محوطه معدن.',
                    'en' => 'Light honey travertine block with marked dimensions of 300x155, being loaded at the quarry yard.',
                    'ar' => 'كتلة ترافرتين عسلي فاتح بأبعاد مُعلَّمة 300×155، يتم تحميلها في ساحة المحجر.',
                    'hi' => '300x155 के चिह्नित आयामों के साथ हल्का हनी ट्रैवर्टाइन ब्लॉक, खदान यार्ड में लोड किया जा रहा है।',
                    'it' => 'Blocco di travertino miele chiaro con dimensioni segnate 300x155, in fase di carico nel piazzale della cava.',
                ],
                'description' => [
                    'fa' => '<p>این بلوک تراورتن عسلی روشن، با ابعاد ۳۰۰×۱۵۵ سانتی‌متر که با رنگ قرمز روی سطح آن مشخص شده، در حال بارگیری توسط جرثقیل در محوطه معدن است. رنگ گرم و رگه‌های ریز و منظم آن، این بلوک را برای تولید کاشی‌های کف و دیوار با ظاهر کلاسیک مناسب می‌سازد. کیفیت سطح بلوک نشان از عدم وجود ترک و شکستگی عمیق دارد.</p>',
                    'en' => '<p>This light honey travertine block, with dimensions of 300x155 cm marked in red on its surface, is being loaded by crane at the quarry yard. Its warm color and fine, regular veining make this block suitable for producing floor and wall tiles with a classic appearance. The surface quality shows no deep cracks or fractures.</p>',
                    'ar' => '<p>تُحمَّل هذه الكتلة من الترافرتين العسلي الفاتح، بأبعاد 300×155 سم مُعلَّمة بالأحمر على سطحها، بواسطة الرافعة في ساحة المحجر. لونها الدافئ وعروقها الدقيقة والمنتظمة تجعل هذه الكتلة مناسبة لإنتاج بلاط الأرضيات والجدران بمظهر كلاسيكي. تُظهر جودة السطح عدم وجود شقوق أو كسور عميقة.</p>',
                    'hi' => '<p>इस हल्के हनी ट्रैवर्टाइन ब्लॉक को, जिसकी सतह पर लाल रंग में 300x155 सेमी आयाम चिह्नित हैं, खदान यार्ड में क्रेन द्वारा लोड किया जा रहा है। इसका गर्म रंग और बारीक, नियमित धारियाँ इस ब्लॉक को क्लासिक दिखावट वाली फ़्लोर और वॉल टाइलें बनाने के लिए उपयुक्त बनाती हैं। सतह की गुणवत्ता गहरी दरारों या फ्रैक्चर की अनुपस्थिति को दर्शाती है।</p>',
                    'it' => '<p>Questo blocco di travertino miele chiaro, con dimensioni di 300x155 cm segnate in rosso sulla sua superficie, viene caricato con la grua nel piazzale della cava. Il suo colore caldo e le venature fini e regolari rendono questo blocco adatto per la produzione di piastrelle da pavimento e da parete con un aspetto classico. La qualità della superficie non mostra crepe o fratture profonde.</p>',
                ],
                'sku' => 'EN-TRV-HNY-009',
                'price' => 23500000,
                'price_usd' => 470,
                'price_eur' => null,
                'price_on_request' => true,
                'status' => 'unavailable',
                'is_featured' => false,
                'is_active' => true,
                'is_new' => false,
                'sort_order' => 9,
                'meta_title' => [
                    'fa' => 'خرید بلوک تراورتن عسلی EN - کد ۹ | EN Trading Group',
                    'en' => 'Buy EN Honey Travertine Block - Code 9 | EN Trading Group',
                    'ar' => 'شراء كتلة ترافرتين عسلي EN - كود ٩ | EN Trading Group',
                    'hi' => 'EN हनी ट्रैवर्टाइन ब्लॉक खरीदें - कोड 9 | EN Trading Group',
                    'it' => 'Acquista Blocco Travertino Miele EN - Codice 9 | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بلوک تراورتن عسلی روشن با ابعاد ۳۰۰×۱۵۵، مناسب کاشی کف و دیوار. استعلام قیمت از EN Trading Group.',
                    'en' => 'Light honey travertine block sized 300x155, suitable for floor and wall tiles. Request a quote from EN Trading Group.',
                    'ar' => 'كتلة ترافرتين عسلي فاتح بمقاس 300×155، مناسبة لبلاط الأرضيات والجدران. اطلب عرض سعر من EN Trading Group.',
                    'hi' => '300x155 आकार का हल्का हनी ट्रैवर्टाइन ब्लॉक, फ़्लोर और वॉल टाइल्स के लिए उपयुक्त। EN Trading Group से कोटेशन प्राप्त करें।',
                    'it' => 'Blocco di travertino miele chiaro di dimensioni 300x155, adatto per piastrelle da pavimento e da parete. Richiedi un preventivo a EN Trading Group.',
                ],
                'meta_keywords' => [
                    'fa' => 'تراورتن عسلی روشن, بلوک ۳۰۰x۱۵۵, کاشی کف, صادرات سنگ',
                    'en' => 'light honey travertine, 300x155 block, floor tile, stone export',
                    'ar' => 'ترافرتين عسلي فاتح, كتلة 300x155, بلاط أرضيات, تصدير الحجر',
                    'hi' => 'हल्का हनी ट्रैवर्टाइन, 300x155 ब्लॉक, फ़्लोर टाइल, पत्थर निर्यात',
                    'it' => 'travertino miele chiaro, blocco 300x155, piastrella da pavimento, esportazione pietra',
                ],
                'attributes' => [
                    'color'                 => 'honey',
                    'surface_finish'        => 'natural_split',
                    'length'                => 300,
                    'width'                 => 155,
                    'thickness'             => 170,
                    'weight'                => 17600,
                    'block_volume'          => 7.91,
                    'quarry_origin'         => [
                        'fa' => 'معدن عباس‌آباد',
                        'en' => 'Abbasabad Mine',
                        'ar' => 'محجر عباس آباد',
                        'hi' => 'अब्बासाबाद खदान',
                        'it' => 'Miniera di Abbasabad',
                    ],
                    'country_of_origin'     => 'Iran',
                    'quality_grade'         => 'standard',
                    'pattern'               => 'uniform',
                    'water_absorption'      => 2.7,
                    'compressive_strength'  => 64,
                    'density'               => 2.32,
                    'frost_resistant'       => false,
                    'usage'                 => 'flooring',
                    'usage_environment'     => 'indoor',
                    'slip_resistance'       => 'low',
                    'edge_profile'          => 'straight',
                    'packaging_type'        => 'loose',
                ],
            ],
        ];

        // ══════════════════════════════════════════════════════════
        //  SEED LOOP
        // ══════════════════════════════════════════════════════════
        foreach ($products as $data) {
            $attributesData = $data['attributes'];
            $folder         = $data['folder'];
            $mainImage      = $data['main'];
            $galleryImages  = $data['gallery'];
            $category       = $data['category'];

            unset($data['attributes'], $data['folder'], $data['main'], $data['gallery'], $data['category']);

            /** @var Product $product */
            $product = Product::updateOrCreate(
                ['sku' => $data['sku']],
                $data
            );

            // ── Categories (primary) ────────────────────────────
            if ($category) {
                $product->categories()->syncWithoutDetaching([
                    $category->id => ['is_primary' => true],
                ]);
            }

            // ── Media: main image ───────────────────────────────
            $mainPath = storage_path("app/{$this->imagesBasePath}/{$folder}/{$mainImage}");
            if (file_exists($mainPath)) {
                $product->clearMediaCollection('main_image');
                $product->addMedia($mainPath)
                    ->preservingOriginal()
                    ->toMediaCollection('main_image');
            }

            // ── Media: gallery ───────────────────────────────────
            $product->clearMediaCollection('gallery');
            foreach ($galleryImages as $galleryFile) {
                $galleryPath = storage_path("app/{$this->imagesBasePath}/{$folder}/{$galleryFile}");
                if (file_exists($galleryPath)) {
                    $product->addMedia($galleryPath)
                        ->preservingOriginal()
                        ->toMediaCollection('gallery');
                }
            }

            // ── Attributes (pivot via attribute_id) ─────────────
            $sort = 1;
            foreach ($attributesData as $key => $value) {
                $attribute = $attr->get($key);

                if (!$attribute) {
                    continue; // attribute not found, skip safely
                }

                $storedValue = match (true) {
                    $attribute->isText()   => $value, // translatable array {fa,en,ar,hi,it}
                    $attribute->isBool()   => ['value' => (bool) $value],
                    default                => ['value' => $value], // number / select
                };

                ProductAttribute::updateOrCreate(
                    [
                        'product_id'   => $product->id,
                        'attribute_id' => $attribute->id,
                    ],
                    [
                        'value'      => $storedValue,
                        'sort_order' => $sort++,
                    ]
                );
            }
        }

        $this->command->info(count($products) . ' products seeded successfully with attributes, categories, and media.');
    }
}
