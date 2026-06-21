<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translation;

class TranslationSeeder extends Seeder
{
    private array $keys = [

        // ══════════════════════════════════════════════════════════
        //  ADMIN — Panel translations
        // ══════════════════════════════════════════════════════════
        'admin' => [

            // Navigation & resource labels
            'dashboard'        => ['fa' => 'داشبورد',          'en' => 'Dashboard',        'ar' => 'الرئيسية',        'hi' => 'मुख्य पृष्ठ',  'it' => 'Dashboard'],
            'store'            => ['fa' => 'فروشگاه',          'en' => 'Store',            'ar' => 'المتجر',          'hi' => 'स्टोर',         'it' => 'Negozio'],
            'content'          => ['fa' => 'محتوا',            'en' => 'Content',          'ar' => 'المحتوى',         'hi' => 'सामग्री',       'it' => 'Contenuto'],
            'appearance'       => ['fa' => 'ظاهر سایت',        'en' => 'Appearance',       'ar' => 'المظهر',          'hi' => 'दिखावट',        'it' => 'Aspetto'],
            'management'       => ['fa' => 'مدیریت',           'en' => 'Management',       'ar' => 'الإدارة',         'hi' => 'प्रबंधन',       'it' => 'Gestione'],
            'reports'          => ['fa' => 'گزارشات',          'en' => 'Reports',          'ar' => 'التقارير',        'hi' => 'रिपोर्ट',       'it' => 'Rapporti'],
            'products'         => ['fa' => 'محصولات',          'en' => 'Products',         'ar' => 'المنتجات',        'hi' => 'उत्पाद',        'it' => 'Prodotti'],
            'categories'       => ['fa' => 'دسته‌بندی‌ها',    'en' => 'Categories',       'ar' => 'الفئات',          'hi' => 'श्रेणियाँ',     'it' => 'Categorie'],
            'attributes'       => ['fa' => 'ویژگی‌ها',         'en' => 'Attributes',       'ar' => 'المواصفات',       'hi' => 'विशेषताएं',     'it' => 'Attributi'],
            'orders'           => ['fa' => 'سفارشات',          'en' => 'Orders',           'ar' => 'الطلبات',         'hi' => 'ऑर्डर',         'it' => 'Ordini'],
            'payments'         => ['fa' => 'پرداخت‌ها',        'en' => 'Payments',         'ar' => 'المدفوعات',       'hi' => 'भुगतान',        'it' => 'Pagamenti'],
            'users'            => ['fa' => 'کاربران',          'en' => 'Users',            'ar' => 'المستخدمون',      'hi' => 'उपयोगकर्ता',    'it' => 'Utenti'],
            'roles'            => ['fa' => 'نقش‌ها',           'en' => 'Roles',            'ar' => 'الأدوار',         'hi' => 'भूमिकाएँ',      'it' => 'Ruoli'],
            'permissions'      => ['fa' => 'دسترسی‌ها',        'en' => 'Permissions',      'ar' => 'الصلاحيات',       'hi' => 'अनुमतियाँ',     'it' => 'Permessi'],
            'sliders'          => ['fa' => 'اسلایدرها',        'en' => 'Sliders',          'ar' => 'الشرائح',         'hi' => 'स्लाइडर',       'it' => 'Slider'],
            'pages'            => ['fa' => 'صفحات',            'en' => 'Pages',            'ar' => 'الصفحات',         'hi' => 'पृष्ठ',         'it' => 'Pagine'],
            'menus'            => ['fa' => 'منوها',            'en' => 'Menus',            'ar' => 'القوائم',         'hi' => 'मेनू',           'it' => 'Menu'],
            'posts'            => ['fa' => 'اخبار',            'en' => 'News',             'ar' => 'الأخبار',         'hi' => 'समाचार',        'it' => 'Notizie'],
            'events'           => ['fa' => 'نمایشگاه‌ها',     'en' => 'Exhibitions',      'ar' => 'المعارض',         'hi' => 'प्रदर्शनी',     'it' => 'Fiere'],
            'settings'         => ['fa' => 'تنظیمات',          'en' => 'Settings',         'ar' => 'الإعدادات',       'hi' => 'सेटिंग्स',      'it' => 'Impostazioni'],
            'languages'        => ['fa' => 'زبان‌ها',          'en' => 'Languages',        'ar' => 'اللغات',          'hi' => 'भाषाएँ',        'it' => 'Lingue'],
            'translations'     => ['fa' => 'ترجمه‌ها',         'en' => 'Translations',     'ar' => 'الترجمات',        'hi' => 'अनुवाद',        'it' => 'Traduzioni'],
            'contact_messages' => ['fa' => 'پیام‌های دریافتی', 'en' => 'Inbox',            'ar' => 'الرسائل الواردة', 'hi' => 'इनबॉक्स',       'it' => 'Messaggi Ricevuti'],
            'coupons'          => ['fa' => 'کوپن‌ها',          'en' => 'Coupons',          'ar' => 'الكوبونات',       'hi' => 'कूपन',           'it' => 'Coupon'],
            'newsletters'      => ['fa' => 'خبرنامه',          'en' => 'Newsletter',       'ar' => 'النشرة البريدية', 'hi' => 'न्यूज़लेटर',    'it' => 'Newsletter'],
            'redirects'        => ['fa' => 'ریدایرکت‌ها',      'en' => 'Redirects',        'ar' => 'إعادة التوجيه',   'hi' => 'रीडायरेक्ट',    'it' => 'Reindirizzamenti'],
            'reviews'          => ['fa' => 'نظرات',            'en' => 'Reviews',          'ar' => 'التقييمات',       'hi' => 'समीक्षाएं',      'it' => 'Recensioni'],

            // CRUD actions
            'create'       => ['fa' => 'ایجاد',           'en' => 'Create',                  'ar' => 'إنشاء',           'hi' => 'बनाएं',                    'it' => 'Crea'],
            'edit'         => ['fa' => 'ویرایش',          'en' => 'Edit',                    'ar' => 'تعديل',           'hi' => 'संपादित',                  'it' => 'Modifica'],
            'delete'       => ['fa' => 'حذف',             'en' => 'Delete',                  'ar' => 'حذف',             'hi' => 'हटाएं',                    'it' => 'Elimina'],
            'save'         => ['fa' => 'ذخیره',           'en' => 'Save',                    'ar' => 'حفظ',             'hi' => 'सहेजें',                   'it' => 'Salva'],
            'cancel'       => ['fa' => 'انصراف',          'en' => 'Cancel',                  'ar' => 'إلغاء',           'hi' => 'रद्द करें',                'it' => 'Annulla'],
            'back'         => ['fa' => 'بازگشت',          'en' => 'Back',                    'ar' => 'رجوع',            'hi' => 'वापस',                     'it' => 'Indietro'],
            'search'       => ['fa' => 'جستجو',           'en' => 'Search',                  'ar' => 'بحث',             'hi' => 'खोजें',                    'it' => 'Cerca'],
            'filter'       => ['fa' => 'فیلتر',           'en' => 'Filter',                  'ar' => 'تصفية',           'hi' => 'फ़िल्टर',                  'it' => 'Filtra'],
            'export'       => ['fa' => 'خروجی بگیر',      'en' => 'Export',                  'ar' => 'تصدير',           'hi' => 'निर्यात',                  'it' => 'Esporta'],
            'import'       => ['fa' => 'وارد کن',         'en' => 'Import',                  'ar' => 'استيراد',         'hi' => 'आयात',                     'it' => 'Importa'],
            'confirm'      => ['fa' => 'تأیید',           'en' => 'Confirm',                 'ar' => 'تأكيد',           'hi' => 'पुष्टि करें',              'it' => 'Conferma'],
            'view'         => ['fa' => 'مشاهده',          'en' => 'View',                    'ar' => 'عرض',             'hi' => 'देखें',                    'it' => 'Visualizza'],
            'restore'      => ['fa' => 'بازیابی',         'en' => 'Restore',                 'ar' => 'استعادة',         'hi' => 'पुनर्स्थापित',             'it' => 'Ripristina'],
            'force_delete' => ['fa' => 'حذف دائمی',       'en' => 'Permanently Delete',      'ar' => 'حذف نهائي',       'hi' => 'स्थायी रूप से हटाएं',      'it' => 'Elimina Definitivamente'],
            'approve'      => ['fa' => 'تأیید کن',        'en' => 'Approve',                 'ar' => 'قبول',            'hi' => 'स्वीकृत करें',             'it' => 'Approva'],
            'reject'       => ['fa' => 'رد کن',           'en' => 'Reject',                  'ar' => 'رفض',             'hi' => 'अस्वीकार',                 'it' => 'Rifiuta'],
            'upload'       => ['fa' => 'آپلود',           'en' => 'Upload',                  'ar' => 'رفع الملف',       'hi' => 'अपलोड',                    'it' => 'Carica'],
            'download'     => ['fa' => 'دانلود',          'en' => 'Download',                'ar' => 'تحميل',           'hi' => 'डाउनलोड',                  'it' => 'Scarica'],
            'print'        => ['fa' => 'چاپ',             'en' => 'Print',                   'ar' => 'طباعة',           'hi' => 'प्रिंट',                   'it' => 'Stampa'],
            'activate'     => ['fa' => 'فعال کن',         'en' => 'Activate',                'ar' => 'تفعيل',           'hi' => 'सक्रिय करें',              'it' => 'Attiva'],
            'deactivate'   => ['fa' => 'غیرفعال کن',      'en' => 'Deactivate',              'ar' => 'إلغاء التفعيل',   'hi' => 'निष्क्रिय करें',           'it' => 'Disattiva'],
            'duplicate'    => ['fa' => 'کپی',             'en' => 'Duplicate',               'ar' => 'تكرار',           'hi' => 'डुप्लिकेट',                'it' => 'Duplica'],

            // Status values
            'status'      => ['fa' => 'وضعیت',           'en' => 'Status',      'ar' => 'الحالة',          'hi' => 'स्थिति',        'it' => 'Stato'],
            'active'      => ['fa' => 'فعال',             'en' => 'Active',      'ar' => 'نشط',             'hi' => 'सक्रिय',        'it' => 'Attivo'],
            'inactive'    => ['fa' => 'غیرفعال',          'en' => 'Inactive',    'ar' => 'غير نشط',         'hi' => 'निष्क्रिय',     'it' => 'Inattivo'],
            'published'   => ['fa' => 'منتشر شده',        'en' => 'Published',   'ar' => 'منشور',           'hi' => 'प्रकाशित',      'it' => 'Pubblicato'],
            'draft'       => ['fa' => 'پیش‌نویس',         'en' => 'Draft',       'ar' => 'مسودة',           'hi' => 'मसौदा',         'it' => 'Bozza'],
            'archived'    => ['fa' => 'آرشیو شده',        'en' => 'Archived',    'ar' => 'مؤرشف',           'hi' => 'संग्रहीत',      'it' => 'Archiviato'],
            'available'   => ['fa' => 'موجود',            'en' => 'Available',   'ar' => 'متاح',            'hi' => 'उपलब्ध',        'it' => 'Disponibile'],
            'unavailable' => ['fa' => 'ناموجود',          'en' => 'Unavailable', 'ar' => 'غير متاح',        'hi' => 'अनुपलब्ध',      'it' => 'Non Disponibile'],
            'reserved'    => ['fa' => 'رزرو شده',         'en' => 'Reserved',    'ar' => 'محجوز',           'hi' => 'आरक्षित',       'it' => 'Riservato'],
            'sold'        => ['fa' => 'فروخته شده',       'en' => 'Sold',        'ar' => 'مباع',            'hi' => 'बिक गया',       'it' => 'Venduto'],
            'pending'     => ['fa' => 'در انتظار',        'en' => 'Pending',     'ar' => 'قيد الانتظار',    'hi' => 'लंबित',         'it' => 'In Attesa'],
            'processing'  => ['fa' => 'در حال بررسی',    'en' => 'Processing',  'ar' => 'قيد المعالجة',    'hi' => 'प्रक्रिया में',  'it' => 'In Elaborazione'],
            'confirmed'   => ['fa' => 'تأیید شده',        'en' => 'Confirmed',   'ar' => 'مؤكد',            'hi' => 'पुष्टि हुई',    'it' => 'Confermato'],
            'shipped'     => ['fa' => 'ارسال شده',        'en' => 'Shipped',     'ar' => 'تم الشحن',        'hi' => 'भेजा गया',      'it' => 'Spedito'],
            'delivered'   => ['fa' => 'تحویل داده شده',  'en' => 'Delivered',   'ar' => 'تم التسليم',      'hi' => 'डिलीवर हुआ',    'it' => 'Consegnato'],
            'cancelled'   => ['fa' => 'لغو شده',          'en' => 'Cancelled',   'ar' => 'ملغى',            'hi' => 'रद्द हुआ',      'it' => 'Annullato'],
            'refunded'    => ['fa' => 'مسترد شده',        'en' => 'Refunded',    'ar' => 'مسترد',           'hi' => 'वापसी हुई',     'it' => 'Rimborsato'],
            'paid'        => ['fa' => 'پرداخت شده',       'en' => 'Paid',        'ar' => 'مدفوع',           'hi' => 'भुगतान हुआ',    'it' => 'Pagato'],
            'failed'      => ['fa' => 'ناموفق',           'en' => 'Failed',      'ar' => 'فشل',             'hi' => 'विफल',          'it' => 'Fallito'],
            'upcoming'    => ['fa' => 'آینده',            'en' => 'Upcoming',    'ar' => 'قادم',            'hi' => 'आगामी',         'it' => 'Prossimo'],
            'ongoing'     => ['fa' => 'در حال برگزاری',  'en' => 'Ongoing',     'ar' => 'جارٍ',            'hi' => 'चल रहा है',     'it' => 'In Corso'],
            'finished'    => ['fa' => 'پایان یافته',      'en' => 'Finished',    'ar' => 'منتهي',           'hi' => 'समाप्त',        'it' => 'Terminato'],
            'yes'         => ['fa' => 'بله',              'en' => 'Yes',         'ar' => 'نعم',             'hi' => 'हाँ',           'it' => 'Sì'],
            'no'          => ['fa' => 'خیر',              'en' => 'No',          'ar' => 'لا',              'hi' => 'नहीं',          'it' => 'No'],

            // System messages
            'created_successfully' => ['fa' => 'با موفقیت ثبت شد',              'en' => 'Saved successfully',          'ar' => 'تم الحفظ بنجاح',        'hi' => 'सफलतापूर्वक सहेजा',   'it' => 'Salvato con successo'],
            'updated_successfully' => ['fa' => 'با موفقیت به‌روز شد',           'en' => 'Updated successfully',        'ar' => 'تم التحديث بنجاح',      'hi' => 'सफलतापूर्वक अपडेट',   'it' => 'Aggiornato con successo'],
            'deleted_successfully' => ['fa' => 'با موفقیت حذف شد',              'en' => 'Deleted successfully',        'ar' => 'تم الحذف بنجاح',        'hi' => 'सफलतापूर्वक हटाया',   'it' => 'Eliminato con successo'],
            'are_you_sure'         => ['fa' => 'مطمئنی؟',                       'en' => 'Are you sure?',               'ar' => 'هل أنت متأكد؟',         'hi' => 'क्या आप सुनिश्चित हैं?','it' => 'Sei sicuro?'],
            'no_records'           => ['fa' => 'هیچ رکوردی پیدا نشد',           'en' => 'Nothing found',               'ar' => 'لا توجد نتائج',          'hi' => 'कुछ नहीं मिला',       'it' => 'Nessun risultato'],
            'select_option'        => ['fa' => 'یکی رو انتخاب کن',              'en' => 'Select an option',            'ar' => 'اختر خياراً',            'hi' => 'विकल्प चुनें',        'it' => 'Seleziona'],
            'required_field'       => ['fa' => 'این فیلد الزامیه',               'en' => 'This field is required',      'ar' => 'هذا الحقل مطلوب',        'hi' => 'यह फ़ील्ड आवश्यक है',  'it' => 'Campo obbligatorio'],
            'invalid_format'       => ['fa' => 'فرمت اشتباهه',                  'en' => 'Invalid format',              'ar' => 'تنسيق غير صالح',         'hi' => 'अमान्य प्रारूप',      'it' => 'Formato non valido'],
            'permission_denied'    => ['fa' => 'دسترسی نداری',                  'en' => 'Access denied',               'ar' => 'الوصول مرفوض',           'hi' => 'पहुंच नहीं',          'it' => 'Accesso negato'],
            'payment_verified'     => ['fa' => 'پرداخت تأیید شد',               'en' => 'Payment verified',            'ar' => 'تم التحقق من الدفع',      'hi' => 'भुगतान सत्यापित',     'it' => 'Pagamento verificato'],
            'payment_rejected'     => ['fa' => 'پرداخت رد شد',                  'en' => 'Payment rejected',            'ar' => 'تم رفض الدفع',           'hi' => 'भुगतान अस्वीकृत',     'it' => 'Pagamento rifiutato'],
            'processing_action'    => ['fa' => 'صبر کن، داره پردازش می‌شه...', 'en' => 'Processing...',               'ar' => 'جارٍ المعالجة...',        'hi' => 'प्रसंस्करण हो रहा है...','it' => 'Elaborazione...'],

            // Table & form fields
            'key'            => ['fa' => 'کلید',               'en' => 'Key',             'ar' => 'المفتاح',         'hi' => 'कुंजी',         'it' => 'Chiave'],
            'value'          => ['fa' => 'مقدار',              'en' => 'Value',           'ar' => 'القيمة',          'hi' => 'मान',           'it' => 'Valore'],
            'group'          => ['fa' => 'گروه',               'en' => 'Group',           'ar' => 'المجموعة',        'hi' => 'समूह',          'it' => 'Gruppo'],
            'language'       => ['fa' => 'زبان',               'en' => 'Language',        'ar' => 'اللغة',           'hi' => 'भाषा',          'it' => 'Lingua'],
            'title'          => ['fa' => 'عنوان',              'en' => 'Title',           'ar' => 'العنوان',         'hi' => 'शीर्षक',        'it' => 'Titolo'],
            'description'    => ['fa' => 'توضیحات',            'en' => 'Description',     'ar' => 'الوصف',           'hi' => 'विवरण',         'it' => 'Descrizione'],
            'slug'           => ['fa' => 'اسلاگ (URL)',        'en' => 'Slug (URL)',       'ar' => 'الرابط',          'hi' => 'URL स्लग',      'it' => 'Slug (URL)'],
            'image'          => ['fa' => 'تصویر',              'en' => 'Image',           'ar' => 'الصورة',          'hi' => 'छवि',           'it' => 'Immagine'],
            'gallery'        => ['fa' => 'گالری',              'en' => 'Gallery',         'ar' => 'معرض الصور',      'hi' => 'गैलरी',         'it' => 'Galleria'],
            'price'          => ['fa' => 'قیمت',               'en' => 'Price',           'ar' => 'السعر',           'hi' => 'मूल्य',         'it' => 'Prezzo'],
            'sku'            => ['fa' => 'کد محصول (SKU)',      'en' => 'SKU',             'ar' => 'رمز المنتج',      'hi' => 'SKU',           'it' => 'Codice SKU'],
            'attribute_key_internal' => ['fa' => 'کلید داخلی ویژگی', 'en' => 'Internal Attribute Key', 'ar' => 'المفتاح الداخلي للخاصية', 'hi' => 'आंतरिक विशेषता कुंजी', 'it' => 'Chiave Interna Attributo'],
            'name'           => ['fa' => 'نام',                'en' => 'Name',            'ar' => 'الاسم',           'hi' => 'नाम',           'it' => 'Nome'],
            'email'          => ['fa' => 'ایمیل',              'en' => 'Email',           'ar' => 'البريد الإلكتروني','hi' => 'ईमेल',         'it' => 'Email'],
            'phone'          => ['fa' => 'شماره تماس',         'en' => 'Phone',           'ar' => 'الهاتف',          'hi' => 'फ़ोन',          'it' => 'Telefono'],
            'address'        => ['fa' => 'آدرس',               'en' => 'Address',         'ar' => 'العنوان',         'hi' => 'पता',           'it' => 'Indirizzo'],
            'country'        => ['fa' => 'کشور',               'en' => 'Country',         'ar' => 'البلد',           'hi' => 'देश',           'it' => 'Paese'],
            'company'        => ['fa' => 'شرکت',               'en' => 'Company',         'ar' => 'الشركة',          'hi' => 'कंपनी',         'it' => 'Azienda'],
            'password'       => ['fa' => 'رمز عبور',           'en' => 'Password',        'ar' => 'كلمة المرور',     'hi' => 'पासवर्ड',       'it' => 'Password'],
            'notes'          => ['fa' => 'یادداشت',            'en' => 'Notes',           'ar' => 'ملاحظات',         'hi' => 'टिप्पणी',       'it' => 'Note'],
            'total'          => ['fa' => 'مبلغ کل',            'en' => 'Total',           'ar' => 'المجموع',         'hi' => 'कुल',           'it' => 'Totale'],
            'subtotal'       => ['fa' => 'جمع جزء',            'en' => 'Subtotal',        'ar' => 'المجموع الجزئي',  'hi' => 'उपकुल',         'it' => 'Subtotale'],
            'discount'       => ['fa' => 'تخفیف',              'en' => 'Discount',        'ar' => 'الخصم',           'hi' => 'छूट',           'it' => 'Sconto'],
            'currency'       => ['fa' => 'واحد پول',           'en' => 'Currency',        'ar' => 'العملة',          'hi' => 'मुद्रा',        'it' => 'Valuta'],
            'gateway'        => ['fa' => 'درگاه پرداخت',       'en' => 'Payment Gateway', 'ar' => 'بوابة الدفع',     'hi' => 'गेटवे',         'it' => 'Gateway'],
            'transaction_id' => ['fa' => 'شناسه تراکنش',       'en' => 'Transaction ID',  'ar' => 'رقم العملية',     'hi' => 'लेन-देन ID',    'it' => 'ID Transazione'],
            'receipt_file'   => ['fa' => 'فایل رسید بانکی',    'en' => 'Bank Receipt',    'ar' => 'ملف الإيصال',     'hi' => 'रसीद फ़ाइल',    'it' => 'Ricevuta Bancaria'],
            'order_number'   => ['fa' => 'شماره سفارش',        'en' => 'Order #',         'ar' => 'رقم الطلب',       'hi' => 'ऑर्डर नंबर',    'it' => 'N° Ordine'],
            'is_featured'    => ['fa' => 'محصول ویژه',         'en' => 'Featured',        'ar' => 'منتج مميز',       'hi' => 'विशेष उत्पाद',  'it' => 'In Evidenza'],
            'is_active'      => ['fa' => 'فعال باشد',          'en' => 'Active',          'ar' => 'نشط',             'hi' => 'सक्रिय',        'it' => 'Attivo'],
            'sort_order'     => ['fa' => 'ترتیب نمایش',        'en' => 'Display Order',   'ar' => 'ترتيب العرض',     'hi' => 'प्रदर्शन क्रम', 'it' => 'Ordine di Visualizzazione'],
            'created_at'     => ['fa' => 'تاریخ ایجاد',        'en' => 'Created At',      'ar' => 'تاريخ الإنشاء',   'hi' => 'बनाने की तारीख', 'it' => 'Creato Il'],
            'updated_at'     => ['fa' => 'آخرین ویرایش',       'en' => 'Last Updated',    'ar' => 'آخر تحديث',       'hi' => 'अंतिम अपडेट',   'it' => 'Ultimo Aggiornamento'],
            'published_at'   => ['fa' => 'تاریخ انتشار',       'en' => 'Published At',    'ar' => 'تاريخ النشر',     'hi' => 'प्रकाशन तिथि',  'it' => 'Pubblicato Il'],
            'views_count'    => ['fa' => 'تعداد بازدید',       'en' => 'Views',           'ar' => 'المشاهدات',       'hi' => 'दृश्य',         'it' => 'Visualizzazioni'],
            'type'           => ['fa' => 'نوع',                'en' => 'Type',            'ar' => 'النوع',           'hi' => 'प्रकार',        'it' => 'Tipo'],
            'label'          => ['fa' => 'برچسب',              'en' => 'Label',           'ar' => 'تسمية',           'hi' => 'लेबल',          'it' => 'Etichetta'],
            'locale'         => ['fa' => 'زبان',               'en' => 'Locale',          'ar' => 'اللغة',           'hi' => 'भाषा कोड',      'it' => 'Lingua'],
            'position'       => ['fa' => 'جایگاه',             'en' => 'Position',        'ar' => 'الموضع',          'hi' => 'स्थान',         'it' => 'Posizione'],

            // Dashboard widgets
            'revenue_this_month' => ['fa' => 'درآمد این ماه',       'en' => 'Revenue This Month', 'ar' => 'إيرادات هذا الشهر', 'hi' => 'इस माह राजस्व',  'it' => 'Entrate Questo Mese'],
            'orders_today'       => ['fa' => 'سفارشات امروز',       'en' => 'Orders Today',       'ar' => 'طلبات اليوم',       'hi' => 'आज के ऑर्डर',    'it' => 'Ordini Oggi'],
            'available_products' => ['fa' => 'محصولات موجود',       'en' => 'Available Products', 'ar' => 'المنتجات المتاحة',  'hi' => 'उपलब्ध उत्पाद',  'it' => 'Prodotti Disponibili'],
            'total_users'        => ['fa' => 'تعداد کاربران',       'en' => 'Total Users',        'ar' => 'إجمالي المستخدمين','hi' => 'कुल उपयोगकर्ता', 'it' => 'Utenti Totali'],
            'sold_products'      => ['fa' => 'محصولات فروخته شده',  'en' => 'Sold Products',      'ar' => 'المنتجات المباعة',  'hi' => 'बेचे उत्पाद',    'it' => 'Prodotti Venduti'],
            'new_messages'       => ['fa' => 'پیام‌های جدید',       'en' => 'New Messages',       'ar' => 'رسائل جديدة',       'hi' => 'नए संदेश',        'it' => 'Nuovi Messaggi'],
            'latest_orders'      => ['fa' => 'آخرین سفارشات',       'en' => 'Latest Orders',      'ar' => 'أحدث الطلبات',      'hi' => 'नवीनतम ऑर्डर',   'it' => 'Ultimi Ordini'],
            'no_orders'          => ['fa' => 'هنوز سفارشی ثبت نشده','en' => 'No orders yet',      'ar' => 'لا توجد طلبات بعد', 'hi' => 'अभी तक कोई ऑर्डर नहीं','it' => 'Nessun ordine ancora'],

            // SEO fields
            'meta_title'       => ['fa' => 'عنوان SEO',          'en' => 'Meta Title',       'ar' => 'عنوان SEO',        'hi' => 'मेटा शीर्षक', 'it' => 'Meta Titolo'],
            'meta_description' => ['fa' => 'توضیح SEO',          'en' => 'Meta Description', 'ar' => 'وصف SEO',          'hi' => 'मेटा विवरण',  'it' => 'Meta Descrizione'],
            'meta_keywords'    => ['fa' => 'کلمات کلیدی',        'en' => 'Keywords',         'ar' => 'الكلمات المفتاحية', 'hi' => 'कीवर्ड',      'it' => 'Parole Chiave'],
            'og_image'         => ['fa' => 'تصویر شبکه اجتماعی', 'en' => 'Social Image',     'ar' => 'صورة OG',          'hi' => 'सोशल छवि',    'it' => 'Immagine Social'],

            // Translation management
            'auto_translated'      => ['fa' => 'ترجمه خودکار',            'en' => 'Auto Translated',    'ar' => 'ترجمة تلقائية',     'hi' => 'स्वचालित',      'it' => 'Auto Tradotto'],
            'generate_lang_files'  => ['fa' => 'ساخت فایل‌های زبان',      'en' => 'Generate Lang Files','ar' => 'إنشاء ملفات اللغة', 'hi' => 'फ़ाइलें बनाएं',  'it' => 'Genera File Lang'],
            'lang_files_generated' => ['fa' => 'فایل‌های زبان ساخته شدند','en' => 'Lang files generated','ar' => 'تم إنشاء الملفات', 'hi' => 'फ़ाइलें तैयार हुईं','it' => 'File generati'],

            // Category form
            'basic_info'              => ['fa' => 'اطلاعات اصلی',          'en' => 'Basic Info',              'ar' => 'المعلومات الأساسية',  'hi' => 'मूल जानकारी',         'it' => 'Info Base'],
            'parent_category'         => ['fa' => 'دسته والد',              'en' => 'Parent Category',         'ar' => 'الفئة الأم',          'hi' => 'मूल श्रेणी',           'it' => 'Categoria Padre'],
            'no_parent'               => ['fa' => 'بدون والد (دسته اصلی)',  'en' => 'No parent (root)',        'ar' => 'بدون أصل (جذرية)',    'hi' => 'कोई मूल नहीं',         'it' => 'Nessun genitore'],
            'first_before_all'        => ['fa' => '⬆ اول از همه',          'en' => '⬆ First',                'ar' => '⬆ الأول',            'hi' => '⬆ पहले',               'it' => '⬆ Primo'],
            'after'                   => ['fa' => 'بعد از',                 'en' => 'After',                   'ar' => 'بعد',                 'hi' => 'बाद में',              'it' => 'Dopo'],
            'total_siblings'          => ['fa' => 'تعداد همتایان',          'en' => 'Total siblings',          'ar' => 'إجمالي الأشقاء',      'hi' => 'कुल समकक्ष',          'it' => 'Totale fratelli'],
            'sub_categories'          => ['fa' => 'زیردسته‌ها',             'en' => 'Sub-categories',          'ar' => 'الفئات الفرعية',      'hi' => 'उप श्रेणियाँ',         'it' => 'Sottocategorie'],
            'root_categories'         => ['fa' => 'دسته‌های اصلی',          'en' => 'Root Categories',         'ar' => 'الفئات الرئيسية',     'hi' => 'मुख्य श्रेणियाँ',      'it' => 'Categorie Principali'],
            'root_only'               => ['fa' => 'فقط دسته‌های اصلی',      'en' => 'Root only',               'ar' => 'الفئات الرئيسية فقط','hi' => 'केवल मुख्य श्रेणियाँ', 'it' => 'Solo principali'],
            'slug_helper'             => ['fa' => 'به‌صورت خودکار از نام ساخته می‌شه', 'en' => 'Auto-generated from name', 'ar' => 'يُنشأ تلقائياً من الاسم', 'hi' => 'नाम से स्वतः उत्पन्न', 'it' => 'Generato dal nome'],

            // Attribute fields
            'attribute_key_helper'        => ['fa' => 'یه کلید انگلیسی یکتا (مثل: color, thickness)', 'en' => 'Unique key in English (e.g. color, thickness)', 'ar' => 'مفتاح إنجليزي فريد', 'hi' => 'अद्वितीय अंग्रेजी कुंजी', 'it' => 'Chiave univoca in inglese'],
            'unit'                        => ['fa' => 'واحد اندازه‌گیری',     'en' => 'Unit',                 'ar' => 'الوحدة',              'hi' => 'इकाई',             'it' => 'Unità di Misura'],
            'is_filterable'               => ['fa' => 'قابل فیلتر شدن',       'en' => 'Filterable',           'ar' => 'قابل للتصفية',        'hi' => 'फ़िल्टर योग्य',   'it' => 'Filtrabile'],
            'min_value'                   => ['fa' => 'حداقل مقدار',           'en' => 'Min',                  'ar' => 'الحد الأدنى',         'hi' => 'न्यूनतम',         'it' => 'Minimo'],
            'max_value'                   => ['fa' => 'حداکثر مقدار',          'en' => 'Max',                  'ar' => 'الحد الأقصى',         'hi' => 'अधिकतम',          'it' => 'Massimo'],
            'step_value'                  => ['fa' => 'گام (بازه تغییر)',      'en' => 'Step',                 'ar' => 'الخطوة',              'hi' => 'चरण',             'it' => 'Passo'],
            'default_value'               => ['fa' => 'مقدار پیش‌فرض',        'en' => 'Default',              'ar' => 'القيمة الافتراضية',   'hi' => 'डिफ़ॉल्ट',        'it' => 'Predefinito'],
            'show_in_product_page'        => ['fa' => 'نمایش در صفحه محصول',  'en' => 'Show on Product Page', 'ar' => 'عرض في صفحة المنتج', 'hi' => 'उत्पाद पेज पर',  'it' => 'Mostra nella Pagina'],
            'attribute_group'             => ['fa' => 'گروه‌بندی ویژگی',      'en' => 'Attribute Group',      'ar' => 'مجموعة المواصفات',    'hi' => 'विशेषता समूह',    'it' => 'Gruppo Attributo'],
            'attribute_group_desc'        => ['fa' => 'ویژگی‌ها رو برای نمایش بهتر در فرم محصول دسته‌بندی کن (اختیاریه)', 'en' => 'Group attributes for better display in the product form (optional)', 'ar' => 'تجميع المواصفات للعرض الأفضل (اختياري)', 'hi' => 'बेहतर प्रदर्शन के लिए समूह बनाएं (वैकल्पिक)', 'it' => 'Raggruppa attributi nel modulo (opzionale)'],
            'attribute_group_placeholder' => ['fa' => 'مثلاً: ابعاد و وزن',   'en' => 'e.g. Dimensions & Weight', 'ar' => 'مثال: الأبعاد والوزن', 'hi' => 'उदा: आयाम और वजन', 'it' => 'es. Dimensioni e Peso'],
            'field_preview'               => ['fa' => 'پیش‌نمایش فیلد',       'en' => 'Field Preview',        'ar' => 'معاينة الحقل',        'hi' => 'फ़ील्ड झलक',      'it' => 'Anteprima Campo'],
            'usage_count'                 => ['fa' => 'استفاده در محصولات',    'en' => 'Used in Products',     'ar' => 'مستخدم في المنتجات',  'hi' => 'उत्पादों में उपयोग','it' => 'Usato in Prodotti'],
            'translation_complete'        => ['fa' => 'همه ترجمه‌ها کامله',    'en' => 'Translation complete', 'ar' => 'الترجمة مكتملة',      'hi' => 'अनुवाद पूर्ण',    'it' => 'Traduzione completa'],
            'translation_incomplete'      => ['fa' => 'ترجمه ناقصه',           'en' => 'Missing translations', 'ar' => 'ترجمة ناقصة',         'hi' => 'अनुवाद अधूरा',    'it' => 'Traduzione incompleta'],
            'attribute_delete_warning'    => ['fa' => 'این ویژگی در :count محصول استفاده شده. با حذفش مقادیر مربوطه هم پاک می‌شن.', 'en' => 'This attribute is used in :count product(s). Deleting it will remove all related values.', 'ar' => 'هذه المواصفة مستخدمة في :count منتج. حذفها سيزيل جميع القيم المرتبطة.', 'hi' => 'यह विशेषता :count उत्पाद में उपयोग में है। हटाने पर सभी मान हट जाएंगे।', 'it' => 'Questo attributo è usato in :count prodotti. L\'eliminazione rimuoverà tutti i valori correlati.'],
            'attr_type_text'              => ['fa' => 'متن (قابل ترجمه)',       'en' => 'Text (translatable)',   'ar' => 'نص (قابل للترجمة)',   'hi' => 'पाठ (अनुवाद योग्य)', 'it' => 'Testo (traducibile)'],
            'attr_type_select'            => ['fa' => 'لیست انتخابی',           'en' => 'Select (dropdown)',     'ar' => 'قائمة منسدلة',        'hi' => 'ड्रॉपडाउन',          'it' => 'Selezione dropdown'],
            'attr_type_number'            => ['fa' => 'عدد',                    'en' => 'Number',               'ar' => 'رقم',                 'hi' => 'संख्या',              'it' => 'Numero'],
            'attr_type_bool'              => ['fa' => 'بله / خیر',              'en' => 'Yes / No',             'ar' => 'نعم / لا',            'hi' => 'हाँ / नहीं',          'it' => 'Sì / No'],
            'options_for_select'          => ['fa' => 'گزینه‌ها (برای لیست انتخابی)', 'en' => 'Options (for select type)', 'ar' => 'الخيارات (للقائمة)', 'hi' => 'विकल्प (ड्रॉपडाउन के लिए)', 'it' => 'Opzioni (per selezione)'],
            'options_desc'                => ['fa' => 'برای هر گزینه ترجمه‌اش رو توی همه زبان‌ها بنویس', 'en' => 'For each option, add its translation in all languages', 'ar' => 'لكل خيار، أضف ترجمته بجميع اللغات', 'hi' => 'प्रत्येक विकल्प का अनुवाद सभी भाषाओं में दर्ज करें', 'it' => 'Per ogni opzione aggiungi la traduzione in tutte le lingue'],
            'add_option'                  => ['fa' => 'گزینه جدید اضافه کن',   'en' => 'Add option',           'ar' => 'إضافة خيار',          'hi' => 'विकल्प जोड़ें',        'it' => 'Aggiungi opzione'],
            'add_attribute'               => ['fa' => 'ویژگی جدید اضافه کن',   'en' => 'Add Attribute',        'ar' => 'إضافة مواصفة',        'hi' => 'विशेषता जोड़ें',       'it' => 'Aggiungi Attributo'],
            'label_per_language'          => ['fa' => 'نام این ویژگی به هر زبان', 'en' => 'Name per language', 'ar' => 'الاسم بكل لغة',       'hi' => 'प्रति भाषा नाम',       'it' => 'Nome per lingua'],

            // Product form
            'price_on_request' => ['fa' => 'قیمت با استعلام', 'en' => 'Price on Request', 'ar' => 'السعر عند الطلب', 'hi' => 'मांग पर मूल्य', 'it' => 'Prezzo su Richiesta'],
            'is_new'           => ['fa' => 'محصول جدید',       'en' => 'New Arrival',      'ar' => 'وصل حديثاً',      'hi' => 'नया आगमन',       'it' => 'Nuovo Arrivo'],

            // Event fields
            'start_date'    => ['fa' => 'تاریخ شروع',        'en' => 'Start Date',     'ar' => 'تاريخ البدء',     'hi' => 'प्रारंभ तिथि',    'it' => 'Data Inizio'],
            'end_date'      => ['fa' => 'تاریخ پایان',       'en' => 'End Date',       'ar' => 'تاريخ الانتهاء',  'hi' => 'समाप्ति तिथि',    'it' => 'Data Fine'],
            'city'          => ['fa' => 'شهر',                'en' => 'City',           'ar' => 'المدينة',         'hi' => 'शहर',             'it' => 'Città'],
            'hall_number'   => ['fa' => 'شماره سالن',         'en' => 'Hall',           'ar' => 'رقم القاعة',      'hi' => 'हॉल नंबर',        'it' => 'Padiglione'],
            'booth_number'  => ['fa' => 'شماره غرفه',         'en' => 'Booth',          'ar' => 'رقم الجناح',      'hi' => 'बूथ नंबर',        'it' => 'Stand'],
            'event_website' => ['fa' => 'وب‌سایت رویداد',    'en' => 'Event Website',  'ar' => 'موقع الفعالية',   'hi' => 'इवेंट वेबसाइट',   'it' => 'Sito dell\'Evento'],
        ],

        // ══════════════════════════════════════════════════════════
        //  MESSAGES — Frontend (site) translations
        // ══════════════════════════════════════════════════════════
        'messages' => [

            // Navigation
            'home'       => ['fa' => 'خانه',         'en' => 'Home',        'ar' => 'الرئيسية',   'hi' => 'होम',           'it' => 'Home'],
            'products'   => ['fa' => 'محصولات',      'en' => 'Products',    'ar' => 'المنتجات',   'hi' => 'उत्पाद',        'it' => 'Prodotti'],
            'categories' => ['fa' => 'دسته‌بندی‌ها', 'en' => 'Categories',  'ar' => 'الفئات',     'hi' => 'श्रेणियाँ',     'it' => 'Categorie'],
            'about'      => ['fa' => 'درباره ما',    'en' => 'About Us',    'ar' => 'من نحن',     'hi' => 'हमारे बारे में', 'it' => 'Chi Siamo'],
            'contact'    => ['fa' => 'تماس با ما',   'en' => 'Contact',     'ar' => 'تواصل معنا', 'hi' => 'संपर्क करें',   'it' => 'Contattaci'],
            'news'       => ['fa' => 'اخبار',        'en' => 'News',        'ar' => 'الأخبار',    'hi' => 'समाचार',        'it' => 'Notizie'],
            'events'     => ['fa' => 'نمایشگاه‌ها', 'en' => 'Exhibitions', 'ar' => 'المعارض',    'hi' => 'प्रदर्शनी',     'it' => 'Fiere'],
            'search'     => ['fa' => 'جستجو',        'en' => 'Search',      'ar' => 'بحث',        'hi' => 'खोजें',         'it' => 'Cerca'],
            'welcome'    => ['fa' => 'خوش اومدی',    'en' => 'Welcome',     'ar' => 'أهلاً بك',   'hi' => 'स्वागत है',     'it' => 'Benvenuto'],

            // Auth
            'login'    => ['fa' => 'ورود',       'en' => 'Sign In',  'ar' => 'تسجيل الدخول', 'hi' => 'लॉगिन',         'it' => 'Accedi'],
            'register' => ['fa' => 'ثبت نام',    'en' => 'Sign Up',  'ar' => 'إنشاء حساب',   'hi' => 'रजिस्टर',       'it' => 'Registrati'],
            'logout'   => ['fa' => 'خروج',       'en' => 'Sign Out', 'ar' => 'تسجيل الخروج', 'hi' => 'लॉगआउट',        'it' => 'Esci'],
            'profile'  => ['fa' => 'پروفایل من', 'en' => 'My Profile','ar' => 'ملفي الشخصي', 'hi' => 'मेरी प्रोफ़ाइल', 'it' => 'Il Mio Profilo'],
            'orders'   => ['fa' => 'سفارشات من', 'en' => 'My Orders','ar' => 'طلباتي',        'hi' => 'मेरे ऑर्डर',    'it' => 'I Miei Ordini'],

            // Cart & checkout
            'cart'        => ['fa' => 'سبد خرید',      'en' => 'Cart',           'ar' => 'سلة التسوق',  'hi' => 'कार्ट',           'it' => 'Carrello'],
            'checkout'    => ['fa' => 'تکمیل خرید',    'en' => 'Checkout',       'ar' => 'إتمام الشراء', 'hi' => 'चेकआउट',          'it' => 'Acquista'],
            'add_to_cart' => ['fa' => 'افزودن به سبد', 'en' => 'Add to Cart',    'ar' => 'أضف للسلة',   'hi' => 'कार्ट में जोड़ें',  'it' => 'Aggiungi'],
            'buy_now'     => ['fa' => 'همین الان بخر', 'en' => 'Buy Now',        'ar' => 'اشتري الآن',  'hi' => 'अभी खरीदें',       'it' => 'Acquista Ora'],
            'price'       => ['fa' => 'قیمت',          'en' => 'Price',          'ar' => 'السعر',        'hi' => 'मूल्य',            'it' => 'Prezzo'],
            'quantity'    => ['fa' => 'تعداد',         'en' => 'Quantity',       'ar' => 'الكمية',       'hi' => 'मात्रा',           'it' => 'Quantità'],
            'total'       => ['fa' => 'جمع کل',        'en' => 'Total',          'ar' => 'المجموع',      'hi' => 'कुल',              'it' => 'Totale'],
            'inquiry'     => ['fa' => 'استعلام قیمت', 'en' => 'Request a Quote','ar' => 'طلب عرض سعر', 'hi' => 'कोटेशन माँगें',    'it' => 'Richiedi Preventivo'],

            // Form fields
            'name'         => ['fa' => 'نام',          'en' => 'Name',         'ar' => 'الاسم',             'hi' => 'नाम',         'it' => 'Nome'],
            'email'        => ['fa' => 'ایمیل',        'en' => 'Email',        'ar' => 'البريد الإلكتروني', 'hi' => 'ईमेल',        'it' => 'Email'],
            'phone'        => ['fa' => 'شماره تماس',   'en' => 'Phone',        'ar' => 'الهاتف',            'hi' => 'फ़ोन',         'it' => 'Telefono'],
            'message'      => ['fa' => 'پیام',         'en' => 'Message',      'ar' => 'الرسالة',           'hi' => 'संदेश',        'it' => 'Messaggio'],
            'address'      => ['fa' => 'آدرس',         'en' => 'Address',      'ar' => 'العنوان',           'hi' => 'पता',         'it' => 'Indirizzo'],
            'country'      => ['fa' => 'کشور',         'en' => 'Country',      'ar' => 'البلد',             'hi' => 'देश',         'it' => 'Paese'],
            'company'      => ['fa' => 'شرکت',         'en' => 'Company',      'ar' => 'الشركة',            'hi' => 'कंपनी',        'it' => 'Azienda'],
            'password'     => ['fa' => 'رمز عبور',     'en' => 'Password',     'ar' => 'كلمة المرور',       'hi' => 'पासवर्ड',      'it' => 'Password'],
            'submit'       => ['fa' => 'ارسال',        'en' => 'Submit',       'ar' => 'إرسال',             'hi' => 'सबमिट',        'it' => 'Invia'],
            'send_message' => ['fa' => 'ارسال پیام',   'en' => 'Send Message', 'ar' => 'إرسال رسالة',       'hi' => 'संदेश भेजें',  'it' => 'Invia Messaggio'],

            // Product status
            'product_available'   => ['fa' => 'موجود',           'en' => 'In Stock',        'ar' => 'متوفر',          'hi' => 'स्टॉक में',  'it' => 'Disponibile'],
            'product_unavailable' => ['fa' => 'ناموجود',         'en' => 'Out of Stock',    'ar' => 'غير متوفر',      'hi' => 'स्टॉक नहीं', 'it' => 'Non Disponibile'],
            'product_reserved'    => ['fa' => 'رزرو شده',        'en' => 'Reserved',        'ar' => 'محجوز',          'hi' => 'आरक्षित',    'it' => 'Riservato'],
            'product_sold'        => ['fa' => 'فروخته شد',       'en' => 'Sold',            'ar' => 'مباع',           'hi' => 'बिक गया',    'it' => 'Venduto'],
            'price_on_request'    => ['fa' => 'قیمت با استعلام', 'en' => 'Price on Request','ar' => 'السعر عند الطلب','hi' => 'मांग पर मूल्य','it' => 'Prezzo su Richiesta'],

            // Homepage sections
            'years_experience'  => ['fa' => 'سال تجربه',                            'en' => 'Years of Experience',                'ar' => 'سنوات من الخبرة',               'hi' => 'वर्षों का अनुभव',               'it' => 'Anni di Esperienza'],
            'featured_products' => ['fa' => 'محصولات ویژه',                         'en' => 'Featured Products',                  'ar' => 'المنتجات المميزة',               'hi' => 'विशेष उत्पाद',                  'it' => 'Prodotti in Evidenza'],
            'latest_stones'     => ['fa' => 'جدیدترین <br> سنگ‌های موجود',         'en' => 'Latest <br> Available Stones',       'ar' => 'أحدث <br> الأحجار المتاحة',     'hi' => 'नवीनतम <br> उपलब्ध पत्थर',     'it' => 'Le Ultime <br> Pietre Disponibili'],
            'any_questions'     => ['fa' => 'سوال داری؟',                           'en' => 'Any Questions?',                     'ar' => 'هل لديك سؤال؟',                 'hi' => 'कोई सवाल?',                     'it' => 'Hai domande?'],
            'latest_news'       => ['fa' => 'آخرین اخبار',                          'en' => 'Latest News',                        'ar' => 'آخر الأخبار',                   'hi' => 'नवीनतम समाचार',                 'it' => 'Ultime Notizie'],
            'latest_news_desc'  => ['fa' => 'تازه‌ترین اخبار و رویدادهای صنعت سنگ', 'en' => 'The latest news from the stone industry', 'ar' => 'آخر أخبار وأحداث صناعة الحجر', 'hi' => 'पत्थर उद्योग की ताज़ा खबरें', 'it' => 'Le ultime notizie dal settore lapideo'],
            'more_information'  => ['fa' => 'بیشتر بدون',                           'en' => 'Learn More',                         'ar' => 'اعرف أكثر',                     'hi' => 'और जानें',                      'it' => 'Scopri di più'],
            'customers'         => ['fa' => 'مشتریان',                              'en' => 'Customers',                          'ar' => 'عملاؤنا',                       'hi' => 'ग्राहक',                        'it' => 'Clienti'],

            // Buttons & links
            'read_more'    => ['fa' => 'ادامه بده',        'en' => 'Read More',    'ar' => 'اقرأ المزيد', 'hi' => 'और पढ़ें', 'it' => 'Leggi tutto'],
            'view_details' => ['fa' => 'مشاهده جزئیات',   'en' => 'View Details', 'ar' => 'عرض التفاصيل','hi' => 'विवरण देखें','it' => 'Vedi Dettagli'],
            'clear_filter' => ['fa' => 'حذف فیلتر',       'en' => 'Clear Filter', 'ar' => 'مسح الفلتر',  'hi' => 'फ़िल्टर हटाएं','it' => 'Rimuovi Filtro'],

            // Footer
            'newsletter_title'       => ['fa' => 'عضویت در خبرنامه',      'en' => 'Subscribe to Newsletter', 'ar' => 'اشترك في النشرة البريدية', 'hi' => 'न्यूज़लेटर सदस्यता', 'it' => 'Iscriviti alla Newsletter'],
            'newsletter_placeholder' => ['fa' => 'ایمیلت رو وارد کن',    'en' => 'Enter your email',        'ar' => 'أدخل بريدك الإلكتروني',   'hi' => 'अपना ईमेल दर्ज करें', 'it' => 'Inserisci la tua email'],
            'newsletter_subscribe'   => ['fa' => 'عضو شو',                'en' => 'Subscribe',               'ar' => 'اشترك',                    'hi' => 'सदस्य बनें',          'it' => 'Iscriviti'],
            'quick_links'            => ['fa' => 'لینک‌های سریع',         'en' => 'Quick Links',             'ar' => 'روابط سريعة',              'hi' => 'त्वरित लिंक',         'it' => 'Link Rapidi'],
            'my_account'             => ['fa' => 'حساب کاربری',           'en' => 'My Account',              'ar' => 'حسابي',                    'hi' => 'मेरा खाता',           'it' => 'Il Mio Account'],
            'contact_info'           => ['fa' => 'اطلاعات تماس',          'en' => 'Contact Info',            'ar' => 'معلومات التواصل',          'hi' => 'संपर्क जानकारी',      'it' => 'Informazioni di Contatto'],
            'wishlist'               => ['fa' => 'علاقه‌مندی‌ها',        'en' => 'Wishlist',                'ar' => 'المفضلة',                  'hi' => 'पसंदीदा',             'it' => 'Lista Desideri'],
            'all_rights_reserved'    => ['fa' => 'تمامی حقوق محفوظ است', 'en' => 'All rights reserved',     'ar' => 'جميع الحقوق محفوظة',       'hi' => 'सर्वाधिकार सुरक्षित','it' => 'Tutti i diritti riservati'],

            // General messages
            'no_products'  => ['fa' => 'هیچ محصولی پیدا نشد',    'en' => 'No products found',    'ar' => 'لا توجد منتجات',           'hi' => 'कोई उत्पाद नहीं',  'it' => 'Nessun prodotto'],
            'loading'      => ['fa' => 'لطفاً صبر کن...',        'en' => 'Loading...',           'ar' => 'جارٍ التحميل...',          'hi' => 'लोड हो रहा है...',  'it' => 'Caricamento...'],
            'error'        => ['fa' => 'یه مشکلی پیش اومد',     'en' => 'Something went wrong', 'ar' => 'حدث خطأ',                  'hi' => 'कुछ गलत हुआ',      'it' => 'Qualcosa è andato storto'],
            'success'      => ['fa' => 'انجام شد!',              'en' => 'Done!',                'ar' => 'تمت العملية',              'hi' => 'हो गया!',           'it' => 'Fatto!'],
            'contact_sent' => ['fa' => 'پیامت ارسال شد ✓',      'en' => 'Message sent ✓',       'ar' => 'تم إرسال رسالتك ✓',        'hi' => 'संदेश भेजा गया ✓', 'it' => 'Messaggio inviato ✓'],
            'not_found'    => ['fa' => 'صفحه پیدا نشد',          'en' => 'Page not found',       'ar' => 'الصفحة غير موجودة',        'hi' => 'पृष्ठ नहीं मिला',  'it' => 'Pagina non trovata'],

            // Products page
            'all_products'       => ['fa' => 'همه محصولات',                     'en' => 'All Products',               'ar' => 'جميع المنتجات',                 'hi' => 'सभी उत्पाद',           'it' => 'Tutti i Prodotti'],
            'search_placeholder' => ['fa' => 'جستجو در محصولات...',             'en' => 'Search products...',         'ar' => 'ابحث في المنتجات...',           'hi' => 'उत्पाद खोजें...',       'it' => 'Cerca prodotti...'],
            'of'                 => ['fa' => 'از',                              'en' => 'of',                         'ar' => 'من',                            'hi' => 'में से',                'it' => 'di'],
            'grid_view'          => ['fa' => 'نمای شبکه',                       'en' => 'Grid View',                  'ar' => 'عرض الشبكة',                    'hi' => 'ग्रिड व्यू',            'it' => 'Vista Griglia'],
            'list_view'          => ['fa' => 'نمای لیست',                       'en' => 'List View',                  'ar' => 'عرض القائمة',                   'hi' => 'सूची दृश्य',            'it' => 'Vista Lista'],
            'sort_latest'        => ['fa' => 'جدیدترین',                        'en' => 'Latest',                     'ar' => 'الأحدث',                        'hi' => 'नवीनतम',               'it' => 'Più Recenti'],
            'sort_featured'      => ['fa' => 'محصولات ویژه',                    'en' => 'Featured',                   'ar' => 'المميز',                        'hi' => 'विशेष',                'it' => 'In Evidenza'],
            'sort_price_asc'     => ['fa' => 'قیمت: کم به زیاد',               'en' => 'Price: Low to High',         'ar' => 'السعر: من الأقل',               'hi' => 'मूल्य: कम से अधिक',    'it' => 'Prezzo: Crescente'],
            'sort_price_desc'    => ['fa' => 'قیمت: زیاد به کم',               'en' => 'Price: High to Low',         'ar' => 'السعر: من الأعلى',              'hi' => 'मूल्य: अधिक से कम',    'it' => 'Prezzo: Decrescente'],
            'sort_name_asc'      => ['fa' => 'نام: الف تا ی',                  'en' => 'Name: A to Z',               'ar' => 'الاسم: أ إلى ي',                'hi' => 'नाम: A से Z',          'it' => 'Nome: A-Z'],
            'no_products_desc'   => ['fa' => 'محصولی با این مشخصات پیدا نشد',  'en' => 'No products match your criteria', 'ar' => 'لا توجد منتجات تطابق معاييرك', 'hi' => 'कोई उत्पाद नहीं मिला', 'it' => 'Nessun prodotto trovato'],
            'new'                => ['fa' => 'جدید',                            'en' => 'New',                        'ar' => 'جديد',                          'hi' => 'नया',                  'it' => 'Nuovo'],
            'featured'           => ['fa' => 'ویژه',                            'en' => 'Featured',                   'ar' => 'مميز',                          'hi' => 'विशेष',                'it' => 'In Evidenza'],
            'currency_rial'      => ['fa' => 'ریال',                            'en' => 'IRR',                        'ar' => 'ريال',                          'hi' => 'रियाल',                'it' => 'IRR'],
            'status'             => ['fa' => 'وضعیت',                           'en' => 'Status',                     'ar' => 'الحالة',                        'hi' => 'स्थिति',               'it' => 'Stato'],

            // Events page
            'event_start_date'   => ['fa' => 'تاریخ شروع',         'en' => 'Start Date',        'ar' => 'تاريخ البدء',      'hi' => 'प्रारंभ तिथि',  'it' => 'Data Inizio'],
            'event_end_date'     => ['fa' => 'تاریخ پایان',        'en' => 'End Date',          'ar' => 'تاريخ الانتهاء',   'hi' => 'समाप्ति तिथि',  'it' => 'Data Fine'],
            'event_website'      => ['fa' => 'وب‌سایت رویداد',    'en' => 'Event Website',     'ar' => 'موقع الفعالية',    'hi' => 'इवेंट वेबसाइट', 'it' => 'Sito dell\'Evento'],

            // Modern theme — hero / categories / footer / cards (added with the new design)
            'hero_eyebrow'         => ['fa' => 'تأمین‌کننده مستقیم معدن تا صادرات', 'en' => 'From quarry to export, direct', 'ar' => 'من المحجر إلى التصدير، مباشرة', 'hi' => 'खदान से निर्यात तक, सीधे', 'it' => 'Dalla cava all\'esportazione, diretto'],
            'experience_years'     => ['fa' => 'سال تجربه',                       'en' => 'Years experience',              'ar' => 'سنوات الخبرة',                   'hi' => 'वर्षों का अनुभव',        'it' => 'Anni di esperienza'],
            'happy_customers'      => ['fa' => 'مشتری راضی',                      'en' => 'Happy customers',               'ar' => 'عملاء سعداء',                    'hi' => 'संतुष्ट ग्राहक',          'it' => 'Clienti soddisfatti'],
            'stone_categories'     => ['fa' => 'دسته‌بندی سنگ',                   'en' => 'Stone categories',              'ar' => 'فئات الأحجار',                   'hi' => 'पत्थर श्रेणियाँ',         'it' => 'Categorie di pietra'],
            'exhibitions'          => ['fa' => 'نمایشگاه بین‌المللی',             'en' => 'International exhibitions',     'ar' => 'معارض دولية',                    'hi' => 'अंतर्राष्ट्रीय प्रदर्शनी', 'it' => 'Fiere internazionali'],
            'our_categories'       => ['fa' => 'دسته‌بندی محصولات',               'en' => 'Our Categories',                'ar' => 'فئاتنا',                         'hi' => 'हमारी श्रेणियाँ',         'it' => 'Le Nostre Categorie'],
            'categories_desc'      => ['fa' => 'مجموعه‌ای متنوع از سنگ‌های طبیعی برای هر پروژه', 'en' => 'A curated range of natural stone for every project', 'ar' => 'مجموعة مختارة من الأحجار الطبيعية لكل مشروع', 'hi' => 'हर प्रोजेक्ट के लिए प्राकृतिक पत्थर की विविध श्रृंखला', 'it' => 'Una selezione di pietre naturali per ogni progetto'],
            'explore_categories'   => ['fa' => 'مشاهده دسته‌بندی‌ها',             'en' => 'Explore Categories',            'ar' => 'استكشف الفئات',                  'hi' => 'श्रेणियाँ देखें',         'it' => 'Esplora le Categorie'],
            'find_your_stone'      => ['fa' => 'سنگ مورد نظر خود را پیدا کنید',  'en' => 'Find your stone',               'ar' => 'ابحث عن حجرك',                   'hi' => 'अपना पत्थर खोजें',        'it' => 'Trova la tua pietra'],
            'view_all'             => ['fa' => 'مشاهده همه',                      'en' => 'View all',                      'ar' => 'عرض الكل',                       'hi' => 'सभी देखें',               'it' => 'Vedi tutto'],
            'newsletter_desc'      => ['fa' => 'از جدیدترین محصولات، رویدادها و اخبار سنگ‌های طبیعی باخبر شوید.', 'en' => 'Get the latest stones, exhibitions and news in your inbox.', 'ar' => 'احصل على أحدث الأحجار والمعارض والأخبار في بريدك.', 'hi' => 'नवीनतम पत्थर, प्रदर्शनी और समाचार सीधे अपने इनबॉक्स में पाएं।', 'it' => 'Ricevi le ultime pietre, fiere e notizie nella tua casella di posta.'],
            'footer_about_fallback'=> ['fa' => 'تأمین‌کننده و صادرکننده انواع سنگ‌های طبیعی با کیفیت صادراتی.', 'en' => 'A trusted source and exporter of premium natural stone.', 'ar' => 'مورد موثوق ومصدّر للأحجار الطبيعية الفاخرة.', 'hi' => 'प्रीमियम प्राकृतिक पत्थर का विश्वसनीय आपूर्तिकर्ता और निर्यातक।', 'it' => 'Un fornitore affidabile ed esportatore di pietra naturale di pregio.'],
            'min_read'             => ['fa' => 'دقیقه مطالعه',                    'en' => 'min read',                      'ar' => 'دقيقة قراءة',                    'hi' => 'मिनट पढ़ें',              'it' => 'min di lettura'],
            'booth'                => ['fa' => 'غرفه',                            'en' => 'Booth',                         'ar' => 'جناح',                           'hi' => 'बूथ',                     'it' => 'Stand'],
            'hall'                 => ['fa' => 'سالن',                            'en' => 'Hall',                          'ar' => 'قاعة',                           'hi' => 'हॉल',                     'it' => 'Sala'],
            'empty_cart'           => ['fa' => 'سبد خرید شما خالی است',          'en' => 'Your cart is empty',            'ar' => 'سلة التسوق فارغة',                'hi' => 'आपकी कार्ट खाली है',      'it' => 'Il tuo carrello è vuoto'],
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
