<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // ── General ──────────────────────────────────────
            [
                'group'     => 'general',
                'key'       => 'site_name',
                'value'     => 'Stone Commerce',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_name_en',
                'value'     => 'Stone Commerce',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_tagline',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_logo',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_favicon',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_email',
                'value'     => 'info@example.com',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_phone',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_phone_whatsapp',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_address',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_address_en',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_working_hours',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_map_lat',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_map_lng',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'general',
                'key'       => 'site_google_map_embed',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],

            // ── SEO ──────────────────────────────────────────
            [
                'group'     => 'seo',
                'key'       => 'meta_title_fa',
                'value'     => 'Stone Commerce | خرید سنگ ساختمانی',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'meta_title_en',
                'value'     => 'Stone Commerce | Natural Stone',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'meta_description_fa',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'meta_description_en',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'google_analytics_id',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'google_tag_manager_id',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'google_search_console',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'seo',
                'key'       => 'robots_txt',
                'value'     => "User-agent: *\nAllow: /",
                'type'      => 'string',
                'is_public' => false,
            ],

            // ── Social ───────────────────────────────────────
            [
                'group'     => 'social',
                'key'       => 'social_instagram',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_linkedin',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_youtube',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_twitter',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_facebook',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_telegram',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'social',
                'key'       => 'social_whatsapp',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],

            // ── Payment ──────────────────────────────────────
            [
                'group'     => 'payment',
                'key'       => 'payment_zarinpal_merchant',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_zarinpal_sandbox',
                'value'     => '1',
                'type'      => 'boolean',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_mellat_terminal',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_mellat_username',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_mellat_password',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_bank_name',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_account_number',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_swift',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_iban',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'payment',
                'key'       => 'payment_receipt_instructions',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],

            // ── SMTP / Email ──────────────────────────────────
            [
                'group'     => 'smtp',
                'key'       => 'smtp_host',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_port',
                'value'     => '587',
                'type'      => 'integer',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_username',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_password',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_from_address',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'smtp',
                'key'       => 'smtp_from_name',
                'value'     => 'Stone Commerce',
                'type'      => 'string',
                'is_public' => false,
            ],

            // ── SMS ───────────────────────────────────────────
            [
                'group'     => 'sms',
                'key'       => 'sms_provider',
                'value'     => 'kavenegar',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_api_key',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_sender',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_order_confirmed_template',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_order_shipped_template',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'sms',
                'key'       => 'sms_otp_template',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],

            // ── Shipping ──────────────────────────────────────
            [
                'group'     => 'shipping',
                'key'       => 'shipping_free_above',
                'value'     => '0',
                'type'      => 'integer',
                'is_public' => true,
            ],
            [
                'group'     => 'shipping',
                'key'       => 'shipping_default_cost',
                'value'     => '0',
                'type'      => 'integer',
                'is_public' => true,
            ],

            // ── Contact ───────────────────────────────────────
            [
                'group'     => 'contact',
                'key'       => 'contact_notify_email',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'contact',
                'key'       => 'contact_notify_sms',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
            [
                'group'     => 'contact',
                'key'       => 'contact_recaptcha_enabled',
                'value'     => '0',
                'type'      => 'boolean',
                'is_public' => false,
            ],
            [
                'group'     => 'contact',
                'key'       => 'contact_recaptcha_site_key',
                'value'     => '',
                'type'      => 'string',
                'is_public' => true,
            ],
            [
                'group'     => 'contact',
                'key'       => 'contact_recaptcha_secret_key',
                'value'     => '',
                'type'      => 'string',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
