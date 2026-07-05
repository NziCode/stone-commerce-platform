<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [

            // ── About Us ─────────────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'درباره ما',
                    'en' => 'About Us',
                    'ar' => 'من نحن',
                    'hi' => 'हमारे बारे में',
                    'it' => 'Chi Siamo',
                    'zh' => '关于我们',
                    'tr' => 'Hakkımızda',
                ],
                'slug' => [
                    'fa' => 'about',
                    'en' => 'about',
                    'ar' => 'about',
                    'hi' => 'about',
                    'it' => 'about',
                    'zh' => 'about',
                    'tr' => 'about',
                ],
                'excerpt' => [
                    'fa' => 'بیش از ۲۵ سال تجربه در استخراج، فرآوری و صادرات سنگ‌های طبیعی ایران به بیش از ۵۰ کشور جهان.',
                    'en' => 'Over 25 years of experience extracting, processing, and exporting Iranian natural stone to more than 50 countries.',
                    'ar' => 'أكثر من 25 عامًا من الخبرة في استخراج ومعالجة وتصدير الحجر الطبيعي الإيراني إلى أكثر من 50 دولة.',
                    'hi' => 'ईरानी प्राकृतिक पत्थर के निष्कर्षण, प्रसंस्करण और 50 से अधिक देशों में निर्यात में 25 से अधिक वर्षों का अनुभव।',
                    'it' => 'Oltre 25 anni di esperienza nell\'estrazione, lavorazione ed esportazione di pietra naturale iraniana in oltre 50 paesi.',
                    'zh' => '我们拥有超过25年在伊朗天然石材开采、加工和出口方面的经验,产品远销50多个国家。',
                    'tr' => "İran doğal taşlarının çıkarılması, işlenmesi ve 50'den fazla ülkeye ihracatında 25 yılı aşkın deneyim.",
                ],
                'content' => [
                    'fa' => <<<'HTML'
                        <p>گروه <strong>EN Trading</strong> با بیش از ۲۵ سال فعالیت مستمر در صنعت سنگ‌های طبیعی، از معدن تا میز مشتری، یک زنجیره کامل و قابل اعتماد را در اختیار خریداران داخلی و بین‌المللی قرار می‌دهد. کار ما با شناسایی و استخراج مستقیم از معادن اختصاصی در نقاط مختلف ایران آغاز می‌شود و تا فرآوری، کنترل کیفیت، و در نهایت بسته‌بندی و حمل بین‌المللی ادامه پیدا می‌کند.</p>

                        <h3>چرا EN Trading Group؟</h3>
                        <p>تجربه طولانی ما در کنار دسترسی مستقیم به معادن، به ما این امکان را می‌دهد که محصولاتی با کیفیت ثابت، رنگ‌بندی یکنواخت و قیمت رقابتی به مشتریان ارائه دهیم — بدون واسطه‌های غیرضروری. امروز محصولات ما شامل تراورتن، مرمریت، گرانیت، سنگ آنتیک، سنگ آهک و اسلیت، به بیش از ۵۰ کشور در ۵ قاره جهان صادر می‌شود.</p>

                        <h3>تعهد ما به کیفیت</h3>
                        <p>هر بلوک پیش از ورود به خط تولید از نظر رنگ، تخلخل و یکنواختی رگه بررسی می‌شود. اسلب‌های نهایی نیز در چند مرحله کنترل کیفیت قرار می‌گیرند تا مطمئن شویم محصولی که به دست مشتری می‌رسد، دقیقاً همان چیزی است که سفارش داده.</p>

                        <h3>همکاری بین‌المللی</h3>
                        <p>تیم فروش ما به‌صورت مستمر در نمایشگاه‌های بین‌المللی سنگ حضور دارد تا با بازرگانان و پیمانکاران از سراسر جهان در ارتباط مستقیم باشد. این حضور، همراه با امکان ارسال نمونه و مشاوره فنی رایگان، همکاری با ما را برای پروژه‌های کوچک و بزرگ ساده می‌کند.</p>
                        HTML,
                    'en' => <<<'HTML'
                        <p><strong>EN Trading Group</strong> has spent over 25 years building a complete, trustworthy supply chain for natural stone — from quarry to customer — serving both domestic and international buyers. Our work begins with sourcing and direct extraction from dedicated mines across Iran, and continues through processing, quality control, and international packaging and shipping.</p>

                        <h3>Why EN Trading Group?</h3>
                        <p>Our long-standing experience, combined with direct access to our own quarries, lets us offer consistent quality, uniform coloring, and competitive pricing — without unnecessary middlemen. Today our products, including travertine, marble, granite, antique stone, limestone, and slate, are exported to more than 50 countries across 5 continents.</p>

                        <h3>Our Commitment to Quality</h3>
                        <p>Every block is inspected for color, porosity, and vein consistency before it enters production. Finished slabs go through multiple quality-control checkpoints to make sure what reaches the customer is exactly what was ordered.</p>

                        <h3>International Partnerships</h3>
                        <p>Our sales team regularly attends international stone exhibitions to stay in direct contact with traders and contractors worldwide. That presence, combined with free sample shipping and technical consultation, makes working with us straightforward for projects of any size.</p>
                        HTML,
                    'ar' => <<<'HTML'
                        <p>على مدى أكثر من 25 عامًا، بنت <strong>مجموعة EN Trading</strong> سلسلة توريد كاملة وموثوقة للحجر الطبيعي — من المحجر إلى العميل — لخدمة المشترين المحليين والدوليين. يبدأ عملنا بالتوريد والاستخراج المباشر من مناجم مخصصة في جميع أنحاء إيران، ويستمر عبر المعالجة ومراقبة الجودة والتعبئة والشحن الدولي.</p>

                        <h3>لماذا مجموعة EN Trading؟</h3>
                        <p>تتيح لنا خبرتنا الطويلة، إلى جانب الوصول المباشر إلى محاجرنا الخاصة، تقديم جودة ثابتة وألوان موحدة وأسعار تنافسية. تُصدَّر منتجاتنا اليوم — بما في ذلك الترافرتين والرخام والجرانيت والحجر الأنتيك والحجر الجيري والإردواز — إلى أكثر من 50 دولة عبر 5 قارات.</p>

                        <h3>التزامنا بالجودة</h3>
                        <p>يتم فحص كل كتلة من حيث اللون والمسامية وانتظام العروق قبل دخولها مرحلة الإنتاج.</p>

                        <h3>شراكات دولية</h3>
                        <p>يحضر فريق المبيعات لدينا بانتظام المعارض الدولية للحجر للبقاء على اتصال مباشر مع التجار والمقاولين حول العالم.</p>
                        HTML,
                    'hi' => <<<'HTML'
                        <p><strong>EN Trading Group</strong> ने 25 से अधिक वर्षों में प्राकृतिक पत्थर के लिए एक संपूर्ण, भरोसेमंद आपूर्ति श्रृंखला बनाई है — खदान से ग्राहक तक — घरेलू और अंतरराष्ट्रीय खरीदारों दोनों की सेवा करते हुए। हमारा काम ईरान भर की समर्पित खदानों से सोर्सिंग और प्रत्यक्ष निष्कर्षण से शुरू होता है।</p>

                        <h3>EN Trading Group क्यों?</h3>
                        <p>हमारा दीर्घकालिक अनुभव, अपनी खदानों तक सीधी पहुंच के साथ मिलकर, हमें सुसंगत गुणवत्ता, एकसमान रंग और प्रतिस्पर्धी मूल्य निर्धारण प्रदान करने देता है। आज हमारे उत्पाद 5 महाद्वीपों के 50 से अधिक देशों में निर्यात किए जाते हैं।</p>

                        <h3>गुणवत्ता के प्रति हमारी प्रतिबद्धता</h3>
                        <p>उत्पादन में प्रवेश करने से पहले हर ब्लॉक का रंग, सरंध्रता और नस की एकरूपता के लिए निरीक्षण किया जाता है।</p>

                        <h3>अंतरराष्ट्रीय साझेदारी</h3>
                        <p>हमारी बिक्री टीम दुनिया भर के व्यापारियों और ठेकेदारों के साथ सीधे संपर्क में रहने के लिए नियमित रूप से अंतरराष्ट्रीय पत्थर प्रदर्शनियों में भाग लेती है।</p>
                        HTML,
                    'it' => <<<'HTML'
                        <p>Da oltre 25 anni, <strong>EN Trading Group</strong> costruisce una filiera completa e affidabile per la pietra naturale — dalla cava al cliente — servendo acquirenti sia nazionali che internazionali. Il nostro lavoro inizia con l'approvvigionamento e l'estrazione diretta da cave dedicate in tutto l'Iran.</p>

                        <h3>Perché EN Trading Group?</h3>
                        <p>La nostra lunga esperienza, unita all'accesso diretto alle nostre cave, ci permette di offrire qualità costante, colorazione uniforme e prezzi competitivi. Oggi i nostri prodotti vengono esportati in oltre 50 paesi in 5 continenti.</p>

                        <h3>Il Nostro Impegno per la Qualità</h3>
                        <p>Ogni blocco viene ispezionato per colore, porosità e uniformità delle venature prima di entrare in produzione.</p>

                        <h3>Collaborazioni Internazionali</h3>
                        <p>Il nostro team commerciale partecipa regolarmente a fiere internazionali della pietra per rimanere in contatto diretto con commercianti e appaltatori di tutto il mondo.</p>
                        HTML,
                    'zh' => <<<'HTML'
                        <p><strong>EN Trading Group</strong> 拥有超过25年的经验,为国内外买家打造了一条完整、值得信赖的天然石材供应链——从矿山到客户。我们的工作始于在伊朗各地自有矿区的直接采购与开采,并延续至加工、质量控制以及国际包装与运输。</p>

                        <h3>为什么选择 EN Trading Group?</h3>
                        <p>我们悠久的经验,加上对自有矿区的直接掌控,使我们能够提供稳定的品质、均匀的色泽和具有竞争力的价格——省去不必要的中间环节。如今,我们的产品包括洞石、大理石、花岗岩、复古石材、石灰石和板岩,已出口至五大洲50多个国家。</p>

                        <h3>我们对品质的承诺</h3>
                        <p>每一块荒料在进入生产线前都要经过颜色、孔隙率和纹理一致性的检测。成品板材还要经过多道质量控制关卡,确保交付给客户的产品与订单完全一致。</p>

                        <h3>国际合作</h3>
                        <p>我们的销售团队定期参加国际石材展会,与世界各地的贸易商和承包商保持直接联系。这种参展加上免费样品寄送和技术咨询服务,使无论大小项目与我们的合作都变得简单。</p>
                        HTML,
                    'tr' => <<<'HTML'
                        <p><strong>EN Trading Group</strong>, 25 yılı aşkın süredir doğal taş için eksiksiz ve güvenilir bir tedarik zinciri kurmaktadır — ocaktan müşteriye — hem yerli hem de uluslararası alıcılara hizmet vererek. Çalışmalarımız, İran genelindeki kendi ocaklarımızdan doğrudan tedarik ve çıkarma ile başlar; işleme, kalite kontrol ve uluslararası paketleme ile sevkiyata kadar devam eder.</p>

                        <h3>Neden EN Trading Group?</h3>
                        <p>Uzun yıllara dayanan deneyimimiz, kendi ocaklarımıza doğrudan erişimimizle birleşince, gereksiz aracılar olmadan tutarlı kalite, tek tip renk ve rekabetçi fiyatlar sunmamızı sağlıyor. Bugün traverten, mermer, granit, antik taş, kireçtaşı ve arduvaz dahil ürünlerimiz 5 kıtada 50'den fazla ülkeye ihraç edilmektedir.</p>

                        <h3>Kaliteye Bağlılığımız</h3>
                        <p>Her blok, üretime girmeden önce renk, gözeneklilik ve damar tutarlılığı açısından incelenir. Bitmiş plakalar, müşteriye ulaşan ürünün tam olarak sipariş edilen ürün olduğundan emin olmak için birçok kalite kontrol aşamasından geçer.</p>

                        <h3>Uluslararası Ortaklıklar</h3>
                        <p>Satış ekibimiz, dünya çapındaki tüccar ve müteahhitlerle doğrudan temas halinde kalmak için düzenli olarak uluslararası taş fuarlarına katılır. Bu varlık, ücretsiz numune gönderimi ve teknik danışmanlık ile birleşince, her ölçekteki projeler için bizimle çalışmayı kolaylaştırır.</p>
                        HTML,
                ],
                'meta_title' => [
                    'fa' => 'درباره ما | EN Trading Group',
                    'en' => 'About Us | EN Trading Group',
                    'ar' => 'من نحن | EN Trading Group',
                    'hi' => 'हमारे बारे में | EN Trading Group',
                    'it' => 'Chi Siamo | EN Trading Group',
                    'zh' => '关于我们 | EN Trading Group',
                    'tr' => 'Hakkımızda | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بیش از ۲۵ سال تجربه در استخراج، فرآوری و صادرات سنگ‌های طبیعی ایران به بیش از ۵۰ کشور جهان.',
                    'en' => 'Over 25 years of experience extracting, processing, and exporting Iranian natural stone to more than 50 countries.',
                    'ar' => 'أكثر من 25 عامًا من الخبرة في استخراج ومعالجة وتصدير الحجر الطبيعي الإيراني.',
                    'hi' => 'ईरानी प्राकृतिक पत्थर के निष्कर्षण और निर्यात में 25 से अधिक वर्षों का अनुभव।',
                    'it' => 'Oltre 25 anni di esperienza nell\'estrazione ed esportazione di pietra naturale iraniana.',
                    'zh' => '我们拥有超过25年在伊朗天然石材开采、加工和出口方面的经验。',
                    'tr' => 'İran doğal taşlarının çıkarılması ve ihracatında 25 yılı aşkın deneyim.',
                ],
                'template'    => 'sidebar',
                'is_active'   => true,
                'cover_seed'  => 'about-cover.jpg',
            ],

            // ── Certificates ─────────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'گواهینامه‌ها',
                    'en' => 'Certificates',
                    'ar' => 'الشهادات',
                    'hi' => 'प्रमाण पत्र',
                    'it' => 'Certificati',
                    'zh' => '认证证书',
                    'tr' => 'Sertifikalar',
                ],
                'slug' => [
                    'fa' => 'certificates',
                    'en' => 'certificates',
                    'ar' => 'certificates',
                    'hi' => 'certificates',
                    'it' => 'certificates',
                    'zh' => 'certificates',
                    'tr' => 'certificates',
                ],
                'excerpt' => [
                    'fa' => 'استانداردهای کیفیت، آزمایشگاهی و صادراتی که محصولات و فرآیندهای ما براساس آن‌ها ارزیابی می‌شوند.',
                    'en' => 'The quality, laboratory, and export standards our products and processes are assessed against.',
                    'ar' => 'معايير الجودة والمختبرات والتصدير التي يتم تقييم منتجاتنا وعملياتنا وفقًا لها.',
                    'hi' => 'गुणवत्ता, प्रयोगशाला और निर्यात मानक जिनके आधार पर हमारे उत्पादों और प्रक्रियाओं का मूल्यांकन किया जाता है।',
                    'it' => 'Gli standard di qualità, laboratorio ed esportazione in base ai quali vengono valutati i nostri prodotti e processi.',
                    'zh' => '我们产品与流程所依据评估的质量、实验室及出口标准。',
                    'tr' => 'Ürünlerimizin ve süreçlerimizin değerlendirildiği kalite, laboratuvar ve ihracat standartları.',
                ],
                'content' => [
                    'fa' => <<<'HTML'
                        <p>کیفیت برای ما فقط یک ادعا نیست — بخشی از فرآیند تولید است که در هر مرحله، از استخراج تا بسته‌بندی نهایی، با استانداردهای ملی و بین‌المللی سنجیده می‌شود. در ادامه مهم‌ترین گواهینامه‌ها و استانداردهایی که فرآیندهای ما براساس آن‌ها مدیریت می‌شود را می‌بینید.</p>

                        <div class="mt-cert-grid">
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 9001:2015</strong>
                                <span>سیستم مدیریت کیفیت</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 14001:2015</strong>
                                <span>سیستم مدیریت زیست‌محیطی</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>CE Marking — EN 1469</strong>
                                <span>استاندارد اروپایی روکش سنگ طبیعی</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>استاندارد ملی ایران (INSO)</strong>
                                <span>الزامات سنگ‌های ساختمانی</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>مجوز صادرات</strong>
                                <span>وزارت صنعت، معدن و تجارت</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>گزارش آزمایشگاهی</strong>
                                <span>مقاومت فشاری، جذب آب و سایش</span>
                            </div>
                        </div>

                        <p>نسخه دیجیتال هر یک از این مدارک برای مشتریان و شرکای تجاری، در صورت درخواست از طریق صفحه تماس با ما قابل ارسال است.</p>
                        HTML,
                    'en' => <<<'HTML'
                        <p>Quality isn't just a claim for us — it's built into our process and verified at every stage, from extraction to final packaging, against national and international standards. Below are the main certificates and standards our processes are managed against.</p>

                        <div class="mt-cert-grid">
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 9001:2015</strong>
                                <span>Quality Management System</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 14001:2015</strong>
                                <span>Environmental Management System</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>CE Marking — EN 1469</strong>
                                <span>European standard for natural stone cladding</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>Iran National Standard (INSO)</strong>
                                <span>Building stone requirements</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>Export License</strong>
                                <span>Ministry of Industry, Mine &amp; Trade</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>Laboratory Test Reports</strong>
                                <span>Compressive strength, water absorption, abrasion</span>
                            </div>
                        </div>

                        <p>A digital copy of any of these documents can be sent to customers and trade partners on request via our contact page.</p>
                        HTML,
                    'ar' => <<<'HTML'
                        <p>الجودة بالنسبة لنا ليست مجرد ادعاء — إنها جزء من عمليتنا ويتم التحقق منها في كل مرحلة، من الاستخراج إلى التعبئة النهائية، وفقًا للمعايير الوطنية والدولية.</p>

                        <div class="mt-cert-grid">
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 9001:2015</strong>
                                <span>نظام إدارة الجودة</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 14001:2015</strong>
                                <span>نظام الإدارة البيئية</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>علامة CE — EN 1469</strong>
                                <span>المعيار الأوروبي لتكسية الحجر الطبيعي</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>المعيار الوطني الإيراني (INSO)</strong>
                                <span>متطلبات حجر البناء</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>رخصة التصدير</strong>
                                <span>وزارة الصناعة والمعدن والتجارة</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>تقارير الفحص المخبري</strong>
                                <span>مقاومة الضغط، امتصاص الماء، التآكل</span>
                            </div>
                        </div>

                        <p>يمكن إرسال نسخة رقمية من أي من هذه المستندات للعملاء والشركاء التجاريين عند الطلب عبر صفحة الاتصال.</p>
                        HTML,
                    'hi' => <<<'HTML'
                        <p>हमारे लिए गुणवत्ता केवल एक दावा नहीं है — यह हमारी प्रक्रिया का हिस्सा है और निष्कर्षण से लेकर अंतिम पैकेजिंग तक, राष्ट्रीय और अंतरराष्ट्रीय मानकों के अनुसार हर चरण में सत्यापित की जाती है।</p>

                        <div class="mt-cert-grid">
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 9001:2015</strong>
                                <span>गुणवत्ता प्रबंधन प्रणाली</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 14001:2015</strong>
                                <span>पर्यावरण प्रबंधन प्रणाली</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>CE मार्किंग — EN 1469</strong>
                                <span>प्राकृतिक पत्थर क्लैडिंग के लिए यूरोपीय मानक</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ईरान राष्ट्रीय मानक (INSO)</strong>
                                <span>निर्माण पत्थर आवश्यकताएं</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>निर्यात लाइसेंस</strong>
                                <span>उद्योग, खान एवं व्यापार मंत्रालय</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>प्रयोगशाला परीक्षण रिपोर्ट</strong>
                                <span>संपीड़न शक्ति, जल अवशोषण, घर्षण</span>
                            </div>
                        </div>

                        <p>अनुरोध पर इन दस्तावेजों की डिजिटल प्रति हमारे संपर्क पृष्ठ के माध्यम से ग्राहकों और व्यापार भागीदारों को भेजी जा सकती है।</p>
                        HTML,
                    'it' => <<<'HTML'
                        <p>Per noi la qualità non è solo un'affermazione: fa parte del nostro processo e viene verificata in ogni fase, dall'estrazione all'imballaggio finale, secondo standard nazionali e internazionali.</p>

                        <div class="mt-cert-grid">
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 9001:2015</strong>
                                <span>Sistema di Gestione della Qualità</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 14001:2015</strong>
                                <span>Sistema di Gestione Ambientale</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>Marcatura CE — EN 1469</strong>
                                <span>Standard europeo per i rivestimenti in pietra naturale</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>Standard Nazionale Iraniano (INSO)</strong>
                                <span>Requisiti per la pietra da costruzione</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>Licenza di Esportazione</strong>
                                <span>Ministero dell'Industria, delle Miniere e del Commercio</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>Rapporti di Prova di Laboratorio</strong>
                                <span>Resistenza a compressione, assorbimento d'acqua, abrasione</span>
                            </div>
                        </div>

                        <p>Una copia digitale di ciascuno di questi documenti può essere inviata a clienti e partner commerciali su richiesta tramite la nostra pagina di contatto.</p>
                        HTML,
                    'zh' => <<<'HTML'
                        <p>对我们而言,质量不仅仅是一句口号——它贯穿于我们流程的每一个环节,从开采到最终包装,均依照国家和国际标准进行验证。以下是我们流程所遵循的主要认证与标准。</p>

                        <div class="mt-cert-grid">
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 9001:2015</strong>
                                <span>质量管理体系</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 14001:2015</strong>
                                <span>环境管理体系</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>CE Marking — EN 1469</strong>
                                <span>天然石材饰面欧洲标准</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>伊朗国家标准 (INSO)</strong>
                                <span>建筑石材要求</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>出口许可证</strong>
                                <span>工业、矿业与贸易部</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>实验室检测报告</strong>
                                <span>抗压强度、吸水率、耐磨性</span>
                            </div>
                        </div>

                        <p>如有需要,可通过我们的联系页面向客户和贸易伙伴发送这些文件的数字副本。</p>
                        HTML,
                    'tr' => <<<'HTML'
                        <p>Bizim için kalite sadece bir iddia değildir — çıkarımdan nihai ambalajlamaya kadar her aşamada ulusal ve uluslararası standartlara göre doğrulanan sürecimizin bir parçasıdır. Aşağıda süreçlerimizin yönetildiği başlıca sertifikalar ve standartlar yer almaktadır.</p>

                        <div class="mt-cert-grid">
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 9001:2015</strong>
                                <span>Kalite Yönetim Sistemi</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>ISO 14001:2015</strong>
                                <span>Çevre Yönetim Sistemi</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>CE İşareti — EN 1469</strong>
                                <span>Doğal taş kaplama için Avrupa standardı</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>İran Ulusal Standardı (INSO)</strong>
                                <span>Yapı taşı gereklilikleri</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>İhracat Lisansı</strong>
                                <span>Sanayi, Maden ve Ticaret Bakanlığı</span>
                            </div>
                            <div class="mt-cert-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <strong>Laboratuvar Test Raporları</strong>
                                <span>Basınç dayanımı, su emme, aşınma</span>
                            </div>
                        </div>

                        <p>Bu belgelerin dijital bir kopyası, talep üzerine iletişim sayfamız aracılığıyla müşterilere ve ticari ortaklara gönderilebilir.</p>
                        HTML,
                ],
                'meta_title' => [
                    'fa' => 'گواهینامه‌ها | EN Trading Group',
                    'en' => 'Certificates | EN Trading Group',
                    'ar' => 'الشهادات | EN Trading Group',
                    'hi' => 'प्रमाण पत्र | EN Trading Group',
                    'it' => 'Certificati | EN Trading Group',
                    'zh' => '认证证书 | EN Trading Group',
                    'tr' => 'Sertifikalar | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'استانداردهای کیفیت، آزمایشگاهی و صادراتی محصولات و فرآیندهای EN Trading Group.',
                    'en' => 'Quality, laboratory, and export standards behind EN Trading Group products and processes.',
                    'ar' => 'معايير الجودة والمختبرات والتصدير وراء منتجات وعمليات EN Trading Group.',
                    'hi' => 'EN Trading Group उत्पादों और प्रक्रियाओं के पीछे गुणवत्ता, प्रयोगशाला और निर्यात मानक।',
                    'it' => 'Standard di qualità, laboratorio ed esportazione alla base dei prodotti e processi di EN Trading Group.',
                    'zh' => 'EN Trading Group产品与流程所依据的质量、实验室及出口标准。',
                    'tr' => 'EN Trading Group ürün ve süreçlerinin arkasındaki kalite, laboratuvar ve ihracat standartları.',
                ],
                'template'    => 'sidebar',
                'is_active'   => true,
                'cover_seed'  => 'certificates-cover.jpg',
            ],

            // ── Our Mines ────────────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'معادن ما',
                    'en' => 'Our Mines',
                    'ar' => 'مناجمنا',
                    'hi' => 'हमारी खदानें',
                    'it' => 'Le Nostre Cave',
                    'zh' => '我们的矿区',
                    'tr' => 'Ocaklarımız',
                ],
                'slug' => [
                    'fa' => 'our-mines',
                    'en' => 'our-mines',
                    'ar' => 'our-mines',
                    'hi' => 'our-mines',
                    'it' => 'our-mines',
                    'zh' => 'our-mines',
                    'tr' => 'our-mines',
                ],
                'excerpt' => [
                    'fa' => 'دسترسی مستقیم به معادن اختصاصی در نقاط مختلف ایران، پایه و اساس کیفیت ثابت محصولات ماست.',
                    'en' => 'Direct access to dedicated quarries across Iran is the foundation of our consistent product quality.',
                    'ar' => 'الوصول المباشر إلى محاجر مخصصة في جميع أنحاء إيران هو أساس جودة منتجاتنا الثابتة.',
                    'hi' => 'ईरान भर में समर्पित खदानों तक प्रत्यक्ष पहुंच हमारे सुसंगत उत्पाद गुणवत्ता की नींव है।',
                    'it' => 'L\'accesso diretto a cave dedicate in tutto l\'Iran è la base della qualità costante dei nostri prodotti.',
                    'zh' => '直接掌控伊朗各地的自有矿区,是我们产品质量稳定的基础。',
                    'tr' => 'İran genelindeki kendi ocaklarımıza doğrudan erişim, tutarlı ürün kalitemizin temelidir.',
                ],
                'content' => [
                    'fa' => <<<'HTML'
                        <p>دسترسی مستقیم به معادن، مهم‌ترین مزیت رقابتی ماست؛ چون کنترل کیفیت را از همان نقطه استخراج آغاز می‌کند و وابستگی به واسطه‌ها را حذف می‌کند. در ادامه با مهم‌ترین معادن همکار ما آشنا می‌شوید.</p>

                        <div class="mt-mine-list">
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>معدن تراورتن محلات</strong>
                                    <p>استان مرکزی — منبع اصلی تراورتن سفید و عسلی با رگه‌های یکنواخت، مناسب نما و کف‌سازی پروژه‌های بزرگ.</p>
                                    <span class="tag">تراورتن</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>معدن مرمریت آذرشهر</strong>
                                    <p>استان آذربایجان شرقی — تأمین‌کننده مرمریت سفید با درخشندگی بالا، گزینه محبوب برای نما و دکوراسیون داخلی.</p>
                                    <span class="tag">مرمریت</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>معدن انیکس آباده</strong>
                                    <p>استان فارس — منبع انیکس و الاباستر با رگه‌های شفاف، عمدتاً برای پروژه‌های لوکس و نورپردازی پشت سنگ استفاده می‌شود.</p>
                                    <span class="tag">انیکس</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>معدن گرانیت بیرجند</strong>
                                    <p>استان خراسان جنوبی — تأمین‌کننده گرانیت با مقاومت بالا، مناسب کف‌سازی فضای باز و پروژه‌های با ترافیک سنگین.</p>
                                    <span class="tag">گرانیت</span>
                                </div>
                            </div>
                        </div>

                        <p>تیم فنی ما به‌صورت دوره‌ای از این معادن بازدید می‌کند تا کیفیت استخراج و یکنواختی رگه‌ها را پیش از خرید تأیید کند.</p>
                        HTML,
                    'en' => <<<'HTML'
                        <p>Direct access to our quarries is our most important competitive advantage — it lets us control quality starting at the point of extraction and removes dependence on middlemen. Below are the main partner quarries we work with.</p>

                        <div class="mt-mine-list">
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Mahallat Travertine Quarry</strong>
                                    <p>Markazi Province — our main source of white and honey travertine with consistent veining, suited for facades and flooring on large projects.</p>
                                    <span class="tag">Travertine</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Azarshahr Marble Quarry</strong>
                                    <p>East Azerbaijan Province — supplier of high-gloss white marble, a popular choice for facades and interior decoration.</p>
                                    <span class="tag">Marble</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Abadeh Onyx Quarry</strong>
                                    <p>Fars Province — source of onyx and alabaster with translucent veining, mainly used in luxury projects and backlit applications.</p>
                                    <span class="tag">Onyx</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Birjand Granite Quarry</strong>
                                    <p>South Khorasan Province — supplier of high-durability granite, suited for outdoor flooring and high-traffic projects.</p>
                                    <span class="tag">Granite</span>
                                </div>
                            </div>
                        </div>

                        <p>Our technical team periodically visits these quarries to verify extraction quality and vein consistency before purchase.</p>
                        HTML,
                    'ar' => <<<'HTML'
                        <p>الوصول المباشر إلى محاجرنا هو أهم ميزة تنافسية لدينا — فهو يتيح لنا التحكم في الجودة بدءًا من نقطة الاستخراج ويزيل الاعتماد على الوسطاء.</p>

                        <div class="mt-mine-list">
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>محجر ترافرتين محلات</strong>
                                    <p>محافظة مركزي — مصدرنا الرئيسي للترافرتين الأبيض والعسلي بعروق متناسقة.</p>
                                    <span class="tag">ترافرتين</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>محجر رخام آذرشهر</strong>
                                    <p>محافظة أذربيجان الشرقية — مورد للرخام الأبيض عالي اللمعان.</p>
                                    <span class="tag">رخام</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>محجر أونكس آباده</strong>
                                    <p>محافظة فارس — مصدر الأونكس والمرمر الرخامي بعروق شفافة.</p>
                                    <span class="tag">أونكس</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>محجر جرانيت بيرجند</strong>
                                    <p>محافظة خراسان الجنوبية — مورد للجرانيت عالي المتانة.</p>
                                    <span class="tag">جرانيت</span>
                                </div>
                            </div>
                        </div>

                        <p>يزور فريقنا الفني هذه المحاجر بشكل دوري للتحقق من جودة الاستخراج قبل الشراء.</p>
                        HTML,
                    'hi' => <<<'HTML'
                        <p>हमारी खदानों तक प्रत्यक्ष पहुंच हमारी सबसे महत्वपूर्ण प्रतिस्पर्धी बढ़त है — यह हमें निष्कर्षण बिंदु से ही गुणवत्ता नियंत्रित करने देती है और बिचौलियों पर निर्भरता को हटाती है।</p>

                        <div class="mt-mine-list">
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>महल्लात ट्रैवर्टीन खदान</strong>
                                    <p>मरकज़ी प्रांत — सफेद और शहद ट्रैवर्टीन का हमारा मुख्य स्रोत, सुसंगत नसों के साथ।</p>
                                    <span class="tag">ट्रैवर्टीन</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>आज़रशहर मार्बल खदान</strong>
                                    <p>पूर्वी आज़रबैजान प्रांत — उच्च-चमक सफेद मार्बल का आपूर्तिकर्ता।</p>
                                    <span class="tag">मार्बल</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>आबादेह ओनिक्स खदान</strong>
                                    <p>फ़ार्स प्रांत — पारभासी नसों के साथ ओनिक्स और अलबास्टर का स्रोत।</p>
                                    <span class="tag">ओनिक्स</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>बीरजंद ग्रेनाइट खदान</strong>
                                    <p>दक्षिण खुरासान प्रांत — उच्च-स्थायित्व ग्रेनाइट का आपूर्तिकर्ता।</p>
                                    <span class="tag">ग्रेनाइट</span>
                                </div>
                            </div>
                        </div>

                        <p>हमारी तकनीकी टीम खरीद से पहले निष्कर्षण गुणवत्ता सत्यापित करने के लिए समय-समय पर इन खदानों का दौरा करती है।</p>
                        HTML,
                    'it' => <<<'HTML'
                        <p>L'accesso diretto alle nostre cave è il nostro vantaggio competitivo più importante: ci permette di controllare la qualità fin dal punto di estrazione ed elimina la dipendenza da intermediari.</p>

                        <div class="mt-mine-list">
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Cava di Travertino di Mahallat</strong>
                                    <p>Provincia di Markazi — la nostra principale fonte di travertino bianco e miele con venature uniformi.</p>
                                    <span class="tag">Travertino</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Cava di Marmo di Azarshahr</strong>
                                    <p>Provincia dell'Azerbaigian Orientale — fornitore di marmo bianco ad alta lucentezza.</p>
                                    <span class="tag">Marmo</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Cava di Onice di Abadeh</strong>
                                    <p>Provincia del Fars — fonte di onice e alabastro con venature traslucide.</p>
                                    <span class="tag">Onice</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Cava di Granito di Birjand</strong>
                                    <p>Provincia del Khorasan Meridionale — fornitore di granito ad alta durabilità.</p>
                                    <span class="tag">Granito</span>
                                </div>
                            </div>
                        </div>

                        <p>Il nostro team tecnico visita periodicamente queste cave per verificare la qualità dell'estrazione prima dell'acquisto.</p>
                        HTML,
                    'zh' => <<<'HTML'
                        <p>直接掌控自有矿区是我们最重要的竞争优势——这使我们能够从开采源头就开始控制质量,并消除对中间商的依赖。以下是我们主要合作矿区的介绍。</p>

                        <div class="mt-mine-list">
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>马哈拉特洞石矿区</strong>
                                    <p>马尔卡齐省 — 我们白色和蜂蜜色洞石的主要来源,纹理均匀,适用于大型项目的立面和地面铺装。</p>
                                    <span class="tag">洞石</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>阿扎尔沙尔大理石矿区</strong>
                                    <p>东阿塞拜疆省 — 高光泽白色大理石供应商,是立面和室内装饰的热门选择。</p>
                                    <span class="tag">大理石</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>阿巴德玛瑙矿区</strong>
                                    <p>法尔斯省 — 具透明纹理的玛瑙和雪花石供应源,主要用于奢华项目和背光应用。</p>
                                    <span class="tag">玛瑙</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>比尔詹德花岗岩矿区</strong>
                                    <p>南呼罗珊省 — 高耐久性花岗岩供应商,适用于户外地面铺装和高流量项目。</p>
                                    <span class="tag">花岗岩</span>
                                </div>
                            </div>
                        </div>

                        <p>我们的技术团队定期实地考察这些矿区,在采购前确认开采质量和纹理一致性。</p>
                        HTML,
                    'tr' => <<<'HTML'
                        <p>Ocaklarımıza doğrudan erişim, en önemli rekabet avantajımızdır — bu, kaliteyi çıkarım noktasından itibaren kontrol etmemizi sağlar ve aracılara bağımlılığı ortadan kaldırır. Aşağıda birlikte çalıştığımız başlıca ortak ocaklar yer almaktadır.</p>

                        <div class="mt-mine-list">
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Mahallat Traverten Ocağı</strong>
                                    <p>Merkezi Eyaleti — büyük projelerde cephe ve zemin kaplaması için uygun, tutarlı damarlara sahip beyaz ve bal rengi traverteninin ana kaynağımız.</p>
                                    <span class="tag">Traverten</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Azarşehr Mermer Ocağı</strong>
                                    <p>Doğu Azerbaycan Eyaleti — cephe ve iç mekan dekorasyonu için popüler bir seçim olan yüksek parlaklıkta beyaz mermer tedarikçisi.</p>
                                    <span class="tag">Mermer</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Abadeh Oniks Ocağı</strong>
                                    <p>Fars Eyaleti — çoğunlukla lüks projelerde ve arkadan aydınlatmalı uygulamalarda kullanılan, yarı saydam damarlara sahip oniks ve alçıtaşı kaynağı.</p>
                                    <span class="tag">Oniks</span>
                                </div>
                            </div>
                            <div class="mt-mine-card">
                                <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 20 6-12 4 6 3-4 5 10z"/></svg></span>
                                <div>
                                    <strong>Birjand Granit Ocağı</strong>
                                    <p>Güney Horasan Eyaleti — açık hava zemin kaplaması ve yoğun trafikli projeler için uygun, yüksek dayanıklılığa sahip granit tedarikçisi.</p>
                                    <span class="tag">Granit</span>
                                </div>
                            </div>
                        </div>

                        <p>Teknik ekibimiz, satın alma öncesinde çıkarım kalitesini ve damar tutarlılığını doğrulamak için bu ocakları düzenli olarak ziyaret eder.</p>
                        HTML,
                ],
                'meta_title' => [
                    'fa' => 'معادن ما | EN Trading Group',
                    'en' => 'Our Mines | EN Trading Group',
                    'ar' => 'مناجمنا | EN Trading Group',
                    'hi' => 'हमारी खदानें | EN Trading Group',
                    'it' => 'Le Nostre Cave | EN Trading Group',
                    'zh' => '我们的矿区 | EN Trading Group',
                    'tr' => 'Ocaklarımız | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'دسترسی مستقیم به معادن اختصاصی در نقاط مختلف ایران، پایه کیفیت ثابت محصولات ماست.',
                    'en' => 'Direct access to dedicated quarries across Iran is the foundation of our consistent product quality.',
                    'ar' => 'الوصول المباشر إلى محاجر مخصصة في جميع أنحاء إيران هو أساس جودة منتجاتنا.',
                    'hi' => 'ईरान भर में समर्पित खदानों तक प्रत्यक्ष पहुंच हमारे उत्पाद गुणवत्ता की नींव है।',
                    'it' => 'L\'accesso diretto a cave dedicate in tutto l\'Iran è la base della qualità dei nostri prodotti.',
                    'zh' => '直接掌控伊朗各地自有矿区,是我们产品质量稳定的基础。',
                    'tr' => 'İran genelindeki özel ocaklara doğrudan erişim, tutarlı ürün kalitemizin temelidir.',
                ],
                'template'    => 'sidebar',
                'is_active'   => true,
                'cover_seed'  => 'our-mines-cover.jpg',
            ],

            // ── Buying Guide ─────────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'راهنمای خرید',
                    'en' => 'Buying Guide',
                    'ar' => 'دليل الشراء',
                    'hi' => 'खरीदारी गाइड',
                    'it' => 'Guida all\'Acquisto',
                    'zh' => '购买指南',
                    'tr' => 'Satın Alma Rehberi',
                ],
                'slug' => [
                    'fa' => 'buying-guide',
                    'en' => 'buying-guide',
                    'ar' => 'buying-guide',
                    'hi' => 'buying-guide',
                    'it' => 'buying-guide',
                    'zh' => 'buying-guide',
                    'tr' => 'buying-guide',
                ],
                'excerpt' => [
                    'fa' => 'از انتخاب نوع سنگ تا تأیید سفارش و ارسال — هر آنچه برای اولین خرید از ما باید بدانید.',
                    'en' => 'From choosing the right stone type to confirming your order and shipment — everything you need to know for your first purchase from us.',
                    'ar' => 'من اختيار نوع الحجر المناسب إلى تأكيد الطلب والشحن — كل ما تحتاج معرفته لأول عملية شراء منا.',
                    'hi' => 'सही पत्थर के प्रकार को चुनने से लेकर ऑर्डर की पुष्टि और शिपमेंट तक — हमसे पहली खरीद के लिए आपको जो कुछ जानना चाहिए।',
                    'it' => 'Dalla scelta del tipo di pietra giusto alla conferma dell\'ordine e alla spedizione — tutto ciò che devi sapere per il tuo primo acquisto da noi.',
                    'zh' => '从选择石材类型到确认订单和发货——首次向我们购买时您需要了解的一切。',
                    'tr' => 'Doğru taş türünü seçmekten siparişinizi ve sevkiyatınızı onaylamaya kadar — bizden ilk alışverişiniz için bilmeniz gereken her şey.',
                ],
                'content' => [
                    'fa' => <<<'HTML'
                        <p>خرید سنگ طبیعی برای اولین بار می‌تواند سردرگم‌کننده باشد — تنوع محصولات زیاد است، واحدهای اندازه‌گیری فرق دارند، و مسیر سفارش تا تحویل ممکن است مبهم باشد. این راهنما آن مسیر را مرحله به مرحله شفاف می‌کند.</p>

                        <h3>۱. نوع سنگ را مشخص کنید</h3>
                        <p>ابتدا بدانید برای چه کاربردی نیاز دارید: <strong>نما</strong>، <strong>کف داخلی</strong>، <strong>کف فضای باز</strong>، یا <strong>دکوراسیون</strong>؟ هر کاربرد مشخصه‌های فنی خاصی می‌طلبد:</p>
                        <ul>
                            <li><strong>تراورتن</strong> — گزینه اول نمای خارجی؛ سبک، دوام‌پذیر، قیمت رقابتی.</li>
                            <li><strong>مرمریت</strong> — کف و دیوار داخلی پروژه‌های لوکس؛ درخشندگی بالا.</li>
                            <li><strong>گرانیت</strong> — کف فضای باز و صنعتی؛ سخت‌ترین و مقاوم‌ترین.</li>
                            <li><strong>انیکس</strong> — دکوراسیون ویژه و نورپردازی پشت سنگ.</li>
                            <li><strong>سنگ آنتیک</strong> — فضای باز، پروژه‌های کلاسیک.</li>
                        </ul>

                        <h3>۲. ابعاد و واحد را درست تعیین کنید</h3>
                        <p>محصولات ما در دو فرمت اصلی ارائه می‌شوند: <strong>بلوک</strong> (خام، برحسب متر مکعب) و <strong>اسلب / تایل</strong> (فرآوری‌شده، برحسب متر مربع). اگر مطمئن نیستید به کدام نیاز دارید، ابعاد پروژه و نوع استفاده را برای تیم ما توضیح دهید — در محاسبه کمک می‌کنیم.</p>

                        <h3>۳. نمونه درخواست کنید</h3>
                        <p>برای سفارش‌های بالای ۵۰ متر مربع، ارسال نمونه رایگان انجام می‌شود. نمونه زیر نور طبیعی محل پروژه را ارزیابی کنید — رنگ سنگ زیر نور مصنوعی متفاوت دیده می‌شود.</p>

                        <h3>۴. استعلام قیمت و ثبت سفارش</h3>
                        <p>قیمت‌گذاری ما براساس نوع سنگ، درجه کیفیت، ابعاد، و حجم سفارش متفاوت است. برای استعلام رسمی از صفحه <strong>تماس با ما</strong> یا از طریق شماره مستقیم اقدام کنید. پس از توافق قیمت، فاکتور پیش‌پرداخت صادر می‌شود.</p>

                        <h3>۵. پرداخت</h3>
                        <p>برای مشتریان <strong>داخلی</strong>: درگاه‌های پرداخت آنلاین ریالی (زرین‌پال / آیدی‌پی).
                        برای مشتریان <strong>بین‌المللی</strong>: حواله بانکی (Swift/TT) یا آپلود رسید پرداخت برای تأیید دستی.</p>

                        <h3>۶. آماده‌سازی و ارسال</h3>
                        <p>پس از تأیید پرداخت، محصول در بازه ۵ تا ۱۵ روز کاری — بسته به حجم سفارش — بسته‌بندی صادراتی می‌شود. بارنامه و اسناد گمرکی برای تمام محموله‌های صادراتی ارائه می‌شود.</p>

                        <h3>۷. پشتیبانی پس از تحویل</h3>
                        <p>در صورت هرگونه مغایرت با مشخصات سفارش، ظرف ۷ روز از تحویل از طریق صفحه تماس اطلاع دهید. تیم ما پیگیری و رسیدگی خواهد کرد.</p>
                        HTML,
                    'en' => <<<'HTML'
                        <p>Buying natural stone for the first time can feel overwhelming — the variety is wide, units of measurement differ, and the path from order to delivery may not be clear. This guide walks you through that process, step by step.</p>

                        <h3>1. Identify Your Stone Type</h3>
                        <p>Start by knowing your application: <strong>facade</strong>, <strong>interior flooring</strong>, <strong>exterior flooring</strong>, or <strong>decoration</strong>? Each use case calls for different technical properties:</p>
                        <ul>
                            <li><strong>Travertine</strong> — the top choice for exterior facades; lightweight, durable, competitively priced.</li>
                            <li><strong>Marble</strong> — interior flooring and walls for luxury projects; high gloss.</li>
                            <li><strong>Granite</strong> — exterior and industrial flooring; the hardest and most resistant.</li>
                            <li><strong>Onyx</strong> — premium decoration and backlit applications.</li>
                            <li><strong>Antique Stone</strong> — outdoor spaces and classic-style projects.</li>
                        </ul>

                        <h3>2. Define Your Dimensions and Unit</h3>
                        <p>Our products come in two main formats: <strong>blocks</strong> (raw, sold by m³) and <strong>slabs / tiles</strong> (processed, sold by m²). If you're unsure which you need, share your project dimensions and intended use with our team — we'll help with the calculation.</p>

                        <h3>3. Request a Sample</h3>
                        <p>For orders above 50 m², we ship a free sample. Evaluate it under the natural light of your project site — stone color looks different under artificial light.</p>

                        <h3>4. Request a Quote and Place Your Order</h3>
                        <p>Pricing varies by stone type, quality grade, dimensions, and order volume. Use the <strong>Contact Us</strong> page or our direct line for a formal quote. Once the price is agreed, a pro-forma invoice is issued.</p>

                        <h3>5. Payment</h3>
                        <p>For <strong>domestic customers</strong>: online payment gateways (ZarinPal / IDPay).
                        For <strong>international customers</strong>: bank transfer (Swift/TT) or receipt upload for manual approval.</p>

                        <h3>6. Preparation and Shipment</h3>
                        <p>After payment confirmation, your order is packed for export within 5–15 business days depending on volume. A bill of lading and customs documents are provided for all export shipments.</p>

                        <h3>7. After-Delivery Support</h3>
                        <p>If there is any discrepancy with the order specifications, notify us via the contact page within 7 days of delivery. Our team will follow up and resolve the issue.</p>
                        HTML,
                    'ar' => <<<'HTML'
                        <p>قد يبدو شراء الحجر الطبيعي لأول مرة أمرًا مربكًا. يرشدك هذا الدليل خطوة بخطوة خلال العملية بأكملها.</p>

                        <h3>1. حدد نوع الحجر</h3>
                        <p>ابدأ بمعرفة الاستخدام المطلوب: <strong>واجهة</strong>، <strong>أرضية داخلية</strong>، <strong>أرضية خارجية</strong>، أم <strong>ديكور</strong>؟</p>
                        <ul>
                            <li><strong>الترافرتين</strong> — الخيار الأول للواجهات الخارجية.</li>
                            <li><strong>الرخام</strong> — للأرضيات الداخلية والمشاريع الفاخرة.</li>
                            <li><strong>الجرانيت</strong> — للأرضيات الخارجية والصناعية.</li>
                            <li><strong>الأونكس</strong> — للديكور الراقي والإضاءة الخلفية.</li>
                            <li><strong>الحجر الأنتيك</strong> — للمساحات الخارجية والمشاريع الكلاسيكية.</li>
                        </ul>

                        <h3>2. حدد الأبعاد والوحدة</h3>
                        <p>منتجاتنا متاحة في شكل <strong>كتل</strong> (خام، بالمتر المكعب) أو <strong>ألواح/بلاط</strong> (مصنعة، بالمتر المربع). تواصل معنا إذا كنت غير متأكد.</p>

                        <h3>3. اطلب عينة</h3>
                        <p>للطلبات التي تزيد عن 50 م²، نوفر شحن عينة مجاني. قيّم العينة تحت الإضاءة الطبيعية لموقع مشروعك.</p>

                        <h3>4. اطلب عرض سعر وقدّم طلبك</h3>
                        <p>استخدم صفحة <strong>اتصل بنا</strong> للحصول على عرض سعر رسمي. بعد الاتفاق على السعر، يُصدر فاتورة أولية.</p>

                        <h3>5. الدفع</h3>
                        <p>للعملاء <strong>الدوليين</strong>: تحويل بنكي (Swift/TT) أو رفع إيصال للموافقة اليدوية.</p>

                        <h3>6. التحضير والشحن</h3>
                        <p>بعد تأكيد الدفع، يُعبأ طلبك للتصدير خلال 5–15 يوم عمل. يتم توفير بوليصة الشحن والوثائق الجمركية لجميع شحنات التصدير.</p>

                        <h3>7. دعم ما بعد التسليم</h3>
                        <p>في حال وجود أي تعارض مع مواصفات الطلب، أبلغنا عبر صفحة الاتصال خلال 7 أيام من التسليم.</p>
                        HTML,
                    'hi' => <<<'HTML'
                        <p>पहली बार प्राकृतिक पत्थर खरीदना भारी लग सकता है। यह गाइड आपको उस प्रक्रिया में कदम-दर-कदम मार्गदर्शन करता है।</p>

                        <h3>1. अपना पत्थर का प्रकार पहचानें</h3>
                        <p>अपने उपयोग को जानकर शुरू करें: <strong>अग्रभाग</strong>, <strong>इंटीरियर फर्श</strong>, <strong>बाहरी फर्श</strong>, या <strong>सजावट</strong>?</p>
                        <ul>
                            <li><strong>ट्रैवर्टीन</strong> — बाहरी फेसाड के लिए पहली पसंद।</li>
                            <li><strong>मार्बल</strong> — लक्जरी इंटीरियर के लिए।</li>
                            <li><strong>ग्रेनाइट</strong> — बाहरी और औद्योगिक फर्श के लिए।</li>
                            <li><strong>ओनिक्स</strong> — प्रीमियम सजावट और बैकलिट अनुप्रयोगों के लिए।</li>
                        </ul>

                        <h3>2. अपने आयाम और इकाई परिभाषित करें</h3>
                        <p>हमारे उत्पाद <strong>ब्लॉक</strong> (कच्चे, m³ द्वारा) या <strong>स्लैब/टाइल्स</strong> (प्रसंस्कृत, m² द्वारा) में उपलब्ध हैं।</p>

                        <h3>3. नमूना मांगें</h3>
                        <p>50 m² से अधिक के ऑर्डर के लिए, हम मुफ्त नमूना भेजते हैं।</p>

                        <h3>4. उद्धरण अनुरोध करें और ऑर्डर दें</h3>
                        <p>औपचारिक उद्धरण के लिए <strong>हमसे संपर्क करें</strong> पृष्ठ का उपयोग करें।</p>

                        <h3>5. भुगतान</h3>
                        <p><strong>अंतरराष्ट्रीय ग्राहकों</strong> के लिए: बैंक ट्रांसफर (Swift/TT) या मैन्युअल अनुमोदन के लिए रसीद अपलोड।</p>

                        <h3>6. तैयारी और शिपमेंट</h3>
                        <p>भुगतान पुष्टि के बाद, आपका ऑर्डर मात्रा के आधार पर 5–15 कार्य दिवसों में निर्यात के लिए पैक किया जाता है।</p>

                        <h3>7. डिलीवरी के बाद सहायता</h3>
                        <p>यदि ऑर्डर विनिर्देशों में कोई विसंगति है, तो डिलीवरी के 7 दिनों के भीतर संपर्क पृष्ठ के माध्यम से हमें सूचित करें।</p>
                        HTML,
                    'it' => <<<'HTML'
                        <p>Acquistare pietra naturale per la prima volta può sembrare complicato. Questa guida ti accompagna nel processo passo dopo passo.</p>

                        <h3>1. Identifica il Tipo di Pietra</h3>
                        <p>Inizia conoscendo la tua applicazione: <strong>facciata</strong>, <strong>pavimento interno</strong>, <strong>pavimento esterno</strong>, o <strong>decorazione</strong>?</p>
                        <ul>
                            <li><strong>Travertino</strong> — la prima scelta per le facciate esterne.</li>
                            <li><strong>Marmo</strong> — pavimenti e pareti interni per progetti di lusso.</li>
                            <li><strong>Granito</strong> — pavimenti esterni e industriali.</li>
                            <li><strong>Onice</strong> — decorazione premium e applicazioni retroilluminate.</li>
                            <li><strong>Pietra Antica</strong> — spazi esterni e progetti in stile classico.</li>
                        </ul>

                        <h3>2. Definisci le Dimensioni e l'Unità</h3>
                        <p>I nostri prodotti sono disponibili in <strong>blocchi</strong> (grezzi, venduti per m³) o <strong>lastre/piastrelle</strong> (lavorati, venduti per m²).</p>

                        <h3>3. Richiedi un Campione</h3>
                        <p>Per ordini superiori a 50 m², spediamo un campione gratuito. Valutalo sotto la luce naturale del cantiere.</p>

                        <h3>4. Richiedi un Preventivo e Fai il Tuo Ordine</h3>
                        <p>Usa la pagina <strong>Contattaci</strong> per un preventivo formale. Una volta concordato il prezzo, viene emessa una fattura pro-forma.</p>

                        <h3>5. Pagamento</h3>
                        <p>Per i clienti <strong>internazionali</strong>: bonifico bancario (Swift/TT) o caricamento della ricevuta per approvazione manuale.</p>

                        <h3>6. Preparazione e Spedizione</h3>
                        <p>Dopo la conferma del pagamento, il tuo ordine viene imballato per l'esportazione entro 5–15 giorni lavorativi in base al volume.</p>

                        <h3>7. Assistenza Post-Consegna</h3>
                        <p>In caso di discrepanze con le specifiche dell'ordine, contattaci tramite la pagina dei contatti entro 7 giorni dalla consegna.</p>
                        HTML,
                    'zh' => <<<'HTML'
                        <p>第一次购买天然石材可能会让人感到无所适从——产品种类繁多,计量单位各不相同,从下单到交付的流程也可能不够清晰。本指南将逐步为您理清这一过程。</p>

                        <h3>1. 确定您需要的石材类型</h3>
                        <p>首先明确您的使用场景:<strong>立面</strong>、<strong>室内地面</strong>、<strong>室外地面</strong>,还是<strong>装饰</strong>?每种用途都需要不同的技术特性:</p>
                        <ul>
                            <li><strong>洞石</strong> — 室外立面的首选;轻质、耐用、价格具竞争力。</li>
                            <li><strong>大理石</strong> — 用于豪华项目的室内地面和墙面;高光泽度。</li>
                            <li><strong>花岗岩</strong> — 用于室外及工业地面;最坚硬、最耐用。</li>
                            <li><strong>玛瑙</strong> — 用于高端装饰及背光应用。</li>
                            <li><strong>复古石材</strong> — 用于户外空间及古典风格项目。</li>
                        </ul>

                        <h3>2. 正确确定尺寸和单位</h3>
                        <p>我们的产品有两种主要形式:<strong>荒料</strong>(未加工,按立方米计价)和<strong>板材/瓷砖</strong>(已加工,按平方米计价)。如果您不确定需要哪一种,请将项目尺寸和用途告知我们的团队——我们会协助您计算。</p>

                        <h3>3. 申请样品</h3>
                        <p>订单面积超过50平方米时,我们提供免费样品寄送。请在项目现场的自然光下评估样品——石材在人工光源下颜色会有所不同。</p>

                        <h3>4. 询价并下单</h3>
                        <p>我们的定价因石材类型、质量等级、尺寸和订单量而异。如需正式报价,请通过<strong>联系我们</strong>页面或直接联系电话咨询。价格确认后,我们将开具形式发票。</p>

                        <h3>5. 付款</h3>
                        <p>对于<strong>国内客户</strong>:在线里亚尔支付网关(ZarinPal / IDPay)。
                        对于<strong>国际客户</strong>:银行电汇(Swift/TT)或上传付款凭证以进行人工确认。</p>

                        <h3>6. 备货与发货</h3>
                        <p>付款确认后,订单将在5至15个工作日内(视订单量而定)完成出口包装。所有出口货物均提供提单及海关文件。</p>

                        <h3>7. 交付后支持</h3>
                        <p>如订单规格存在任何差异,请在收货后7天内通过联系页面告知我们。我们的团队将跟进并处理。</p>
                        HTML,
                    'tr' => <<<'HTML'
                        <p>Doğal taşı ilk kez satın almak kafa karıştırıcı olabilir — ürün çeşitliliği geniştir, ölçü birimleri farklılık gösterir ve siparişten teslimata kadar olan süreç net olmayabilir. Bu rehber, o süreçte size adım adım yol gösterir.</p>

                        <h3>1. Taş Türünüzü Belirleyin</h3>
                        <p>Öncelikle uygulamanızı belirleyin: <strong>cephe</strong>, <strong>iç mekan zemini</strong>, <strong>dış mekan zemini</strong> veya <strong>dekorasyon</strong>? Her kullanım farklı teknik özellikler gerektirir:</p>
                        <ul>
                            <li><strong>Traverten</strong> — dış cepheler için ilk tercih; hafif, dayanıklı, rekabetçi fiyatlı.</li>
                            <li><strong>Mermer</strong> — lüks projelerde iç mekan zemini ve duvarları; yüksek parlaklık.</li>
                            <li><strong>Granit</strong> — dış mekan ve endüstriyel zeminler; en sert ve en dayanıklı.</li>
                            <li><strong>Oniks</strong> — premium dekorasyon ve arkadan aydınlatmalı uygulamalar.</li>
                            <li><strong>Antik Taş</strong> — dış mekanlar ve klasik tarz projeler.</li>
                        </ul>

                        <h3>2. Boyutlarınızı ve Biriminizi Belirleyin</h3>
                        <p>Ürünlerimiz iki ana formatta sunulur: <strong>bloklar</strong> (ham, m³ olarak satılır) ve <strong>plaka/fayans</strong> (işlenmiş, m² olarak satılır). Hangisine ihtiyacınız olduğundan emin değilseniz, proje boyutlarınızı ve kullanım amacınızı ekibimizle paylaşın — hesaplamada size yardımcı oluruz.</p>

                        <h3>3. Numune Talep Edin</h3>
                        <p>50 m² üzerindeki siparişler için ücretsiz numune gönderiyoruz. Numuneyi proje sahanızın doğal ışığı altında değerlendirin — taş rengi yapay ışık altında farklı görünür.</p>

                        <h3>4. Teklif Alın ve Siparişinizi Verin</h3>
                        <p>Fiyatlandırma taş türüne, kalite derecesine, boyutlara ve sipariş hacmine göre değişir. Resmi bir teklif için <strong>Bize Ulaşın</strong> sayfasını veya doğrudan hattımızı kullanın. Fiyat üzerinde anlaşıldıktan sonra bir proforma fatura düzenlenir.</p>

                        <h3>5. Ödeme</h3>
                        <p><strong>Yerli müşteriler</strong> için: çevrimiçi ödeme ağ geçitleri (ZarinPal / IDPay).
                        <strong>Uluslararası müşteriler</strong> için: banka havalesi (Swift/TT) veya manuel onay için makbuz yükleme.</p>

                        <h3>6. Hazırlık ve Sevkiyat</h3>
                        <p>Ödeme onayının ardından siparişiniz, hacme bağlı olarak 5-15 iş günü içinde ihracat için paketlenir. Tüm ihracat sevkiyatları için konşimento ve gümrük belgeleri sağlanır.</p>

                        <h3>7. Teslimat Sonrası Destek</h3>
                        <p>Sipariş özellikleriyle herhangi bir uyuşmazlık olması durumunda, teslimattan itibaren 7 gün içinde iletişim sayfası üzerinden bize bildirin. Ekibimiz takip edip sorunu çözecektir.</p>
                        HTML,
                ],
                'meta_title' => [
                    'fa' => 'راهنمای خرید سنگ طبیعی | EN Trading Group',
                    'en' => 'Natural Stone Buying Guide | EN Trading Group',
                    'ar' => 'دليل شراء الحجر الطبيعي | EN Trading Group',
                    'hi' => 'प्राकृतिक पत्थर खरीद गाइड | EN Trading Group',
                    'it' => 'Guida all\'Acquisto di Pietra Naturale | EN Trading Group',
                    'zh' => '天然石材购买指南 | EN Trading Group',
                    'tr' => 'Doğal Taş Satın Alma Rehberi | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'از انتخاب نوع سنگ تا پرداخت و ارسال — راهنمای کامل خرید سنگ طبیعی از EN Trading Group.',
                    'en' => 'From choosing the right stone to payment and shipping — the complete guide to buying natural stone from EN Trading Group.',
                    'ar' => 'من اختيار الحجر المناسب إلى الدفع والشحن — الدليل الكامل لشراء الحجر الطبيعي من EN Trading Group.',
                    'hi' => 'सही पत्थर चुनने से लेकर भुगतान और शिपिंग तक — EN Trading Group से प्राकृतिक पत्थर खरीदने की पूरी गाइड।',
                    'it' => 'Dalla scelta della pietra giusta al pagamento e alla spedizione — la guida completa all\'acquisto di pietra naturale da EN Trading Group.',
                    'zh' => '从选择石材类型到付款和发货——从EN Trading Group购买天然石材的完整指南。',
                    'tr' => "Doğru taşı seçmekten ödeme ve sevkiyata kadar — EN Trading Group'tan doğal taş satın almaya yönelik eksiksiz rehber.",
                ],
                'template'    => 'sidebar',
                'is_active'   => true,
                'cover_seed'  => 'buying-guide-cover.jpg',
            ],

            // ── Payment Methods ──────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'روش‌های پرداخت',
                    'en' => 'Payment Methods',
                    'ar' => 'طرق الدفع',
                    'hi' => 'भुगतान के तरीके',
                    'it' => 'Metodi di Pagamento',
                    'zh' => '付款方式',
                    'tr' => 'Ödeme Yöntemleri',
                ],
                'slug' => [
                    'fa' => 'payment-methods',
                    'en' => 'payment-methods',
                    'ar' => 'payment-methods',
                    'hi' => 'payment-methods',
                    'it' => 'payment-methods',
                    'zh' => 'payment-methods',
                    'tr' => 'payment-methods',
                ],
                'excerpt' => [
                    'fa' => 'خریداران داخلی و بین‌المللی می‌توانند از طریق روش‌های مختلف پرداخت، سفارش خود را نهایی کنند.',
                    'en' => 'Domestic and international buyers can finalize their orders through several flexible payment methods.',
                    'ar' => 'يمكن للمشترين المحليين والدوليين إتمام طلباتهم عبر طرق دفع مرنة متعددة.',
                    'hi' => 'घरेलू और अंतरराष्ट्रीय खरीदार कई लचीले भुगतान तरीकों के माध्यम से अपने ऑर्डर को अंतिम रूप दे सकते हैं।',
                    'it' => 'Gli acquirenti nazionali e internazionali possono finalizzare i loro ordini tramite diversi metodi di pagamento flessibili.',
                    'zh' => '国内外买家均可通过多种灵活的付款方式完成订单。',
                    'tr' => 'Yerli ve uluslararası alıcılar, çeşitli esnek ödeme yöntemleriyle siparişlerini tamamlayabilir.',
                ],
                'content' => [
                    'fa' => <<<'HTML'
                        <p>ما تلاش می‌کنیم فرآیند پرداخت را برای هر دو گروه خریداران داخلی و بین‌المللی تا حد امکان ساده و امن نگه داریم. در ادامه روش‌های پرداخت پذیرفته‌شده را می‌بینید.</p>

                        <h3>پرداخت آنلاین ریالی (خریداران داخلی)</h3>
                        <p>خریداران داخلی می‌توانند مبلغ سفارش را از طریق درگاه‌های پرداخت امن زیر پرداخت کنند:</p>
                        <ul>
                            <li><strong>زرین‌پال</strong> — پذیرش تمامی کارت‌های عضو شتاب</li>
                            <li><strong>آیدی‌پی</strong> — پرداخت مستقیم از حساب بانکی</li>
                        </ul>
                        <p>پس از پرداخت موفق، تأییدیه سفارش به‌صورت خودکار صادر می‌شود و پردازش سفارش آغاز می‌گردد.</p>

                        <h3>حواله بانکی بین‌المللی (Swift / TT)</h3>
                        <p>برای خریداران خارج از ایران، روش اصلی پرداخت حواله بانکی بین‌المللی است. پس از توافق قیمت، اطلاعات حساب و فاکتور پیش‌پرداخت (Pro-forma Invoice) برای شما ارسال می‌شود. به‌محض دریافت تأییدیه واریز، پردازش و آماده‌سازی محموله آغاز می‌شود.</p>
                        <ul>
                            <li>ارز پذیرفته‌شده: <strong>USD، EUR، AED</strong></li>
                            <li>مدت زمان تأیید واریز: معمولاً ۱ تا ۳ روز کاری</li>
                        </ul>

                        <h3>آپلود رسید پرداخت</h3>
                        <p>در صورتی که امکان حواله مستقیم وجود ندارد، می‌توانید رسید انتقال وجه را از طریق پنل کاربری آپلود کنید. پس از بررسی و تأیید دستی توسط تیم مالی، سفارش وارد چرخه پردازش می‌شود.</p>

                        <h3>شرایط پرداخت و پیش‌پرداخت</h3>
                        <ul>
                            <li>سفارش‌های زیر ۵۰۰ دلار: <strong>پرداخت کامل پیش از ارسال</strong></li>
                            <li>سفارش‌های ۵۰۰ تا ۵۰۰۰ دلار: <strong>۵۰٪ پیش‌پرداخت + ۵۰٪ پیش از بارگیری</strong></li>
                            <li>سفارش‌های بالای ۵۰۰۰ دلار: <strong>شرایط قابل مذاکره</strong></li>
                        </ul>

                        <h3>امنیت و حریم خصوصی</h3>
                        <p>تمام تراکنش‌های آنلاین با پروتکل SSL رمزنگاری شده‌اند. اطلاعات کارت یا حساب شما در سرورهای ما ذخیره نمی‌شود و پردازش پرداخت کاملاً توسط درگاه‌های معتبر انجام می‌گیرد.</p>

                        <h3>سؤال دارید؟</h3>
                        <p>برای هرگونه سؤال درباره روش پرداخت یا دریافت اطلاعات حساب بانکی با تیم فروش ما از طریق صفحه <a href="/contact">تماس با ما</a> در ارتباط باشید.</p>
                        HTML,
                    'en' => <<<'HTML'
                        <p>We keep the payment process as simple and secure as possible for both domestic and international buyers. Here are the accepted payment methods.</p>

                        <h3>Online Rial Payment (Domestic Buyers)</h3>
                        <p>Domestic buyers can pay via the following secure gateways:</p>
                        <ul>
                            <li><strong>ZarinPal</strong> — accepts all Shetab network cards</li>
                            <li><strong>IDPay</strong> — direct payment from bank account</li>
                        </ul>
                        <p>After a successful payment, an order confirmation is issued automatically and processing begins.</p>

                        <h3>International Bank Transfer (Swift / TT)</h3>
                        <p>For buyers outside Iran, the primary payment method is an international bank transfer. Once the price is agreed, we send you the bank account details and a Pro-forma Invoice. Processing and preparation begin as soon as the transfer is confirmed.</p>
                        <ul>
                            <li>Accepted currencies: <strong>USD, EUR, AED</strong></li>
                            <li>Transfer confirmation time: typically 1–3 business days</li>
                        </ul>

                        <h3>Receipt Upload</h3>
                        <p>If a direct wire transfer isn't possible, you can upload your payment receipt via your account panel. After manual review and approval by our finance team, the order enters the processing cycle.</p>

                        <h3>Payment Terms and Advance Payment</h3>
                        <ul>
                            <li>Orders under $500: <strong>full payment before shipment</strong></li>
                            <li>Orders $500–$5,000: <strong>50% advance + 50% before loading</strong></li>
                            <li>Orders above $5,000: <strong>terms negotiable</strong></li>
                        </ul>

                        <h3>Security and Privacy</h3>
                        <p>All online transactions are SSL-encrypted. Your card or account details are never stored on our servers — payment processing is handled entirely by certified gateways.</p>

                        <h3>Have a Question?</h3>
                        <p>For any questions about payment methods or to receive our bank account details, contact our sales team via the <a href="/en/contact">Contact Us</a> page.</p>
                        HTML,
                    'ar' => <<<'HTML'
                        <p>نحرص على إبقاء عملية الدفع بسيطة وآمنة قدر الإمكان لكل من المشترين المحليين والدوليين.</p>

                        <h3>الدفع الإلكتروني بالريال (للمشترين المحليين)</h3>
                        <p>يمكن للمشترين المحليين الدفع عبر بوابات الدفع الآمنة التالية: <strong>زرين‌بال</strong> و<strong>آيدي‌باي</strong>.</p>

                        <h3>التحويل البنكي الدولي (Swift / TT)</h3>
                        <p>للمشترين خارج إيران، الطريقة الرئيسية للدفع هي التحويل البنكي الدولي. بعد الاتفاق على السعر، نرسل تفاصيل الحساب وفاتورة أولية (Pro-forma Invoice).</p>
                        <ul>
                            <li>العملات المقبولة: <strong>USD، EUR، AED</strong></li>
                            <li>وقت تأكيد التحويل: عادةً 1–3 أيام عمل</li>
                        </ul>

                        <h3>رفع إيصال الدفع</h3>
                        <p>إذا تعذّر التحويل المباشر، يمكنك رفع إيصال الدفع عبر لوحة حسابك. بعد المراجعة والموافقة اليدوية من فريقنا المالي، يدخل الطلب في دورة المعالجة.</p>

                        <h3>شروط الدفع والدفعة المقدمة</h3>
                        <ul>
                            <li>الطلبات دون 500$: <strong>دفع كامل قبل الشحن</strong></li>
                            <li>الطلبات 500$–5,000$: <strong>50% مقدمًا + 50% قبل التحميل</strong></li>
                            <li>الطلبات فوق 5,000$: <strong>شروط قابلة للتفاوض</strong></li>
                        </ul>

                        <h3>الأمان والخصوصية</h3>
                        <p>جميع المعاملات الإلكترونية مشفرة بـ SSL. لا يتم تخزين بيانات بطاقتك أو حسابك على خوادمنا أبدًا.</p>
                        HTML,
                    'hi' => <<<'HTML'
                        <p>हम घरेलू और अंतरराष्ट्रीय दोनों खरीदारों के लिए भुगतान प्रक्रिया को यथासंभव सरल और सुरक्षित रखते हैं।</p>

                        <h3>ऑनलाइन रियाल भुगतान (घरेलू खरीदार)</h3>
                        <p>घरेलू खरीदार निम्नलिखित सुरक्षित गेटवे के माध्यम से भुगतान कर सकते हैं: <strong>ZarinPal</strong> और <strong>IDPay</strong>।</p>

                        <h3>अंतरराष्ट्रीय बैंक ट्रांसफर (Swift / TT)</h3>
                        <p>ईरान के बाहर के खरीदारों के लिए, प्राथमिक भुगतान विधि अंतरराष्ट्रीय बैंक ट्रांसफर है।</p>
                        <ul>
                            <li>स्वीकृत मुद्राएं: <strong>USD, EUR, AED</strong></li>
                            <li>ट्रांसफर पुष्टि समय: आमतौर पर 1–3 व्यावसायिक दिन</li>
                        </ul>

                        <h3>रसीद अपलोड</h3>
                        <p>यदि प्रत्यक्ष वायर ट्रांसफर संभव नहीं है, तो आप अपने खाता पैनल के माध्यम से अपनी भुगतान रसीद अपलोड कर सकते हैं।</p>

                        <h3>भुगतान शर्तें और अग्रिम भुगतान</h3>
                        <ul>
                            <li>$500 से कम के ऑर्डर: <strong>शिपमेंट से पहले पूरा भुगतान</strong></li>
                            <li>$500–$5,000 के ऑर्डर: <strong>50% अग्रिम + लोडिंग से पहले 50%</strong></li>
                            <li>$5,000 से अधिक के ऑर्डर: <strong>शर्तें परक्राम्य</strong></li>
                        </ul>

                        <h3>सुरक्षा और गोपनीयता</h3>
                        <p>सभी ऑनलाइन लेनदेन SSL-एन्क्रिप्टेड हैं। आपके कार्ड या खाते का विवरण कभी भी हमारे सर्वर पर संग्रहीत नहीं किया जाता।</p>
                        HTML,
                    'it' => <<<'HTML'
                        <p>Manteniamo il processo di pagamento il più semplice e sicuro possibile per gli acquirenti nazionali e internazionali.</p>

                        <h3>Pagamento Online in Rial (Acquirenti Nazionali)</h3>
                        <p>Gli acquirenti nazionali possono pagare tramite i seguenti gateway sicuri: <strong>ZarinPal</strong> e <strong>IDPay</strong>.</p>

                        <h3>Bonifico Bancario Internazionale (Swift / TT)</h3>
                        <p>Per gli acquirenti fuori dall'Iran, il metodo di pagamento principale è il bonifico bancario internazionale.</p>
                        <ul>
                            <li>Valute accettate: <strong>USD, EUR, AED</strong></li>
                            <li>Tempi di conferma del bonifico: tipicamente 1–3 giorni lavorativi</li>
                        </ul>

                        <h3>Caricamento della Ricevuta</h3>
                        <p>Se un bonifico diretto non è possibile, puoi caricare la tua ricevuta di pagamento tramite il pannello del tuo account.</p>

                        <h3>Termini di Pagamento e Acconto</h3>
                        <ul>
                            <li>Ordini sotto $500: <strong>pagamento completo prima della spedizione</strong></li>
                            <li>Ordini $500–$5.000: <strong>50% di acconto + 50% prima del carico</strong></li>
                            <li>Ordini oltre $5.000: <strong>termini negoziabili</strong></li>
                        </ul>

                        <h3>Sicurezza e Privacy</h3>
                        <p>Tutte le transazioni online sono crittografate con SSL. I tuoi dati di carta o conto non vengono mai memorizzati sui nostri server.</p>
                        HTML,
                    'zh' => <<<'HTML'
                        <p>我们致力于让国内外买家的付款流程尽可能简单和安全。以下是我们接受的付款方式。</p>

                        <h3>在线里亚尔付款(国内买家)</h3>
                        <p>国内买家可通过以下安全网关付款:</p>
                        <ul>
                            <li><strong>ZarinPal</strong> — 接受所有Shetab网络银行卡</li>
                            <li><strong>IDPay</strong> — 银行账户直接付款</li>
                        </ul>
                        <p>付款成功后,系统将自动开具订单确认书并开始处理订单。</p>

                        <h3>国际银行电汇(Swift / TT)</h3>
                        <p>对于伊朗境外的买家,主要付款方式为国际银行电汇。价格确认后,我们将向您发送银行账户信息和形式发票(Pro-forma Invoice)。收到汇款确认后,即开始处理和备货。</p>
                        <ul>
                            <li>接受币种:<strong>美元、欧元、迪拉姆</strong></li>
                            <li>汇款确认时间:通常为1至3个工作日</li>
                        </ul>

                        <h3>上传付款凭证</h3>
                        <p>如无法直接电汇,您可以通过账户面板上传付款凭证。经我们财务团队人工审核确认后,订单将进入处理流程。</p>

                        <h3>付款条件与预付款</h3>
                        <ul>
                            <li>500美元以下订单:<strong>发货前全额付款</strong></li>
                            <li>500至5000美元订单:<strong>预付50% + 装货前付清50%</strong></li>
                            <li>5000美元以上订单:<strong>条款可协商</strong></li>
                        </ul>

                        <h3>安全与隐私</h3>
                        <p>所有在线交易均采用SSL加密。您的银行卡或账户信息绝不会存储在我们的服务器上——付款处理完全由认证网关完成。</p>

                        <h3>有疑问?</h3>
                        <p>如对付款方式有任何疑问,或需要获取我们的银行账户信息,请通过<a href="/contact">联系我们</a>页面与我们的销售团队联系。</p>
                        HTML,
                    'tr' => <<<'HTML'
                        <p>Hem yerli hem de uluslararası alıcılar için ödeme sürecini mümkün olduğunca basit ve güvenli tutuyoruz. İşte kabul edilen ödeme yöntemleri.</p>

                        <h3>Çevrimiçi Riyal Ödemesi (Yerli Alıcılar)</h3>
                        <p>Yerli alıcılar aşağıdaki güvenli ağ geçitleri üzerinden ödeme yapabilir:</p>
                        <ul>
                            <li><strong>ZarinPal</strong> — tüm Şetab ağı kartlarını kabul eder</li>
                            <li><strong>IDPay</strong> — banka hesabından doğrudan ödeme</li>
                        </ul>
                        <p>Başarılı bir ödemenin ardından sipariş onayı otomatik olarak düzenlenir ve işleme başlanır.</p>

                        <h3>Uluslararası Banka Havalesi (Swift / TT)</h3>
                        <p>İran dışındaki alıcılar için birincil ödeme yöntemi uluslararası banka havalesidir. Fiyat üzerinde anlaşıldıktan sonra size banka hesap bilgilerini ve bir proforma fatura göndeririz. Havale onaylanır onaylanmaz işleme ve hazırlık başlar.</p>
                        <ul>
                            <li>Kabul edilen para birimleri: <strong>USD, EUR, AED</strong></li>
                            <li>Havale onay süresi: genellikle 1-3 iş günü</li>
                        </ul>

                        <h3>Makbuz Yükleme</h3>
                        <p>Doğrudan banka havalesi mümkün değilse, ödeme makbuzunuzu hesap panelinizden yükleyebilirsiniz. Finans ekibimiz tarafından manuel inceleme ve onaydan sonra sipariş işleme döngüsüne girer.</p>

                        <h3>Ödeme Koşulları ve Peşinat</h3>
                        <ul>
                            <li>500 doların altındaki siparişler: <strong>sevkiyattan önce tam ödeme</strong></li>
                            <li>500-5.000 dolar arası siparişler: <strong>%50 peşinat + yüklemeden önce %50</strong></li>
                            <li>5.000 doların üzerindeki siparişler: <strong>koşullar müzakere edilebilir</strong></li>
                        </ul>

                        <h3>Güvenlik ve Gizlilik</h3>
                        <p>Tüm çevrimiçi işlemler SSL ile şifrelenmiştir. Kart veya hesap bilgileriniz sunucularımızda asla saklanmaz — ödeme işlemleri tamamen sertifikalı ağ geçitleri tarafından gerçekleştirilir.</p>

                        <h3>Sorunuz mu var?</h3>
                        <p>Ödeme yöntemleri hakkında herhangi bir sorunuz varsa veya banka hesap bilgilerimizi almak isterseniz, <a href="/contact">Bize Ulaşın</a> sayfası üzerinden satış ekibimizle iletişime geçin.</p>
                        HTML,
                ],
                'meta_title' => [
                    'fa' => 'روش‌های پرداخت | EN Trading Group',
                    'en' => 'Payment Methods | EN Trading Group',
                    'ar' => 'طرق الدفع | EN Trading Group',
                    'hi' => 'भुगतान के तरीके | EN Trading Group',
                    'it' => 'Metodi di Pagamento | EN Trading Group',
                    'zh' => '付款方式 | EN Trading Group',
                    'tr' => 'Ödeme Yöntemleri | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'روش‌های پرداخت داخلی و بین‌المللی EN Trading Group، از درگاه‌های آنلاین تا حواله بانکی.',
                    'en' => 'Domestic and international payment methods at EN Trading Group, from online gateways to bank wire transfer.',
                    'ar' => 'طرق الدفع المحلية والدولية في EN Trading Group، من بوابات الدفع إلى التحويل البنكي.',
                    'hi' => 'EN Trading Group में घरेलू और अंतरराष्ट्रीय भुगतान विधियां, ऑनलाइन गेटवे से बैंक ट्रांसफर तक।',
                    'it' => 'Metodi di pagamento nazionali e internazionali di EN Trading Group, dai gateway online al bonifico bancario.',
                    'zh' => 'EN Trading Group的国内外付款方式,从在线网关到银行电汇。',
                    'tr' => "EN Trading Group'un çevrimiçi ağ geçitlerinden banka havalesine kadar yerli ve uluslararası ödeme yöntemleri.",
                ],
                'template'   => 'sidebar',
                'is_active'  => true,
                'cover_seed' => 'payment-methods-cover.jpg',
            ],

            // ── Shipping ─────────────────────────────────────────────────
            [
                'title' => [
                    'fa' => 'حمل و نقل',
                    'en' => 'Shipping',
                    'ar' => 'الشحن',
                    'hi' => 'शिपिंग',
                    'it' => 'Spedizione',
                    'zh' => '运输',
                    'tr' => 'Sevkiyat',
                ],
                'slug' => [
                    'fa' => 'shipping',
                    'en' => 'shipping',
                    'ar' => 'shipping',
                    'hi' => 'shipping',
                    'it' => 'shipping',
                    'zh' => 'shipping',
                    'tr' => 'shipping',
                ],
                'excerpt' => [
                    'fa' => 'از بارگیری در معدن تا تحویل درب کارخانه یا بندر مقصد — همه‌چیز درباره نحوه ارسال سفارش‌های شما.',
                    'en' => 'From loading at the quarry to delivery at your factory door or destination port — everything about how your orders are shipped.',
                    'ar' => 'من التحميل في المحجر إلى التسليم عند باب مصنعك أو ميناء الوجهة — كل شيء عن كيفية شحن طلباتك.',
                    'hi' => 'खदान पर लोडिंग से आपकी फैक्ट्री के दरवाजे या गंतव्य बंदरगाह तक डिलीवरी — आपके ऑर्डर कैसे भेजे जाते हैं, इसके बारे में सब कुछ।',
                    'it' => 'Dal carico in cava alla consegna alla porta della tua fabbrica o al porto di destinazione — tutto su come vengono spediti i tuoi ordini.',
                    'zh' => '从矿区装货到交付至您的工厂门口或目的港——关于订单运输方式的一切信息。',
                    'tr' => 'Ocakta yüklemeden fabrika kapınıza veya varış limanına teslimata kadar — siparişlerinizin nasıl sevk edildiğine dair her şey.',
                ],
                'content' => [
                    'fa' => <<<'HTML'
                        <p>ما تجربه بیش از ۲۵ سال در لجستیک صادرات سنگ داریم و با شبکه‌ای از شرکت‌های حمل‌ونقل زمینی، دریایی و هوایی کار می‌کنیم تا محموله شما با کمترین هزینه و بیشترین امنیت به مقصد برسد.</p>

                        <h3>روش‌های حمل و نقل</h3>
                        <ul>
                            <li><strong>کانتینر دریایی (FCL / LCL)</strong> — مناسب سفارش‌های بزرگ و صادرات بین‌المللی. بنادر اصلی ارسال: بندرعباس، بندر امام خمینی.</li>
                            <li><strong>کامیون زمینی</strong> — برای کشورهای همسایه (ترکیه، عراق، امارات از طریق مرز) سریع‌تر و اقتصادی‌تر از دریا.</li>
                            <li><strong>هوایی</strong> — برای نمونه‌های تجاری و محموله‌های فوری با وزن کم، از طریق فرودگاه‌های تهران و شیراز.</li>
                        </ul>

                        <h3>اینکوترمز پذیرفته‌شده</h3>
                        <p>سفارش شما می‌تواند براساس هر یک از شرایط زیر تحویل داده شود:</p>
                        <ul>
                            <li><strong>EXW</strong> (تحویل در کارخانه) — حمل‌ونقل کامل بر عهده خریدار</li>
                            <li><strong>FOB</strong> (تحویل در بندر مبدأ) — هزینه بارگیری روی کشتی با فروشنده</li>
                            <li><strong>CIF</strong> (هزینه، بیمه و کرایه حمل) — تمام هزینه تا بندر مقصد با فروشنده</li>
                            <li><strong>DAP</strong> (تحویل در مقصد) — تحویل تا درب انبار یا کارخانه خریدار</li>
                        </ul>

                        <h3>بسته‌بندی صادراتی</h3>
                        <p>اسلب‌ها و تایل‌ها روی <strong>پالت‌های چوبی استاندارد</strong> با دیواره‌های محافظ بسته‌بندی می‌شوند. بلوک‌های خام با <strong>زنجیر و قید فولادی</strong> روی تریلر ثابت می‌شوند. همه محموله‌ها قبل از بارگیری تصویربرداری می‌شوند و گزارش تصویری برای خریدار ارسال می‌گردد.</p>

                        <h3>مدارک صادراتی</h3>
                        <p>برای هر محموله صادراتی مدارک زیر صادر می‌شود:</p>
                        <ul>
                            <li>فاکتور تجاری (Commercial Invoice)</li>
                            <li>لیست بار (Packing List)</li>
                            <li>بارنامه دریایی یا زمینی (B/L یا CMR)</li>
                            <li>گواهی مبدأ (Certificate of Origin — اتاق بازرگانی ایران)</li>
                            <li>گواهی کیفیت (در صورت درخواست)</li>
                        </ul>

                        <h3>زمان‌بندی ارسال</h3>
                        <ul>
                            <li>آماده‌سازی و بسته‌بندی: <strong>۵ تا ۱۵ روز کاری</strong> پس از تأیید پرداخت</li>
                            <li>حمل دریایی به اروپا: <strong>۲۵ تا ۳۵ روز</strong></li>
                            <li>حمل دریایی به خلیج فارس و آسیای جنوب شرقی: <strong>۷ تا ۱۵ روز</strong></li>
                            <li>حمل زمینی به ترکیه و عراق: <strong>۵ تا ۱۰ روز</strong></li>
                        </ul>

                        <h3>بیمه محموله</h3>
                        <p>بیمه باربری برای تمام محموله‌های صادراتی در صورت درخواست خریدار قابل ترتیب است. هزینه بیمه معمولاً ۰.۵ تا ۱٪ ارزش محموله است و در فاکتور نهایی لحاظ می‌شود.</p>

                        <h3>ردیابی محموله</h3>
                        <p>پس از بارگیری، شماره بارنامه و لینک ردیابی کانتینر از طریق ایمیل یا پیام برای شما ارسال می‌شود.</p>
                        HTML,
                    'en' => <<<'HTML'
                        <p>With over 25 years of experience in stone export logistics, we work with a network of road, sea, and air freight partners to make sure your shipment reaches its destination safely and cost-effectively.</p>

                        <h3>Shipping Methods</h3>
                        <ul>
                            <li><strong>Sea Container (FCL / LCL)</strong> — suited for large orders and international exports. Main departure ports: Bandar Abbas, Imam Khomeini Port.</li>
                            <li><strong>Road Freight (Truck)</strong> — faster and more economical than sea for neighboring countries (Turkey, Iraq, UAE via border crossings).</li>
                            <li><strong>Air Freight</strong> — for commercial samples and urgent low-weight shipments, via Tehran and Shiraz airports.</li>
                        </ul>

                        <h3>Accepted Incoterms</h3>
                        <p>Your order can be delivered under any of the following terms:</p>
                        <ul>
                            <li><strong>EXW</strong> (Ex Works) — full transport at the buyer's expense</li>
                            <li><strong>FOB</strong> (Free On Board) — seller covers loading onto the vessel</li>
                            <li><strong>CIF</strong> (Cost, Insurance &amp; Freight) — seller covers all costs to destination port</li>
                            <li><strong>DAP</strong> (Delivered At Place) — delivery to the buyer's warehouse or factory door</li>
                        </ul>

                        <h3>Export Packaging</h3>
                        <p>Slabs and tiles are packed on <strong>standard wooden pallets</strong> with protective edge guards. Raw blocks are secured on trailers with <strong>steel chains and brackets</strong>. All shipments are photographed before loading and a photo report is sent to the buyer.</p>

                        <h3>Export Documents</h3>
                        <p>The following documents are issued for every export shipment:</p>
                        <ul>
                            <li>Commercial Invoice</li>
                            <li>Packing List</li>
                            <li>Bill of Lading or CMR (sea / road)</li>
                            <li>Certificate of Origin (Iran Chamber of Commerce)</li>
                            <li>Quality Certificate (on request)</li>
                        </ul>

                        <h3>Shipping Timeline</h3>
                        <ul>
                            <li>Preparation and packaging: <strong>5–15 business days</strong> after payment confirmation</li>
                            <li>Sea freight to Europe: <strong>25–35 days</strong></li>
                            <li>Sea freight to the Gulf and Southeast Asia: <strong>7–15 days</strong></li>
                            <li>Road freight to Turkey and Iraq: <strong>5–10 days</strong></li>
                        </ul>

                        <h3>Cargo Insurance</h3>
                        <p>Cargo insurance can be arranged for all export shipments on request. Insurance typically costs 0.5–1% of the shipment value and is included in the final invoice.</p>

                        <h3>Shipment Tracking</h3>
                        <p>After loading, the bill of lading number and container tracking link are sent to you by email or message.</p>
                        HTML,
                    'ar' => <<<'HTML'
                        <p>مع أكثر من 25 عامًا من الخبرة في لوجستيات تصدير الحجر، نعمل مع شبكة من شركاء الشحن البري والبحري والجوي لضمان وصول شحنتك بأمان وبتكلفة فعّالة.</p>

                        <h3>طرق الشحن</h3>
                        <ul>
                            <li><strong>حاوية بحرية (FCL / LCL)</strong> — مناسبة للطلبات الكبيرة. موانئ المغادرة الرئيسية: بندر عباس، ميناء الإمام الخميني.</li>
                            <li><strong>شحن بري (شاحنة)</strong> — أسرع وأوفر للدول المجاورة (تركيا، العراق، الإمارات).</li>
                            <li><strong>شحن جوي</strong> — للعينات التجارية والشحنات العاجلة خفيفة الوزن.</li>
                        </ul>

                        <h3>شروط التسليم (Incoterms)</h3>
                        <ul>
                            <li><strong>EXW</strong> — النقل الكامل على عاتق المشتري</li>
                            <li><strong>FOB</strong> — يتحمل البائع تكاليف الشحن على متن السفينة</li>
                            <li><strong>CIF</strong> — يتحمل البائع جميع التكاليف حتى ميناء الوجهة</li>
                            <li><strong>DAP</strong> — التسليم حتى مستودع المشتري</li>
                        </ul>

                        <h3>التغليف للتصدير</h3>
                        <p>تُعبأ الألواح والبلاطات على <strong>منصات خشبية قياسية</strong> مع حواف واقية. تُثبَّت الكتل الخام بـ<strong>سلاسل وأقواس فولادية</strong>. تُصوَّر جميع الشحنات قبل التحميل.</p>

                        <h3>مستندات التصدير</h3>
                        <ul>
                            <li>الفاتورة التجارية</li>
                            <li>قائمة التعبئة</li>
                            <li>سند الشحن (B/L أو CMR)</li>
                            <li>شهادة المنشأ (غرفة تجارة إيران)</li>
                            <li>شهادة الجودة (عند الطلب)</li>
                        </ul>

                        <h3>الجدول الزمني للشحن</h3>
                        <ul>
                            <li>التحضير والتعبئة: <strong>5–15 يوم عمل</strong> بعد تأكيد الدفع</li>
                            <li>الشحن البحري إلى أوروبا: <strong>25–35 يومًا</strong></li>
                            <li>الشحن البحري إلى الخليج وجنوب شرق آسيا: <strong>7–15 يومًا</strong></li>
                            <li>الشحن البري إلى تركيا والعراق: <strong>5–10 أيام</strong></li>
                        </ul>

                        <h3>تأمين البضائع</h3>
                        <p>يمكن ترتيب تأمين البضائع لجميع شحنات التصدير عند الطلب، بتكلفة تتراوح بين 0.5–1% من قيمة الشحنة.</p>
                        HTML,
                    'hi' => <<<'HTML'
                        <p>पत्थर निर्यात लॉजिस्टिक्स में 25 से अधिक वर्षों के अनुभव के साथ, हम सड़क, समुद्र और वायु माल भागीदारों के नेटवर्क के साथ काम करते हैं।</p>

                        <h3>शिपिंग के तरीके</h3>
                        <ul>
                            <li><strong>समुद्री कंटेनर (FCL / LCL)</strong> — बड़े ऑर्डर के लिए उपयुक्त। मुख्य प्रस्थान बंदरगाह: बंदर अब्बास।</li>
                            <li><strong>सड़क माल (ट्रक)</strong> — पड़ोसी देशों के लिए तेज़ और अधिक किफायती।</li>
                            <li><strong>हवाई माल</strong> — वाणिज्यिक नमूनों और तत्काल कम-वजन शिपमेंट के लिए।</li>
                        </ul>

                        <h3>स्वीकृत Incoterms</h3>
                        <ul>
                            <li><strong>EXW</strong> — खरीदार की कीमत पर पूर्ण परिवहन</li>
                            <li><strong>FOB</strong> — विक्रेता जहाज पर लोडिंग को कवर करता है</li>
                            <li><strong>CIF</strong> — विक्रेता गंतव्य बंदरगाह तक सभी लागत कवर करता है</li>
                            <li><strong>DAP</strong> — खरीदार के गोदाम या फैक्ट्री दरवाजे तक डिलीवरी</li>
                        </ul>

                        <h3>निर्यात पैकेजिंग</h3>
                        <p>स्लैब और टाइलें <strong>मानक लकड़ी के पैलेट</strong> पर सुरक्षात्मक किनारे के साथ पैक की जाती हैं। सभी शिपमेंट लोडिंग से पहले फोटोग्राफ किए जाते हैं।</p>

                        <h3>शिपिंग समयरेखा</h3>
                        <ul>
                            <li>तैयारी और पैकेजिंग: भुगतान पुष्टि के बाद <strong>5–15 व्यावसायिक दिन</strong></li>
                            <li>यूरोप के लिए समुद्री माल: <strong>25–35 दिन</strong></li>
                            <li>खाड़ी और दक्षिण पूर्व एशिया के लिए समुद्री माल: <strong>7–15 दिन</strong></li>
                            <li>तुर्की और इराक के लिए सड़क माल: <strong>5–10 दिन</strong></li>
                        </ul>

                        <h3>कार्गो बीमा</h3>
                        <p>अनुरोध पर सभी निर्यात शिपमेंट के लिए कार्गो बीमा की व्यवस्था की जा सकती है। बीमा आमतौर पर शिपमेंट मूल्य का 0.5–1% होता है।</p>
                        HTML,
                    'it' => <<<'HTML'
                        <p>Con oltre 25 anni di esperienza nella logistica di esportazione della pietra, lavoriamo con una rete di partner di trasporto su strada, via mare e via aerea.</p>

                        <h3>Metodi di Spedizione</h3>
                        <ul>
                            <li><strong>Container Marittimo (FCL / LCL)</strong> — adatto per ordini grandi. Principali porti di partenza: Bandar Abbas, Porto Imam Khomeini.</li>
                            <li><strong>Trasporto su Strada (Camion)</strong> — più veloce ed economico per i paesi vicini.</li>
                            <li><strong>Trasporto Aereo</strong> — per campioni commerciali e spedizioni urgenti di piccolo peso.</li>
                        </ul>

                        <h3>Incoterms Accettati</h3>
                        <ul>
                            <li><strong>EXW</strong> — trasporto completo a carico dell'acquirente</li>
                            <li><strong>FOB</strong> — il venditore copre il carico sulla nave</li>
                            <li><strong>CIF</strong> — il venditore copre tutti i costi fino al porto di destinazione</li>
                            <li><strong>DAP</strong> — consegna al magazzino o alla porta della fabbrica dell'acquirente</li>
                        </ul>

                        <h3>Imballaggio per l'Esportazione</h3>
                        <p>Lastre e piastrelle vengono imballate su <strong>pallet standard in legno</strong> con bordi protettivi. I blocchi grezzi sono fissati su rimorchi con <strong>catene e staffe in acciaio</strong>. Tutte le spedizioni vengono fotografate prima del carico.</p>

                        <h3>Documenti di Esportazione</h3>
                        <ul>
                            <li>Fattura Commerciale</li>
                            <li>Packing List</li>
                            <li>Polizza di Carico o CMR (mare / strada)</li>
                            <li>Certificato di Origine (Camera di Commercio dell'Iran)</li>
                            <li>Certificato di Qualità (su richiesta)</li>
                        </ul>

                        <h3>Tempi di Spedizione</h3>
                        <ul>
                            <li>Preparazione e imballaggio: <strong>5–15 giorni lavorativi</strong> dopo la conferma del pagamento</li>
                            <li>Trasporto marittimo verso l'Europa: <strong>25–35 giorni</strong></li>
                            <li>Trasporto marittimo verso il Golfo e il Sud-Est asiatico: <strong>7–15 giorni</strong></li>
                            <li>Trasporto su strada verso Turchia e Iraq: <strong>5–10 giorni</strong></li>
                        </ul>

                        <h3>Assicurazione del Carico</h3>
                        <p>L'assicurazione del carico può essere organizzata per tutte le spedizioni su richiesta, con un costo tipico dello 0,5–1% del valore della spedizione.</p>
                        HTML,
                    'zh' => <<<'HTML'
                        <p>我们拥有超过25年的石材出口物流经验,与陆运、海运和空运合作伙伴组成的网络合作,确保您的货物安全、经济地抵达目的地。</p>

                        <h3>运输方式</h3>
                        <ul>
                            <li><strong>海运集装箱(整箱/拼箱)</strong> — 适用于大宗订单和国际出口。主要发货港口:阿巴斯港、霍梅尼港。</li>
                            <li><strong>陆运卡车</strong> — 对于邻国(土耳其、伊拉克、阿联酋,经陆路口岸)比海运更快、更经济。</li>
                            <li><strong>空运</strong> — 适用于商业样品和低重量紧急货物,经由德黑兰和设拉子机场。</li>
                        </ul>

                        <h3>接受的国际贸易术语(Incoterms)</h3>
                        <p>您的订单可按以下任一条款交付:</p>
                        <ul>
                            <li><strong>EXW</strong>(工厂交货)— 全部运输费用由买方承担</li>
                            <li><strong>FOB</strong>(船上交货)— 卖方承担装船费用</li>
                            <li><strong>CIF</strong>(成本、保险费加运费)— 卖方承担至目的港的全部费用</li>
                            <li><strong>DAP</strong>(目的地交货)— 交付至买方仓库或工厂门口</li>
                        </ul>

                        <h3>出口包装</h3>
                        <p>板材和瓷砖采用<strong>标准木托盘</strong>包装,并配有防护边角。荒料则用<strong>钢链和支架</strong>固定在拖车上。所有货物在装货前均会拍照,并向买方发送图片报告。</p>

                        <h3>出口单证</h3>
                        <p>每批出口货物均会开具以下单证:</p>
                        <ul>
                            <li>商业发票(Commercial Invoice)</li>
                            <li>装箱单(Packing List)</li>
                            <li>海运或陆运提单(B/L 或 CMR)</li>
                            <li>原产地证书(伊朗商会出具)</li>
                            <li>质量证书(应要求提供)</li>
                        </ul>

                        <h3>运输时间表</h3>
                        <ul>
                            <li>备货与包装:付款确认后 <strong>5至15个工作日</strong></li>
                            <li>海运至欧洲:<strong>25至35天</strong></li>
                            <li>海运至海湾地区及东南亚:<strong>7至15天</strong></li>
                            <li>陆运至土耳其和伊拉克:<strong>5至10天</strong></li>
                        </ul>

                        <h3>货物保险</h3>
                        <p>应买方要求,可为所有出口货物安排货运保险。保险费用通常为货值的0.5%至1%,并计入最终发票。</p>

                        <h3>货物追踪</h3>
                        <p>装货后,提单号和集装箱追踪链接将通过电子邮件或消息发送给您。</p>
                        HTML,
                    'tr' => <<<'HTML'
                        <p>Taş ihracat lojistiğinde 25 yılı aşkın deneyimimizle, sevkiyatınızın güvenli ve uygun maliyetli bir şekilde varış noktasına ulaşmasını sağlamak için kara, deniz ve hava taşımacılığı ortaklarından oluşan bir ağla çalışıyoruz.</p>

                        <h3>Sevkiyat Yöntemleri</h3>
                        <ul>
                            <li><strong>Deniz Konteyneri (FCL / LCL)</strong> — büyük siparişler ve uluslararası ihracatlar için uygundur. Başlıca kalkış limanları: Bender Abbas, İmam Humeyni Limanı.</li>
                            <li><strong>Karayolu Taşımacılığı (Kamyon)</strong> — komşu ülkeler için (Türkiye, Irak, BAE sınır geçişleri yoluyla) denizden daha hızlı ve ekonomiktir.</li>
                            <li><strong>Hava Kargo</strong> — ticari numuneler ve acil düşük ağırlıklı sevkiyatlar için, Tahran ve Şiraz havalimanları üzerinden.</li>
                        </ul>

                        <h3>Kabul Edilen Incoterms</h3>
                        <p>Siparişiniz aşağıdaki koşullardan herhangi biri altında teslim edilebilir:</p>
                        <ul>
                            <li><strong>EXW</strong> (Fabrika Teslim) — tüm nakliye alıcının sorumluluğundadır</li>
                            <li><strong>FOB</strong> (Gemi Güvertesi Teslim) — satıcı gemiye yükleme masraflarını karşılar</li>
                            <li><strong>CIF</strong> (Maliyet, Sigorta ve Navlun) — satıcı varış limanına kadar tüm masrafları karşılar</li>
                            <li><strong>DAP</strong> (Belirtilen Yerde Teslim) — alıcının deposu veya fabrika kapısına teslim</li>
                        </ul>

                        <h3>İhracat Ambalajı</h3>
                        <p>Plakalar ve fayanslar, koruyucu kenar takviyeli <strong>standart ahşap paletler</strong> üzerinde paketlenir. Ham bloklar, <strong>çelik zincir ve braketlerle</strong> römorklara sabitlenir. Tüm sevkiyatlar yüklemeden önce fotoğraflanır ve alıcıya bir fotoğraf raporu gönderilir.</p>

                        <h3>İhracat Belgeleri</h3>
                        <p>Her ihracat sevkiyatı için aşağıdaki belgeler düzenlenir:</p>
                        <ul>
                            <li>Ticari Fatura</li>
                            <li>Çeki Listesi</li>
                            <li>Konşimento veya CMR (deniz / kara)</li>
                            <li>Menşe Şahadetnamesi (İran Ticaret Odası)</li>
                            <li>Kalite Sertifikası (talep üzerine)</li>
                        </ul>

                        <h3>Sevkiyat Zaman Çizelgesi</h3>
                        <ul>
                            <li>Hazırlık ve ambalajlama: ödeme onayından sonra <strong>5-15 iş günü</strong></li>
                            <li>Avrupa'ya deniz taşımacılığı: <strong>25-35 gün</strong></li>
                            <li>Körfez ve Güneydoğu Asya'ya deniz taşımacılığı: <strong>7-15 gün</strong></li>
                            <li>Türkiye ve Irak'a karayolu taşımacılığı: <strong>5-10 gün</strong></li>
                        </ul>

                        <h3>Kargo Sigortası</h3>
                        <p>Talep üzerine tüm ihracat sevkiyatları için kargo sigortası düzenlenebilir. Sigorta genellikle sevkiyat değerinin %0,5-1'i kadardır ve nihai faturaya dahil edilir.</p>

                        <h3>Sevkiyat Takibi</h3>
                        <p>Yükleme sonrasında konşimento numarası ve konteyner takip bağlantısı size e-posta veya mesaj yoluyla gönderilir.</p>
                        HTML,
                ],
                'meta_title' => [
                    'fa' => 'حمل و نقل | EN Trading Group',
                    'en' => 'Shipping | EN Trading Group',
                    'ar' => 'الشحن | EN Trading Group',
                    'hi' => 'शिपिंग | EN Trading Group',
                    'it' => 'Spedizione | EN Trading Group',
                    'zh' => '运输 | EN Trading Group',
                    'tr' => 'Sevkiyat | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'روش‌های حمل و نقل، اینکوترمز، مدارک صادراتی و زمان‌بندی ارسال سفارش‌های سنگ EN Trading Group.',
                    'en' => 'Shipping methods, Incoterms, export documents, and order dispatch timeline at EN Trading Group.',
                    'ar' => 'طرق الشحن وشروط التسليم ومستندات التصدير والجدول الزمني لإرسال الطلبات في EN Trading Group.',
                    'hi' => 'EN Trading Group में शिपिंग के तरीके, Incoterms, निर्यात दस्तावेज़ और ऑर्डर डिस्पैच समयरेखा।',
                    'it' => 'Metodi di spedizione, Incoterms, documenti di esportazione e tempistiche di invio ordini di EN Trading Group.',
                    'zh' => 'EN Trading Group的运输方式、国际贸易术语、出口单证及订单发货时间表。',
                    'tr' => "EN Trading Group'ta sevkiyat yöntemleri, Incoterms, ihracat belgeleri ve sipariş sevkiyat zaman çizelgesi.",
                ],
                'template'   => 'sidebar',
                'is_active'  => true,
                'cover_seed' => 'shipping-cover.jpg',
            ],
        ];

        foreach ($pages as $data) {
            $coverSeed = $data['cover_seed'];
            unset($data['cover_seed']);

            $page = Page::updateOrCreate(
                ['slug->fa' => $data['slug']['fa']],
                array_merge($data, ['views_count' => rand(80, 900)])
            );

            if ($page->getMedia('cover')->isEmpty()) {
                $coverPath = storage_path('app/seeders/pages/' . $coverSeed);
                if (file_exists($coverPath)) {
                    $page->addMedia($coverPath)
                        ->preservingOriginal()
                        ->toMediaCollection('cover');
                } else {
                    $this->command->warn("Missing cover image: storage/app/seeders/pages/{$coverSeed} (page will fall back to a placeholder)");
                }
            }
        }

        $this->command->info(count($pages) . ' page(s) seeded.');
    }
}
