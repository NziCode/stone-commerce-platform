<?php

namespace App\Services;

use App\Mail\AdminAlert;
use App\Models\Setting;
use App\Support\MailSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Tells the site owner when something needs attention (new reservation request,
 * contact message, order, bank receipt). Three independent channels, each used only
 * when it is configured in the admin panel (Settings → Contact):
 *
 *   email — "notification email" (falls back to the site email); needs a working mailer
 *   sms   — "SMS notification numbers" + the SMS tab (Kavenegar)
 *   bot   — a Telegram-compatible bot (Telegram, or Bale via its API address)
 *
 * A failing channel never breaks the visitor's request: errors are logged and swallowed.
 */
class AdminNotifier
{
    /** SMS and bot messages per hour — a cap on cost/spam if a form is hammered. */
    private const HOURLY_LIMIT = 40;

    /** Link into the admin panel (its path is "admin"), e.g. adminUrl('orders'). */
    public static function adminUrl(string $path = ''): string
    {
        return url('/admin/' . ltrim($path, '/'));
    }

    /** Send after the response has gone out (the visitor never waits for an SMTP/HTTP call). */
    public static function dispatch(string $title, array|string $lines = [], ?string $url = null): void
    {
        app()->terminating(function () use ($title, $lines, $url) {
            try {
                static::send($title, $lines, $url);
            } catch (\Throwable $e) {
                Log::warning('AdminNotifier: ' . $e->getMessage());
            }
        });
    }

    /**
     * Send right now to every configured channel.
     *
     * @return array<string, string> channel => "ok" | "skipped: …" | "error: …"
     */
    public static function send(string $title, array|string $lines = [], ?string $url = null): array
    {
        $body = static::compose($lines, $url);
        $text = $title . "\n" . $body;

        return [
            'email' => static::viaEmail($title, $body),
            'sms'   => static::viaSms($text),
            'bot'   => static::viaBot($text),
        ];
    }

    private static function compose(array|string $lines, ?string $url): string
    {
        $body = is_array($lines) ? implode("\n", array_filter($lines, fn ($l) => $l !== null && $l !== '')) : $lines;
        $body = preg_replace('/[^\P{Cc}\n]+/u', '', $body);   // no control characters from visitor input (Persian ZWNJ is kept)
        $body = Str::limit(trim($body), 700, '…');

        return $url ? $body . "\n" . $url : $body;
    }

    private static function viaEmail(string $title, string $body): string
    {
        $recipients = static::emails((string) (static::setting('contact_notify_email') ?: static::setting('site_email')));

        if ($recipients === []) {
            return 'skipped: no notification email';
        }

        try {
            MailSettings::apply();
            Mail::to($recipients)->send(new AdminAlert($title, $body));

            return 'ok';
        } catch (\Throwable $e) {
            Log::warning('AdminNotifier email failed: ' . $e->getMessage());

            return 'error: ' . Str::limit($e->getMessage(), 160);
        }
    }

    private static function viaSms(string $text): string
    {
        $sms = new SmsService();

        if (! $sms->isConfigured()) {
            return 'skipped: SMS gateway not configured';
        }

        if (RateLimiter::tooManyAttempts('admin-notify:sms', self::HOURLY_LIMIT)) {
            return 'skipped: hourly limit reached';
        }

        RateLimiter::hit('admin-notify:sms', 3600);

        return $sms->notifyAdmins($text) ? 'ok' : 'skipped: no admin numbers or the gateway refused the message';
    }

    private static function viaBot(string $text): string
    {
        $token = trim((string) static::setting('contact_notify_bot_token'));
        $chat  = trim((string) static::setting('contact_notify_bot_chat_id'));

        if ($token === '' || $chat === '') {
            return 'skipped: bot not configured';
        }

        if (RateLimiter::tooManyAttempts('admin-notify:bot', self::HOURLY_LIMIT)) {
            return 'skipped: hourly limit reached';
        }

        RateLimiter::hit('admin-notify:bot', 3600);

        $api = rtrim(trim((string) static::setting('contact_notify_bot_api')) ?: 'https://api.telegram.org', '/');

        try {
            $response = Http::timeout(6)->asJson()->post("{$api}/bot{$token}/sendMessage", [
                'chat_id'                  => $chat,
                'text'                     => $text,
                'disable_web_page_preview' => true,
            ]);

            return $response->successful() ? 'ok' : 'error: HTTP ' . $response->status();
        } catch (\Throwable $e) {
            // connection errors quote the request URL, which contains the bot token
            $message = str_replace($token, '***', $e->getMessage());
            Log::warning('AdminNotifier bot failed: ' . $message);

            return 'error: ' . Str::limit($message, 160);
        }
    }

    /** @return list<string> */
    private static function emails(string $raw): array
    {
        return array_values(array_filter(
            preg_split('/[\s,;]+/', $raw) ?: [],
            fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL)
        ));
    }

    private static function setting(string $key): ?string
    {
        try {
            $value = Setting::get($key);

            return $value === null ? null : (string) $value;
        } catch (\Throwable) {
            return null;
        }
    }
}
