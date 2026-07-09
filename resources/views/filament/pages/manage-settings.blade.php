<x-filament-panels::page>

@php
$tabs = [
    ['key'=>'general', 'label'=>__('admin.general'), 'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['key'=>'seo',     'label'=>'SEO',               'icon'=>'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
    ['key'=>'social',  'label'=>__('admin.social'),  'icon'=>'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2V6a2 2 0 012-2h8z'],
    ['key'=>'payment', 'label'=>__('admin.payment'), 'icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
    ['key'=>'smtp',    'label'=>__('admin.email'),   'icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
    ['key'=>'sms',     'label'=>__('admin.sms'),     'icon'=>'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
    ['key'=>'contact', 'label'=>__('admin.contact'), 'icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V6a2 2 0 012-2z'],
    ['key'=>'about',   'label'=>__('admin.about'),   'icon'=>'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
];
@endphp

<div x-data="{ tab: '{{ $activeTab }}' }">

{{-- Tab nav --}}
<div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1.5rem;background:#fff;border-radius:14px;padding:.55rem;box-shadow:0 1px 2px rgba(0,0,0,.04),0 8px 20px -14px rgba(11,33,71,.15);border:1px solid rgba(11,33,71,.06)">
    @foreach($tabs as $t)
        <button type="button" @click="tab = '{{ $t['key'] }}'"
                :style="'display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1rem;border-radius:9px;border:none;cursor:pointer;font-size:.83rem;font-weight:600;font-family:inherit;transition:all .15s;' + (tab === '{{ $t['key'] }}' ? 'background:linear-gradient(135deg,#ff5a1f,#ff8a3d);color:#fff;box-shadow:0 6px 14px -6px rgba(255,90,31,.5)' : 'color:#6b7280;background:transparent')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="{{ $t['icon'] }}"/></svg>
            {{ $t['label'] }}
        </button>
    @endforeach
</div>

@php
$inputClass = "fi-input block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm";
$labelStyle = "display:block;font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.3rem";
$sectionStyle = "background:#fff;border-radius:12px;border:1px solid rgba(11,33,71,.07);padding:1.4rem 1.6rem;margin-bottom:1.2rem;box-shadow:0 1px 3px rgba(0,0,0,.04)";
$gridStyle = "display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;";
@endphp

{{-- GENERAL --}}
<div x-show="tab === 'general'" x-cloak>
<form wire:submit.prevent="save('general')">
    @include('filament.pages.partials.settings-action-bar')
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">{{ __('admin.site_info') }}</h3>
        @include('filament.pages.partials.settings-translations', [
            'inputClass' => $inputClass,
            'labelStyle' => $labelStyle,
            'fields' => [
                ['key' => 'site_name', 'label' => __('admin.site_name'), 'help' => __('admin.site_name_help')],
            ],
        ])
        <div style="margin-top:1.1rem">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.2rem">
                <label style="{{ $labelStyle }}margin-bottom:0">{{ __('admin.tagline') }}</label>
                <button type="button"
                    wire:click="mountAction('translateField', { field: 'site_tagline', isHtml: true })"
                    style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .55rem;border-radius:6px;border:none;cursor:pointer;background:transparent;color:#ff5a1f;font-size:.72rem;font-weight:600;font-family:inherit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="m5 8 6 6M4 14l6-6 2-3M2 5h12M7 2h1m10 20-4-9-4 9m1.5-3.5h5"/></svg>
                    {{ __('admin.translate_automatically') }}
                </button>
            </div>
            <p style="font-size:.72rem;color:#9ca3af;margin:0 0 .4rem">{{ __('admin.site_tagline_help') }}</p>
            {{ $this->taglineForm }}
        </div>
    </div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">{{ __('admin.contact_info') }}</h3>
        <div style="{{ $gridStyle }}; margin-bottom:1rem">
            <div><label style="{{ $labelStyle }}">{{ __('admin.email') }}</label><input type="email" wire:model.defer="site_email" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">{{ __('admin.phone') }}</label><input type="text" wire:model.defer="site_phone" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">WhatsApp</label><input type="text" wire:model.defer="site_phone_whatsapp" class="{{ $inputClass }}"></div>
        </div>
        @include('filament.pages.partials.settings-translations', [
            'inputClass' => $inputClass,
            'labelStyle' => $labelStyle,
            'fields' => [
                ['key' => 'site_working_hours', 'label' => __('admin.working_hours')],
                ['key' => 'site_address', 'label' => __('admin.address'), 'type' => 'textarea', 'rows' => 2],
            ],
        ])
    </div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">{{ __('admin.map') }}</h3>
        <div style="{{ $gridStyle }}">
            <div><label style="{{ $labelStyle }}">Latitude</label><input type="text" wire:model.defer="site_map_lat" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">Longitude</label><input type="text" wire:model.defer="site_map_lng" class="{{ $inputClass }}"></div>
            <div style="grid-column:span 2"><label style="{{ $labelStyle }}">Google Maps Embed Code</label><textarea wire:model.defer="site_google_map_embed" rows="3" class="{{ $inputClass }} font-mono text-xs"></textarea></div>
        </div>
    </div>
    <div style="display:flex;justify-content:flex-end"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
</form>
</div>

{{-- SEO --}}
<div x-show="tab === 'seo'" x-cloak>
<form wire:submit.prevent="save('seo')">
    @include('filament.pages.partials.settings-action-bar')
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">Meta Tags</h3>
        @include('filament.pages.partials.settings-translations', [
            'inputClass' => $inputClass,
            'labelStyle' => $labelStyle,
            'fields' => [
                ['key' => 'meta_title', 'label' => __('admin.meta_title')],
                ['key' => 'meta_description', 'label' => __('admin.meta_description'), 'type' => 'textarea', 'rows' => 2],
            ],
        ])
        <div style="margin-top:1rem"><label style="{{ $labelStyle }}">OG Image URL</label><input type="text" wire:model.defer="og_image" class="{{ $inputClass }}"></div>
    </div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">Google</h3>
        <div style="{{ $gridStyle }}">
            <div><label style="{{ $labelStyle }}">Google Analytics ID</label><input type="text" wire:model.defer="google_analytics_id" placeholder="G-XXXXXXXXXX" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">Google Tag Manager ID</label><input type="text" wire:model.defer="google_tag_manager_id" placeholder="GTM-XXXXXXX" class="{{ $inputClass }}"></div>
            <div style="grid-column:span 2"><label style="{{ $labelStyle }}">Search Console Verification</label><input type="text" wire:model.defer="google_search_console" class="{{ $inputClass }}"></div>
        </div>
    </div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">Robots.txt</h3>
        <textarea wire:model.defer="robots_txt" rows="10" class="{{ $inputClass }} font-mono text-xs"></textarea>
    </div>
    <div style="display:flex;justify-content:flex-end"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
</form>
</div>

{{-- SOCIAL --}}
<div x-show="tab === 'social'" x-cloak>
<form wire:submit.prevent="save('social')">
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.2rem"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">{{ __('admin.social_networks') }}</h3>
        <div style="{{ $gridStyle }}">
            @foreach([['Instagram','social_instagram'],['Telegram','social_telegram'],['WhatsApp','social_whatsapp'],['LinkedIn','social_linkedin'],['YouTube','social_youtube'],['X (Twitter)','social_twitter'],['Facebook','social_facebook']] as [$name, $prop])
                <div><label style="{{ $labelStyle }}">{{ $name }}</label><input type="url" wire:model.defer="{{ $prop }}" placeholder="https://" class="{{ $inputClass }}"></div>
            @endforeach
        </div>
    </div>
    <div style="display:flex;justify-content:flex-end"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
</form>
</div>

{{-- PAYMENT --}}
<div x-show="tab === 'payment'" x-cloak>
<form wire:submit.prevent="save('payment')">
    @include('filament.pages.partials.settings-action-bar')
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">ZarinPal</h3>
        <div style="{{ $gridStyle }}">
            <div><label style="{{ $labelStyle }}">Merchant ID</label><input type="text" wire:model.defer="payment_zarinpal_merchant" class="{{ $inputClass }}"></div>
            <div style="display:flex;align-items:center;gap:.6rem;padding-top:1.4rem">
                <input type="checkbox" wire:model.defer="payment_zarinpal_sandbox" id="sandbox" style="width:16px;height:16px;accent-color:#ff5a1f">
                <label for="sandbox" style="font-size:.85rem;font-weight:500;color:#374151;cursor:pointer">Sandbox Mode</label>
            </div>
        </div>
    </div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">{{ __('admin.bank_receipt') }}</h3>
        <div style="{{ $gridStyle }}; margin-bottom:1rem">
            <div><label style="{{ $labelStyle }}">{{ __('admin.account_number') }}</label><input type="text" wire:model.defer="payment_receipt_account_number" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">IBAN</label><input type="text" wire:model.defer="payment_receipt_iban" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">SWIFT</label><input type="text" wire:model.defer="payment_receipt_swift" class="{{ $inputClass }}"></div>
        </div>
        @include('filament.pages.partials.settings-translations', [
            'inputClass' => $inputClass,
            'labelStyle' => $labelStyle,
            'fields' => [
                ['key' => 'payment_receipt_bank_name', 'label' => __('admin.bank_name')],
                ['key' => 'payment_receipt_instructions', 'label' => __('admin.payment_instructions'), 'type' => 'textarea', 'rows' => 3],
            ],
        ])
    </div>
    <div style="display:flex;justify-content:flex-end"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
</form>
</div>

{{-- SMTP --}}
<div x-show="tab === 'smtp'" x-cloak>
<form wire:submit.prevent="save('smtp')">
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.2rem"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">SMTP</h3>
        <div style="{{ $gridStyle }}">
            <div><label style="{{ $labelStyle }}">Host</label><input type="text" wire:model.defer="smtp_host" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">Port</label><input type="number" wire:model.defer="smtp_port" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">Username</label><input type="text" wire:model.defer="smtp_username" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">Password</label><input type="password" wire:model.defer="smtp_password" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">From Address</label><input type="email" wire:model.defer="smtp_from_address" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">From Name</label><input type="text" wire:model.defer="smtp_from_name" class="{{ $inputClass }}"></div>
        </div>
    </div>
    <div style="display:flex;justify-content:flex-end"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
</form>
</div>

{{-- SMS --}}
<div x-show="tab === 'sms'" x-cloak>
<form wire:submit.prevent="save('sms')">
    @include('filament.pages.partials.settings-action-bar')
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">SMS Gateway</h3>
        <div style="{{ $gridStyle }}">
            <div>
                <label style="{{ $labelStyle }}">{{ __('admin.provider') }}</label>
                <select wire:model.defer="sms_provider" class="{{ $inputClass }}">
                    <option value="">-- Select --</option>
                    <option value="kavenegar">Kavenegar</option>
                    <option value="melipayamak">Melipayamak</option>
                </select>
            </div>
            <div><label style="{{ $labelStyle }}">API Key</label><input type="password" wire:model.defer="sms_api_key" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">Sender Number</label><input type="text" wire:model.defer="sms_sender" class="{{ $inputClass }}"></div>
        </div>
    </div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">{{ __('admin.sms_templates') }}</h3>
        @include('filament.pages.partials.settings-translations', [
            'inputClass' => $inputClass,
            'labelStyle' => $labelStyle,
            'fields' => [
                ['key' => 'sms_otp_template', 'label' => 'OTP Template'],
                ['key' => 'sms_order_confirmed_template', 'label' => 'Order Confirmed Template'],
                ['key' => 'sms_order_shipped_template', 'label' => 'Order Shipped Template'],
            ],
        ])
    </div>
    <div style="display:flex;justify-content:flex-end"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
</form>
</div>

{{-- CONTACT --}}
<div x-show="tab === 'contact'" x-cloak>
<form wire:submit.prevent="save('contact')">
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.2rem"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">{{ __('admin.notifications') }}</h3>
        <div style="{{ $gridStyle }}">
            <div><label style="{{ $labelStyle }}">{{ __('admin.notify_email') }}</label><input type="email" wire:model.defer="contact_notify_email" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">{{ __('admin.notify_sms') }}</label><input type="text" wire:model.defer="contact_notify_sms" class="{{ $inputClass }}"></div>
        </div>
    </div>
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">reCAPTCHA</h3>
        <div style="{{ $gridStyle }}">
            <div><label style="{{ $labelStyle }}">Site Key</label><input type="text" wire:model.defer="contact_recaptcha_site_key" class="{{ $inputClass }}"></div>
            <div><label style="{{ $labelStyle }}">Secret Key</label><input type="password" wire:model.defer="contact_recaptcha_secret_key" class="{{ $inputClass }}"></div>
        </div>
        <div style="display:flex;align-items:center;gap:.6rem;margin-top:1rem">
            <input type="checkbox" wire:model.defer="contact_recaptcha_enabled" id="recaptcha_on" style="width:16px;height:16px;accent-color:#ff5a1f">
            <label for="recaptcha_on" style="font-size:.85rem;font-weight:500;color:#374151;cursor:pointer">{{ __('admin.recaptcha_enabled') }}</label>
        </div>
    </div>
    <div style="display:flex;justify-content:flex-end"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
</form>
</div>

{{-- ABOUT --}}
<div x-show="tab === 'about'" x-cloak>
<form wire:submit.prevent="save('about')">
    @include('filament.pages.partials.settings-action-bar')
    <div style="{{ $sectionStyle }}">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 1rem;padding-bottom:.7rem;border-bottom:1px solid #f3f4f6">{{ __('admin.about_section') }}</h3>
        <div style="max-width:220px;margin-bottom:1rem"><label style="{{ $labelStyle }}">{{ __('admin.years_experience') }}</label><input type="number" wire:model.defer="about_years" class="{{ $inputClass }}"></div>
        @include('filament.pages.partials.settings-translations', [
            'inputClass' => $inputClass,
            'labelStyle' => $labelStyle,
            'fields' => [
                ['key' => 'about_title', 'label' => __('admin.about_title')],
                ['key' => 'about_desc', 'label' => __('admin.about_desc'), 'type' => 'textarea', 'rows' => 3],
                ['key' => 'about_feature_1', 'label' => 'Feature 1'],
                ['key' => 'about_feature_2', 'label' => 'Feature 2'],
                ['key' => 'about_feature_3', 'label' => 'Feature 3'],
            ],
        ])
    </div>
    <div style="display:flex;justify-content:flex-end"><x-filament::button type="submit" icon="heroicon-o-check-circle">{{ __('admin.save_settings') }}</x-filament::button></div>
</form>
</div>

</div>

</x-filament-panels::page>
