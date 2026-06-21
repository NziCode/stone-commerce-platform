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
                ],
                'slug' => [
                    'fa' => 'about',
                    'en' => 'about',
                    'ar' => 'about',
                    'hi' => 'about',
                    'it' => 'about',
                ],
                'excerpt' => [
                    'fa' => 'بیش از ۲۵ سال تجربه در استخراج، فرآوری و صادرات سنگ‌های طبیعی ایران به بیش از ۵۰ کشور جهان.',
                    'en' => 'Over 25 years of experience extracting, processing, and exporting Iranian natural stone to more than 50 countries.',
                    'ar' => 'أكثر من 25 عامًا من الخبرة في استخراج ومعالجة وتصدير الحجر الطبيعي الإيراني إلى أكثر من 50 دولة.',
                    'hi' => 'ईरानी प्राकृतिक पत्थर के निष्कर्षण, प्रसंस्करण और 50 से अधिक देशों में निर्यात में 25 से अधिक वर्षों का अनुभव।',
                    'it' => 'Oltre 25 anni di esperienza nell\'estrazione, lavorazione ed esportazione di pietra naturale iraniana in oltre 50 paesi.',
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
                ],
                'meta_title' => [
                    'fa' => 'درباره ما | EN Trading Group',
                    'en' => 'About Us | EN Trading Group',
                    'ar' => 'من نحن | EN Trading Group',
                    'hi' => 'हमारे बारे में | EN Trading Group',
                    'it' => 'Chi Siamo | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'بیش از ۲۵ سال تجربه در استخراج، فرآوری و صادرات سنگ‌های طبیعی ایران به بیش از ۵۰ کشور جهان.',
                    'en' => 'Over 25 years of experience extracting, processing, and exporting Iranian natural stone to more than 50 countries.',
                    'ar' => 'أكثر من 25 عامًا من الخبرة في استخراج ومعالجة وتصدير الحجر الطبيعي الإيراني.',
                    'hi' => 'ईरानी प्राकृतिक पत्थर के निष्कर्षण और निर्यात में 25 से अधिक वर्षों का अनुभव।',
                    'it' => 'Oltre 25 anni di esperienza nell\'estrazione ed esportazione di pietra naturale iraniana.',
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
                ],
                'slug' => [
                    'fa' => 'certificates',
                    'en' => 'certificates',
                    'ar' => 'certificates',
                    'hi' => 'certificates',
                    'it' => 'certificates',
                ],
                'excerpt' => [
                    'fa' => 'استانداردهای کیفیت، آزمایشگاهی و صادراتی که محصولات و فرآیندهای ما براساس آن‌ها ارزیابی می‌شوند.',
                    'en' => 'The quality, laboratory, and export standards our products and processes are assessed against.',
                    'ar' => 'معايير الجودة والمختبرات والتصدير التي يتم تقييم منتجاتنا وعملياتنا وفقًا لها.',
                    'hi' => 'गुणवत्ता, प्रयोगशाला और निर्यात मानक जिनके आधार पर हमारे उत्पादों और प्रक्रियाओं का मूल्यांकन किया जाता है।',
                    'it' => 'Gli standard di qualità, laboratorio ed esportazione in base ai quali vengono valutati i nostri prodotti e processi.',
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
                ],
                'meta_title' => [
                    'fa' => 'گواهینامه‌ها | EN Trading Group',
                    'en' => 'Certificates | EN Trading Group',
                    'ar' => 'الشهادات | EN Trading Group',
                    'hi' => 'प्रमाण पत्र | EN Trading Group',
                    'it' => 'Certificati | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'استانداردهای کیفیت، آزمایشگاهی و صادراتی محصولات و فرآیندهای EN Trading Group.',
                    'en' => 'Quality, laboratory, and export standards behind EN Trading Group products and processes.',
                    'ar' => 'معايير الجودة والمختبرات والتصدير وراء منتجات وعمليات EN Trading Group.',
                    'hi' => 'EN Trading Group उत्पादों और प्रक्रियाओं के पीछे गुणवत्ता, प्रयोगशाला और निर्यात मानक।',
                    'it' => 'Standard di qualità, laboratorio ed esportazione alla base dei prodotti e processi di EN Trading Group.',
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
                ],
                'slug' => [
                    'fa' => 'our-mines',
                    'en' => 'our-mines',
                    'ar' => 'our-mines',
                    'hi' => 'our-mines',
                    'it' => 'our-mines',
                ],
                'excerpt' => [
                    'fa' => 'دسترسی مستقیم به معادن اختصاصی در نقاط مختلف ایران، پایه و اساس کیفیت ثابت محصولات ماست.',
                    'en' => 'Direct access to dedicated quarries across Iran is the foundation of our consistent product quality.',
                    'ar' => 'الوصول المباشر إلى محاجر مخصصة في جميع أنحاء إيران هو أساس جودة منتجاتنا الثابتة.',
                    'hi' => 'ईरान भर में समर्पित खदानों तक प्रत्यक्ष पहुंच हमारे सुसंगत उत्पाद गुणवत्ता की नींव है।',
                    'it' => 'L\'accesso diretto a cave dedicate in tutto l\'Iran è la base della qualità costante dei nostri prodotti.',
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
                ],
                'meta_title' => [
                    'fa' => 'معادن ما | EN Trading Group',
                    'en' => 'Our Mines | EN Trading Group',
                    'ar' => 'مناجمنا | EN Trading Group',
                    'hi' => 'हमारी खदानें | EN Trading Group',
                    'it' => 'Le Nostre Cave | EN Trading Group',
                ],
                'meta_description' => [
                    'fa' => 'دسترسی مستقیم به معادن اختصاصی در نقاط مختلف ایران، پایه کیفیت ثابت محصولات ماست.',
                    'en' => 'Direct access to dedicated quarries across Iran is the foundation of our consistent product quality.',
                    'ar' => 'الوصول المباشر إلى محاجر مخصصة في جميع أنحاء إيران هو أساس جودة منتجاتنا.',
                    'hi' => 'ईरान भर में समर्पित खदानों तक प्रत्यक्ष पहुंच हमारे उत्पाद गुणवत्ता की नींव है।',
                    'it' => 'L\'accesso diretto a cave dedicate in tutto l\'Iran è la base della qualità dei nostri prodotti.',
                ],
                'template'    => 'sidebar',
                'is_active'   => true,
                'cover_seed'  => 'our-mines-cover.jpg',
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
