<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $apiKey;
    protected ?string $sender;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = (string) config('services.kavenegar.api_key');
        $this->sender  = config('services.kavenegar.sender');
        $this->baseUrl = "https://api.kavenegar.com/v1/{$this->apiKey}/sms/send.json";
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
     * Get admin notification numbers from config (comma separated in .env).
     */
    protected function getAdminNumbers(): array
    {
        $raw = (string) config('services.kavenegar.review_notify_numbers');

        if (blank($raw)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }
}
