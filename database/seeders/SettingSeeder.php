<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

/**
 * Defines every setting *field* — its group, storage type, and whether it's
 * public — with empty placeholder values. The actual content (site name,
 * contact info, translations, etc.) lives in SettingValuesSeeder instead,
 * which is gitignored so real site/client data never reaches source control.
 *
 * After configuring Settings through the admin panel, run
 * `php artisan db:sync-seeders SettingValuesSeeder` to (re)generate that
 * file locally from the current database.
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            // ── General ──────────────────────────────────────
            ['group' => 'general', 'key' => 'site_name', 'type' => 'translatable', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_tagline', 'type' => 'translatable', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_logo', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_favicon', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_email', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_phone', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_address', 'type' => 'translatable', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_working_hours', 'type' => 'translatable', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_map_lat', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'site_map_lng', 'type' => 'string', 'is_public' => true],

            // ── SEO ──────────────────────────────────────────
            ['group' => 'seo', 'key' => 'meta_title', 'type' => 'translatable', 'is_public' => false],
            ['group' => 'seo', 'key' => 'meta_description', 'type' => 'translatable', 'is_public' => false],
            ['group' => 'seo', 'key' => 'og_image', 'type' => 'string', 'is_public' => false],
            ['group' => 'seo', 'key' => 'google_analytics_id', 'type' => 'string', 'is_public' => false],
            ['group' => 'seo', 'key' => 'google_tag_manager_id', 'type' => 'string', 'is_public' => false],
            ['group' => 'seo', 'key' => 'google_search_console', 'type' => 'string', 'is_public' => false],
            ['group' => 'seo', 'key' => 'robots_txt', 'type' => 'string', 'is_public' => false],

            // ── Social ───────────────────────────────────────
            ['group' => 'social', 'key' => 'social_instagram', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'social_linkedin', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'social_youtube', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'social_twitter', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'social_facebook', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'social_telegram', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'social_whatsapp', 'type' => 'string', 'is_public' => true],

            // ── Payment ──────────────────────────────────────
            ['group' => 'payment', 'key' => 'payment_zarinpal_sandbox', 'type' => 'boolean', 'is_public' => false],
            ['group' => 'payment', 'key' => 'payment_mellat_terminal', 'type' => 'string', 'is_public' => false],
            ['group' => 'payment', 'key' => 'payment_receipt_bank_name', 'type' => 'translatable', 'is_public' => true],
            ['group' => 'payment', 'key' => 'payment_receipt_account_number', 'type' => 'string', 'is_public' => true],
            ['group' => 'payment', 'key' => 'payment_receipt_swift', 'type' => 'string', 'is_public' => true],
            ['group' => 'payment', 'key' => 'payment_receipt_iban', 'type' => 'string', 'is_public' => true],
            ['group' => 'payment', 'key' => 'payment_receipt_instructions', 'type' => 'translatable', 'is_public' => true],

            // ── SMTP / Email ───────────────────────────────────
            ['group' => 'smtp', 'key' => 'smtp_host', 'type' => 'string', 'is_public' => false],
            ['group' => 'smtp', 'key' => 'smtp_port', 'type' => 'integer', 'is_public' => false],
            ['group' => 'smtp', 'key' => 'smtp_username', 'type' => 'string', 'is_public' => false],
            ['group' => 'smtp', 'key' => 'smtp_from_address', 'type' => 'string', 'is_public' => false],
            ['group' => 'smtp', 'key' => 'smtp_from_name', 'type' => 'string', 'is_public' => false],

            // ── SMS ────────────────────────────────────────────
            ['group' => 'sms', 'key' => 'sms_provider', 'type' => 'string', 'is_public' => false],
            ['group' => 'sms', 'key' => 'sms_sender', 'type' => 'string', 'is_public' => false],
            ['group' => 'sms', 'key' => 'sms_order_confirmed_template', 'type' => 'translatable', 'is_public' => false],
            ['group' => 'sms', 'key' => 'sms_order_shipped_template', 'type' => 'translatable', 'is_public' => false],
            ['group' => 'sms', 'key' => 'sms_otp_template', 'type' => 'translatable', 'is_public' => false],

            // ── Shipping ─────────────────────────────────────
            ['group' => 'shipping', 'key' => 'shipping_free_above', 'type' => 'integer', 'is_public' => true],
            ['group' => 'shipping', 'key' => 'shipping_default_cost', 'type' => 'integer', 'is_public' => true],

            // ── Contact ──────────────────────────────────────
            ['group' => 'contact', 'key' => 'contact_notify_email', 'type' => 'string', 'is_public' => false],
            ['group' => 'contact', 'key' => 'contact_notify_sms', 'type' => 'string', 'is_public' => false],
            ['group' => 'contact', 'key' => 'contact_recaptcha_enabled', 'type' => 'boolean', 'is_public' => false],
            ['group' => 'contact', 'key' => 'contact_recaptcha_site_key', 'type' => 'string', 'is_public' => true],

            // ── Home ─────────────────────────────────────────
            ['group' => 'home', 'key' => 'banner_1_title', 'type' => 'string', 'is_public' => false],
            ['group' => 'home', 'key' => 'banner_1_desc', 'type' => 'string', 'is_public' => false],
            ['group' => 'home', 'key' => 'banner_2_title', 'type' => 'string', 'is_public' => false],
            ['group' => 'home', 'key' => 'banner_2_desc', 'type' => 'string', 'is_public' => false],
            ['group' => 'home', 'key' => 'banner_3_title', 'type' => 'string', 'is_public' => false],
            ['group' => 'home', 'key' => 'banner_3_desc', 'type' => 'string', 'is_public' => false],
            ['group' => 'home', 'key' => 'about_years', 'type' => 'string', 'is_public' => false],
            ['group' => 'home', 'key' => 'about_title', 'type' => 'translatable', 'is_public' => false],
            ['group' => 'home', 'key' => 'about_desc', 'type' => 'translatable', 'is_public' => false],
            ['group' => 'home', 'key' => 'about_feature_1', 'type' => 'translatable', 'is_public' => false],
            ['group' => 'home', 'key' => 'about_feature_2', 'type' => 'translatable', 'is_public' => false],
            ['group' => 'home', 'key' => 'about_feature_3', 'type' => 'translatable', 'is_public' => false],
        ];

        foreach ($fields as $field) {
            Setting::firstOrCreate(
                ['key' => $field['key']],
                [
                    'group' => $field['group'],
                    'type' => $field['type'],
                    'is_public' => $field['is_public'],
                    'value' => $field['type'] === 'translatable' ? json_encode([]) : ($field['type'] === 'boolean' ? '0' : ''),
                ]
            );
        }
    }
}
