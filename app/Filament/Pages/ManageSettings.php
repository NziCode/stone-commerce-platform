<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort    = 99;

    public static function getNavigationLabel(): string
    {
        return __('admin.settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.settings');
    }

    protected static string $view = 'filament.pages.manage-settings';

    public string $activeTab = 'general';

    // ── Public properties for each group ────────────────
    public string $site_name            = '';
    public string $site_name_en         = '';
    public string $site_tagline         = '';
    public string $site_email           = '';
    public string $site_phone           = '';
    public string $site_phone_whatsapp  = '';
    public string $site_working_hours   = '';
    public string $site_address         = '';
    public string $site_address_en      = '';
    public string $site_map_lat         = '';
    public string $site_map_lng         = '';
    public string $site_google_map_embed = '';

    public string $meta_title_fa        = '';
    public string $meta_title_en        = '';
    public string $meta_description_fa  = '';
    public string $meta_description_en  = '';
    public string $og_image             = '';
    public string $google_analytics_id  = '';
    public string $google_tag_manager_id = '';
    public string $google_search_console = '';
    public string $robots_txt           = '';

    public string $social_instagram     = '';
    public string $social_telegram      = '';
    public string $social_whatsapp      = '';
    public string $social_linkedin      = '';
    public string $social_youtube       = '';
    public string $social_twitter       = '';
    public string $social_facebook      = '';

    public string $payment_zarinpal_merchant = '';
    public bool   $payment_zarinpal_sandbox  = false;
    public string $payment_receipt_bank_name = '';
    public string $payment_receipt_account_number = '';
    public string $payment_receipt_iban    = '';
    public string $payment_receipt_swift   = '';
    public string $payment_receipt_instructions = '';

    public string $smtp_host            = '';
    public string $smtp_port            = '';
    public string $smtp_username        = '';
    public string $smtp_password        = '';
    public string $smtp_from_address    = '';
    public string $smtp_from_name       = '';

    public string $sms_provider         = '';
    public string $sms_api_key          = '';
    public string $sms_sender           = '';
    public string $sms_otp_template     = '';
    public string $sms_order_confirmed_template = '';
    public string $sms_order_shipped_template   = '';

    public string $contact_notify_email         = '';
    public string $contact_notify_sms           = '';
    public string $contact_recaptcha_site_key   = '';
    public string $contact_recaptcha_secret_key = '';
    public bool   $contact_recaptcha_enabled    = false;

    public string $about_years          = '';
    public string $about_title          = '';
    public string $about_desc           = '';
    public string $about_feature_1      = '';
    public string $about_feature_2      = '';
    public string $about_feature_3      = '';

    // ── Group → property mapping ─────────────────────────
    protected array $groupKeys = [
        'general' => [
            'site_name','site_name_en','site_tagline','site_email','site_phone',
            'site_phone_whatsapp','site_working_hours','site_address','site_address_en',
            'site_map_lat','site_map_lng','site_google_map_embed',
        ],
        'seo' => [
            'meta_title_fa','meta_title_en','meta_description_fa','meta_description_en',
            'og_image','google_analytics_id','google_tag_manager_id',
            'google_search_console','robots_txt',
        ],
        'social' => [
            'social_instagram','social_telegram','social_whatsapp','social_linkedin',
            'social_youtube','social_twitter','social_facebook',
        ],
        'payment' => [
            'payment_zarinpal_merchant','payment_zarinpal_sandbox',
            'payment_receipt_bank_name','payment_receipt_account_number',
            'payment_receipt_iban','payment_receipt_swift','payment_receipt_instructions',
        ],
        'smtp' => [
            'smtp_host','smtp_port','smtp_username','smtp_password',
            'smtp_from_address','smtp_from_name',
        ],
        'sms' => [
            'sms_provider','sms_api_key','sms_sender',
            'sms_otp_template','sms_order_confirmed_template','sms_order_shipped_template',
        ],
        'contact' => [
            'contact_notify_email','contact_notify_sms',
            'contact_recaptcha_site_key','contact_recaptcha_secret_key',
            'contact_recaptcha_enabled',
        ],
        'about' => [
            'about_years','about_title','about_desc',
            'about_feature_1','about_feature_2','about_feature_3',
        ],
    ];

    public function mount(): void
    {
        $all = Setting::all()->pluck('value', 'key');

        foreach ($all as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = is_bool($this->$key)
                    ? (bool) $value
                    : (string) ($value ?? '');
            }
        }
    }

    public function save(string $group): void
    {
        $keys = $this->groupKeys[$group] ?? [];

        foreach ($keys as $key) {
            $value = $this->$key;
            Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value, $group);
        }

        Cache::forget(Setting::CACHE_KEY);
        Cache::forget('site_settings_public');

        Notification::make()
            ->title(__('admin.settings_saved'))
            ->success()
            ->send();
    }
}
