<?php

namespace App\Support;

use App\Models\Setting;

/**
 * The admin "Email" tab (Settings → SMTP) is stored in the settings table; this copies it
 * into the mail config when a host is filled in, so those fields actually take effect.
 * With no host set, the mailer from .env is used untouched.
 */
class MailSettings
{
    public static function apply(): void
    {
        try {
            $host = trim((string) Setting::get('smtp_host', ''));

            if ($host === '') {
                return;
            }

            config([
                'mail.default'               => 'smtp',
                'mail.mailers.smtp.host'     => $host,
                'mail.mailers.smtp.port'     => (int) (Setting::get('smtp_port') ?: 587),
                'mail.mailers.smtp.username' => Setting::get('smtp_username') ?: null,
                'mail.mailers.smtp.password' => Setting::get('smtp_password') ?: null,
            ]);

            if ($from = Setting::get('smtp_from_address')) {
                config(['mail.from.address' => $from]);
            }

            if ($name = Setting::get('smtp_from_name')) {
                config(['mail.from.name' => $name]);
            }
        } catch (\Throwable) {
            // the settings table doesn't exist yet (fresh install, `migrate:fresh`, test database)
        }
    }
}
