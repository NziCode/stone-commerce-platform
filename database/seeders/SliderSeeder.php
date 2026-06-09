<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        Slider::truncate();

        $sliders = [
            [
                'title' => [
                    'fa' => 'صادرات سنگ به ۵ قاره دنیا',
                    'en' => 'Exporting Stone to 5 Continents',
                    'ar' => 'تصدير الحجر إلى ٥ قارات',
                    'hi' => '5 महाद्वीपों में पत्थर निर्यात',
                    'it' => 'Esportazione di Pietra in 5 Continenti',
                ],
                'subtitle' => [
                    'fa' => 'سنگ‌های طبیعی ایران',
                    'en' => 'Iran\'s Natural Stones',
                    'ar' => 'أحجار إيران الطبيعية',
                    'hi' => 'ईरान के प्राकृतिक पत्थर',
                    'it' => 'Pietre Naturali dell\'Iran',
                ],
                'button_text' => [
                    'fa' => 'مشاهده محصولات',
                    'en' => 'View Products',
                    'ar' => 'عرض المنتجات',
                    'hi' => 'उत्पाद देखें',
                    'it' => 'Vedi Prodotti',
                ],
                'button_link'    => '/products',
                'button_target'  => '_self',
                'image'          => 'assets/images/slider/bg/1.jpg',
                'type'           => 'image',
                'overlay_color'  => '#000000',
                'overlay_opacity'=> 30,
                'is_active'      => true,
                'sort_order'     => 1,
            ],
            [
                'title' => [
                    'fa' => 'بهترین سنگ‌های معدنی ایران',
                    'en' => 'Best Natural Stones of Iran',
                    'ar' => 'أفضل أحجار إيران المعدنية',
                    'hi' => 'ईरान के सर्वश्रेष्ठ खनिज पत्थर',
                    'it' => 'Le Migliori Pietre Naturali dell\'Iran',
                ],
                'subtitle' => [
                    'fa' => 'تراورتن، مرمریت، گرانیت و سنگ‌های طبیعی برتر',
                    'en' => 'Travertine, Marble, Granite and Premium Natural Stones',
                    'ar' => 'تراورتين، رخام، جرانيت وأحجار طبيعية متميزة',
                    'hi' => 'ट्रैवर्टाइन, मार्बल, ग्रेनाइट और प्रीमियम प्राकृतिक पत्थर',
                    'it' => 'Travertino, Marmo, Granito e Pietre Naturali Premium',
                ],
                'button_text' => [
                    'fa' => 'تماس با ما',
                    'en' => 'Contact Us',
                    'ar' => 'اتصل بنا',
                    'hi' => 'संपर्क करें',
                    'it' => 'Contattaci',
                ],
                'button_link'    => '/contact',
                'button_target'  => '_self',
                'image'          => 'assets/images/slider/bg/2.jpg',
                'type'           => 'image',
                'overlay_color'  => '#000000',
                'overlay_opacity'=> 30,
                'is_active'      => true,
                'sort_order'     => 2,
            ],
        ];

        foreach ($sliders as $index => $data) {
            $imagePath = $data['image'];
            unset($data['image']);

            $slider = Slider::create($data);

            // اضافه کردن تصویر از public path
            if (file_exists(public_path($imagePath))) {
                $slider->addMedia(public_path($imagePath))
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            }
        }
    }
}
