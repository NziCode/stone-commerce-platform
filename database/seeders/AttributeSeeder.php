<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [

            // ── 1. Color ────────────────────────────────────────
            [
                'key'   => 'color',
                'type'  => 'select',
                'label' => [
                    'fa' => 'رنگ',
                    'en' => 'Color',
                    'ar' => 'اللون',
                    'hi' => 'रंग',
                    'it' => 'Colore',
                ],
                'group' => [
                    'fa' => 'ظاهر و رنگ',
                    'en' => 'Appearance & Color',
                    'ar' => 'المظهر واللون',
                    'hi' => 'दिखावट और रंग',
                    'it' => 'Aspetto e Colore',
                ],
                'options' => [
                    ['key' => 'white',  'label' => ['fa' => 'سفید',   'en' => 'White',  'ar' => 'أبيض',   'hi' => 'सफ़ेद',  'it' => 'Bianco']],
                    ['key' => 'cream',  'label' => ['fa' => 'کرم',    'en' => 'Cream',  'ar' => 'كريمي',  'hi' => 'क्रीम',  'it' => 'Crema']],
                    ['key' => 'honey',  'label' => ['fa' => 'عسلی',   'en' => 'Honey',  'ar' => 'عسلي',   'hi' => 'शहद',    'it' => 'Miele']],
                    ['key' => 'beige',  'label' => ['fa' => 'بژ',     'en' => 'Beige',  'ar' => 'بيج',    'hi' => 'बेज',    'it' => 'Beige']],
                    ['key' => 'gray',   'label' => ['fa' => 'طوسی',   'en' => 'Gray',   'ar' => 'رمادي',  'hi' => 'धूसर',   'it' => 'Grigio']],
                    ['key' => 'black',  'label' => ['fa' => 'مشکی',   'en' => 'Black',  'ar' => 'أسود',   'hi' => 'काला',   'it' => 'Nero']],
                    ['key' => 'red',    'label' => ['fa' => 'قرمز',   'en' => 'Red',    'ar' => 'أحمر',   'hi' => 'लाल',    'it' => 'Rosso']],
                    ['key' => 'pink',   'label' => ['fa' => 'صورتی',  'en' => 'Pink',   'ar' => 'وردي',   'hi' => 'गुलाबी', 'it' => 'Rosa']],
                    ['key' => 'green',  'label' => ['fa' => 'سبز',    'en' => 'Green',  'ar' => 'أخضر',   'hi' => 'हरा',    'it' => 'Verde']],
                    ['key' => 'gold',   'label' => ['fa' => 'طلایی',  'en' => 'Gold',   'ar' => 'ذهبي',   'hi' => 'सुनहरा', 'it' => 'Oro']],
                    ['key' => 'brown',  'label' => ['fa' => 'قهوه‌ای', 'en' => 'Brown', 'ar' => 'بني',    'hi' => 'भूरा',   'it' => 'Marrone']],
                ],
                'unit'                 => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 1,
            ],

            // ── 2. Surface Finish ─────────────────────────────────
            [
                'key'   => 'surface_finish',
                'type'  => 'select',
                'label' => [
                    'fa' => 'نوع پرداخت سطح',
                    'en' => 'Surface Finish',
                    'ar' => 'تشطيب السطح',
                    'hi' => 'सतह फिनिश',
                    'it' => 'Finitura Superficie',
                ],
                'group' => [
                    'fa' => 'ظاهر و رنگ',
                    'en' => 'Appearance & Color',
                    'ar' => 'المظهر واللون',
                    'hi' => 'दिखावट और रंग',
                    'it' => 'Aspetto e Colore',
                ],
                'options' => [
                    ['key' => 'polished',  'label' => ['fa' => 'پولیش',     'en' => 'Polished',  'ar' => 'مصقول',     'hi' => 'पॉलिश्ड',     'it' => 'Lucidato']],
                    ['key' => 'honed',     'label' => ['fa' => 'هانیکام',   'en' => 'Honed',     'ar' => 'مصنفر',     'hi' => 'होन्ड',       'it' => 'Levigato']],
                    ['key' => 'flamed',    'label' => ['fa' => 'فلیم',      'en' => 'Flamed',    'ar' => 'محروق',     'hi' => 'फ्लेम्ड',     'it' => 'Fiammato']],
                    ['key' => 'bush_hammered', 'label' => ['fa' => 'بوشهمر', 'en' => 'Bush-hammered', 'ar' => 'مطروق', 'hi' => 'बश-हैमर्ड', 'it' => 'Bocciardato']],
                    ['key' => 'sandblasted', 'label' => ['fa' => 'ساتنی (سندبلاست)', 'en' => 'Sandblasted', 'ar' => 'منفوخ بالرمل', 'hi' => 'सैंडब्लास्टेड', 'it' => 'Sabbiato']],
                    ['key' => 'antique',   'label' => ['fa' => 'آنتیک',     'en' => 'Antique',   'ar' => 'أنتيك',     'hi' => 'प्राचीन',     'it' => 'Anticato']],
                    ['key' => 'rustic',    'label' => ['fa' => 'روستیک',    'en' => 'Rustic',    'ar' => 'ريفي',      'hi' => 'देहाती',      'it' => 'Rustico']],
                    ['key' => 'natural_split', 'label' => ['fa' => 'لاشه (شکافت طبیعی)', 'en' => 'Natural Split', 'ar' => 'مشقوق طبيعي', 'hi' => 'प्राकृतिक विभाजन', 'it' => 'Spaccatura Naturale']],
                ],
                'unit'                 => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 2,
            ],

            // ── 3. Length ─────────────────────────────────────────
            [
                'key'   => 'length',
                'type'  => 'number',
                'label' => [
                    'fa' => 'طول',
                    'en' => 'Length',
                    'ar' => 'الطول',
                    'hi' => 'लंबाई',
                    'it' => 'Lunghezza',
                ],
                'group' => [
                    'fa' => 'ابعاد و وزن',
                    'en' => 'Dimensions & Weight',
                    'ar' => 'الأبعاد والوزن',
                    'hi' => 'आयाम और वजन',
                    'it' => 'Dimensioni e Peso',
                ],
                'unit'                 => 'cm',
                'min_value'            => 0,
                'max_value'            => 500,
                'step_value'           => 0.5,
                'default_value'        => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => true,
                'is_active'            => true,
                'sort_order'           => 3,
            ],

            // ── 4. Width ──────────────────────────────────────────
            [
                'key'   => 'width',
                'type'  => 'number',
                'label' => [
                    'fa' => 'عرض',
                    'en' => 'Width',
                    'ar' => 'العرض',
                    'hi' => 'चौड़ाई',
                    'it' => 'Larghezza',
                ],
                'group' => [
                    'fa' => 'ابعاد و وزن',
                    'en' => 'Dimensions & Weight',
                    'ar' => 'الأبعاد والوزن',
                    'hi' => 'आयाम और वजन',
                    'it' => 'Dimensioni e Peso',
                ],
                'unit'                 => 'cm',
                'min_value'            => 0,
                'max_value'            => 500,
                'step_value'           => 0.5,
                'default_value'        => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => true,
                'is_active'            => true,
                'sort_order'           => 4,
            ],

            // ── 5. Thickness ──────────────────────────────────────
            [
                'key'   => 'thickness',
                'type'  => 'number',
                'label' => [
                    'fa' => 'ضخامت',
                    'en' => 'Thickness',
                    'ar' => 'السماكة',
                    'hi' => 'मोटाई',
                    'it' => 'Spessore',
                ],
                'group' => [
                    'fa' => 'ابعاد و وزن',
                    'en' => 'Dimensions & Weight',
                    'ar' => 'الأبعاد والوزن',
                    'hi' => 'आयाम और वजन',
                    'it' => 'Dimensioni e Peso',
                ],
                'unit'                 => 'cm',
                'min_value'            => 0,
                'max_value'            => 30,
                'step_value'           => 0.5,
                'default_value'        => 2,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 5,
            ],

            // ── 6. Weight ─────────────────────────────────────────
            [
                'key'   => 'weight',
                'type'  => 'number',
                'label' => [
                    'fa' => 'وزن',
                    'en' => 'Weight',
                    'ar' => 'الوزن',
                    'hi' => 'वजन',
                    'it' => 'Peso',
                ],
                'group' => [
                    'fa' => 'ابعاد و وزن',
                    'en' => 'Dimensions & Weight',
                    'ar' => 'الأبعاد والوزن',
                    'hi' => 'आयाम और वजन',
                    'it' => 'Dimensioni e Peso',
                ],
                'unit'                 => 'kg',
                'min_value'            => 0,
                'max_value'            => null,
                'step_value'           => 1,
                'default_value'        => null,
                'is_filterable'        => false,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 6,
            ],

            // ── 7. Block Volume ───────────────────────────────────
            [
                'key'   => 'block_volume',
                'type'  => 'number',
                'label' => [
                    'fa' => 'حجم بلوک',
                    'en' => 'Block Volume',
                    'ar' => 'حجم الكتلة',
                    'hi' => 'ब्लॉक आयतन',
                    'it' => 'Volume Blocco',
                ],
                'group' => [
                    'fa' => 'ابعاد و وزن',
                    'en' => 'Dimensions & Weight',
                    'ar' => 'الأبعاد والوزن',
                    'hi' => 'आयाम और वजन',
                    'it' => 'Dimensioni e Peso',
                ],
                'unit'                 => 'm3',
                'min_value'            => 0,
                'max_value'            => null,
                'step_value'           => 0.01,
                'default_value'        => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 7,
            ],

            // ── 8. Mine / Quarry Origin ────────────────────────────
            [
                'key'   => 'quarry_origin',
                'type'  => 'text',
                'label' => [
                    'fa' => 'معدن استخراج',
                    'en' => 'Quarry Origin',
                    'ar' => 'منشأ المحجر',
                    'hi' => 'खदान मूल',
                    'it' => 'Origine Cava',
                ],
                'group' => [
                    'fa' => 'منشأ و کیفیت',
                    'en' => 'Origin & Quality',
                    'ar' => 'المنشأ والجودة',
                    'hi' => 'मूल और गुणवत्ता',
                    'it' => 'Origine e Qualità',
                ],
                'unit'                 => null,
                'default_value'        => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 8,
            ],

            // ── 9. Country of Origin ───────────────────────────────
            [
                'key'   => 'country_of_origin',
                'type'  => 'text',
                'label' => [
                    'fa' => 'کشور مبدأ',
                    'en' => 'Country of Origin',
                    'ar' => 'بلد المنشأ',
                    'hi' => 'मूल देश',
                    'it' => 'Paese di Origine',
                ],
                'group' => [
                    'fa' => 'منشأ و کیفیت',
                    'en' => 'Origin & Quality',
                    'ar' => 'المنشأ والجودة',
                    'hi' => 'मूल और गुणवत्ता',
                    'it' => 'Origine e Qualità',
                ],
                'unit'                 => null,
                'default_value'        => 'Iran',
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 9,
            ],

            // ── 10. Quality Grade ───────────────────────────────────
            [
                'key'   => 'quality_grade',
                'type'  => 'select',
                'label' => [
                    'fa' => 'درجه کیفیت',
                    'en' => 'Quality Grade',
                    'ar' => 'درجة الجودة',
                    'hi' => 'गुणवत्ता ग्रेड',
                    'it' => 'Grado di Qualità',
                ],
                'group' => [
                    'fa' => 'منشأ و کیفیت',
                    'en' => 'Origin & Quality',
                    'ar' => 'المنشأ والجودة',
                    'hi' => 'मूल और गुणवत्ता',
                    'it' => 'Origine e Qualità',
                ],
                'options' => [
                    ['key' => 'premium',  'label' => ['fa' => 'ممتاز (درجه یک)', 'en' => 'Premium (Grade A)', 'ar' => 'ممتاز (الدرجة أ)', 'hi' => 'प्रीमियम (ग्रेड ए)', 'it' => 'Premium (Grado A)']],
                    ['key' => 'standard', 'label' => ['fa' => 'استاندارد (درجه دو)', 'en' => 'Standard (Grade B)', 'ar' => 'قياسي (الدرجة ب)', 'hi' => 'स्टैंडर्ड (ग्रेड बी)', 'it' => 'Standard (Grado B)']],
                    ['key' => 'commercial', 'label' => ['fa' => 'تجاری (درجه سه)', 'en' => 'Commercial (Grade C)', 'ar' => 'تجاري (الدرجة ج)', 'hi' => 'कमर्शियल (ग्रेड सी)', 'it' => 'Commerciale (Grado C)']],
                ],
                'unit'                 => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 10,
            ],

            // ── 11. Pattern / Veining ───────────────────────────────
            [
                'key'   => 'pattern',
                'type'  => 'select',
                'label' => [
                    'fa' => 'نوع طرح و رگه',
                    'en' => 'Pattern / Veining',
                    'ar' => 'النمط / التعريق',
                    'hi' => 'पैटर्न / वेनिंग',
                    'it' => 'Motivo / Venatura',
                ],
                'group' => [
                    'fa' => 'ظاهر و رنگ',
                    'en' => 'Appearance & Color',
                    'ar' => 'المظهر واللون',
                    'hi' => 'दिखावट और रंग',
                    'it' => 'Aspetto e Colore',
                ],
                'options' => [
                    ['key' => 'uniform',  'label' => ['fa' => 'یکدست', 'en' => 'Uniform', 'ar' => 'متجانس', 'hi' => 'समान', 'it' => 'Uniforme']],
                    ['key' => 'veined',   'label' => ['fa' => 'رگه‌دار', 'en' => 'Veined', 'ar' => 'معرّق', 'hi' => 'धारीदार', 'it' => 'Venato']],
                    ['key' => 'wavy',     'label' => ['fa' => 'موجی', 'en' => 'Wavy', 'ar' => 'متموج', 'hi' => 'लहरदार', 'it' => 'Ondulato']],
                    ['key' => 'spotted',  'label' => ['fa' => 'لکه‌دار', 'en' => 'Spotted', 'ar' => 'مبقع', 'hi' => 'धब्बेदार', 'it' => 'Macchiato']],
                ],
                'unit'                 => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 11,
            ],

            // ── 12. Water Absorption ────────────────────────────────
            [
                'key'   => 'water_absorption',
                'type'  => 'number',
                'label' => [
                    'fa' => 'میزان جذب آب',
                    'en' => 'Water Absorption',
                    'ar' => 'امتصاص الماء',
                    'hi' => 'जल अवशोषण',
                    'it' => 'Assorbimento d\'Acqua',
                ],
                'group' => [
                    'fa' => 'مشخصات فنی',
                    'en' => 'Technical Specifications',
                    'ar' => 'المواصفات التقنية',
                    'hi' => 'तकनीकी विशिष्टताएं',
                    'it' => 'Specifiche Tecniche',
                ],
                'unit'                 => '%',
                'min_value'            => 0,
                'max_value'            => 100,
                'step_value'           => 0.01,
                'default_value'        => null,
                'is_filterable'        => false,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 12,
            ],

            // ── 13. Compressive Strength ─────────────────────────────
            [
                'key'   => 'compressive_strength',
                'type'  => 'number',
                'label' => [
                    'fa' => 'مقاومت فشاری',
                    'en' => 'Compressive Strength',
                    'ar' => 'قوة الضغط',
                    'hi' => 'संपीड़न शक्ति',
                    'it' => 'Resistenza alla Compressione',
                ],
                'group' => [
                    'fa' => 'مشخصات فنی',
                    'en' => 'Technical Specifications',
                    'ar' => 'المواصفات التقنية',
                    'hi' => 'तकनीकी विशिष्टताएं',
                    'it' => 'Specifiche Tecniche',
                ],
                'unit'                 => 'MPa',
                'min_value'            => 0,
                'max_value'            => null,
                'step_value'           => 0.1,
                'default_value'        => null,
                'is_filterable'        => false,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 13,
            ],

            // ── 14. Density ───────────────────────────────────────
            [
                'key'   => 'density',
                'type'  => 'number',
                'label' => [
                    'fa' => 'چگالی',
                    'en' => 'Density',
                    'ar' => 'الكثافة',
                    'hi' => 'घनत्व',
                    'it' => 'Densità',
                ],
                'group' => [
                    'fa' => 'مشخصات فنی',
                    'en' => 'Technical Specifications',
                    'ar' => 'المواصفات التقنية',
                    'hi' => 'तकनीकी विशिष्टताएं',
                    'it' => 'Specifiche Tecniche',
                ],
                'unit'                 => 'g/cm3',
                'min_value'            => 0,
                'max_value'            => null,
                'step_value'           => 0.01,
                'default_value'        => null,
                'is_filterable'        => false,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 14,
            ],

            // ── 15. Frost Resistant ───────────────────────────────
            [
                'key'   => 'frost_resistant',
                'type'  => 'bool',
                'label' => [
                    'fa' => 'مقاوم در برابر یخ‌زدگی',
                    'en' => 'Frost Resistant',
                    'ar' => 'مقاوم للصقيع',
                    'hi' => 'ठंढ प्रतिरोधी',
                    'it' => 'Resistente al Gelo',
                ],
                'group' => [
                    'fa' => 'مشخصات فنی',
                    'en' => 'Technical Specifications',
                    'ar' => 'المواصفات التقنية',
                    'hi' => 'तकनीकी विशिष्टताएं',
                    'it' => 'Specifiche Tecniche',
                ],
                'unit'                 => null,
                'default_value'        => '0',
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 15,
            ],

            // ── 16. Suitable Usage ────────────────────────────────
            [
                'key'   => 'usage',
                'type'  => 'select',
                'label' => [
                    'fa' => 'کاربری مناسب',
                    'en' => 'Suitable Usage',
                    'ar' => 'الاستخدام المناسب',
                    'hi' => 'उपयुक्त उपयोग',
                    'it' => 'Utilizzo Consigliato',
                ],
                'group' => [
                    'fa' => 'کاربری',
                    'en' => 'Application',
                    'ar' => 'التطبيق',
                    'hi' => 'अनुप्रयोग',
                    'it' => 'Applicazione',
                ],
                'options' => [
                    ['key' => 'flooring',     'label' => ['fa' => 'کف‌پوش', 'en' => 'Flooring', 'ar' => 'أرضيات', 'hi' => 'फ़्लोरिंग', 'it' => 'Pavimentazione']],
                    ['key' => 'wall_cladding','label' => ['fa' => 'نمای دیوار', 'en' => 'Wall Cladding', 'ar' => 'تكسية الجدران', 'hi' => 'दीवार आवरण', 'it' => 'Rivestimento Murale']],
                    ['key' => 'facade',       'label' => ['fa' => 'نمای ساختمان', 'en' => 'Facade', 'ar' => 'واجهة', 'hi' => 'फ़साड', 'it' => 'Facciata']],
                    ['key' => 'countertop',   'label' => ['fa' => 'صفحه کابینت', 'en' => 'Countertop', 'ar' => 'سطح العمل', 'hi' => 'काउंटरटॉप', 'it' => 'Piano di Lavoro']],
                    ['key' => 'staircase',    'label' => ['fa' => 'پله', 'en' => 'Staircase', 'ar' => 'درج', 'hi' => 'सीढ़ी', 'it' => 'Scala']],
                    ['key' => 'landscape',    'label' => ['fa' => 'محوطه‌سازی', 'en' => 'Landscape', 'ar' => 'تنسيق الحدائق', 'hi' => 'भूदृश्य', 'it' => 'Paesaggistica']],
                    ['key' => 'pool',         'label' => ['fa' => 'استخر', 'en' => 'Pool', 'ar' => 'حمام سباحة', 'hi' => 'पूल', 'it' => 'Piscina']],
                ],
                'unit'                 => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 16,
            ],

            // ── 17. Indoor / Outdoor Use ────────────────────────────
            [
                'key'   => 'usage_environment',
                'type'  => 'select',
                'label' => [
                    'fa' => 'محل کاربری',
                    'en' => 'Indoor / Outdoor',
                    'ar' => 'داخلي / خارجي',
                    'hi' => 'इनडोर / आउटडोर',
                    'it' => 'Interno / Esterno',
                ],
                'group' => [
                    'fa' => 'کاربری',
                    'en' => 'Application',
                    'ar' => 'التطبيق',
                    'hi' => 'अनुप्रयोग',
                    'it' => 'Applicazione',
                ],
                'options' => [
                    ['key' => 'indoor',  'label' => ['fa' => 'داخلی',  'en' => 'Indoor',  'ar' => 'داخلي',  'hi' => 'इनडोर',  'it' => 'Interno']],
                    ['key' => 'outdoor', 'label' => ['fa' => 'خارجی',  'en' => 'Outdoor', 'ar' => 'خارجي',  'hi' => 'आउटडोर', 'it' => 'Esterno']],
                    ['key' => 'both',    'label' => ['fa' => 'هر دو',  'en' => 'Both',    'ar' => 'كلاهما', 'hi' => 'दोनों',  'it' => 'Entrambi']],
                ],
                'unit'                 => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 17,
            ],

            // ── 18. Slip Resistance ───────────────────────────────
            [
                'key'   => 'slip_resistance',
                'type'  => 'select',
                'label' => [
                    'fa' => 'مقاومت در برابر لغزش',
                    'en' => 'Slip Resistance',
                    'ar' => 'مقاومة الانزلاق',
                    'hi' => 'फिसलन प्रतिरोध',
                    'it' => 'Resistenza allo Scivolamento',
                ],
                'group' => [
                    'fa' => 'مشخصات فنی',
                    'en' => 'Technical Specifications',
                    'ar' => 'المواصفات التقنية',
                    'hi' => 'तकनीकी विशिष्टताएं',
                    'it' => 'Specifiche Tecniche',
                ],
                'options' => [
                    ['key' => 'low',    'label' => ['fa' => 'کم',    'en' => 'Low',    'ar' => 'منخفض', 'hi' => 'कम',    'it' => 'Bassa']],
                    ['key' => 'medium', 'label' => ['fa' => 'متوسط', 'en' => 'Medium', 'ar' => 'متوسط', 'hi' => 'मध्यम', 'it' => 'Media']],
                    ['key' => 'high',   'label' => ['fa' => 'بالا',  'en' => 'High',   'ar' => 'عالي',  'hi' => 'उच्च',  'it' => 'Alta']],
                ],
                'unit'                 => null,
                'is_filterable'        => true,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 18,
            ],

            // ── 19. Edge Profile ──────────────────────────────────
            [
                'key'   => 'edge_profile',
                'type'  => 'select',
                'label' => [
                    'fa' => 'پروفیل لبه',
                    'en' => 'Edge Profile',
                    'ar' => 'شكل الحافة',
                    'hi' => 'एज प्रोफ़ाइल',
                    'it' => 'Profilo del Bordo',
                ],
                'group' => [
                    'fa' => 'مشخصات فنی',
                    'en' => 'Technical Specifications',
                    'ar' => 'المواصفات التقنية',
                    'hi' => 'तकनीकी विशिष्टताएं',
                    'it' => 'Specifiche Tecniche',
                ],
                'options' => [
                    ['key' => 'straight', 'label' => ['fa' => 'صاف', 'en' => 'Straight', 'ar' => 'مستقيم', 'hi' => 'सीधा', 'it' => 'Dritto']],
                    ['key' => 'bullnose', 'label' => ['fa' => 'گرد (بول‌نوز)', 'en' => 'Bullnose', 'ar' => 'مستدير', 'hi' => 'बुलनोज़', 'it' => 'Bullnose']],
                    ['key' => 'beveled',  'label' => ['fa' => 'پخ‌دار', 'en' => 'Beveled', 'ar' => 'مشطوف', 'hi' => 'बेवल्ड', 'it' => 'Smussato']],
                    ['key' => 'chiseled', 'label' => ['fa' => 'تراش‌خورده', 'en' => 'Chiseled', 'ar' => 'منحوت', 'hi' => 'छेनी से तराशा', 'it' => 'Scalpellato']],
                ],
                'unit'                 => null,
                'is_filterable'        => false,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 19,
            ],

            // ── 20. Packaging Type ────────────────────────────────
            [
                'key'   => 'packaging_type',
                'type'  => 'select',
                'label' => [
                    'fa' => 'نوع بسته‌بندی',
                    'en' => 'Packaging Type',
                    'ar' => 'نوع التعبئة',
                    'hi' => 'पैकेजिंग प्रकार',
                    'it' => 'Tipo di Imballaggio',
                ],
                'group' => [
                    'fa' => 'بسته‌بندی و حمل',
                    'en' => 'Packaging & Shipping',
                    'ar' => 'التعبئة والشحن',
                    'hi' => 'पैकेजिंग और शिपिंग',
                    'it' => 'Imballaggio e Spedizione',
                ],
                'options' => [
                    ['key' => 'wooden_crate', 'label' => ['fa' => 'باکس چوبی', 'en' => 'Wooden Crate', 'ar' => 'صندوق خشبي', 'hi' => 'लकड़ी का बक्सा', 'it' => 'Cassa di Legno']],
                    ['key' => 'bundle',       'label' => ['fa' => 'بسته (باندل)', 'en' => 'Bundle', 'ar' => 'حزمة', 'hi' => 'बंडल', 'it' => 'Fascio']],
                    ['key' => 'pallet',       'label' => ['fa' => 'پالت', 'en' => 'Pallet', 'ar' => 'منصة نقالة', 'hi' => 'पैलेट', 'it' => 'Pallet']],
                    ['key' => 'loose',        'label' => ['fa' => 'فاقد بسته‌بندی', 'en' => 'Loose', 'ar' => 'سائب', 'hi' => 'खुला', 'it' => 'Sfuso']],
                ],
                'unit'                 => null,
                'is_filterable'        => false,
                'show_in_product_page' => true,
                'show_in_card'         => false,
                'is_active'            => true,
                'sort_order'           => 20,
            ],

        ];

        foreach ($attributes as $attr) {
            Attribute::updateOrCreate(
                ['key' => $attr['key']],
                $attr
            );
        }

        $this->command->info(count($attributes) . ' attributes seeded successfully.');
    }
}
