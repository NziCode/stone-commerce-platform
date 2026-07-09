<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\LanguageService;
use App\Services\TranslationService;
use Filament\Actions\Action;
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
    // Translatable fields (locale code => value) — see Setting::TRANSLATABLE_KEYS.
    public array $site_name             = [];
    public array $site_tagline          = [];
    public array $site_working_hours    = [];
    public array $site_address          = [];

    public string $site_email           = '';
    public string $site_phone           = '';
    public string $site_phone_whatsapp  = '';
    public string $site_map_lat         = '';
    public string $site_map_lng         = '';
    public string $site_google_map_embed = '';

    public array $meta_title            = [];
    public array $meta_description      = [];

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
    public array  $payment_receipt_bank_name = [];
    public string $payment_receipt_account_number = '';
    public string $payment_receipt_iban    = '';
    public string $payment_receipt_swift   = '';
    public array  $payment_receipt_instructions = [];

    public string $smtp_host            = '';
    public string $smtp_port            = '';
    public string $smtp_username        = '';
    public string $smtp_password        = '';
    public string $smtp_from_address    = '';
    public string $smtp_from_name       = '';

    public string $sms_provider         = '';
    public string $sms_api_key          = '';
    public string $sms_sender           = '';
    public array  $sms_otp_template     = [];
    public array  $sms_order_confirmed_template = [];
    public array  $sms_order_shipped_template   = [];

    public string $contact_notify_email         = '';
    public string $contact_notify_sms           = '';
    public string $contact_recaptcha_site_key   = '';
    public string $contact_recaptcha_secret_key = '';
    public bool   $contact_recaptcha_enabled    = false;

    public string $about_years          = '';
    public array  $about_title          = [];
    public array  $about_desc           = [];
    public array  $about_feature_1      = [];
    public array  $about_feature_2      = [];
    public array  $about_feature_3      = [];

    // ── Group → property mapping ─────────────────────────
    protected array $groupKeys = [
        'general' => [
            'site_name','site_tagline','site_email','site_phone',
            'site_phone_whatsapp','site_working_hours','site_address',
            'site_map_lat','site_map_lng','site_google_map_embed',
        ],
        'seo' => [
            'meta_title','meta_description',
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
            if (! property_exists($this, $key)) {
                continue;
            }

            if (in_array($key, Setting::TRANSLATABLE_KEYS, true)) {
                $decoded = json_decode((string) $value, true);
                $this->$key = is_array($decoded) ? $decoded : [];
                continue;
            }

            $this->$key = is_bool($this->$key)
                ? (bool) $value
                : (string) ($value ?? '');
        }
    }

    public function save(string $group): void
    {
        $keys = $this->groupKeys[$group] ?? [];

        foreach ($keys as $key) {
            $value = $this->$key;

            if (in_array($key, Setting::TRANSLATABLE_KEYS, true)) {
                Setting::set($key, json_encode($value, JSON_UNESCAPED_UNICODE), $group);
                continue;
            }

            Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value, $group);
        }

        Cache::forget(Setting::CACHE_KEY);
        Cache::forget('site_settings_public');

        Notification::make()
            ->title(__('admin.settings_saved'))
            ->success()
            ->send();
    }

    /**
     * "Translate Automatically" for a single translatable field — fills (or, with the
     * overwrite footer button, replaces) every active locale from the site's default
     * locale using the same TranslationService the Product resource uses. Triggered
     * per-field (not per-tab) so e.g. the site title and tagline each get their own button.
     */
    public function translateFieldAction(): Action
    {
        return Action::make('translateField')
            ->label(__('admin.translate_automatically'))
            ->icon('heroicon-o-language')
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription(__('admin.translate_confirm_body'))
            ->extraModalFooterActions(fn (Action $action): array => [
                $action->makeModalSubmitAction('translateFieldOverwrite', arguments: ['overwrite' => true])
                    ->label(__('admin.translate_confirm_overwrite'))
                    ->color('danger'),
            ])
            ->action(function (array $arguments) {
                $field = $arguments['field'] ?? null;
                $isHtml = (bool) ($arguments['isHtml'] ?? false);
                $overwrite = (bool) ($arguments['overwrite'] ?? false);

                if (! $field || ! in_array($field, Setting::TRANSLATABLE_KEYS, true)) {
                    return;
                }

                $sourceCode = LanguageService::getDefault()?->code ?? 'fa';
                $sourceValue = $this->$field[$sourceCode] ?? '';

                if (blank($sourceValue)) {
                    return;
                }

                $targets = LanguageService::getActive()
                    ->pluck('code')
                    ->reject(fn ($code) => $code === $sourceCode)
                    ->values();

                $translator = app(TranslationService::class);
                $failedLocales = [];

                foreach ($targets as $targetCode) {
                    if (! $overwrite && filled($this->$field[$targetCode] ?? null)) {
                        continue;
                    }

                    $translated = $isHtml
                        ? $translator->translateHtml($sourceValue, $targetCode, $sourceCode)
                        : $translator->translate($sourceValue, $targetCode, $sourceCode);

                    if ($translated === null) {
                        $failedLocales[$targetCode] = true;
                        continue;
                    }

                    $this->{$field}[$targetCode] = $translated;
                }

                Notification::make()
                    ->title($failedLocales
                        ? __('admin.translate_partial')
                        : __('admin.translate_success'))
                    ->body($failedLocales ? implode(', ', array_keys($failedLocales)) : null)
                    ->color($failedLocales ? 'warning' : 'success')
                    ->send();
            });
    }
}
