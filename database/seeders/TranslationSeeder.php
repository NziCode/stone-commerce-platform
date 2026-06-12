<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translation;

class TranslationSeeder extends Seeder
{
    private array $keys = [

        'admin' => [

            // ── Navigation & Groups ───────────────────────────────
            'dashboard'        => ['fa' => 'داشبورد',         'en' => 'Dashboard',        'ar' => 'لوحة التحكم',     'hi' => 'डैशबोर्ड',    'it' => 'Dashboard'],
            'products'         => ['fa' => 'محصولات',         'en' => 'Products',         'ar' => 'المنتجات',        'hi' => 'उत्पाद',       'it' => 'Prodotti'],
            'categories'       => ['fa' => 'دسته‌بندی‌ها',   'en' => 'Categories',       'ar' => 'الفئات',          'hi' => 'श्रेणियाँ',    'it' => 'Categorie'],
            'orders'           => ['fa' => 'سفارشات',         'en' => 'Orders',           'ar' => 'الطلبات',         'hi' => 'ऑर्डर',       'it' => 'Ordini'],
            'payments'         => ['fa' => 'پرداخت‌ها',      'en' => 'Payments',         'ar' => 'المدفوعات',       'hi' => 'भुगतान',      'it' => 'Pagamenti'],
            'users'            => ['fa' => 'کاربران',         'en' => 'Users',            'ar' => 'المستخدمون',      'hi' => 'उपयोगकर्ता',  'it' => 'Utenti'],
            'roles'            => ['fa' => 'نقش‌ها',          'en' => 'Roles',            'ar' => 'الأدوار',         'hi' => 'भूमिकाएँ',    'it' => 'Ruoli'],
            'permissions'      => ['fa' => 'دسترسی‌ها',      'en' => 'Permissions',      'ar' => 'الصلاحيات',       'hi' => 'अनुमतियाँ',   'it' => 'Permessi'],
            'sliders'          => ['fa' => 'اسلایدرها',       'en' => 'Sliders',          'ar' => 'الشرائح',         'hi' => 'स्लाइडर',     'it' => 'Slider'],
            'pages'            => ['fa' => 'صفحات',           'en' => 'Pages',            'ar' => 'الصفحات',         'hi' => 'पृष्ठ',       'it' => 'Pagine'],
            'menus'            => ['fa' => 'منوها',           'en' => 'Menus',            'ar' => 'القوائم',         'hi' => 'मेनू',        'it' => 'Menu'],
            'posts'            => ['fa' => 'اخبار',           'en' => 'News',             'ar' => 'الأخبار',         'hi' => 'समाचार',      'it' => 'Notizie'],
            'events'           => ['fa' => 'نمایشگاه‌ها',    'en' => 'Exhibitions',      'ar' => 'المعارض',         'hi' => 'प्रदर्शनी',   'it' => 'Fiere'],
            'settings'         => ['fa' => 'تنظیمات',        'en' => 'Settings',         'ar' => 'الإعدادات',       'hi' => 'सेटिंग्स',    'it' => 'Impostazioni'],
            'languages'        => ['fa' => 'زبان‌ها',         'en' => 'Languages',        'ar' => 'اللغات',          'hi' => 'भाषाएँ',      'it' => 'Lingue'],
            'contact_messages' => ['fa' => 'پیام‌های تماس',  'en' => 'Contact Messages', 'ar' => 'رسائل التواصل',   'hi' => 'संपर्क संदेश', 'it' => 'Messaggi di Contatto'],
            'reports'          => ['fa' => 'گزارشات',        'en' => 'Reports',          'ar' => 'التقارير',        'hi' => 'रिपोर्ट',     'it' => 'Rapporti'],
            'content'          => ['fa' => 'محتوا',          'en' => 'Content',          'ar' => 'المحتوى',         'hi' => 'सामग्री',     'it' => 'Contenuto'],
            'appearance'       => ['fa' => 'ظاهر سایت',      'en' => 'Appearance',       'ar' => 'المظهر',          'hi' => 'दिखावट',      'it' => 'Aspetto'],
            'management'       => ['fa' => 'مدیریت',         'en' => 'Management',       'ar' => 'الإدارة',         'hi' => 'प्रबंधन',     'it' => 'Gestione'],
            'store'            => ['fa' => 'فروشگاه',        'en' => 'Store',            'ar' => 'المتجر',          'hi' => 'दुकान',       'it' => 'Negozio'],

            // ── Resource Labels ───────────────────────────────────
            'coupons'          => ['fa' => 'کوپن‌ها',        'en' => 'Coupons',          'ar' => 'الكوبونات',       'hi' => 'कूपन',        'it' => 'Coupon'],
            'newsletters'      => ['fa' => 'خبرنامه',        'en' => 'Newsletter',       'ar' => 'النشرة البريدية', 'hi' => 'न्यूज़लेटर',  'it' => 'Newsletter'],
            'redirects'        => ['fa' => 'ریدایرکت‌ها',   'en' => 'Redirects',        'ar' => 'إعادة التوجيه',   'hi' => 'रीडायरेक्ट',  'it' => 'Reindirizzamenti'],
            'reviews'          => ['fa' => 'نظرات',          'en' => 'Reviews',          'ar' => 'التقييمات',       'hi' => 'समीक्षाएं',   'it' => 'Recensioni'],
            'translations'     => ['fa' => 'ترجمه‌ها',       'en' => 'Translations',     'ar' => 'الترجمات',        'hi' => 'अनुवाद',      'it' => 'Traduzioni'],

            // ── CRUD Actions ──────────────────────────────────────
            'create'       => ['fa' => 'ایجاد',         'en' => 'Create',       'ar' => 'إنشاء',       'hi' => 'बनाएं',       'it' => 'Crea'],
            'edit'         => ['fa' => 'ویرایش',        'en' => 'Edit',         'ar' => 'تعديل',       'hi' => 'संपादित',     'it' => 'Modifica'],
            'delete'       => ['fa' => 'حذف',           'en' => 'Delete',       'ar' => 'حذف',         'hi' => 'हटाएं',      'it' => 'Elimina'],
            'save'         => ['fa' => 'ذخیره',         'en' => 'Save',         'ar' => 'حفظ',         'hi' => 'सहेजें',     'it' => 'Salva'],
            'cancel'       => ['fa' => 'لغو',           'en' => 'Cancel',       'ar' => 'إلغاء',       'hi' => 'रद्द करें',  'it' => 'Annulla'],
            'back'         => ['fa' => 'بازگشت',        'en' => 'Back',         'ar' => 'رجوع',        'hi' => 'वापस',       'it' => 'Indietro'],
            'search'       => ['fa' => 'جستجو',         'en' => 'Search',       'ar' => 'بحث',         'hi' => 'खोजें',      'it' => 'Cerca'],
            'filter'       => ['fa' => 'فیلتر',         'en' => 'Filter',       'ar' => 'تصفية',       'hi' => 'फ़िल्टर',    'it' => 'Filtra'],
            'export'       => ['fa' => 'خروجی',         'en' => 'Export',       'ar' => 'تصدير',       'hi' => 'निर्यात',    'it' => 'Esporta'],
            'import'       => ['fa' => 'ورودی',         'en' => 'Import',       'ar' => 'استيراد',     'hi' => 'आयात',       'it' => 'Importa'],
            'confirm'      => ['fa' => 'تأیید',         'en' => 'Confirm',      'ar' => 'تأكيد',       'hi' => 'पुष्टि करें', 'it' => 'Conferma'],
            'view'         => ['fa' => 'مشاهده',        'en' => 'View',         'ar' => 'عرض',         'hi' => 'देखें',      'it' => 'Visualizza'],
            'restore'      => ['fa' => 'بازیابی',       'en' => 'Restore',      'ar' => 'استعادة',     'hi' => 'पुनर्स्थापित','it' => 'Ripristina'],
            'force_delete' => ['fa' => 'حذف دائمی',     'en' => 'Force Delete', 'ar' => 'حذف نهائي',   'hi' => 'स्थायी हटाएं','it' => 'Elimina Definitivamente'],
            'approve'      => ['fa' => 'تأیید',         'en' => 'Approve',      'ar' => 'قبول',        'hi' => 'स्वीकृत',    'it' => 'Approva'],
            'reject'       => ['fa' => 'رد',            'en' => 'Reject',       'ar' => 'رفض',         'hi' => 'अस्वीकार',   'it' => 'Rifiuta'],
            'upload'       => ['fa' => 'آپلود',         'en' => 'Upload',       'ar' => 'رفع',         'hi' => 'अपलोड',      'it' => 'Carica'],
            'download'     => ['fa' => 'دانلود',        'en' => 'Download',     'ar' => 'تحميل',       'hi' => 'डाउनलोड',    'it' => 'Scarica'],
            'print'        => ['fa' => 'چاپ',           'en' => 'Print',        'ar' => 'طباعة',       'hi' => 'प्रिंट',     'it' => 'Stampa'],

            // ── Status Values ─────────────────────────────────────
            'status'      => ['fa' => 'وضعیت',       'en' => 'Status',      'ar' => 'الحالة',        'hi' => 'स्थिति',     'it' => 'Stato'],
            'active'      => ['fa' => 'فعال',         'en' => 'Active',      'ar' => 'نشط',           'hi' => 'सक्रिय',     'it' => 'Attivo'],
            'inactive'    => ['fa' => 'غیرفعال',      'en' => 'Inactive',    'ar' => 'غير نشط',       'hi' => 'निष्क्रिय',  'it' => 'Inattivo'],
            'published'   => ['fa' => 'منتشر شده',    'en' => 'Published',   'ar' => 'منشور',         'hi' => 'प्रकाशित',   'it' => 'Pubblicato'],
            'draft'       => ['fa' => 'پیش‌نویس',     'en' => 'Draft',       'ar' => 'مسودة',         'hi' => 'मसौदा',      'it' => 'Bozza'],
            'archived'    => ['fa' => 'آرشیو',        'en' => 'Archived',    'ar' => 'مؤرشف',         'hi' => 'संग्रहीत',   'it' => 'Archiviato'],
            'available'   => ['fa' => 'موجود',        'en' => 'Available',   'ar' => 'متاح',          'hi' => 'उपलब्ध',     'it' => 'Disponibile'],
            'unavailable' => ['fa' => 'ناموجود',      'en' => 'Unavailable', 'ar' => 'غير متاح',      'hi' => 'अनुपलब्ध',   'it' => 'Non Disponibile'],
            'reserved'    => ['fa' => 'رزرو شده',     'en' => 'Reserved',    'ar' => 'محجوز',         'hi' => 'आरक्षित',    'it' => 'Riservato'],
            'sold'        => ['fa' => 'فروخته شده',   'en' => 'Sold',        'ar' => 'مباع',          'hi' => 'बिका हुआ',   'it' => 'Venduto'],
            'pending'     => ['fa' => 'در انتظار',    'en' => 'Pending',     'ar' => 'قيد الانتظار',  'hi' => 'लंबित',      'it' => 'In Attesa'],
            'processing'  => ['fa' => 'در حال بررسی', 'en' => 'Processing',  'ar' => 'قيد المعالجة',  'hi' => 'प्रसंस्करण', 'it' => 'In Elaborazione'],
            'confirmed'   => ['fa' => 'تأیید شده',    'en' => 'Confirmed',   'ar' => 'مؤكد',          'hi' => 'पुष्टि हुई', 'it' => 'Confermato'],
            'shipped'     => ['fa' => 'ارسال شده',    'en' => 'Shipped',     'ar' => 'تم الشحن',      'hi' => 'भेजा गया',   'it' => 'Spedito'],
            'delivered'   => ['fa' => 'تحویل داده',   'en' => 'Delivered',   'ar' => 'تم التسليم',    'hi' => 'डिलीवर हुआ', 'it' => 'Consegnato'],
            'cancelled'   => ['fa' => 'لغو شده',      'en' => 'Cancelled',   'ar' => 'ملغى',          'hi' => 'रद्द हुआ',   'it' => 'Annullato'],
            'refunded'    => ['fa' => 'مسترد شده',    'en' => 'Refunded',    'ar' => 'مسترد',         'hi' => 'वापसी',      'it' => 'Rimborsato'],
            'paid'        => ['fa' => 'پرداخت شده',   'en' => 'Paid',        'ar' => 'مدفوع',         'hi' => 'भुगतान',     'it' => 'Pagato'],
            'failed'      => ['fa' => 'ناموفق',       'en' => 'Failed',      'ar' => 'فشل',           'hi' => 'विफल',       'it' => 'Fallito'],
            'upcoming'    => ['fa' => 'آینده',         'en' => 'Upcoming',    'ar' => 'قادم',          'hi' => 'आगामी',      'it' => 'Prossimo'],
            'ongoing'     => ['fa' => 'در حال برگزاری','en'=> 'Ongoing',     'ar' => 'جارٍ',          'hi' => 'चल रहा',     'it' => 'In Corso'],
            'finished'    => ['fa' => 'پایان یافته',  'en' => 'Finished',    'ar' => 'منتهي',         'hi' => 'समाप्त',     'it' => 'Terminato'],

            // ── Flash Messages ────────────────────────────────────
            'created_successfully'  => ['fa' => 'با موفقیت ایجاد شد',         'en' => 'Created successfully',   'ar' => 'تم الإنشاء بنجاح',    'hi' => 'सफलतापूर्वक बनाया',   'it' => 'Creato con successo'],
            'updated_successfully'  => ['fa' => 'با موفقیت به‌روزرسانی شد',   'en' => 'Updated successfully',   'ar' => 'تم التحديث بنجاح',    'hi' => 'सफलतापूर्वक अपडेट',   'it' => 'Aggiornato con successo'],
            'deleted_successfully'  => ['fa' => 'با موفقیت حذف شد',           'en' => 'Deleted successfully',   'ar' => 'تم الحذف بنجاح',      'hi' => 'सफलतापूर्वक हटाया',   'it' => 'Eliminato con successo'],
            'are_you_sure'          => ['fa' => 'آیا مطمئن هستید؟',           'en' => 'Are you sure?',          'ar' => 'هل أنت متأكد؟',       'hi' => 'क्या आप सुनिश्चित हैं?','it' => 'Sei sicuro?'],
            'no_records'            => ['fa' => 'رکوردی یافت نشد',            'en' => 'No records found',       'ar' => 'لا توجد سجلات',       'hi' => 'कोई रिकॉर्ड नहीं',    'it' => 'Nessun record trovato'],
            'select_option'         => ['fa' => 'انتخاب کنید',                'en' => 'Select an option',       'ar' => 'اختر خياراً',         'hi' => 'विकल्प चुनें',        'it' => 'Seleziona un\'opzione'],
            'required_field'        => ['fa' => 'این فیلد الزامی است',        'en' => 'This field is required', 'ar' => 'هذا الحقل مطلوب',     'hi' => 'यह फ़ील्ड आवश्यक है',  'it' => 'Campo obbligatorio'],
            'invalid_format'        => ['fa' => 'فرمت نامعتبر است',           'en' => 'Invalid format',         'ar' => 'تنسيق غير صالح',      'hi' => 'अमान्य प्रारूप',      'it' => 'Formato non valido'],
            'permission_denied'     => ['fa' => 'دسترسی ندارید',              'en' => 'Permission denied',      'ar' => 'الوصول مرفوض',        'hi' => 'अनुमति नहीं',         'it' => 'Accesso negato'],
            'payment_verified'      => ['fa' => 'پرداخت تأیید شد',            'en' => 'Payment verified',       'ar' => 'تم التحقق من الدفع',   'hi' => 'भुगतान सत्यापित',     'it' => 'Pagamento verificato'],
            'payment_rejected'      => ['fa' => 'پرداخت رد شد',               'en' => 'Payment rejected',       'ar' => 'تم رفض الدفع',        'hi' => 'भुगतान अस्वीकृत',     'it' => 'Pagamento rifiutato'],

            // ── Table / Form Field Labels ─────────────────────────
            'key'                   => ['fa' => 'کلید',            'en' => 'Key',              'ar' => 'المفتاح',         'hi' => 'कुंजी',       'it' => 'Chiave'],
            'value'                 => ['fa' => 'مقدار',           'en' => 'Value',            'ar' => 'القيمة',          'hi' => 'मान',         'it' => 'Valore'],
            'group'                 => ['fa' => 'گروه',            'en' => 'Group',            'ar' => 'المجموعة',        'hi' => 'समूह',        'it' => 'Gruppo'],
            'language'              => ['fa' => 'زبان',            'en' => 'Language',         'ar' => 'اللغة',           'hi' => 'भाषा',        'it' => 'Lingua'],
            'title'                 => ['fa' => 'عنوان',           'en' => 'Title',            'ar' => 'العنوان',         'hi' => 'शीर्षक',      'it' => 'Titolo'],
            'description'           => ['fa' => 'توضیحات',         'en' => 'Description',      'ar' => 'الوصف',           'hi' => 'विवरण',       'it' => 'Descrizione'],
            'slug'                  => ['fa' => 'اسلاگ',           'en' => 'Slug',             'ar' => 'الرابط',          'hi' => 'स्लग',        'it' => 'Slug'],
            'image'                 => ['fa' => 'تصویر',           'en' => 'Image',            'ar' => 'الصورة',          'hi' => 'छवि',         'it' => 'Immagine'],
            'gallery'               => ['fa' => 'گالری',           'en' => 'Gallery',          'ar' => 'المعرض',          'hi' => 'गैलरी',       'it' => 'Galleria'],
            'price'                 => ['fa' => 'قیمت',            'en' => 'Price',            'ar' => 'السعر',           'hi' => 'मूल्य',       'it' => 'Prezzo'],
            'sku'                   => ['fa' => 'کد محصول',        'en' => 'SKU',              'ar' => 'رمز المنتج',      'hi' => 'SKU',         'it' => 'SKU'],
            'name'                  => ['fa' => 'نام',             'en' => 'Name',             'ar' => 'الاسم',           'hi' => 'नाम',         'it' => 'Nome'],
            'email'                 => ['fa' => 'ایمیل',           'en' => 'Email',            'ar' => 'البريد',          'hi' => 'ईमेल',        'it' => 'Email'],
            'phone'                 => ['fa' => 'تلفن',            'en' => 'Phone',            'ar' => 'الهاتف',          'hi' => 'फ़ोन',        'it' => 'Telefono'],
            'address'               => ['fa' => 'آدرس',            'en' => 'Address',          'ar' => 'العنوان',         'hi' => 'पता',         'it' => 'Indirizzo'],
            'country'               => ['fa' => 'کشور',            'en' => 'Country',          'ar' => 'البلد',           'hi' => 'देश',         'it' => 'Paese'],
            'company'               => ['fa' => 'شرکت',            'en' => 'Company',          'ar' => 'الشركة',          'hi' => 'कंपनी',       'it' => 'Azienda'],
            'password'              => ['fa' => 'رمز عبور',        'en' => 'Password',         'ar' => 'كلمة المرور',     'hi' => 'पासवर्ड',     'it' => 'Password'],
            'is_featured'           => ['fa' => 'ویژه',            'en' => 'Featured',         'ar' => 'مميز',            'hi' => 'विशेष',       'it' => 'In Evidenza'],
            'is_active'             => ['fa' => 'فعال',            'en' => 'Active',           'ar' => 'نشط',             'hi' => 'सक्रिय',      'it' => 'Attivo'],
            'sort_order'            => ['fa' => 'ترتیب',           'en' => 'Order',            'ar' => 'الترتيب',         'hi' => 'क्रम',        'it' => 'Ordine'],
            'created_at'            => ['fa' => 'تاریخ ایجاد',     'en' => 'Created At',       'ar' => 'تاريخ الإنشاء',   'hi' => 'बनाया गया',   'it' => 'Creato Il'],
            'updated_at'            => ['fa' => 'آخرین ویرایش',    'en' => 'Updated At',       'ar' => 'آخر تحديث',       'hi' => 'अपडेट समय',   'it' => 'Aggiornato'],
            'published_at'          => ['fa' => 'تاریخ انتشار',    'en' => 'Published At',     'ar' => 'تاريخ النشر',     'hi' => 'प्रकाशित',    'it' => 'Pubblicato Il'],
            'views_count'           => ['fa' => 'بازدید',          'en' => 'Views',            'ar' => 'المشاهدات',       'hi' => 'दृश्य',       'it' => 'Visualizzazioni'],
            'notes'                 => ['fa' => 'یادداشت',         'en' => 'Notes',            'ar' => 'ملاحظات',         'hi' => 'टिप्पणी',     'it' => 'Note'],
            'total'                 => ['fa' => 'مبلغ کل',         'en' => 'Total',            'ar' => 'المجموع',         'hi' => 'कुल',         'it' => 'Totale'],
            'subtotal'              => ['fa' => 'جمع جزء',         'en' => 'Subtotal',         'ar' => 'المجموع الفرعي',  'hi' => 'उपकुल',       'it' => 'Subtotale'],
            'discount'              => ['fa' => 'تخفیف',           'en' => 'Discount',         'ar' => 'الخصم',           'hi' => 'छूट',         'it' => 'Sconto'],
            'currency'              => ['fa' => 'ارز',             'en' => 'Currency',         'ar' => 'العملة',          'hi' => 'मुद्रा',      'it' => 'Valuta'],
            'gateway'               => ['fa' => 'درگاه پرداخت',    'en' => 'Payment Gateway',  'ar' => 'بوابة الدفع',     'hi' => 'गेटवे',       'it' => 'Gateway'],
            'transaction_id'        => ['fa' => 'شناسه تراکنش',    'en' => 'Transaction ID',   'ar' => 'رقم العملية',     'hi' => 'लेन-देन ID',  'it' => 'ID Transazione'],
            'receipt_file'          => ['fa' => 'فایل رسید',       'en' => 'Receipt File',     'ar' => 'ملف الإيصال',     'hi' => 'रसीद फ़ाइल',  'it' => 'File Ricevuta'],
            'order_number'          => ['fa' => 'شماره سفارش',     'en' => 'Order Number',     'ar' => 'رقم الطلب',       'hi' => 'ऑर्डर नंबर',  'it' => 'Numero Ordine'],

            // ── Translation Management ────────────────────────────
            'auto_translated'       => ['fa' => 'ترجمه خودکار',    'en' => 'Auto Translated',  'ar' => 'ترجمة تلقائية',   'hi' => 'स्वचालित',    'it' => 'Auto Tradotto'],
            'generate_lang_files'   => ['fa' => 'تولید فایل‌های lang','en'=> 'Generate Lang Files','ar'=> 'إنشاء ملفات اللغة','hi'=> 'फ़ाइलें बनाएं','it'=> 'Genera File Lang'],
            'lang_files_generated'  => ['fa' => 'فایل‌های lang تولید شدند','en'=> 'Lang files generated','ar'=> 'تم إنشاء الملفات','hi'=> 'फ़ाइलें बनाई गईं','it'=> 'File generati'],

            // ── SEO Fields ────────────────────────────────────────
            'meta_title'            => ['fa' => 'عنوان SEO',        'en' => 'Meta Title',       'ar' => 'عنوان SEO',       'hi' => 'मेटा शीर्षक', 'it' => 'Meta Titolo'],
            'meta_description'      => ['fa' => 'توضیح SEO',        'en' => 'Meta Description', 'ar' => 'وصف SEO',         'hi' => 'मेटा विवरण',  'it' => 'Meta Descrizione'],
            'meta_keywords'         => ['fa' => 'کلمات کلیدی',      'en' => 'Meta Keywords',    'ar' => 'الكلمات المفتاحية','hi' => 'कीवर्ड',      'it' => 'Parole Chiave'],
            'og_image'              => ['fa' => 'تصویر شبکه اجتماعی','en'=> 'OG Image',         'ar' => 'صورة OG',         'hi' => 'OG छवि',      'it' => 'Immagine OG'],

            // ── Dashboard Widgets ─────────────────────────────────
            'revenue_this_month'    => ['fa' => 'درآمد این ماه',    'en' => 'Revenue This Month','ar'=> 'إيرادات هذا الشهر','hi'=> 'इस माह राजस्व','it'=> 'Entrate Questo Mese'],
            'orders_today'          => ['fa' => 'سفارشات امروز',    'en' => 'Orders Today',     'ar' => 'طلبات اليوم',     'hi' => 'आज के ऑर्डर', 'it' => 'Ordini Oggi'],
            'available_products'    => ['fa' => 'محصولات موجود',    'en' => 'Available Products','ar'=> 'المنتجات المتاحة', 'hi'=> 'उपलब्ध उत्पाद','it'=> 'Prodotti Disponibili'],
            'total_users'           => ['fa' => 'کاربران',          'en' => 'Total Users',      'ar' => 'إجمالي المستخدمين','hi'=> 'कुल उपयोगकर्ता','it'=> 'Utenti Totali'],
            'sold_products'         => ['fa' => 'محصولات فروخته شده','en'=> 'Sold Products',    'ar' => 'المنتجات المباعة', 'hi'=> 'बेचे उत्पाद',  'it'=> 'Prodotti Venduti'],
            'new_messages'          => ['fa' => 'پیام‌های جدید',    'en' => 'New Messages',     'ar' => 'رسائل جديدة',     'hi' => 'नए संदेश',    'it' => 'Nuovi Messaggi'],
            'latest_orders'         => ['fa' => 'آخرین سفارشات',    'en' => 'Latest Orders',    'ar' => 'أحدث الطلبات',    'hi' => 'नवीनतम ऑर्डर','it'=> 'Ultimi Ordini'],
            'no_orders'             => ['fa' => 'سفارشی یافت نشد',  'en' => 'No orders',        'ar' => 'لا توجد طلبات',   'hi' => 'कोई ऑर्डर नहीं','it'=> 'Nessun ordine'],

            // ── Category Form ─────────────────────────────────────
            'basic_info'            => ['fa' => 'اطلاعات پایه',         'en' => 'Basic Info',               'ar' => 'المعلومات الأساسية',    'hi' => 'बुनियादी जानकारी',    'it' => 'Info Base'],
            'parent_category'       => ['fa' => 'دسته والد',            'en' => 'Parent Category',          'ar' => 'الفئة الأم',            'hi' => 'मूल श्रेणी',           'it' => 'Categoria Padre'],
            'no_parent'             => ['fa' => 'بدون والد (دسته اصلی)','en' => 'No parent (root category)','ar' => 'بدون أصل (فئة جذرية)',  'hi' => 'कोई मूल नहीं',         'it' => 'Nessun genitore'],
            'position'              => ['fa' => 'موقعیت',               'en' => 'Position',                 'ar' => 'الموضع',                'hi' => 'स्थान',                'it' => 'Posizione'],
            'first_before_all'      => ['fa' => '⬆ اول (قبل از همه)',  'en' => '⬆ First (before all)',    'ar' => '⬆ الأول (قبل الكل)',   'hi' => '⬆ पहले (सबसे पहले)',  'it' => '⬆ Primo (prima di tutti)'],
            'after'                 => ['fa' => 'بعد از',               'en' => 'After',                    'ar' => 'بعد',                   'hi' => 'बाद में',              'it' => 'Dopo'],
            'total_siblings'        => ['fa' => 'تعداد همتایان',        'en' => 'Total siblings',           'ar' => 'إجمالي الأشقاء',        'hi' => 'कुल सहोदर',            'it' => 'Totale fratelli'],
            'sub_categories'        => ['fa' => 'زیردسته‌ها',           'en' => 'Sub-categories',           'ar' => 'الفئات الفرعية',        'hi' => 'उप श्रेणियाँ',         'it' => 'Sottocategorie'],
            'root_categories'       => ['fa' => 'دسته‌های اصلی',        'en' => 'Root Categories',          'ar' => 'الفئات الجذرية',        'hi' => 'मूल श्रेणियाँ',        'it' => 'Categorie Principali'],
            'root_only'             => ['fa' => 'فقط دسته‌های اصلی',    'en' => 'Root categories only',     'ar' => 'الفئات الجذرية فقط',   'hi' => 'केवल मूल श्रेणियाँ',   'it' => 'Solo categorie principali'],
            'attribute_schema'      => ['fa' => 'ویژگی‌های دینامیک',    'en' => 'Dynamic Attribute Schema', 'ar' => 'مخطط السمات الديناميكي','hi' => 'गतिशील विशेषता स्कीमा', 'it' => 'Schema Attributi Dinamici'],
            'attribute_schema_desc' => ['fa' => 'ویژگی‌هایی که محصولات این دسته می‌توانند داشته باشند','en' => 'Define attributes that products in this category can have','ar' => 'تعريف سمات المنتجات في هذه الفئة','hi' => 'इस श्रेणी के उत्पादों की विशेषताएं','it' => 'Definisci attributi per i prodotti di questa categoria'],
            'attribute_key_internal'=> ['fa' => 'کلید (داخلی)',         'en' => 'Key (internal)',           'ar' => 'المفتاح (داخلي)',       'hi' => 'कुंजी (आंतरिक)',       'it' => 'Chiave (interna)'],
            'type'                  => ['fa' => 'نوع',                  'en' => 'Type',                     'ar' => 'النوع',                 'hi' => 'प्रकार',               'it' => 'Tipo'],
            'attr_type_text'        => ['fa' => 'متن',                  'en' => 'Text',                     'ar' => 'نص',                    'hi' => 'पाठ',                  'it' => 'Testo'],
            'attr_type_select'      => ['fa' => 'لیست انتخابی',         'en' => 'Select (dropdown)',         'ar' => 'قائمة منسدلة',          'hi' => 'चयन (ड्रॉपडाउन)',      'it' => 'Selezione (dropdown)'],
            'attr_type_number'      => ['fa' => 'عدد',                  'en' => 'Number',                   'ar' => 'رقم',                   'hi' => 'संख्या',               'it' => 'Numero'],
            'attr_type_bool'        => ['fa' => 'بله/خیر',              'en' => 'Yes/No',                   'ar' => 'نعم/لا',                'hi' => 'हाँ/नहीं',             'it' => 'Sì/No'],
            'label_per_language'    => ['fa' => 'برچسب برای هر زبان',   'en' => 'Label per language',       'ar' => 'تسمية لكل لغة',         'hi' => 'प्रति भाषा लेबल',      'it' => 'Etichetta per lingua'],
            'locale'                => ['fa' => 'زبان',                 'en' => 'Locale',                   'ar' => 'اللغة',                 'hi' => 'स्थानीय',              'it' => 'Lingua'],
            'label'                 => ['fa' => 'برچسب',                'en' => 'Label',                    'ar' => 'تسمية',                 'hi' => 'लेबल',                 'it' => 'Etichetta'],
            'options_for_select'    => ['fa' => 'گزینه‌ها (برای لیست)', 'en' => 'Options (for select type)','ar' => 'الخيارات (للقائمة)',    'hi' => 'विकल्प (चयन के लिए)',  'it' => 'Opzioni (per selezione)'],
            'add_option'            => ['fa' => 'افزودن گزینه',         'en' => 'Add option',               'ar' => 'إضافة خيار',            'hi' => 'विकल्प जोड़ें',         'it' => 'Aggiungi opzione'],
            'add_attribute'         => ['fa' => 'افزودن ویژگی',         'en' => 'Add Attribute',            'ar' => 'إضافة سمة',             'hi' => 'विशेषता जोड़ें',        'it' => 'Aggiungi Attributo'],
            'slug_helper'           => ['fa' => 'بصورت خودکار از نام تولید می‌شود','en' => 'Auto-generated from name','ar' => 'يتم إنشاؤه تلقائيًا من الاسم','hi' => 'नाम से स्वतः उत्पन्न','it' => 'Generato automaticamente dal nome'],

            // ── UI Loading State ───────────────────────────────────
            'processing_action'     => ['fa' => 'در حال پردازش...', 'en' => 'Processing...', 'ar' => 'جارٍ المعالجة...', 'hi' => 'प्रसंस्करण हो रहा है...', 'it' => 'Elaborazione...'],
        ],

        // ── Frontend Messages ─────────────────────────────────────
        'messages' => [
            'welcome'      => ['fa' => 'خوش آمدید',       'en' => 'Welcome',             'ar' => 'مرحباً',          'hi' => 'स्वागत',      'it' => 'Benvenuto'],
            'home'         => ['fa' => 'خانه',             'en' => 'Home',                'ar' => 'الرئيسية',        'hi' => 'होम',         'it' => 'Home'],
            'products'     => ['fa' => 'محصولات',          'en' => 'Products',            'ar' => 'المنتجات',        'hi' => 'उत्पाद',      'it' => 'Prodotti'],
            'categories'   => ['fa' => 'دسته‌بندی‌ها',    'en' => 'Categories',          'ar' => 'الفئات',          'hi' => 'श्रेणियाँ',   'it' => 'Categorie'],
            'about'        => ['fa' => 'درباره ما',        'en' => 'About Us',            'ar' => 'من نحن',          'hi' => 'हमारे बारे',  'it' => 'Chi Siamo'],
            'contact'      => ['fa' => 'تماس با ما',       'en' => 'Contact Us',          'ar' => 'اتصل بنا',        'hi' => 'संपर्क करें',  'it' => 'Contattaci'],
            'cart'         => ['fa' => 'سبد خرید',         'en' => 'Cart',                'ar' => 'سلة التسوق',      'hi' => 'कार्ट',       'it' => 'Carrello'],
            'checkout'     => ['fa' => 'تسویه حساب',       'en' => 'Checkout',            'ar' => 'إتمام الشراء',    'hi' => 'चेकआउट',      'it' => 'Pagamento'],
            'login'        => ['fa' => 'ورود',             'en' => 'Login',               'ar' => 'تسجيل الدخول',    'hi' => 'लॉगिन',       'it' => 'Accedi'],
            'register'     => ['fa' => 'ثبت نام',          'en' => 'Register',            'ar' => 'التسجيل',         'hi' => 'रजिस्टर',     'it' => 'Registrati'],
            'logout'       => ['fa' => 'خروج',             'en' => 'Logout',              'ar' => 'تسجيل الخروج',    'hi' => 'लॉगआउट',      'it' => 'Esci'],
            'profile'      => ['fa' => 'پروفایل',          'en' => 'Profile',             'ar' => 'الملف الشخصي',    'hi' => 'प्रोफ़ाइल',   'it' => 'Profilo'],
            'orders'       => ['fa' => 'سفارشات',          'en' => 'Orders',              'ar' => 'الطلبات',         'hi' => 'ऑर्डर',       'it' => 'Ordini'],
            'news'         => ['fa' => 'اخبار',            'en' => 'News',                'ar' => 'الأخبار',         'hi' => 'समाचार',      'it' => 'Notizie'],
            'exhibitions'  => ['fa' => 'نمایشگاه‌ها',     'en' => 'Exhibitions',         'ar' => 'المعارض',         'hi' => 'प्रदर्शनी',   'it' => 'Fiere'],
            'search'       => ['fa' => 'جستجو',            'en' => 'Search',              'ar' => 'بحث',             'hi' => 'खोजें',       'it' => 'Cerca'],
            'read_more'    => ['fa' => 'ادامه مطلب',       'en' => 'Read More',           'ar' => 'اقرأ المزيد',     'hi' => 'और पढ़ें',    'it' => 'Leggi tutto'],
            'add_to_cart'  => ['fa' => 'افزودن به سبد',   'en' => 'Add to Cart',         'ar' => 'أضف للسلة',       'hi' => 'कार्ट में',   'it' => 'Aggiungi'],
            'buy_now'      => ['fa' => 'خرید همین الان',  'en' => 'Buy Now',             'ar' => 'اشتري الآن',      'hi' => 'अभी खरीदें',  'it' => 'Acquista'],
            'price'        => ['fa' => 'قیمت',             'en' => 'Price',               'ar' => 'السعر',           'hi' => 'मूल्य',       'it' => 'Prezzo'],
            'quantity'     => ['fa' => 'تعداد',            'en' => 'Quantity',            'ar' => 'الكمية',          'hi' => 'मात्रा',      'it' => 'Quantità'],
            'total'        => ['fa' => 'مجموع',            'en' => 'Total',               'ar' => 'المجموع',         'hi' => 'कुल',         'it' => 'Totale'],
            'submit'       => ['fa' => 'ارسال',            'en' => 'Submit',              'ar' => 'إرسال',           'hi' => 'सबमिट',       'it' => 'Invia'],
            'send_message' => ['fa' => 'ارسال پیام',       'en' => 'Send Message',        'ar' => 'إرسال رسالة',     'hi' => 'संदेश भेजें',  'it' => 'Invia Messaggio'],
            'name'         => ['fa' => 'نام',              'en' => 'Name',                'ar' => 'الاسم',           'hi' => 'नाम',         'it' => 'Nome'],
            'email'        => ['fa' => 'ایمیل',            'en' => 'Email',               'ar' => 'البريد',          'hi' => 'ईमेल',        'it' => 'Email'],
            'phone'        => ['fa' => 'تلفن',             'en' => 'Phone',               'ar' => 'الهاتف',          'hi' => 'फ़ोन',        'it' => 'Telefono'],
            'message'      => ['fa' => 'پیام',             'en' => 'Message',             'ar' => 'الرسالة',         'hi' => 'संदेश',       'it' => 'Messaggio'],
            'address'      => ['fa' => 'آدرس',             'en' => 'Address',             'ar' => 'العنوان',         'hi' => 'पता',         'it' => 'Indirizzo'],
            'country'      => ['fa' => 'کشور',             'en' => 'Country',             'ar' => 'البلد',           'hi' => 'देश',         'it' => 'Paese'],
            'company'      => ['fa' => 'شرکت',             'en' => 'Company',             'ar' => 'الشركة',          'hi' => 'कंपनी',       'it' => 'Azienda'],
            'password'     => ['fa' => 'رمز عبور',         'en' => 'Password',            'ar' => 'كلمة المرور',     'hi' => 'पासवर्ड',     'it' => 'Password'],
            'no_products'  => ['fa' => 'محصولی یافت نشد', 'en' => 'No products found',   'ar' => 'لا توجد منتجات',   'hi' => 'कोई उत्पाद नहीं','it'=> 'Nessun prodotto'],
            'loading'      => ['fa' => 'در حال بارگذاری', 'en' => 'Loading...',          'ar' => 'جارٍ التحميل',    'hi' => 'लोड हो रहा',  'it' => 'Caricamento...'],
            'error'        => ['fa' => 'خطایی رخ داد',    'en' => 'An error occurred',   'ar' => 'حدث خطأ',         'hi' => 'त्रुटि हुई',  'it' => 'Errore'],
            'success'      => ['fa' => 'عملیات موفق',     'en' => 'Operation successful','ar' => 'تمت العملية',     'hi' => 'सफलता',       'it' => 'Successo'],
            'inquiry'      => ['fa' => 'استعلام قیمت',    'en' => 'Price Inquiry',       'ar' => 'استفسار السعر',   'hi' => 'मूल्य पूछताछ', 'it' => 'Richiesta Prezzo'],
            'contact_sent' => ['fa' => 'پیام ارسال شد',   'en' => 'Message sent',        'ar' => 'تم إرسال الرسالة','hi' => 'संदेश भेजा गया','it'=> 'Messaggio inviato'],
            'not_found'    => ['fa' => 'صفحه یافت نشد',   'en' => 'Page not found',      'ar' => 'الصفحة غير موجودة','hi'=> 'पृष्ठ नहीं मिला','it'=> 'Pagina non trovata'],
        ],
    ];

    public function run(): void
    {
        foreach ($this->keys as $group => $keys) {
            foreach ($keys as $key => $locales) {
                foreach ($locales as $locale => $value) {
                    Translation::updateOrCreate(
                        ['locale' => $locale, 'group' => $group, 'key' => $key],
                        ['value' => $value, 'is_auto' => false]
                    );
                }
            }
        }

        $this->command->info(Translation::count() . ' translations seeded successfully.');
    }
}
