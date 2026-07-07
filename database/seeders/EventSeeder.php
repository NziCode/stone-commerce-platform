<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizerId = User::first()?->id;

        $events = [

            // ── Finished: Participation in 17th Mahallat Stone Expo ──────
            [
                'title' => [
                    'fa' => 'حضور Stone Commerce در هفدهمین نمایشگاه بین‌المللی سنگ ایران',
                    'en' => 'Stone Commerce at the 17th Iran International Stone Exhibition',
                    'ar' => 'حضور Stone Commerce في المعرض الدولي السابع عشر لحجر إيران',
                    'hi' => '17वें ईरान अंतरराष्ट्रीय पत्थर प्रदर्शनी में Stone Commerce',
                    'it' => 'Stone Commerce alla 17ª Fiera Internazionale della Pietra Iraniana',
                    'zh' => 'Stone Commerce 参加第17届伊朗国际石材展',
                    'tr' => 'Stone Commerce 17. İran Uluslararası Taş Fuarı\'nda',
                ],
                'slug' => [
                    'fa' => 'hozoor-namayeshgah-sang-mahallat-17',
                    'en' => 'mahallat-stone-expo-17',
                    'ar' => 'mahallat-stone-expo-17',
                    'hi' => 'mahallat-stone-expo-17',
                    'it' => 'mahallat-stone-expo-17',
                    'zh' => 'mahallat-stone-expo-17',
                    'tr' => 'mahallat-stone-expo-17',
                ],
                'description' => [
                    'fa' => 'Stone Commerce در هفدهمین نمایشگاه بین‌المللی سنگ ایران، برگزار شده در سایت دائمی نمایشگاه نیم‌ور محلات، با غرفه‌ای اختصاصی حضور یافت. در این رویداد، جدیدترین بلوک‌های تراورتن سفید و عسلی شرکت به نمایش گذاشته شد و تیم فروش با ده‌ها بازرگان داخلی و خارجی دیدار و مذاکره کرد. این حضور فرصتی برای معرفی توان تولید و کیفیت محصولات شرکت به بازارهای جدید بود.',
                    'en' => 'Stone Commerce participated in the 17th Iran International Stone Exhibition, held at the permanent Nimvar exhibition site in Mahallat, with a dedicated booth. The company showcased its latest white and honey travertine blocks and the sales team met with dozens of domestic and international traders. The participation provided an opportunity to introduce the company\'s production capacity and product quality to new markets.',
                    'ar' => 'شاركت Stone Commerce في المعرض الدولي السابع عشر لحجر إيران، الذي أُقيم في موقع نیم‌ور الدائم بمحلات، بجناح مخصص. عرضت الشركة أحدث كتل الترافرتين الأبيض والعسلي.',
                    'hi' => 'Stone Commerce ने महल्लात के स्थायी निमवर प्रदर्शनी स्थल पर आयोजित 17वें ईरान अंतरराष्ट्रीय पत्थर प्रदर्शनी में एक समर्पित स्टॉल के साथ भाग लिया।',
                    'it' => 'Stone Commerce ha partecipato alla 17ª Fiera Internazionale della Pietra Iraniana, tenutasi presso il sito espositivo permanente di Nimvar a Mahallat, con uno stand dedicato.',
                    'zh' => 'Stone Commerce参加了在马哈拉特尼姆瓦尔国际石材展览常设场地举行的第十七届伊朗国际石材展，并设有专属展位。公司展示了最新的白色和蜂蜜色travertine荒料，销售团队与数十位国内外贸易商进行了会面与洽谈。此次参展为公司向新市场展示生产能力和产品质量提供了良机。',
                    'tr' => 'Stone Commerce, Mahallat\'taki Nimvar kalıcı fuar alanında düzenlenen 17. İran Uluslararası Taş Fuarı\'na özel bir standla katıldı. Şirket, en yeni beyaz ve bal rengi traverten bloklarını sergiledi ve satış ekibi onlarca yerli ve yabancı tüccarla görüştü. Bu katılım, şirketin üretim kapasitesini ve ürün kalitesini yeni pazarlara tanıtma fırsatı sundu.',
                ],
                'location' => [
                    'fa' => 'سایت دائمی نمایشگاه بین‌المللی سنگ نیم‌ور، غرفه ۲۴',
                    'en' => 'Nimvar Permanent International Stone Exhibition Site, Booth 24',
                    'ar' => 'موقع نیم‌ور الدائم لمعرض الحجر الدولي، الجناح 24',
                    'hi' => 'निमवर स्थायी अंतरराष्ट्रीय पत्थर प्रदर्शनी स्थल, स्टॉल 24',
                    'it' => 'Sito Espositivo Permanente Internazionale della Pietra di Nimvar, Stand 24',
                    'zh' => '尼姆瓦尔国际石材展览常设场地，24号展位',
                    'tr' => 'Nimvar Kalıcı Uluslararası Taş Fuarı Alanı, 24 No\'lu Stand',
                ],
                'city'         => 'محلات (نیم‌ور)',
                'country'      => 'ایران',
                'starts_at'    => Carbon::create(2025, 10, 7),
                'ends_at'      => Carbon::create(2025, 10, 10),
                'status'       => 'finished',
                'hall_number'  => 'A',
                'booth_number' => '24',
                'website_url'  => null,
                'cover_seed'    => 'mahallat-17-cover.jpg',
                'gallery_seeds' => [
                    'mahallat-17-gallery-1.jpg',
                    'mahallat-17-gallery-2.jpg',
                    'mahallat-17-gallery-3.jpg',
                ],
            ],

            // ── Finished: Participation in 16th Mahallat Stone Expo ──────
            [
                'title' => [
                    'fa' => 'حضور Stone Commerce در شانزدهمین نمایشگاه بین‌المللی سنگ ایران',
                    'en' => 'Stone Commerce at the 16th Iran International Stone Exhibition',
                    'ar' => 'حضور Stone Commerce في المعرض الدولي السادس عشر لحجر إيران',
                    'hi' => '16वें ईरान अंतरराष्ट्रीय पत्थर प्रदर्शनी में Stone Commerce',
                    'it' => 'Stone Commerce alla 16ª Fiera Internazionale della Pietra Iraniana',
                    'zh' => 'Stone Commerce 参加第16届伊朗国际石材展',
                    'tr' => 'Stone Commerce 16. İran Uluslararası Taş Fuarı\'nda',
                ],
                'slug' => [
                    'fa' => 'hozoor-namayeshgah-sang-mahallat-16',
                    'en' => 'mahallat-stone-expo-16',
                    'ar' => 'mahallat-stone-expo-16',
                    'hi' => 'mahallat-stone-expo-16',
                    'it' => 'mahallat-stone-expo-16',
                    'zh' => 'mahallat-stone-expo-16',
                    'tr' => 'mahallat-stone-expo-16',
                ],
                'description' => [
                    'fa' => 'تیم Stone Commerce در شانزدهمین نمایشگاه بین‌المللی سنگ محلات با حضور بازدیدکنندگانی از حدود ۵۰ کشور شرکت کرد. در این دوره، نمونه‌های بلوک تراورتن طوسی و کرم شرکت مورد استقبال بازرگانان منطقه‌ای قرار گرفت و چندین قرارداد همکاری اولیه با شرکا تجاری جدید منعقد شد.',
                    'en' => 'The Stone Commerce team took part in the 16th Mahallat International Stone Exhibition, attended by visitors from about 50 countries. During this edition, the company\'s grey and cream travertine block samples were well received by regional traders, and several preliminary cooperation agreements were signed with new business partners.',
                    'ar' => 'شارك فريق Stone Commerce في المعرض الدولي السادس عشر لحجر محلات بحضور زوار من حوالي 50 دولة.',
                    'hi' => 'Stone Commerce की टीम ने 16वें महल्लात अंतरराष्ट्रीय पत्थर प्रदर्शनी में भाग लिया, जिसमें लगभग 50 देशों के आगंतुक शामिल हुए।',
                    'it' => 'Il team di Stone Commerce ha partecipato alla 16ª Fiera Internazionale della Pietra di Mahallat, con visitatori da circa 50 paesi.',
                    'zh' => 'Stone Commerce团队参加了第十六届马哈拉特国际石材展，来自约50个国家的参观者出席了本届展会。在此次展会中，公司的灰色和奶油色travertine荒料样品受到区域贸易商的热烈欢迎，并与多家新的业务伙伴签署了初步合作协议。',
                    'tr' => 'Stone Commerce ekibi, yaklaşık 50 ülkeden ziyaretçinin katıldığı 16. Mahallat Uluslararası Taş Fuarı\'na katıldı. Bu etkinlikte şirketin gri ve krem traverten blok numuneleri bölgesel tüccarlar tarafından büyük ilgiyle karşılandı ve yeni iş ortaklarıyla çeşitli ön işbirliği anlaşmaları imzalandı.',
                ],
                'location' => [
                    'fa' => 'سایت دائمی نمایشگاه بین‌المللی سنگ نیم‌ور، غرفه ۱۷',
                    'en' => 'Nimvar Permanent International Stone Exhibition Site, Booth 17',
                    'ar' => 'موقع نیم‌ور الدائم لمعرض الحجر الدولي، الجناح 17',
                    'hi' => 'निमवर स्थायी अंतरराष्ट्रीय पत्थर प्रदर्शनी स्थल, स्टॉल 17',
                    'it' => 'Sito Espositivo Permanente Internazionale della Pietra di Nimvar, Stand 17',
                    'zh' => '尼姆瓦尔国际石材展览常设场地，17号展位',
                    'tr' => 'Nimvar Kalıcı Uluslararası Taş Fuarı Alanı, 17 No\'lu Stand',
                ],
                'city'         => 'محلات (نیم‌ور)',
                'country'      => 'ایران',
                'starts_at'    => Carbon::create(2024, 10, 8),
                'ends_at'      => Carbon::create(2024, 10, 11),
                'status'       => 'finished',
                'hall_number'  => 'B',
                'booth_number' => '17',
                'website_url'  => null,
                'cover_seed'    => 'mahallat-16-cover.jpg',
                'gallery_seeds' => [
                    'mahallat-16-gallery-1.jpg',
                ],
            ],

            // ── Finished: Participation in Mashhad Stone Exhibition ──────
            [
                'title' => [
                    'fa' => 'حضور Stone Commerce در دومین نمایشگاه بین‌المللی سنگ مشهد',
                    'en' => 'Stone Commerce at the 2nd Mashhad International Stone Exhibition',
                    'ar' => 'حضور Stone Commerce في معرض مشهد الدولي الثاني للحجر',
                    'hi' => 'दूसरे मशहद अंतरराष्ट्रीय पत्थर प्रदर्शनी में Stone Commerce',
                    'it' => 'Stone Commerce alla 2ª Fiera Internazionale della Pietra di Mashhad',
                    'zh' => 'Stone Commerce 参加第二届马什哈德国际石材展',
                    'tr' => 'Stone Commerce 2. Meşhed Uluslararası Taş Fuarı\'nda',
                ],
                'slug' => [
                    'fa' => 'hozoor-namayeshgah-sang-mashhad-2',
                    'en' => 'mashhad-stone-expo-2',
                    'ar' => 'mashhad-stone-expo-2',
                    'hi' => 'mashhad-stone-expo-2',
                    'it' => 'mashhad-stone-expo-2',
                    'zh' => 'mashhad-stone-expo-2',
                    'tr' => 'mashhad-stone-expo-2',
                ],
                'description' => [
                    'fa' => 'Stone Commerce در دومین نمایشگاه تخصصی سنگ و ساختمان مشهد شرکت کرد؛ رویدادی با هدف توسعه صادرات سنگ از شرق کشور به بازارهای قزاقستان، افغانستان و ترکمنستان. حضور در این نمایشگاه فرصتی برای آشنایی با خریداران منطقه شرق ایران و آسیای مرکزی فراهم کرد.',
                    'en' => 'Stone Commerce took part in the 2nd Mashhad Specialized Stone and Construction Exhibition, an event aimed at expanding stone exports from eastern Iran to markets in Kazakhstan, Afghanistan, and Turkmenistan. Participation provided an opportunity to connect with buyers from eastern Iran and Central Asia.',
                    'ar' => 'شاركت Stone Commerce في معرض مشهد التخصصي الثاني للحجر والبناء، وهو حدث يهدف إلى توسيع صادرات الحجر من شرق إيران.',
                    'hi' => 'Stone Commerce ने दूसरे मशहद विशेष पत्थर और निर्माण प्रदर्शनी में भाग लिया।',
                    'it' => 'Stone Commerce ha partecipato alla 2ª Fiera Specializzata della Pietra e Costruzione di Mashhad.',
                    'zh' => 'Stone Commerce参加了第二届马什哈德专业石材与建筑展，该展会旨在扩大伊朗东部地区对哈萨克斯坦、阿富汗和土库曼斯坦市场的石材出口。参展为公司提供了结识伊朗东部及中亚地区买家的机会。',
                    'tr' => 'Stone Commerce, İran\'ın doğusundan Kazakistan, Afganistan ve Türkmenistan pazarlarına taş ihracatını genişletmeyi amaçlayan 2. Meşhed Uzmanlaşmış Taş ve İnşaat Fuarı\'na katıldı. Bu katılım, doğu İran ve Orta Asya\'dan alıcılarla bağlantı kurma fırsatı sağladı.',
                ],
                'location' => [
                    'fa' => 'نمایشگاه بین‌المللی مشهد، غرفه ۹',
                    'en' => 'Mashhad International Exhibition Center, Booth 9',
                    'ar' => 'مركز مشهد الدولي للمعارض، الجناح 9',
                    'hi' => 'मशहद अंतरराष्ट्रीय प्रदर्शनी केंद्र, स्टॉल 9',
                    'it' => 'Centro Espositivo Internazionale di Mashhad, Stand 9',
                    'zh' => '马什哈德国际展览中心，9号展位',
                    'tr' => 'Meşhed Uluslararası Fuar Merkezi, 9 No\'lu Stand',
                ],
                'city'         => 'مشهد',
                'country'      => 'ایران',
                'starts_at'    => Carbon::create(2023, 6, 15),
                'ends_at'      => Carbon::create(2023, 6, 18),
                'status'       => 'finished',
                'hall_number'  => null,
                'booth_number' => '9',
                'website_url'  => null,
                'cover_seed'    => 'mashhad-2-cover.jpg',
                'gallery_seeds' => [],
            ],

            // ── Ongoing: Participation in Tehran Construction & Stone Fair ──
            [
                'title' => [
                    'fa' => 'حضور Stone Commerce در نمایشگاه صنعت ساختمان و سنگ تهران',
                    'en' => 'Stone Commerce at the Tehran Construction & Stone Industry Fair',
                    'ar' => 'حضور Stone Commerce في معرض طهران لصناعة البناء والحجر',
                    'hi' => 'तेहरान निर्माण और पत्थर उद्योग मेले में Stone Commerce',
                    'it' => 'Stone Commerce alla Fiera dell\'Industria Edile e Lapidea di Teheran',
                    'zh' => 'Stone Commerce 参加德黑兰建筑与石材工业博览会',
                    'tr' => 'Stone Commerce Tahran İnşaat ve Taş Endüstrisi Fuarı\'nda',
                ],
                'slug' => [
                    'fa' => 'hozoor-namayeshgah-sakhteman-sang-tehran',
                    'en' => 'tehran-construction-fair',
                    'ar' => 'tehran-construction-fair',
                    'hi' => 'tehran-construction-fair',
                    'it' => 'tehran-construction-fair',
                    'zh' => 'tehran-construction-fair',
                    'tr' => 'tehran-construction-fair',
                ],
                'description' => [
                    'fa' => 'Stone Commerce هم‌اکنون در نمایشگاه صنعت ساختمان و سنگ تهران حضور دارد و محصولات تراورتن و مرمریت خود را به همراه کاتالوگ صادراتی به نمایش گذاشته است. علاقه‌مندان می‌توانند از غرفه شرکت در سالن ۳۸ بازدید کرده و با کارشناسان فروش گفتگو کنند.',
                    'en' => 'Stone Commerce is currently participating in the Tehran Construction & Stone Industry Fair, showcasing its travertine and marble products along with an export catalog. Visitors can stop by the company\'s booth in Hall 38 to speak with the sales team.',
                    'ar' => 'تشارك Stone Commerce حاليًا في معرض طهران لصناعة البناء والحجر، حيث تعرض منتجاتها من الترافرتين والرخام.',
                    'hi' => 'Stone Commerce वर्तमान में तेहरान निर्माण और पत्थर उद्योग मेले में भाग ले रहा है।',
                    'it' => 'Stone Commerce sta attualmente partecipando alla Fiera dell\'Industria Edile e Lapidea di Teheran.',
                    'zh' => 'Stone Commerce目前正在参加德黑兰建筑与石材工业博览会，展示其travertine和大理石产品及出口目录。参观者可前往公司位于38号展厅的展位，与销售团队直接交流。',
                    'tr' => 'Stone Commerce, halihazırda Tahran İnşaat ve Taş Endüstrisi Fuarı\'na katılarak traverten ve mermer ürünlerini ihracat kataloğuyla birlikte sergiliyor. Ziyaretçiler, 38 No\'lu Salon\'daki şirket standına uğrayarak satış ekibiyle görüşebilir.',
                ],
                'location' => [
                    'fa' => 'محل دائمی نمایشگاه‌های بین‌المللی تهران، سالن ۳۸، غرفه ۱۱۲',
                    'en' => 'Tehran International Permanent Fairground, Hall 38, Booth 112',
                    'ar' => 'أرض المعارض الدولية الدائمة بطهران، القاعة 38، الجناح 112',
                    'hi' => 'तेहरान अंतरराष्ट्रीय स्थायी मेला मैदान, हॉल 38, स्टॉल 112',
                    'it' => 'Quartiere Fieristico Permanente Internazionale di Teheran, Padiglione 38, Stand 112',
                    'zh' => '德黑兰国际展览常设场地，38号厅，112号展位',
                    'tr' => 'Tahran Uluslararası Kalıcı Fuar Alanı, 38 No\'lu Salon, 112 No\'lu Stand',
                ],
                'city'         => 'تهران',
                'country'      => 'ایران',
                'starts_at'    => Carbon::now()->subDays(2),
                'ends_at'      => Carbon::now()->addDays(3),
                'status'       => 'ongoing',
                'hall_number'  => '38',
                'booth_number' => '112',
                'website_url'  => null,
                'cover_seed'    => 'tehran-fair-cover.jpg',
                'gallery_seeds' => [
                    'tehran-fair-gallery-1.jpg',
                ],
            ],
        ];

        foreach ($events as $data) {
            $coverSeed    = $data['cover_seed'];
            $gallerySeeds = $data['gallery_seeds'];
            unset($data['cover_seed'], $data['gallery_seeds']);

            $event = Event::updateOrCreate(
                ['slug->fa' => $data['slug']['fa']],
                array_merge($data, ['user_id' => $organizerId, 'views_count' => rand(40, 600)])
            );

            // Cover image
            if ($event->getMedia('cover')->isEmpty()) {
                $coverPath = storage_path('app/seeders/events/' . $coverSeed);
                if (file_exists($coverPath)) {
                    $event->addMedia($coverPath)
                        ->preservingOriginal()
                        ->toMediaCollection('cover');
                } else {
                    $this->command->warn("Missing cover image: storage/app/seeders/events/{$coverSeed}");
                }
            }

            // Gallery images
            if ($event->getMedia('gallery')->isEmpty()) {
                foreach ($gallerySeeds as $seed) {
                    $galleryPath = storage_path('app/seeders/events/' . $seed);
                    if (file_exists($galleryPath)) {
                        $event->addMedia($galleryPath)
                            ->preservingOriginal()
                            ->toMediaCollection('gallery');
                    } else {
                        $this->command->warn("Missing gallery image: storage/app/seeders/events/{$seed}");
                    }
                }
            }

            // Note: video uploads are intentionally left for manual upload via the
            // admin panel — place .mp4 files in storage/app/seeders/events/ and
            // add them there, or upload directly through the Filament EventResource.
        }

        $this->command->info(count($events) . ' exhibition participation records seeded successfully.');
    }
}
