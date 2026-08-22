<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Hand-written, additive seeder for strings introduced by the reservation
 * feature (and the "all products" menu label). Uses updateOrInsert per
 * locale/key so it's safe to re-run and does NOT touch/replace the rows
 * already generated into TranslationSeeder.php.
 *
 * Run once with: php artisan db:seed --class=ReservationTranslationSeeder
 */
class ReservationTranslationSeeder extends Seeder
{
    public function run(): void
    {
        // group => [ key => [ locale => value ] ]
        $entries = [
            'admin' => [
                'admin_panel' => [
                    'fa' => 'پنل مدیریت', 'en' => 'Admin Panel', 'ar' => 'لوحة الإدارة',
                    'hi' => 'व्यवस्थापक पैनल', 'it' => 'Pannello Admin', 'zh' => '管理面板', 'tr' => 'Yönetim Paneli',
                ],
                'admin_login_subheading' => [
                    'fa' => 'برای دسترسی به پنل مدیریت وارد شوید', 'en' => 'Sign in to access the admin panel', 'ar' => 'سجّل الدخول للوصول إلى لوحة الإدارة',
                    'hi' => 'व्यवस्थापक पैनल तक पहुँचने के लिए साइन इन करें', 'it' => 'Accedi per entrare nel pannello di amministrazione', 'zh' => '登录以访问管理面板', 'tr' => 'Yönetim paneline erişmek için giriş yapın',
                ],
                'seo_autofill_from_name' => [
                    'fa' => 'پر کردن از نام محصول', 'en' => 'Fill from product name', 'ar' => 'تعبئة من اسم المنتج',
                    'hi' => 'उत्पाद नाम से भरें', 'it' => 'Compila dal nome prodotto', 'zh' => '从产品名称填充', 'tr' => 'Ürün adından doldur',
                ],
                'reservations' => [
                    'fa' => 'رزروها', 'en' => 'Reservations', 'ar' => 'الحجوزات',
                    'hi' => 'आरक्षण', 'it' => 'Prenotazioni', 'zh' => '预订', 'tr' => 'Rezervasyonlar',
                ],
                'reservation_request' => [
                    'fa' => 'درخواست رزرو', 'en' => 'Reservation Request', 'ar' => 'طلب حجز',
                    'hi' => 'आरक्षण अनुरोध', 'it' => 'Richiesta di prenotazione', 'zh' => '预订请求', 'tr' => 'Rezervasyon Talebi',
                ],
                'reservation_requests' => [
                    'fa' => 'درخواست‌های رزرو', 'en' => 'Reservation Requests', 'ar' => 'طلبات الحجز',
                    'hi' => 'आरक्षण अनुरोध', 'it' => 'Richieste di prenotazione', 'zh' => '预订请求列表', 'tr' => 'Rezervasyon Talepleri',
                ],
                'product' => [
                    'fa' => 'محصول', 'en' => 'Product', 'ar' => 'المنتج',
                    'hi' => 'उत्पाद', 'it' => 'Prodotto', 'zh' => '产品', 'tr' => 'Ürün',
                ],
                'name' => [
                    'fa' => 'نام', 'en' => 'Name', 'ar' => 'الاسم',
                    'hi' => 'नाम', 'it' => 'Nome', 'zh' => '姓名', 'tr' => 'Ad',
                ],
                'country_code' => [
                    'fa' => 'کد کشور', 'en' => 'Country Code', 'ar' => 'رمز الدولة',
                    'hi' => 'देश कोड', 'it' => 'Prefisso', 'zh' => '国家代码', 'tr' => 'Ülke Kodu',
                ],
                'phone' => [
                    'fa' => 'تلفن', 'en' => 'Phone', 'ar' => 'الهاتف',
                    'hi' => 'फ़ोन', 'it' => 'Telefono', 'zh' => '电话', 'tr' => 'Telefon',
                ],
                'contact_method' => [
                    'fa' => 'روش تماس', 'en' => 'Contact Method', 'ar' => 'طريقة التواصل',
                    'hi' => 'संपर्क का तरीका', 'it' => 'Metodo di contatto', 'zh' => '联系方式', 'tr' => 'İletişim Yöntemi',
                ],
                'whatsapp' => [
                    'fa' => 'واتساپ', 'en' => 'WhatsApp', 'ar' => 'واتساب',
                    'hi' => 'व्हाट्सएप', 'it' => 'WhatsApp', 'zh' => 'WhatsApp', 'tr' => 'WhatsApp',
                ],
                'phone_call' => [
                    'fa' => 'تماس تلفنی', 'en' => 'Phone Call', 'ar' => 'مكالمة هاتفية',
                    'hi' => 'फ़ोन कॉल', 'it' => 'Chiamata telefonica', 'zh' => '电话', 'tr' => 'Telefon Görüşmesi',
                ],
                'customer_note' => [
                    'fa' => 'یادداشت مشتری', 'en' => 'Customer Note', 'ar' => 'ملاحظة العميل',
                    'hi' => 'ग्राहक टिप्पणी', 'it' => 'Nota del cliente', 'zh' => '客户备注', 'tr' => 'Müşteri Notu',
                ],
                'decision' => [
                    'fa' => 'تصمیم', 'en' => 'Decision', 'ar' => 'القرار',
                    'hi' => 'निर्णय', 'it' => 'Decisione', 'zh' => '决定', 'tr' => 'Karar',
                ],
                'status' => [
                    'fa' => 'وضعیت', 'en' => 'Status', 'ar' => 'الحالة',
                    'hi' => 'स्थिति', 'it' => 'Stato', 'zh' => '状态', 'tr' => 'Durum',
                ],
                'reserved_until' => [
                    'fa' => 'رزرو تا', 'en' => 'Reserved Until', 'ar' => 'محجوز حتى',
                    'hi' => 'तक आरक्षित', 'it' => 'Riservato fino a', 'zh' => '预订至', 'tr' => 'Rezerve Bitiş',
                ],
                'admin_note' => [
                    'fa' => 'یادداشت ادمین', 'en' => 'Admin Note', 'ar' => 'ملاحظة المسؤول',
                    'hi' => 'व्यवस्थापक टिप्पणी', 'it' => 'Nota admin', 'zh' => '管理员备注', 'tr' => 'Yönetici Notu',
                ],
                'approve' => [
                    'fa' => 'تایید', 'en' => 'Approve', 'ar' => 'موافقة',
                    'hi' => 'स्वीकृत करें', 'it' => 'Approva', 'zh' => '批准', 'tr' => 'Onayla',
                ],
                'reject' => [
                    'fa' => 'رد', 'en' => 'Reject', 'ar' => 'رفض',
                    'hi' => 'अस्वीकार करें', 'it' => 'Rifiuta', 'zh' => '拒绝', 'tr' => 'Reddet',
                ],
                'details' => [
                    'fa' => 'جزئیات', 'en' => 'Details', 'ar' => 'التفاصيل',
                    'hi' => 'विवरण', 'it' => 'Dettagli', 'zh' => '详情', 'tr' => 'Detaylar',
                ],
                'release_reservation' => [
                    'fa' => 'آزادسازی رزرو', 'en' => 'Release Reservation', 'ar' => 'إلغاء الحجز',
                    'hi' => 'आरक्षण जारी करें', 'it' => 'Rilascia prenotazione', 'zh' => '释放预订', 'tr' => 'Rezervasyonu Serbest Bırak',
                ],
                'requested_at' => [
                    'fa' => 'تاریخ درخواست', 'en' => 'Requested At', 'ar' => 'تاريخ الطلب',
                    'hi' => 'अनुरोध तिथि', 'it' => 'Richiesto il', 'zh' => '请求时间', 'tr' => 'Talep Tarihi',
                ],
                'reservation_status_pending' => [
                    'fa' => 'در انتظار بررسی', 'en' => 'Pending', 'ar' => 'قيد الانتظار',
                    'hi' => 'लंबित', 'it' => 'In attesa', 'zh' => '待处理', 'tr' => 'Beklemede',
                ],
                'reservation_status_approved' => [
                    'fa' => 'تایید شده', 'en' => 'Approved', 'ar' => 'موافق عليه',
                    'hi' => 'स्वीकृत', 'it' => 'Approvato', 'zh' => '已批准', 'tr' => 'Onaylandı',
                ],
                'reservation_status_rejected' => [
                    'fa' => 'رد شده', 'en' => 'Rejected', 'ar' => 'مرفوض',
                    'hi' => 'अस्वीकृत', 'it' => 'Rifiutato', 'zh' => '已拒绝', 'tr' => 'Reddedildi',
                ],
                'reservation_status_expired' => [
                    'fa' => 'منقضی شده', 'en' => 'Expired', 'ar' => 'منتهي الصلاحية',
                    'hi' => 'समाप्त', 'it' => 'Scaduto', 'zh' => '已过期', 'tr' => 'Süresi Doldu',
                ],
                'reservation_status_cancelled' => [
                    'fa' => 'لغو شده', 'en' => 'Cancelled', 'ar' => 'ملغى',
                    'hi' => 'रद्द', 'it' => 'Annullato', 'zh' => '已取消', 'tr' => 'İptal Edildi',
                ],
                'reservation_approved' => [
                    'fa' => 'رزرو با موفقیت تایید شد', 'en' => 'Reservation approved successfully', 'ar' => 'تمت الموافقة على الحجز بنجاح',
                    'hi' => 'आरक्षण सफलतापूर्वक स्वीकृत हुआ', 'it' => 'Prenotazione approvata con successo', 'zh' => '预订已成功批准', 'tr' => 'Rezervasyon başarıyla onaylandı',
                ],
                'reservation_rejected' => [
                    'fa' => 'رزرو رد شد', 'en' => 'Reservation rejected', 'ar' => 'تم رفض الحجز',
                    'hi' => 'आरक्षण अस्वीकृत', 'it' => 'Prenotazione rifiutata', 'zh' => '预订已拒绝', 'tr' => 'Rezervasyon reddedildi',
                ],
                'reservation_released' => [
                    'fa' => 'رزرو آزاد شد و محصول در دسترس قرار گرفت', 'en' => 'Reservation released, product is available again', 'ar' => 'تم إلغاء الحجز وأصبح المنتج متاحًا',
                    'hi' => 'आरक्षण जारी किया गया, उत्पाद फिर उपलब्ध है', 'it' => 'Prenotazione rilasciata, prodotto di nuovo disponibile', 'zh' => '预订已释放，产品重新可用', 'tr' => 'Rezervasyon serbest bırakıldı, ürün tekrar mevcut',
                ],
                'reservation_product_unavailable' => [
                    'fa' => 'این محصول در حال حاضر برای رزرو در دسترس نیست', 'en' => 'This product is no longer available to reserve', 'ar' => 'هذا المنتج لم يعد متاحًا للحجز',
                    'hi' => 'यह उत्पाद अब आरक्षण के लिए उपलब्ध नहीं है', 'it' => 'Questo prodotto non è più disponibile per la prenotazione', 'zh' => '该产品已不可预订', 'tr' => 'Bu ürün artık rezervasyona uygun değil',
                ],
                'reservation_hold_duration' => [
                    'fa' => 'مدت زمان نگه‌داری رزرو', 'en' => 'Reservation Hold Duration', 'ar' => 'مدة الاحتفاظ بالحجز',
                    'hi' => 'आरक्षण होल्ड अवधि', 'it' => 'Durata blocco prenotazione', 'zh' => '预订保留时长', 'tr' => 'Rezervasyon Tutma Süresi',
                ],
                'reservation_hold_duration_help' => [
                    'fa' => 'پس از تایید ادمین، محصول برای این مدت به‌عنوان «رزرو شده» قفل می‌شود و کسی نمی‌تواند برای آن درخواست جدیدی ثبت کند.',
                    'en' => 'Once approved, the product is locked as "reserved" for this long and no new reservation requests can be made for it.',
                    'ar' => 'بعد الموافقة، يبقى المنتج "محجوزًا" لهذه المدة ولا يمكن تقديم طلبات حجز جديدة له.',
                    'hi' => 'स्वीकृति के बाद, उत्पाद इतनी अवधि के लिए "आरक्षित" रहेगा और इसके लिए नया अनुरोध नहीं किया जा सकेगा।',
                    'it' => 'Una volta approvato, il prodotto resta bloccato come "riservato" per questo periodo e non si possono inviare nuove richieste.',
                    'zh' => '获批后，产品将在此时长内锁定为"已预订"，期间无法提交新的预订请求。',
                    'tr' => 'Onaylandıktan sonra ürün bu süre boyunca "rezerve" olarak kilitlenir ve yeni talep alınamaz.',
                ],
                'reservation_days' => [
                    'fa' => 'روز', 'en' => 'Days', 'ar' => 'أيام',
                    'hi' => 'दिन', 'it' => 'Giorni', 'zh' => '天', 'tr' => 'Gün',
                ],
                'reservation_hours' => [
                    'fa' => 'ساعت', 'en' => 'Hours', 'ar' => 'ساعات',
                    'hi' => 'घंटे', 'it' => 'Ore', 'zh' => '小时', 'tr' => 'Saat',
                ],
            ],
            'messages' => [
                'reserve_product' => [
                    'fa' => 'درخواست رزرو', 'en' => 'Reserve Product', 'ar' => 'طلب حجز المنتج',
                    'hi' => 'उत्पाद आरक्षित करें', 'it' => 'Prenota prodotto', 'zh' => '预订产品', 'tr' => 'Ürünü Rezerve Et',
                ],
                'reservation_intro' => [
                    'fa' => 'شماره تماس خود را وارد کنید تا پس از تایید ادمین، این محصول برای شما رزرو شود.',
                    'en' => 'Enter your phone number — once an admin approves it, this product will be reserved for you.',
                    'ar' => 'أدخل رقم هاتفك — بمجرد موافقة المسؤول، سيتم حجز هذا المنتج لك.',
                    'hi' => 'अपना फ़ोन नंबर दर्ज करें — प्रशासक की स्वीकृति के बाद यह उत्पाद आपके लिए आरक्षित हो जाएगा।',
                    'it' => 'Inserisci il tuo numero di telefono: una volta approvato dall\'amministratore, questo prodotto sarà riservato per te.',
                    'zh' => '请输入您的电话号码——管理员批准后，此产品将为您保留。',
                    'tr' => 'Telefon numaranızı girin — yönetici onayladıktan sonra bu ürün sizin için rezerve edilecektir.',
                ],
                'full_name' => [
                    'fa' => 'نام و نام خانوادگی', 'en' => 'Full Name', 'ar' => 'الاسم الكامل',
                    'hi' => 'पूरा नाम', 'it' => 'Nome completo', 'zh' => '姓名', 'tr' => 'Ad Soyad',
                ],
                'phone_number' => [
                    'fa' => 'شماره تماس', 'en' => 'Phone Number', 'ar' => 'رقم الهاتف',
                    'hi' => 'फ़ोन नंबर', 'it' => 'Numero di telefono', 'zh' => '电话号码', 'tr' => 'Telefon Numarası',
                ],
                'other_country' => [
                    'fa' => 'سایر کشورها', 'en' => 'Other', 'ar' => 'دولة أخرى',
                    'hi' => 'अन्य', 'it' => 'Altro', 'zh' => '其他', 'tr' => 'Diğer',
                ],
                'preferred_contact_method' => [
                    'fa' => 'روش ارتباط ترجیحی', 'en' => 'Preferred Contact Method', 'ar' => 'طريقة التواصل المفضلة',
                    'hi' => 'पसंदीदा संपर्क तरीका', 'it' => 'Metodo di contatto preferito', 'zh' => '首选联系方式', 'tr' => 'Tercih Edilen İletişim Yöntemi',
                ],
                'phone_call' => [
                    'fa' => 'تماس تلفنی', 'en' => 'Phone Call', 'ar' => 'مكالمة هاتفية',
                    'hi' => 'फ़ोन कॉल', 'it' => 'Chiamata telefonica', 'zh' => '电话', 'tr' => 'Telefon Görüşmesi',
                ],
                'whatsapp' => [
                    'fa' => 'واتساپ', 'en' => 'WhatsApp', 'ar' => 'واتساب',
                    'hi' => 'व्हाट्सएप', 'it' => 'WhatsApp', 'zh' => 'WhatsApp', 'tr' => 'WhatsApp',
                ],
                'note_optional' => [
                    'fa' => 'یادداشت (اختیاری)', 'en' => 'Note (optional)', 'ar' => 'ملاحظة (اختياري)',
                    'hi' => 'टिप्पणी (वैकल्पिक)', 'it' => 'Nota (facoltativa)', 'zh' => '备注（可选）', 'tr' => 'Not (isteğe bağlı)',
                ],
                'cancel' => [
                    'fa' => 'انصراف', 'en' => 'Cancel', 'ar' => 'إلغاء',
                    'hi' => 'रद्द करें', 'it' => 'Annulla', 'zh' => '取消', 'tr' => 'İptal',
                ],
                'submit_reservation_request' => [
                    'fa' => 'ارسال درخواست رزرو', 'en' => 'Submit Reservation Request', 'ar' => 'إرسال طلب الحجز',
                    'hi' => 'आरक्षण अनुरोध भेजें', 'it' => 'Invia richiesta di prenotazione', 'zh' => '提交预订请求', 'tr' => 'Rezervasyon Talebini Gönder',
                ],
                'reservation_already_pending' => [
                    'fa' => 'برای این محصول قبلاً درخواست رزرو ثبت شده و در انتظار بررسی است.',
                    'en' => 'A reservation request for this product has already been submitted and is pending review.',
                    'ar' => 'تم تقديم طلب حجز لهذا المنتج بالفعل وهو قيد المراجعة.',
                    'hi' => 'इस उत्पाद के लिए आरक्षण अनुरोध पहले ही सबमिट किया जा चुका है और समीक्षाधीन है।',
                    'it' => 'È già stata inviata una richiesta di prenotazione per questo prodotto ed è in attesa di revisione.',
                    'zh' => '该产品的预订请求已提交，正在等待审核。',
                    'tr' => 'Bu ürün için zaten bir rezervasyon talebi gönderildi ve inceleniyor.',
                ],
                'reservation_request_sent' => [
                    'fa' => 'درخواست رزرو شما ثبت شد. پس از تایید ادمین با شما تماس گرفته می‌شود.',
                    'en' => 'Your reservation request has been submitted. We will contact you once it is approved.',
                    'ar' => 'تم إرسال طلب الحجز الخاص بك. سنتواصل معك بعد الموافقة.',
                    'hi' => 'आपका आरक्षण अनुरोध सबमिट कर दिया गया है। स्वीकृति के बाद हम आपसे संपर्क करेंगे।',
                    'it' => 'La tua richiesta di prenotazione è stata inviata. Ti contatteremo dopo l\'approvazione.',
                    'zh' => '您的预订请求已提交，批准后我们将与您联系。',
                    'tr' => 'Rezervasyon talebiniz gönderildi. Onaylandığında sizinle iletişime geçeceğiz.',
                ],
                'reservation_product_unavailable' => [
                    'fa' => 'این محصول در حال حاضر برای رزرو در دسترس نیست.',
                    'en' => 'This product is not currently available to reserve.',
                    'ar' => 'هذا المنتج غير متاح حاليًا للحجز.',
                    'hi' => 'यह उत्पाद फिलहाल आरक्षण के लिए उपलब्ध नहीं है।',
                    'it' => 'Questo prodotto non è attualmente disponibile per la prenotazione.',
                    'zh' => '该产品目前不可预订。',
                    'tr' => 'Bu ürün şu anda rezervasyona uygun değil.',
                ],
                'all_products' => [
                    'fa' => 'همه محصولات', 'en' => 'All Products', 'ar' => 'كل المنتجات',
                    'hi' => 'सभी उत्पाद', 'it' => 'Tutti i prodotti', 'zh' => '所有产品', 'tr' => 'Tüm Ürünler',
                ],
                'about_product' => [
                    'fa' => 'درباره محصول', 'en' => 'About Product', 'ar' => 'عن المنتج',
                    'hi' => 'उत्पाद के बारे में', 'it' => 'Sul prodotto', 'zh' => '产品简介', 'tr' => 'Ürün Hakkında',
                ],
                'product_specs' => [
                    'fa' => 'ویژگی‌های محصول', 'en' => 'Product Specs', 'ar' => 'مواصفات المنتج',
                    'hi' => 'उत्पाद विशेषताएँ', 'it' => 'Specifiche del prodotto', 'zh' => '产品规格', 'tr' => 'Ürün Özellikleri',
                ],
                'dimensions' => [
                    'fa' => 'ابعاد', 'en' => 'Dimensions', 'ar' => 'الأبعاد',
                    'hi' => 'आयाम', 'it' => 'Dimensioni', 'zh' => '尺寸', 'tr' => 'Boyutlar',
                ],
                // Override: drop the word "موجود" (available) from the homepage
                // section title per request — was "جدیدترین سنگ‌های موجود".
                'latest_stones' => [
                    'fa' => 'جدیدترین سنگ‌ها',
                    'en' => 'Latest Stones',
                    'ar' => 'أحدث الأحجار',
                    'hi' => 'नवीनतम पत्थर',
                    'it' => 'Ultime Pietre',
                    'zh' => '最新石材',
                    'tr' => 'En Yeni Taşlar',
                ],
            ],
        ];

        $now = now();
        $touched = [];

        foreach ($entries as $group => $keys) {
            foreach ($keys as $key => $locales) {
                foreach ($locales as $locale => $value) {
                    DB::table('translations')->updateOrInsert(
                        ['locale' => $locale, 'group' => $group, 'key' => $key],
                        ['value' => $value, 'is_auto' => 0, 'created_at' => $now, 'updated_at' => $now]
                    );
                    $touched["{$locale}.{$group}"] = true;
                }
            }
        }

        // Inserted via the query builder, so Translation's saved-event cache
        // invalidation never fired — clear it manually per locale/group.
        foreach (array_keys($touched) as $pair) {
            [$locale, $group] = explode('.', $pair, 2);
            \Illuminate\Support\Facades\Cache::forget("translations.{$locale}.{$group}");
        }
    }
}
