<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::first()?->id;

        $posts = [

            // ── 1) Travertine quarrying process ──────────────────────────
            [
                'title' => [
                    'fa' => 'از معدن تا کارخانه: مراحل استخراج و فرآوری تراورتن',
                    'en' => 'From Quarry to Factory: How Travertine Is Extracted and Processed',
                    'ar' => 'من المحجر إلى المصنع: مراحل استخراج ومعالجة الترافرتين',
                    'hi' => 'खदान से कारखाने तक: ट्रैवर्टीन के निष्कर्षण और प्रसंस्करण के चरण',
                    'it' => 'Dalla Cava alla Fabbrica: Come Viene Estratto e Lavorato il Travertino',
                    'zh' => '从矿山到工厂：travertine的开采与加工流程',
                    'tr' => 'Ocaktan Fabrikaya: Traverten Nasıl Çıkarılır ve İşlenir',
                ],
                'slug' => [
                    'fa' => 'marahel-estekhraj-faravari-traverten',
                    'en' => 'travertine-quarrying-and-processing',
                    'ar' => 'travertine-quarrying-and-processing',
                    'hi' => 'travertine-quarrying-and-processing',
                    'it' => 'travertine-quarrying-and-processing',
                    'zh' => 'travertine-quarrying-and-processing',
                    'tr' => 'travertine-quarrying-and-processing',
                ],
                'excerpt' => [
                    'fa' => 'نگاهی به مسیری که یک بلوک خام تراورتن از دل معدن تا اسلب صیقلی آماده صادرات طی می‌کند.',
                    'en' => 'A look at the journey a raw travertine block takes from the quarry to a polished, export-ready slab.',
                    'ar' => 'نظرة على الرحلة التي تقطعها كتلة الترافرتين الخام من المحجر إلى لوح مصقول جاهز للتصدير.',
                    'hi' => 'एक नज़र इस सफर पर कि कच्चा ट्रैवर्टीन ब्लॉक खदान से निर्यात के लिए तैयार पॉलिश स्लैब तक कैसे पहुँचता है।',
                    'it' => 'Uno sguardo al percorso che un blocco grezzo di travertino compie dalla cava alla lastra lucidata pronta per l\'esportazione.',
                    'zh' => '了解一块travertine荒料如何从矿山一路走向抛光后可供出口的板材。',
                    'tr' => 'Ham bir traverten bloğunun ocaktan ihracata hazır cilalı bir plakaya kadar geçirdiği sürece bir bakış.',
                ],
                'content' => [
                    'fa' => '<p>استخراج تراورتن با شناسایی رگه‌های مرغوب در دل کوه آغاز می‌شود. پس از برش بلوک‌های اولیه با سیم‌برش الماسه، هر بلوک از نظر رنگ، تخلخل و یکنواختی رگه بررسی و درجه‌بندی می‌شود.</p><p>بلوک‌های منتخب به کارخانه منتقل شده و با دستگاه‌های برش گانگ‌ساو به ورق‌های نازک تبدیل می‌شوند. مرحله بعد، پرکردن حفره‌های طبیعی سنگ با رزین و سپس صیقل‌کاری چندمرحله‌ای است تا درخشندگی نهایی حاصل شود.</p><p>در پایان، اسلب‌ها برای حمل بین‌المللی روی پالت‌های مخصوص بسته‌بندی و کنترل کیفیت می‌شوند.</p>',
                    'en' => '<p>Travertine extraction begins with identifying high-quality veins inside the mountain. After the first blocks are cut with diamond wire saws, each block is inspected and graded for color, porosity, and vein consistency.</p><p>Selected blocks are transported to the factory and cut into thin slabs using gangsaw machines. The next stage is filling the stone\'s natural voids with resin, followed by multi-stage polishing to achieve the final shine.</p><p>Finally, the slabs are packed on export-grade pallets and quality-checked for international shipping.</p>',
                    'ar' => '<p>يبدأ استخراج الترافرتين بتحديد العروق عالية الجودة داخل الجبل. بعد قطع الكتل الأولى بمناشير الأسلاك الماسية، يتم فحص كل كتلة وتصنيفها حسب اللون والمسامية وانتظام العروق.</p><p>تُنقل الكتل المختارة إلى المصنع وتُقطّع إلى ألواح رقيقة. تليها مرحلة تعبئة الفراغات الطبيعية بالراتنج ثم الصقل متعدد المراحل للوصول إلى اللمعان النهائي.</p>',
                    'hi' => '<p>ट्रैवर्टीन का निष्कर्षण पहाड़ के भीतर उच्च-गुणवत्ता वाली नसों की पहचान से शुरू होता है। हीरे के तार वाली आरी से शुरुआती ब्लॉक काटने के बाद, हर ब्लॉक को रंग, सरंध्रता और नस की एकरूपता के आधार पर जांचा और श्रेणीबद्ध किया जाता है।</p><p>चुने गए ब्लॉकों को फैक्ट्री ले जाया जाता है और पतले स्लैब में काटा जाता है। अगला चरण प्राकृतिक रिक्तियों को रेज़िन से भरना और फिर कई चरणों में पॉलिश करना है।</p>',
                    'it' => '<p>L\'estrazione del travertino inizia con l\'identificazione delle vene di alta qualità all\'interno della montagna. Dopo il taglio dei primi blocchi con seghe a filo diamantato, ogni blocco viene ispezionato e classificato per colore, porosità e uniformità delle venature.</p><p>I blocchi selezionati vengono trasportati in fabbrica e tagliati in lastre sottili. La fase successiva consiste nel riempire i vuoti naturali della pietra con resina, seguita da una lucidatura multi-fase.</p>',
                    'zh' => '<p>travertine的开采始于在山体内识别优质矿脉。使用金刚石绳锯切割出最初的荒料后，每块荒料都会根据颜色、孔隙率和纹理一致性进行检验和分级。</p><p>入选的荒料被运往工厂，通过排锯机切割成薄板。下一步是用树脂填补石材的天然孔洞，然后进行多道工序的抛光，以达到最终的光泽效果。</p><p>最后，板材被装在适用于出口的托盘上，并经过质量检验后准备国际运输。</p>',
                    'tr' => '<p>Traverten çıkarma işlemi, dağın içindeki yüksek kaliteli damarların belirlenmesiyle başlar. İlk bloklar elmas tel testereyle kesildikten sonra, her blok renk, gözeneklilik ve damar tutarlılığı açısından incelenip sınıflandırılır.</p><p>Seçilen bloklar fabrikaya taşınır ve gang testere makineleriyle ince plakalar halinde kesilir. Bir sonraki aşama, taşın doğal boşluklarının reçineyle doldurulması ve ardından son parlaklığı elde etmek için çok aşamalı cilalamadır.</p><p>Son olarak, plakalar ihracat sınıfı paletlere paketlenir ve uluslararası nakliye için kalite kontrolünden geçirilir.</p>',
                ],
                'reading_time' => 5,
                'published_at' => Carbon::now()->subDays(28),
                'cover_seed'   => 'travertine-process-cover.jpg',
            ],

            // ── 2) Stone Expo participation recap ─────────────────────────
            [
                'title' => [
                    'fa' => 'گزارش حضور ما در نمایشگاه بین‌المللی سنگ محلات',
                    'en' => 'Recap: Our Participation at the Mahallat International Stone Expo',
                    'ar' => 'تقرير: مشاركتنا في معرض محلات الدولي للحجر',
                    'hi' => 'रिपोर्ट: महल्लात अंतरराष्ट्रीय पत्थर एक्सपो में हमारी भागीदारी',
                    'it' => 'Riepilogo: La Nostra Partecipazione alla Fiera Internazionale della Pietra di Mahallat',
                    'zh' => '报告：我们参加马哈拉特国际石材展',
                    'tr' => 'Özet: Mahallat Uluslararası Taş Fuarı\'na Katılımımız',
                ],
                'slug' => [
                    'fa' => 'gozaresh-hozoor-namayeshgah-sang-mahallat',
                    'en' => 'mahallat-stone-expo-recap',
                    'ar' => 'mahallat-stone-expo-recap',
                    'hi' => 'mahallat-stone-expo-recap',
                    'it' => 'mahallat-stone-expo-recap',
                    'zh' => 'mahallat-stone-expo-recap',
                    'tr' => 'mahallat-stone-expo-recap',
                ],
                'excerpt' => [
                    'fa' => 'مروری بر دیدارها، محصولات معرفی‌شده و بازخورد بازرگانان در آخرین نمایشگاه سنگ محلات.',
                    'en' => 'A recap of the meetings, showcased products, and trader feedback from the latest Mahallat stone exhibition.',
                    'ar' => 'استعراض للقاءات والمنتجات المعروضة وردود فعل التجار في أحدث معرض لحجر محلات.',
                    'hi' => 'नवीनतम महल्लात पत्थर प्रदर्शनी से बैठकों, प्रदर्शित उत्पादों और व्यापारी प्रतिक्रिया की समीक्षा।',
                    'it' => 'Un riepilogo degli incontri, dei prodotti esposti e dei feedback dei commercianti dell\'ultima fiera della pietra di Mahallat.',
                    'zh' => '回顾最近一届马哈拉特石材展上的会面、展出产品以及贸易商的反馈。',
                    'tr' => 'Son Mahallat taş fuarındaki görüşmelerin, sergilenen ürünlerin ve tüccar geri bildirimlerinin bir özeti.',
                ],
                'content' => [
                    'fa' => '<p>تیم ما در آخرین دوره نمایشگاه بین‌المللی سنگ محلات با غرفه‌ای اختصاصی حضور یافت و جدیدترین بلوک‌های تراورتن سفید و عسلی را به نمایش گذاشت.</p><p>در طول برگزاری نمایشگاه، بیش از پنجاه بازرگان داخلی و خارجی از غرفه بازدید کردند و چند قرارداد صادراتی جدید در همین رویداد نهایی شد.</p>',
                    'en' => '<p>Our team attended the latest edition of the Mahallat International Stone Expo with a dedicated booth, showcasing our newest white and honey travertine blocks.</p><p>Over fifty domestic and international traders visited the booth during the event, and several new export agreements were finalized on-site.</p>',
                    'ar' => '<p>حضر فريقنا أحدث نسخة من معرض محلات الدولي للحجر بجناح مخصص، حيث عرضنا أحدث كتل الترافرتين الأبيض والعسلي.</p><p>زار أكثر من خمسين تاجرًا محليًا ودوليًا الجناح خلال الفعالية.</p>',
                    'hi' => '<p>हमारी टीम ने महल्लात अंतरराष्ट्रीय पत्थर एक्सपो के नवीनतम संस्करण में एक समर्पित स्टॉल के साथ भाग लिया।</p><p>आयोजन के दौरान पचास से अधिक घरेलू और अंतरराष्ट्रीय व्यापारियों ने स्टॉल का दौरा किया।</p>',
                    'it' => '<p>Il nostro team ha partecipato all\'ultima edizione della Fiera Internazionale della Pietra di Mahallat con uno stand dedicato.</p><p>Oltre cinquanta commercianti nazionali e internazionali hanno visitato lo stand durante l\'evento.</p>',
                    'zh' => '<p>我们的团队参加了最近一届马哈拉特国际石材展，设立了专属展位，展示了我们最新的白色和蜂蜜色travertine荒料。</p><p>展会期间，超过五十位国内外贸易商参观了展位，并在现场敲定了几份新的出口协议。</p>',
                    'tr' => '<p>Ekibimiz, en yeni beyaz ve bal rengi traverten bloklarımızı sergilediğimiz özel bir standla Mahallat Uluslararası Taş Fuarı\'nın son edisyonuna katıldı.</p><p>Etkinlik sırasında ellinin üzerinde yerli ve yabancı tüccar standı ziyaret etti ve yerinde çeşitli yeni ihracat anlaşmaları sonuçlandırıldı.</p>',
                ],
                'reading_time' => 3,
                'published_at' => Carbon::now()->subDays(20),
                'cover_seed'   => 'expo-recap-cover.jpg',
            ],

            // ── 3) Export milestone ────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'صادرات ۲۰۰۰ تن سنگ تراورتن به اروپا؛ گامی تازه برای Stone Commerce',
                    'en' => '2,000 Tons of Travertine Exported to Europe: A New Milestone',
                    'ar' => 'تصدير 2000 طن من حجر الترافرتين إلى أوروبا: إنجاز جديد',
                    'hi' => 'यूरोप को 2,000 टन ट्रैवर्टीन का निर्यात: एक नई उपलब्धि',
                    'it' => '2.000 Tonnellate di Travertino Esportate in Europa: Un Nuovo Traguardo',
                    'zh' => '2000吨travertine石材出口欧洲：Stone Commerce的新里程碑',
                    'tr' => 'Avrupa\'ya 2.000 Ton Traverten İhracatı: Yeni Bir Kilometre Taşı',
                ],
                'slug' => [
                    'fa' => 'sadarat-2000-ton-traverten-be-oropa',
                    'en' => 'travertine-export-milestone-europe',
                    'ar' => 'travertine-export-milestone-europe',
                    'hi' => 'travertine-export-milestone-europe',
                    'it' => 'travertine-export-milestone-europe',
                    'zh' => 'travertine-export-milestone-europe',
                    'tr' => 'travertine-export-milestone-europe',
                ],
                'excerpt' => [
                    'fa' => 'این محموله بزرگ شامل بلوک و اسلب تراورتن سفید، در سه کانتینر آماده و به مقصد ایتالیا بارگیری شد.',
                    'en' => 'This large shipment of white travertine blocks and slabs was prepared across three containers, bound for Italy.',
                    'ar' => 'تم إعداد هذه الشحنة الكبيرة من كتل وألواح الترافرتين الأبيض في ثلاث حاويات متجهة إلى إيطاليا.',
                    'hi' => 'सफेद ट्रैवर्टीन ब्लॉक्स और स्लैब्स की यह बड़ी खेप तीन कंटेनरों में तैयार की गई, जो इटली जा रही है।',
                    'it' => 'Questa grande spedizione di blocchi e lastre di travertino bianco è stata preparata su tre container, diretta in Italia.',
                    'zh' => '这批大型货物包括白色travertine荒料和板材，分装于三个集装箱，运往意大利。',
                    'tr' => 'Bu büyük beyaz traverten blok ve plaka sevkiyatı, İtalya\'ya gönderilmek üzere üç konteynerde hazırlandı.',
                ],
                'content' => [
                    'fa' => '<p>در ادامه روند رشد صادراتی شرکت، محموله‌ای شامل دو هزار تن بلوک و اسلب تراورتن سفید برای مشتریان اروپایی آماده و بارگیری شد.</p><p>این محموله نتیجه چند ماه برنامه‌ریزی دقیق در زمینه تأمین، برش، و کنترل کیفیت بود و گامی مهم در مسیر گسترش حضور شرکت در بازار اروپا محسوب می‌شود.</p>',
                    'en' => '<p>As part of the company\'s continued export growth, a shipment of two thousand tons of white travertine blocks and slabs was prepared and loaded for European customers.</p><p>The shipment was the result of months of careful planning around sourcing, cutting, and quality control, marking an important step in expanding the company\'s presence in the European market.</p>',
                    'ar' => '<p>في إطار النمو المستمر لصادرات الشركة، تم إعداد وتحميل شحنة من ألفي طن من كتل وألواح الترافرتين الأبيض للعملاء الأوروبيين.</p><p>جاءت هذه الشحنة نتيجة لأشهر من التخطيط الدقيق في التوريد والقطع ومراقبة الجودة.</p>',
                    'hi' => '<p>कंपनी के निरंतर निर्यात विकास के हिस्से के रूप में, यूरोपीय ग्राहकों के लिए दो हजार टन सफेद ट्रैवर्टीन ब्लॉक्स और स्लैब्स की खेप तैयार और लोड की गई।</p><p>यह खेप सोर्सिंग, कटिंग और गुणवत्ता नियंत्रण की महीनों की सावधानीपूर्वक योजना का परिणाम थी।</p>',
                    'it' => '<p>Nell\'ambito della continua crescita delle esportazioni dell\'azienda, è stata preparata e caricata una spedizione di duemila tonnellate di blocchi e lastre di travertino bianco per i clienti europei.</p><p>La spedizione è il risultato di mesi di pianificazione accurata.</p>',
                    'zh' => '<p>作为公司持续出口增长的一部分，一批两千吨的白色travertine荒料和板材已准备就绪并装运，发往欧洲客户。</p><p>这批货物是数月来在采购、切割和质量控制方面精心规划的成果，标志着公司拓展欧洲市场的重要一步。</p>',
                    'tr' => '<p>Şirketin sürekli ihracat büyümesinin bir parçası olarak, Avrupalı müşteriler için iki bin ton beyaz traverten blok ve plaka sevkiyatı hazırlanıp yüklendi.</p><p>Bu sevkiyat, tedarik, kesim ve kalite kontrolü konusunda aylarca süren titiz planlamanın sonucuydu ve şirketin Avrupa pazarındaki varlığını genişletme yolunda önemli bir adımı işaret ediyor.</p>',
                ],
                'reading_time' => 4,
                'published_at' => Carbon::now()->subDays(14),
                'cover_seed'   => 'export-milestone-cover.jpg',
            ],

            // ── 4) Buying guide ─────────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'راهنمای انتخاب سنگ طبیعی مناسب برای نمای ساختمان',
                    'en' => 'A Buyer\'s Guide: Choosing the Right Natural Stone for Building Facades',
                    'ar' => 'دليل المشتري: اختيار الحجر الطبيعي المناسب لواجهات المباني',
                    'hi' => 'खरीदार गाइड: भवन के अग्रभाग के लिए सही प्राकृतिक पत्थर चुनना',
                    'it' => 'Guida all\'Acquisto: Scegliere la Pietra Naturale Giusta per le Facciate degli Edifici',
                    'zh' => '购买指南：如何为建筑立面选择合适的天然石材',
                    'tr' => 'Alıcı Rehberi: Bina Cepheleri için Doğru Doğal Taşı Seçmek',
                ],
                'slug' => [
                    'fa' => 'rahnama-entekhab-sang-nama-sakhteman',
                    'en' => 'guide-choosing-natural-stone-facade',
                    'ar' => 'guide-choosing-natural-stone-facade',
                    'hi' => 'guide-choosing-natural-stone-facade',
                    'it' => 'guide-choosing-natural-stone-facade',
                    'zh' => 'guide-choosing-natural-stone-facade',
                    'tr' => 'guide-choosing-natural-stone-facade',
                ],
                'excerpt' => [
                    'fa' => 'چند معیار کلیدی — از مقاومت در برابر آب‌وهوا تا هماهنگی رنگ — برای انتخاب سنگ نمای مناسب پروژه.',
                    'en' => 'A few key criteria — from weather resistance to color consistency — for choosing the right facade stone for your project.',
                    'ar' => 'بعض المعايير الأساسية — من مقاومة الطقس إلى انسجام اللون — لاختيار حجر الواجهة المناسب لمشروعك.',
                    'hi' => 'मौसम प्रतिरोध से लेकर रंग स्थिरता तक — आपके प्रोजेक्ट के लिए सही फेसाड पत्थर चुनने के कुछ प्रमुख मानदंड।',
                    'it' => 'Alcuni criteri chiave — dalla resistenza agli agenti atmosferici alla coerenza del colore — per scegliere la pietra da facciata giusta per il tuo progetto.',
                    'zh' => '从耐候性到色彩一致性，为您的项目挑选合适立面石材的几项关键标准。',
                    'tr' => 'Hava koşullarına dayanıklılıktan renk tutarlılığına kadar, projeniz için doğru cephe taşını seçerken dikkate alınacak birkaç önemli kriter.',
                ],
                'content' => [
                    'fa' => '<p>انتخاب سنگ نما تنها به زیبایی ظاهری محدود نمی‌شود؛ مقاومت در برابر یخ‌زدگی، میزان جذب آب و یکنواختی رگه از معیارهای فنی مهم هستند.</p><p>برای پروژه‌های با اقلیم مرطوب، سنگ‌هایی با تخلخل پایین‌تر مانند گرانیت یا تراورتن رزین‌خورده توصیه می‌شود. در انتخاب نهایی، همیشه نمونه واقعی سنگ را زیر نور طبیعی محل پروژه بررسی کنید.</p>',
                    'en' => '<p>Choosing a facade stone isn\'t just about looks; frost resistance, water absorption, and vein consistency are all important technical criteria.</p><p>For projects in humid climates, lower-porosity stones such as granite or resin-filled travertine are recommended. Always check a real sample under the project site\'s natural light before finalizing.</p>',
                    'ar' => '<p>اختيار حجر الواجهة لا يقتصر على المظهر فقط؛ فمقاومة التجمد وامتصاص الماء وانتظام العروق كلها معايير فنية مهمة.</p><p>للمشاريع في المناخات الرطبة، يُنصح بأحجار ذات مسامية أقل مثل الجرانيت أو الترافرتين المملوء بالراتنج.</p>',
                    'hi' => '<p>फेसाड पत्थर चुनना केवल दिखावे के बारे में नहीं है; ठंढ प्रतिरोध, जल अवशोषण और नस की एकरूपता सभी महत्वपूर्ण तकनीकी मानदंड हैं।</p><p>आर्द्र जलवायु वाले प्रोजेक्ट्स के लिए, ग्रेनाइट या रेज़िन से भरे ट्रैवर्टीन जैसे कम सरंध्रता वाले पत्थरों की सिफारिश की जाती है।</p>',
                    'it' => '<p>Scegliere una pietra da facciata non riguarda solo l\'estetica; la resistenza al gelo, l\'assorbimento d\'acqua e l\'uniformità delle venature sono tutti criteri tecnici importanti.</p><p>Per progetti in climi umidi, si consigliano pietre a bassa porosità come il granito o il travertino resinato.</p>',
                    'zh' => '<p>选择立面石材不仅仅关乎美观；抗冻性、吸水率和纹理一致性都是重要的技术标准。</p><p>对于潮湿气候的项目，建议使用孔隙率较低的石材，如花岗岩或树脂填充的travertine。最终选择前，务必在项目现场的自然光线下查看实物样品。</p>',
                    'tr' => '<p>Cephe taşı seçimi sadece görünümle ilgili değildir; don direnci, su emme oranı ve damar tutarlılığı önemli teknik kriterlerdir.</p><p>Nemli iklimlerdeki projeler için granit veya reçineyle doldurulmuş traverten gibi düşük gözenekli taşlar önerilir. Kesin karar vermeden önce, proje alanının doğal ışığı altında gerçek bir numuneyi mutlaka kontrol edin.</p>',
                ],
                'reading_time' => 6,
                'published_at' => Carbon::now()->subDays(7),
                'cover_seed'   => 'facade-guide-cover.jpg',
            ],

            // ── 5) Onyx vs marble ────────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'انیکس یا مرمر؟ تفاوت‌هایی که قبل از خرید باید بدانید',
                    'en' => 'Onyx vs. Marble: What You Should Know Before You Buy',
                    'ar' => 'الأونكس أم الرخام؟ ما يجب معرفته قبل الشراء',
                    'hi' => 'ओनिक्स या मार्बल? खरीदने से पहले जानने योग्य अंतर',
                    'it' => 'Onice o Marmo? Cosa Sapere Prima di Acquistare',
                    'zh' => '玛瑙石还是大理石？购买前你应该了解的区别',
                    'tr' => 'Oniks mu Mermer mi? Satın Almadan Önce Bilmeniz Gerekenler',
                ],
                'slug' => [
                    'fa' => 'onyx-ya-marmar-tafavotha',
                    'en' => 'onyx-vs-marble-buying-guide',
                    'ar' => 'onyx-vs-marble-buying-guide',
                    'hi' => 'onyx-vs-marble-buying-guide',
                    'it' => 'onyx-vs-marble-buying-guide',
                    'zh' => 'onyx-vs-marble-buying-guide',
                    'tr' => 'onyx-vs-marble-buying-guide',
                ],
                'excerpt' => [
                    'fa' => 'هر دو سنگ شیک و چشم‌نوازند، اما در شفافیت، قیمت و کاربرد مناسب با هم تفاوت‌های مهمی دارند.',
                    'en' => 'Both stones are elegant and eye-catching, but they differ in translucency, price, and ideal application.',
                    'ar' => 'كلا الحجرين أنيقان وجذابان، لكنهما يختلفان في الشفافية والسعر والاستخدام المثالي.',
                    'hi' => 'दोनों पत्थर सुरुचिपूर्ण और आकर्षक हैं, लेकिन पारदर्शिता, कीमत और आदर्श उपयोग में भिन्न हैं।',
                    'it' => 'Entrambe le pietre sono eleganti e accattivanti, ma differiscono per traslucenza, prezzo e applicazione ideale.',
                    'zh' => '两种石材都优雅夺目，但在透光性、价格和最佳用途上有重要差异。',
                    'tr' => 'Her iki taş da zarif ve göz alıcıdır, ancak şeffaflık, fiyat ve ideal kullanım alanı bakımından önemli farklılıklar gösterir.',
                ],
                'content' => [
                    'fa' => '<p>انیکس به دلیل ساختار بلورین، نور را عبور می‌دهد و برای نورپردازی پشت سنگ بسیار محبوب است؛ در حالی که مرمر کدر بوده و معمولاً برای کف و پله مقاوم‌تر است.</p><p>از نظر قیمت، انیکس معمولاً به دلیل کمیابی رگه‌های زیبا گران‌تر از اغلب گونه‌های مرمر است.</p>',
                    'en' => '<p>Thanks to its crystalline structure, onyx is translucent and popular for backlit applications, while marble is opaque and generally more durable for floors and stairs.</p><p>In terms of price, onyx is usually more expensive than most marble varieties due to the rarity of fine veining.</p>',
                    'ar' => '<p>بفضل بنيته البلورية، يكون الأونكس شفافًا وشائعًا في التطبيقات المضاءة من الخلف، بينما الرخام معتم وعمومًا أكثر متانة للأرضيات والسلالم.</p><p>من حيث السعر، عادةً ما يكون الأونكس أغلى من معظم أنواع الرخام.</p>',
                    'hi' => '<p>अपनी क्रिस्टलीय संरचना के कारण, ओनिक्स पारभासी होता है और बैकलिट अनुप्रयोगों के लिए लोकप्रिय है, जबकि मार्बल अपारदर्शी होता है और फर्श व सीढ़ियों के लिए आम तौर पर अधिक टिकाऊ होता है।</p>',
                    'it' => '<p>Grazie alla sua struttura cristallina, l\'onice è traslucido e popolare per applicazioni retroilluminate, mentre il marmo è opaco e generalmente più resistente per pavimenti e scale.</p>',
                    'zh' => '<p>由于其晶体结构，玛瑙石具有透光性，因此非常适合背光应用；而大理石不透光，通常更耐用，适合地面和楼梯。</p><p>在价格方面，由于优质纹理较为稀有，玛瑙石通常比大多数大理石品种更昂贵。</p>',
                    'tr' => '<p>Kristal yapısı sayesinde oniks yarı saydamdır ve arkadan aydınlatmalı uygulamalarda popülerdir; mermer ise opaktır ve genellikle zemin ile merdivenler için daha dayanıklıdır.</p><p>Fiyat açısından, ince damarların nadirliği nedeniyle oniks genellikle çoğu mermer türünden daha pahalıdır.</p>',
                ],
                'reading_time' => 4,
                'published_at' => Carbon::now()->subDays(3),
                'cover_seed'   => 'onyx-marble-cover.jpg',
            ],

            // ── 6) Sustainability ────────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'گام‌های ما در مسیر استخراج مسئولانه و پایدار سنگ',
                    'en' => 'Our Steps Toward Responsible, Sustainable Stone Extraction',
                    'ar' => 'خطواتنا نحو استخراج مسؤول ومستدام للحجر',
                    'hi' => 'जिम्मेदार, टिकाऊ पत्थर निष्कर्षण की दिशा में हमारे कदम',
                    'it' => 'I Nostri Passi Verso un\'Estrazione della Pietra Responsabile e Sostenibile',
                    'zh' => '我们迈向负责任、可持续石材开采的举措',
                    'tr' => 'Sorumlu ve Sürdürülebilir Taş Çıkarımına Doğru Attığımız Adımlar',
                ],
                'slug' => [
                    'fa' => 'gam-haye-estekhraj-paydar-sang',
                    'en' => 'sustainable-stone-extraction-steps',
                    'ar' => 'sustainable-stone-extraction-steps',
                    'hi' => 'sustainable-stone-extraction-steps',
                    'it' => 'sustainable-stone-extraction-steps',
                    'zh' => 'sustainable-stone-extraction-steps',
                    'tr' => 'sustainable-stone-extraction-steps',
                ],
                'excerpt' => [
                    'fa' => 'از بازچرخانی پساب برش تا کاهش ضایعات بلوک، اقداماتی که در معادن همکار خود دنبال می‌کنیم.',
                    'en' => 'From recycling cutting wastewater to reducing block waste, here are the practices we follow at our partner quarries.',
                    'ar' => 'من إعادة تدوير مياه الصرف الناتجة عن القطع إلى تقليل هدر الكتل، إليك الممارسات التي نتبعها في محاجرنا الشريكة.',
                    'hi' => 'कटिंग अपशिष्ट जल के पुनर्चक्रण से लेकर ब्लॉक अपव्यय को कम करने तक, यहाँ हमारी साझेदार खदानों में अपनाई जाने वाली प्रथाएँ हैं।',
                    'it' => 'Dal riciclo delle acque reflue di taglio alla riduzione degli sprechi di blocchi, ecco le pratiche che seguiamo nelle nostre cave partner.',
                    'zh' => '从回收切割废水到减少荒料损耗，这些是我们在合作矿山中遵循的做法。',
                    'tr' => 'Kesim atık suyunun geri dönüştürülmesinden blok israfının azaltılmasına kadar, ortak ocaklarımızda uyguladığımız yöntemler.',
                ],
                'content' => [
                    'fa' => '<p>پایداری در صنعت سنگ، فراتر از شعار است. در معادن همکار، آب مورد استفاده در برش سیمی بازچرخانی شده و گل حاصل از آن برای کاربردهای ساختمانی دیگر استفاده می‌شود.</p><p>همچنین با برنامه‌ریزی دقیق‌تر در نقشه برش بلوک، میزان ضایعات کاهش یافته و بهره‌وری از هر بلوک خام افزایش پیدا کرده است.</p>',
                    'en' => '<p>Sustainability in the stone industry goes beyond a slogan. At our partner quarries, water used in wire-cutting is recycled, and the resulting sludge is repurposed for other construction uses.</p><p>More precise block-cutting planning has also reduced waste and increased the yield from every raw block.</p>',
                    'ar' => '<p>الاستدامة في صناعة الحجر تتجاوز كونها مجرد شعار. في محاجرنا الشريكة، يُعاد تدوير المياه المستخدمة في القطع بالسلك، وتُعاد الاستفادة من الحمأة الناتجة في استخدامات بناء أخرى.</p>',
                    'hi' => '<p>पत्थर उद्योग में स्थिरता एक नारे से कहीं अधिक है। हमारी साझेदार खदानों में, वायर-कटिंग में उपयोग किए जाने वाले पानी का पुनर्चक्रण किया जाता है।</p>',
                    'it' => '<p>La sostenibilità nell\'industria della pietra va oltre uno slogan. Nelle nostre cave partner, l\'acqua utilizzata nel taglio a filo viene riciclata.</p>',
                    'zh' => '<p>石材行业的可持续发展不仅仅是一句口号。在我们的合作矿山，绳锯切割中使用的水会被回收利用，产生的泥浆也被重新用于其他建筑用途。</p><p>此外，通过更精确的荒料切割规划，废料减少，每块荒料的利用率也有所提高。</p>',
                    'tr' => '<p>Taş endüstrisinde sürdürülebilirlik bir sloganın ötesindedir. Ortak ocaklarımızda, tel kesiminde kullanılan su geri dönüştürülür ve ortaya çıkan çamur diğer inşaat kullanımları için yeniden değerlendirilir.</p><p>Daha hassas blok kesim planlaması da israfı azaltmış ve her ham bloktan elde edilen verimi artırmıştır.</p>',
                ],
                'reading_time' => 5,
                'published_at' => Carbon::now()->subDay(),
                'cover_seed'   => 'sustainability-cover.jpg',
            ],
        ];

        foreach ($posts as $data) {
            $coverSeed = $data['cover_seed'];
            unset($data['cover_seed']);

            $post = Post::updateOrCreate(
                ['slug->fa' => $data['slug']['fa']],
                array_merge($data, [
                    'user_id'     => $authorId,
                    'status'      => 'published',
                    'views_count' => rand(30, 450),
                ])
            );

            if ($post->getMedia('cover')->isEmpty()) {
                $coverPath = storage_path('app/seeders/posts/' . $coverSeed);
                if (file_exists($coverPath)) {
                    $post->addMedia($coverPath)
                        ->preservingOriginal()
                        ->toMediaCollection('cover');
                } else {
                    $this->command->warn("Missing cover image: storage/app/seeders/posts/{$coverSeed} (post will fall back to a placeholder)");
                }
            }
        }

        $this->command->info(count($posts) . ' posts seeded.');
    }
}
