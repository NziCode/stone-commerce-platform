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
                    'fa' => 'حضور EN Trading Group در هفدهمین نمایشگاه بین‌المللی سنگ ایران',
                    'en' => 'EN Trading Group at the 17th Iran International Stone Exhibition',
                    'ar' => 'حضور EN Trading Group في المعرض الدولي السابع عشر لحجر إيران',
                    'hi' => '17वें ईरान अंतरराष्ट्रीय पत्थर प्रदर्शनी में EN Trading Group',
                    'it' => 'EN Trading Group alla 17ª Fiera Internazionale della Pietra Iraniana',
                ],
                'slug' => [
                    'fa' => 'hozoor-namayeshgah-sang-mahallat-17',
                    'en' => 'en-trading-mahallat-stone-expo-17',
                    'ar' => 'en-trading-mahallat-stone-expo-17',
                    'hi' => 'en-trading-mahallat-stone-expo-17',
                    'it' => 'en-trading-mahallat-stone-expo-17',
                ],
                'description' => [
                    'fa' => 'EN Trading Group در هفدهمین نمایشگاه بین‌المللی سنگ ایران، برگزار شده در سایت دائمی نمایشگاه نیم‌ور محلات، با غرفه‌ای اختصاصی حضور یافت. در این رویداد، جدیدترین بلوک‌های تراورتن سفید و عسلی شرکت به نمایش گذاشته شد و تیم فروش با ده‌ها بازرگان داخلی و خارجی دیدار و مذاکره کرد. این حضور فرصتی برای معرفی توان تولید و کیفیت محصولات شرکت به بازارهای جدید بود.',
                    'en' => 'EN Trading Group participated in the 17th Iran International Stone Exhibition, held at the permanent Nimvar exhibition site in Mahallat, with a dedicated booth. The company showcased its latest white and honey travertine blocks and the sales team met with dozens of domestic and international traders. The participation provided an opportunity to introduce the company\'s production capacity and product quality to new markets.',
                    'ar' => 'شاركت EN Trading Group في المعرض الدولي السابع عشر لحجر إيران، الذي أُقيم في موقع نیم‌ور الدائم بمحلات، بجناح مخصص. عرضت الشركة أحدث كتل الترافرتين الأبيض والعسلي.',
                    'hi' => 'EN Trading Group ने महल्लात के स्थायी निमवर प्रदर्शनी स्थल पर आयोजित 17वें ईरान अंतरराष्ट्रीय पत्थर प्रदर्शनी में एक समर्पित स्टॉल के साथ भाग लिया।',
                    'it' => 'EN Trading Group ha partecipato alla 17ª Fiera Internazionale della Pietra Iraniana, tenutasi presso il sito espositivo permanente di Nimvar a Mahallat, con uno stand dedicato.',
                ],
                'location' => [
                    'fa' => 'سایت دائمی نمایشگاه بین‌المللی سنگ نیم‌ور، غرفه ۲۴',
                    'en' => 'Nimvar Permanent International Stone Exhibition Site, Booth 24',
                    'ar' => 'موقع نیم‌ور الدائم لمعرض الحجر الدولي، الجناح 24',
                    'hi' => 'निमवर स्थायी अंतरराष्ट्रीय पत्थर प्रदर्शनी स्थल, स्टॉल 24',
                    'it' => 'Sito Espositivo Permanente Internazionale della Pietra di Nimvar, Stand 24',
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
                    'fa' => 'حضور EN Trading Group در شانزدهمین نمایشگاه بین‌المللی سنگ ایران',
                    'en' => 'EN Trading Group at the 16th Iran International Stone Exhibition',
                    'ar' => 'حضور EN Trading Group في المعرض الدولي السادس عشر لحجر إيران',
                    'hi' => '16वें ईरान अंतरराष्ट्रीय पत्थर प्रदर्शनी में EN Trading Group',
                    'it' => 'EN Trading Group alla 16ª Fiera Internazionale della Pietra Iraniana',
                ],
                'slug' => [
                    'fa' => 'hozoor-namayeshgah-sang-mahallat-16',
                    'en' => 'en-trading-mahallat-stone-expo-16',
                    'ar' => 'en-trading-mahallat-stone-expo-16',
                    'hi' => 'en-trading-mahallat-stone-expo-16',
                    'it' => 'en-trading-mahallat-stone-expo-16',
                ],
                'description' => [
                    'fa' => 'تیم EN Trading Group در شانزدهمین نمایشگاه بین‌المللی سنگ محلات با حضور بازدیدکنندگانی از حدود ۵۰ کشور شرکت کرد. در این دوره، نمونه‌های بلوک تراورتن طوسی و کرم شرکت مورد استقبال بازرگانان منطقه‌ای قرار گرفت و چندین قرارداد همکاری اولیه با شرکا تجاری جدید منعقد شد.',
                    'en' => 'The EN Trading Group team took part in the 16th Mahallat International Stone Exhibition, attended by visitors from about 50 countries. During this edition, the company\'s grey and cream travertine block samples were well received by regional traders, and several preliminary cooperation agreements were signed with new business partners.',
                    'ar' => 'شارك فريق EN Trading Group في المعرض الدولي السادس عشر لحجر محلات بحضور زوار من حوالي 50 دولة.',
                    'hi' => 'EN Trading Group की टीम ने 16वें महल्लात अंतरराष्ट्रीय पत्थर प्रदर्शनी में भाग लिया, जिसमें लगभग 50 देशों के आगंतुक शामिल हुए।',
                    'it' => 'Il team di EN Trading Group ha partecipato alla 16ª Fiera Internazionale della Pietra di Mahallat, con visitatori da circa 50 paesi.',
                ],
                'location' => [
                    'fa' => 'سایت دائمی نمایشگاه بین‌المللی سنگ نیم‌ور، غرفه ۱۷',
                    'en' => 'Nimvar Permanent International Stone Exhibition Site, Booth 17',
                    'ar' => 'موقع نیم‌ور الدائم لمعرض الحجر الدولي، الجناح 17',
                    'hi' => 'निमवर स्थायी अंतरराष्ट्रीय पत्थर प्रदर्शनी स्थल, स्टॉल 17',
                    'it' => 'Sito Espositivo Permanente Internazionale della Pietra di Nimvar, Stand 17',
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
                    'fa' => 'حضور EN Trading Group در دومین نمایشگاه بین‌المللی سنگ مشهد',
                    'en' => 'EN Trading Group at the 2nd Mashhad International Stone Exhibition',
                    'ar' => 'حضور EN Trading Group في معرض مشهد الدولي الثاني للحجر',
                    'hi' => 'दूसरे मशहद अंतरराष्ट्रीय पत्थर प्रदर्शनी में EN Trading Group',
                    'it' => 'EN Trading Group alla 2ª Fiera Internazionale della Pietra di Mashhad',
                ],
                'slug' => [
                    'fa' => 'hozoor-namayeshgah-sang-mashhad-2',
                    'en' => 'en-trading-mashhad-stone-expo-2',
                    'ar' => 'en-trading-mashhad-stone-expo-2',
                    'hi' => 'en-trading-mashhad-stone-expo-2',
                    'it' => 'en-trading-mashhad-stone-expo-2',
                ],
                'description' => [
                    'fa' => 'EN Trading Group در دومین نمایشگاه تخصصی سنگ و ساختمان مشهد شرکت کرد؛ رویدادی با هدف توسعه صادرات سنگ از شرق کشور به بازارهای قزاقستان، افغانستان و ترکمنستان. حضور در این نمایشگاه فرصتی برای آشنایی با خریداران منطقه شرق ایران و آسیای مرکزی فراهم کرد.',
                    'en' => 'EN Trading Group took part in the 2nd Mashhad Specialized Stone and Construction Exhibition, an event aimed at expanding stone exports from eastern Iran to markets in Kazakhstan, Afghanistan, and Turkmenistan. Participation provided an opportunity to connect with buyers from eastern Iran and Central Asia.',
                    'ar' => 'شاركت EN Trading Group في معرض مشهد التخصصي الثاني للحجر والبناء، وهو حدث يهدف إلى توسيع صادرات الحجر من شرق إيران.',
                    'hi' => 'EN Trading Group ने दूसरे मशहद विशेष पत्थर और निर्माण प्रदर्शनी में भाग लिया।',
                    'it' => 'EN Trading Group ha partecipato alla 2ª Fiera Specializzata della Pietra e Costruzione di Mashhad.',
                ],
                'location' => [
                    'fa' => 'نمایشگاه بین‌المللی مشهد، غرفه ۹',
                    'en' => 'Mashhad International Exhibition Center, Booth 9',
                    'ar' => 'مركز مشهد الدولي للمعارض، الجناح 9',
                    'hi' => 'मशहद अंतरराष्ट्रीय प्रदर्शनी केंद्र, स्टॉल 9',
                    'it' => 'Centro Espositivo Internazionale di Mashhad, Stand 9',
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
                    'fa' => 'حضور EN Trading Group در نمایشگاه صنعت ساختمان و سنگ تهران',
                    'en' => 'EN Trading Group at the Tehran Construction & Stone Industry Fair',
                    'ar' => 'حضور EN Trading Group في معرض طهران لصناعة البناء والحجر',
                    'hi' => 'तेहरान निर्माण और पत्थर उद्योग मेले में EN Trading Group',
                    'it' => 'EN Trading Group alla Fiera dell\'Industria Edile e Lapidea di Teheran',
                ],
                'slug' => [
                    'fa' => 'hozoor-namayeshgah-sakhteman-sang-tehran',
                    'en' => 'en-trading-tehran-construction-fair',
                    'ar' => 'en-trading-tehran-construction-fair',
                    'hi' => 'en-trading-tehran-construction-fair',
                    'it' => 'en-trading-tehran-construction-fair',
                ],
                'description' => [
                    'fa' => 'EN Trading Group هم‌اکنون در نمایشگاه صنعت ساختمان و سنگ تهران حضور دارد و محصولات تراورتن و مرمریت خود را به همراه کاتالوگ صادراتی به نمایش گذاشته است. علاقه‌مندان می‌توانند از غرفه شرکت در سالن ۳۸ بازدید کرده و با کارشناسان فروش گفتگو کنند.',
                    'en' => 'EN Trading Group is currently participating in the Tehran Construction & Stone Industry Fair, showcasing its travertine and marble products along with an export catalog. Visitors can stop by the company\'s booth in Hall 38 to speak with the sales team.',
                    'ar' => 'تشارك EN Trading Group حاليًا في معرض طهران لصناعة البناء والحجر، حيث تعرض منتجاتها من الترافرتين والرخام.',
                    'hi' => 'EN Trading Group वर्तमान में तेहरान निर्माण और पत्थर उद्योग मेले में भाग ले रहा है।',
                    'it' => 'EN Trading Group sta attualmente partecipando alla Fiera dell\'Industria Edile e Lapidea di Teheran.',
                ],
                'location' => [
                    'fa' => 'محل دائمی نمایشگاه‌های بین‌المللی تهران، سالن ۳۸، غرفه ۱۱۲',
                    'en' => 'Tehran International Permanent Fairground, Hall 38, Booth 112',
                    'ar' => 'أرض المعارض الدولية الدائمة بطهران، القاعة 38، الجناح 112',
                    'hi' => 'तेहरान अंतरराष्ट्रीय स्थायी मेला मैदान, हॉल 38, स्टॉल 112',
                    'it' => 'Quartiere Fieristico Permanente Internazionale di Teheran, Padiglione 38, Stand 112',
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
