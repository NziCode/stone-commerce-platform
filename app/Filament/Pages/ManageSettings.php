<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int    $navigationSort = 99;
    protected static string  $view = 'filament.pages.manage-settings';

    public static function getNavigationLabel(): string
    {
        return __('admin.settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.settings');
    }

    public static function getTitle(): string
    {
        return __('admin.settings');
    }

    // ── Form state ──────────────────────────────────────────────────────────
    public ?array $data = [];
    public string $activeTab = 'general';

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('settings_tabs')
                    ->tabs([

                        // ── General ─────────────────────────────────────
                        Forms\Components\Tabs\Tab::make(__('admin.settings_general'))
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Forms\Components\Section::make(__('admin.settings_site_identity'))
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('site_name')
                                            ->label(__('admin.settings_site_name_fa'))
                                            ->required()
                                            ->maxLength(100),

                                        Forms\Components\TextInput::make('site_name_en')
                                            ->label(__('admin.settings_site_name_en'))
                                            ->maxLength(100),

                                        Forms\Components\TextInput::make('site_tagline')
                                            ->label(__('admin.settings_tagline'))
                                            ->columnSpanFull()
                                            ->maxLength(200),

                                        Forms\Components\FileUpload::make('site_logo')
                                            ->label(__('admin.settings_logo'))
                                            ->image()
                                            ->directory('settings')
                                            ->columnSpanFull(),

                                        Forms\Components\FileUpload::make('site_favicon')
                                            ->label(__('admin.settings_favicon'))
                                            ->image()
                                            ->directory('settings')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make(__('admin.settings_contact_info'))
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('site_email')
                                            ->label(__('admin.settings_email'))
                                            ->email(),

                                        Forms\Components\TextInput::make('site_phone')
                                            ->label(__('admin.settings_phone')),

                                        Forms\Components\TextInput::make('site_phone_whatsapp')
                                            ->label('WhatsApp'),

                                        Forms\Components\TextInput::make('site_working_hours')
                                            ->label(__('admin.settings_working_hours')),

                                        Forms\Components\Textarea::make('site_address')
                                            ->label(__('admin.settings_address_fa'))
                                            ->rows(2),

                                        Forms\Components\Textarea::make('site_address_en')
                                            ->label(__('admin.settings_address_en'))
                                            ->rows(2),
                                    ]),

                                Forms\Components\Section::make(__('admin.settings_map'))
                                    ->columns(2)
                                    ->collapsed()
                                    ->schema([
                                        Forms\Components\TextInput::make('site_map_lat')
                                            ->label(__('admin.settings_map_lat'))
                                            ->numeric(),

                                        Forms\Components\TextInput::make('site_map_lng')
                                            ->label(__('admin.settings_map_lng'))
                                            ->numeric(),

                                        Forms\Components\Textarea::make('site_google_map_embed')
                                            ->label(__('admin.settings_map_embed'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ── SEO ─────────────────────────────────────────
                        Forms\Components\Tabs\Tab::make(__('admin.settings_seo'))
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\Section::make(__('admin.settings_meta'))
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title_fa')
                                            ->label(__('admin.settings_meta_title_fa'))
                                            ->maxLength(70)
                                            ->helperText('حداکثر ۷۰ کاراکتر'),

                                        Forms\Components\TextInput::make('meta_title_en')
                                            ->label(__('admin.settings_meta_title_en'))
                                            ->maxLength(70)
                                            ->helperText('Max 70 characters'),

                                        Forms\Components\Textarea::make('meta_description_fa')
                                            ->label(__('admin.settings_meta_desc_fa'))
                                            ->rows(2)
                                            ->maxLength(160)
                                            ->helperText('حداکثر ۱۶۰ کاراکتر')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('meta_description_en')
                                            ->label(__('admin.settings_meta_desc_en'))
                                            ->rows(2)
                                            ->maxLength(160)
                                            ->helperText('Max 160 characters')
                                            ->columnSpanFull(),

                                        Forms\Components\FileUpload::make('og_image')
                                            ->label('OG Image (1200×630)')
                                            ->image()
                                            ->directory('settings')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make(__('admin.settings_google'))
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('google_analytics_id')
                                            ->label('Google Analytics ID')
                                            ->placeholder('G-XXXXXXXXXX'),

                                        Forms\Components\TextInput::make('google_tag_manager_id')
                                            ->label('Google Tag Manager ID')
                                            ->placeholder('GTM-XXXXXXX'),

                                        Forms\Components\TextInput::make('google_search_console')
                                            ->label('Google Search Console')
                                            ->placeholder('verification code')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Robots.txt')
                                    ->collapsed()
                                    ->schema([
                                        Forms\Components\Textarea::make('robots_txt')
                                            ->label('robots.txt')
                                            ->rows(8)
                                            ->fontFamily('mono')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ── Social ──────────────────────────────────────
                        Forms\Components\Tabs\Tab::make(__('admin.settings_social'))
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Section::make(__('admin.settings_social_networks'))
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('social_instagram')
                                            ->label('Instagram')
                                            ->url()
                                            ->prefixIcon('heroicon-o-photo'),

                                        Forms\Components\TextInput::make('social_telegram')
                                            ->label('Telegram')
                                            ->url()
                                            ->prefixIcon('heroicon-o-paper-airplane'),

                                        Forms\Components\TextInput::make('social_whatsapp')
                                            ->label('WhatsApp')
                                            ->url()
                                            ->prefixIcon('heroicon-o-chat-bubble-left-ellipsis'),

                                        Forms\Components\TextInput::make('social_linkedin')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->prefixIcon('heroicon-o-user'),

                                        Forms\Components\TextInput::make('social_youtube')
                                            ->label('YouTube')
                                            ->url()
                                            ->prefixIcon('heroicon-o-video-camera'),

                                        Forms\Components\TextInput::make('social_twitter')
                                            ->label('X / Twitter')
                                            ->url()
                                            ->prefixIcon('heroicon-o-at-symbol'),
                                    ]),
                            ]),

                        // ── Payment ─────────────────────────────────────
                        Forms\Components\Tabs\Tab::make(__('admin.settings_payment'))
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Forms\Components\Section::make('ZarinPal')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('payment_zarinpal_merchant')
                                            ->label('Merchant ID')
                                            ->password()
                                            ->revealable(),

                                        Forms\Components\Toggle::make('payment_zarinpal_sandbox')
                                            ->label(__('admin.settings_sandbox_mode')),
                                    ]),

                                Forms\Components\Section::make(__('admin.settings_bank_receipt'))
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('payment_receipt_bank_name')
                                            ->label(__('admin.settings_bank_name')),

                                        Forms\Components\TextInput::make('payment_receipt_account_number')
                                            ->label(__('admin.settings_account_number')),

                                        Forms\Components\TextInput::make('payment_receipt_iban')
                                            ->label('IBAN'),

                                        Forms\Components\TextInput::make('payment_receipt_swift')
                                            ->label('SWIFT / BIC'),

                                        Forms\Components\Textarea::make('payment_receipt_instructions')
                                            ->label(__('admin.settings_payment_instructions'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ── Email ────────────────────────────────────────
                        Forms\Components\Tabs\Tab::make(__('admin.settings_email'))
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Forms\Components\Section::make('SMTP')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('smtp_host')
                                            ->label('SMTP Host')
                                            ->placeholder('smtp.gmail.com'),

                                        Forms\Components\TextInput::make('smtp_port')
                                            ->label('SMTP Port')
                                            ->numeric()
                                            ->placeholder('587'),

                                        Forms\Components\TextInput::make('smtp_username')
                                            ->label(__('admin.settings_username')),

                                        Forms\Components\TextInput::make('smtp_password')
                                            ->label(__('admin.settings_password'))
                                            ->password()
                                            ->revealable(),

                                        Forms\Components\TextInput::make('smtp_from_address')
                                            ->label(__('admin.settings_from_email'))
                                            ->email(),

                                        Forms\Components\TextInput::make('smtp_from_name')
                                            ->label(__('admin.settings_from_name')),
                                    ]),
                            ]),

                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString('tab'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
        } catch (Halt $exception) {
            return;
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : (string) ($value ?? '')]
            );
        }

        // Clear Settings cache
        \Illuminate\Support\Facades\Cache::forget('settings.all');

        Notification::make()
            ->title(__('admin.settings_saved'))
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('admin.settings_save'))
                ->submit('save')
                ->icon('heroicon-o-check')
                ->color('primary'),
        ];
    }
}
