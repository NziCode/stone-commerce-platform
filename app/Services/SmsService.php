<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $apiKey;
    protected ?string $sender;
    protected string $baseUrl;

    public function __construct()
    {
        // .env wins; otherwise the admin panel's SMS tab (Kavenegar is the only gateway supported here)
        $provider = (string) $this->setting('sms_provider');
        $fromPanel = in_array($provider, ['', 'kavenegar'], true);

        $this->apiKey  = (string) (config('services.kavenegar.api_key') ?: ($fromPanel ? $this->setting('sms_api_key') : ''));
        $this->sender  = config('services.kavenegar.sender') ?: ($fromPanel ? $this->setting('sms_sender') : null);
        $this->baseUrl = "https://api.kavenegar.com/v1/{$this->apiKey}/sms/send.json";
    }

    public function isConfigured(): bool
    {
        return filled($this->apiKey);
    }

    /** Text message to every configured admin number. False when nothing was sent. */
    public function notifyAdmins(string $message): bool
    {
        $numbers = $this->getAdminNumbers();

        return $numbers !== [] && $this->send($numbers, $message);
    }

    private function setting(string $key): ?string
    {
        try {
            $value = Setting::get($key);

            return $value === null ? null : (string) $value;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Send a plain SMS to one or more recipients.
     *
     * @param string|array $to  Single number or array of numbers (e.g. 09121234567)
     * @param string       $message
     */
    public function send(string|array $to, string $message): bool
    {
        if (blank($this->apiKey)) {
            Log::warning('SmsService: Kavenegar API key is not configured. SMS not sent.');
            return false;
        }

        $receptor = is_array($to) ? implode(',', $to) : $to;

        try {
            $response = Http::asForm()->post($this->baseUrl, [
                'receptor' => $receptor,
                'sender'   => $this->sender,
                'message'  => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('SmsService: Kavenegar send failed.', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('SmsService: Exception while sending SMS.', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send the new-review notification to all configured admin numbers.
     */
    public function notifyNewReview(string $productName, string $reviewerName, int $rating): bool
    {
        $numbers = $this->getAdminNumbers();

        if (empty($numbers)) {
            Log::warning('SmsService: No admin numbers configured for review notifications.');
            return false;
        }

        $message = "نظر جدید ثبت شد\n"
            . "محصول: {$productName}\n"
            . "کاربر: {$reviewerName}\n"
            . "امتیاز: {$rating} از 5\n"
            . "برای بررسی به پنل مدیریت مراجعه کنید.";

        return $this->send($numbers, $message);
    }

    /**
     * Admin notification numbers: .env (comma separated) plus the admin panel's
     * Contact tab ("SMS notification numbers", comma / space / line separated).
     */
    protected function getAdminNumbers(): array
    {
        $raw = config('services.kavenegar.review_notify_numbers') . ',' . $this->setting('contact_notify_sms');

        return array_values(array_unique(array_filter(array_map('trim', preg_split('/[\s,;]+/', $raw)))));
    }
}
