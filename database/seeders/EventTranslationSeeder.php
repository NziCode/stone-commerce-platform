<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * UI strings of the exhibitions pages (list, filters, detail page, lightbox).
 * Additive and safe to re-run: uses updateOrInsert per locale/key and does not
 * touch any other translation row.
 *
 * Run with: php artisan db:seed --class=EventTranslationSeeder
 */
class EventTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // key => [ locale => value ]  (all in the `messages` group)
        $entries = [
            'exh_intro' => [
                'fa' => 'نمایشگاه‌های سنگ که در آن‌ها شرکت می‌کنیم؛ گزارش تصویری دوره‌های برگزار شده و اطلاعات نمایشگاه‌های پیش‌رو.',
                'en' => 'Stone exhibitions we take part in — photo reports of past editions and details of upcoming ones.',
                'ar' => 'معارض الحجر التي نشارك فيها — تقارير مصوّرة للدورات السابقة ومعلومات عن المعارض القادمة.',
                'hi' => 'पत्थर की उन प्रदर्शनियों की जानकारी जिनमें हम भाग लेते हैं — पिछले संस्करणों की फ़ोटो रिपोर्ट और आगामी प्रदर्शनियों का विवरण।',
                'it' => 'Le fiere della pietra a cui partecipiamo: reportage fotografici delle edizioni passate e dettagli sulle prossime.',
                'zh' => '我们参加的石材展会——往届展会图片报道及即将举办的展会信息。',
                'tr' => 'Katıldığımız taş fuarları — geçmiş edisyonların fotoğraf raporları ve yaklaşan fuarların ayrıntıları.',
            ],
            'exh_filter_all' => [
                'fa' => 'همه', 'en' => 'All', 'ar' => 'الكل', 'hi' => 'सभी', 'it' => 'Tutte', 'zh' => '全部', 'tr' => 'Tümü',
            ],
            'exh_filter_ongoing' => [
                'fa' => 'در حال برگزاری و پیش‌رو', 'en' => 'Ongoing & upcoming', 'ar' => 'الجارية والقادمة',
                'hi' => 'जारी और आगामी', 'it' => 'In corso e prossime', 'zh' => '进行中与即将举办', 'tr' => 'Devam eden ve yaklaşan',
            ],
            'exh_filter_held' => [
                'fa' => 'نمایشگاه‌های برگزار شده', 'en' => 'Past exhibitions', 'ar' => 'المعارض السابقة',
                'hi' => 'पिछली प्रदर्शनियाँ', 'it' => 'Fiere passate', 'zh' => '往届展会', 'tr' => 'Geçmiş fuarlar',
            ],
            'exh_status_upcoming' => [
                'fa' => 'پیش‌رو', 'en' => 'Upcoming', 'ar' => 'قادم', 'hi' => 'आगामी', 'it' => 'In arrivo', 'zh' => '即将举办', 'tr' => 'Yaklaşan',
            ],
            'exh_status_ongoing' => [
                'fa' => 'در حال برگزاری', 'en' => 'Ongoing', 'ar' => 'جارٍ الآن', 'hi' => 'जारी', 'it' => 'In corso', 'zh' => '进行中', 'tr' => 'Devam ediyor',
            ],
            'exh_status_finished' => [
                'fa' => 'برگزار شده', 'en' => 'Completed', 'ar' => 'انتهى', 'hi' => 'सम्पन्न', 'it' => 'Concluso', 'zh' => '已结束', 'tr' => 'Tamamlandı',
            ],
            'exh_status_cancelled' => [
                'fa' => 'لغو شده', 'en' => 'Cancelled', 'ar' => 'أُلغي', 'hi' => 'रद्द', 'it' => 'Annullato', 'zh' => '已取消', 'tr' => 'İptal edildi',
            ],
            'exh_empty_held' => [
                'fa' => 'هنوز نمایشگاه برگزار شده‌ای ثبت نشده است.',
                'en' => 'No past exhibitions have been added yet.',
                'ar' => 'لم تتم إضافة أي معارض سابقة بعد.',
                'hi' => 'अभी तक कोई पिछली प्रदर्शनी नहीं जोड़ी गई है।',
                'it' => 'Nessuna fiera passata è stata ancora aggiunta.',
                'zh' => '暂无往届展会。',
                'tr' => 'Henüz geçmiş fuar eklenmedi.',
            ],
            'exh_empty_ongoing' => [
                'fa' => 'در حال حاضر نمایشگاه در حال برگزاری یا پیش‌رویی ثبت نشده است. به‌زودی اطلاع‌رسانی می‌کنیم.',
                'en' => 'There are no ongoing or upcoming exhibitions right now. We will announce the next one soon.',
                'ar' => 'لا توجد معارض جارية أو قادمة حالياً. سنعلن عن المعرض القادم قريباً.',
                'hi' => 'अभी कोई जारी या आगामी प्रदर्शनी नहीं है। अगली प्रदर्शनी की घोषणा जल्द की जाएगी।',
                'it' => 'Al momento non ci sono fiere in corso o in programma. Annunceremo presto la prossima.',
                'zh' => '目前没有进行中或即将举办的展会，下一场展会将尽快公布。',
                'tr' => 'Şu anda devam eden veya yaklaşan fuar yok. Bir sonrakini yakında duyuracağız.',
            ],
            'exh_photo_count' => [
                'fa' => ':count عکس', 'en' => ':count photos', 'ar' => ':count صورة', 'hi' => ':count फ़ोटो',
                'it' => ':count foto', 'zh' => ':count 张照片', 'tr' => ':count fotoğraf',
            ],
            'exh_view_gallery' => [
                'fa' => 'مشاهده گزارش تصویری', 'en' => 'View photo report', 'ar' => 'عرض التقرير المصوّر',
                'hi' => 'फ़ोटो रिपोर्ट देखें', 'it' => 'Guarda il reportage', 'zh' => '查看图片报道', 'tr' => 'Fotoğraf raporunu gör',
            ],
            'exh_gallery' => [
                'fa' => 'گالری تصاویر', 'en' => 'Photo gallery', 'ar' => 'معرض الصور',
                'hi' => 'फ़ोटो गैलरी', 'it' => 'Galleria fotografica', 'zh' => '图片画廊', 'tr' => 'Fotoğraf galerisi',
            ],
            'exh_gallery_soon' => [
                'fa' => 'تصاویر و گزارش این نمایشگاه به‌زودی در همین صفحه منتشر می‌شود.',
                'en' => 'Photos from this exhibition will be published on this page soon.',
                'ar' => 'ستُنشر صور هذا المعرض في هذه الصفحة قريباً.',
                'hi' => 'इस प्रदर्शनी की तस्वीरें जल्द ही इसी पृष्ठ पर प्रकाशित की जाएँगी।',
                'it' => 'Le foto di questa fiera saranno presto pubblicate su questa pagina.',
                'zh' => '本届展会的图片将很快发布在此页面。',
                'tr' => 'Bu fuarın fotoğrafları yakında bu sayfada yayınlanacak.',
            ],
            'exh_videos' => [
                'fa' => 'ویدیوها', 'en' => 'Videos', 'ar' => 'مقاطع الفيديو', 'hi' => 'वीडियो', 'it' => 'Video', 'zh' => '视频', 'tr' => 'Videolar',
            ],
            'exh_details' => [
                'fa' => 'اطلاعات نمایشگاه', 'en' => 'Exhibition details', 'ar' => 'تفاصيل المعرض',
                'hi' => 'प्रदर्शनी का विवरण', 'it' => 'Dettagli della fiera', 'zh' => '展会信息', 'tr' => 'Fuar bilgileri',
            ],
            'exh_dates' => [
                'fa' => 'زمان برگزاری', 'en' => 'Dates', 'ar' => 'التاريخ', 'hi' => 'तिथियाँ', 'it' => 'Date', 'zh' => '日期', 'tr' => 'Tarih',
            ],
            'exh_venue' => [
                'fa' => 'محل برگزاری', 'en' => 'Venue', 'ar' => 'مكان الإقامة', 'hi' => 'स्थान', 'it' => 'Sede', 'zh' => '地点', 'tr' => 'Mekân',
            ],
            'exh_organizer' => [
                'fa' => 'برگزارکننده', 'en' => 'Organizer', 'ar' => 'الجهة المنظّمة', 'hi' => 'आयोजक', 'it' => 'Organizzatore', 'zh' => '主办方', 'tr' => 'Organizatör',
            ],
            'exh_dates_tbd' => [
                'fa' => 'به‌زودی اعلام می‌شود', 'en' => 'To be announced', 'ar' => 'سيُعلن عنه قريباً',
                'hi' => 'जल्द घोषित किया जाएगा', 'it' => 'Da annunciare', 'zh' => '待公布', 'tr' => 'Yakında duyurulacak',
            ],
            'exh_back' => [
                'fa' => 'بازگشت به نمایشگاه‌ها', 'en' => 'Back to exhibitions', 'ar' => 'العودة إلى المعارض',
                'hi' => 'प्रदर्शनियों पर वापस जाएँ', 'it' => 'Torna alle fiere', 'zh' => '返回展会列表', 'tr' => 'Fuarlara dön',
            ],
            'exh_other' => [
                'fa' => 'سایر نمایشگاه‌ها', 'en' => 'Other exhibitions', 'ar' => 'معارض أخرى',
                'hi' => 'अन्य प्रदर्शनियाँ', 'it' => 'Altre fiere', 'zh' => '其他展会', 'tr' => 'Diğer fuarlar',
            ],
            'exh_all_exhibitions' => [
                'fa' => 'همه نمایشگاه‌ها', 'en' => 'All exhibitions', 'ar' => 'جميع المعارض',
                'hi' => 'सभी प्रदर्शनियाँ', 'it' => 'Tutte le fiere', 'zh' => '全部展会', 'tr' => 'Tüm fuarlar',
            ],
            'exh_close' => [
                'fa' => 'بستن', 'en' => 'Close', 'ar' => 'إغلاق', 'hi' => 'बंद करें', 'it' => 'Chiudi', 'zh' => '关闭', 'tr' => 'Kapat',
            ],
            'exh_prev' => [
                'fa' => 'تصویر قبلی', 'en' => 'Previous photo', 'ar' => 'الصورة السابقة',
                'hi' => 'पिछली तस्वीर', 'it' => 'Foto precedente', 'zh' => '上一张', 'tr' => 'Önceki fotoğraf',
            ],
            'exh_next' => [
                'fa' => 'تصویر بعدی', 'en' => 'Next photo', 'ar' => 'الصورة التالية',
                'hi' => 'अगली तस्वीर', 'it' => 'Foto successiva', 'zh' => '下一张', 'tr' => 'Sonraki fotoğraf',
            ],
        ];

        // Page title used by the list, the home banner and the news sidebar —
        // only filled in where it is missing so a customised wording is kept.
        $onlyIfMissing = [
            'exhibitions' => [
                'fa' => 'نمایشگاه‌ها', 'en' => 'Exhibitions', 'ar' => 'المعارض',
                'hi' => 'प्रदर्शनियाँ', 'it' => 'Fiere', 'zh' => '展会', 'tr' => 'Fuarlar',
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

        foreach ($onlyIfMissing as $key => $locales) {
            foreach ($locales as $locale => $value) {
                $exists = DB::table('translations')
                    ->where(['locale' => $locale, 'group' => 'messages', 'key' => $key])
                    ->exists();

                if (! $exists) {
                    DB::table('translations')->insert([
                        'locale' => $locale, 'group' => 'messages', 'key' => $key,
                        'value' => $value, 'is_auto' => 0, 'created_at' => $now, 'updated_at' => $now,
                    ]);
                    $touched[$locale] = true;
                }
            }
        }

        // Query-builder writes skip the model's cache invalidation.
        foreach (array_keys($touched) as $locale) {
            Cache::forget("translations.{$locale}.messages");
        }
    }
}
