<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Free machine translation via Google Translate's public "gtx" web endpoint
 * (the same one translate.google.com uses in the browser). No API key, no billing.
 * It's an undocumented endpoint, so every call is cached and failures degrade to null
 * rather than breaking the admin form.
 */
class TranslationService
{
    protected const ENDPOINT = 'https://translate.googleapis.com/translate_a/single';

    /** Max characters sent per request; the free endpoint truncates long queries. */
    protected const MAX_CHUNK = 3500;

    /** Locale codes this app uses that differ from Google's language codes. */
    protected const LOCALE_MAP = [
        'zh' => 'zh-CN',
    ];

    /**
     * Translate a single plain-text value.
     */
    public function translate(?string $text, string $targetLocale, string $sourceLocale): ?string
    {
        $text = trim((string) $text);

        if ($text === '' || $targetLocale === $sourceLocale) {
            return $text;
        }

        $cacheKey = 'translate.' . md5($sourceLocale . '|' . $targetLocale . '|' . $text);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($text, $targetLocale, $sourceLocale) {
            return $this->translateUncached($text, $targetLocale, $sourceLocale);
        });
    }

    /**
     * Translate an HTML/rich-text value. The free endpoint doesn't understand markup,
     * so we translate the text content only and rewrap it in paragraphs. Manual
     * touch-up of formatting (bold/links) is expected afterwards.
     */
    public function translateHtml(?string $html, string $targetLocale, string $sourceLocale): ?string
    {
        $html = (string) $html;
        // Turn block-level boundaries into newlines before stripping tags, otherwise
        // strip_tags() would glue separate paragraphs together with no separator.
        $html = preg_replace('/<\/(p|div|li|h[1-6]|blockquote)>/i', "\n", $html);
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
        $plain = trim(strip_tags($html));
        $plain = preg_replace('/\n{3,}/', "\n\n", $plain);

        if ($plain === '') {
            return '';
        }

        $translated = $this->translate($plain, $targetLocale, $sourceLocale);

        if ($translated === null) {
            return null;
        }

        $paragraphs = array_filter(array_map('trim', explode("\n", $translated)));

        return implode('', array_map(fn ($p) => '<p>' . e($p) . '</p>', $paragraphs));
    }

    /**
     * Translate several plain-text fields to the same target language in one request,
     * cutting round trips to translate.googleapis.com when a form has many fields.
     *
     * @param  array<string, string|null>  $fields  ['field_name' => 'source text']
     * @return array<string, string|null>           ['field_name' => 'translated text']
     */
    public function translateFields(array $fields, string $targetLocale, string $sourceLocale): array
    {
        $fields = array_filter($fields, fn ($v) => trim((string) $v) !== '');

        if (empty($fields) || $targetLocale === $sourceLocale) {
            return array_map(fn ($v) => (string) $v, $fields);
        }

        $separator = "\n@@@\n";
        $joined = implode($separator, array_map(fn ($v) => trim((string) $v), $fields));

        if (strlen($joined) > self::MAX_CHUNK) {
            $result = [];
            foreach ($fields as $name => $value) {
                $result[$name] = $this->translate($value, $targetLocale, $sourceLocale);
            }
            return $result;
        }

        $translatedJoined = $this->translate($joined, $targetLocale, $sourceLocale);

        if ($translatedJoined === null) {
            return array_fill_keys(array_keys($fields), null);
        }

        $parts = preg_split('/\s*@@@\s*/', $translatedJoined);
        $names = array_keys($fields);

        if (count($parts) !== count($names)) {
            $result = [];
            foreach ($fields as $name => $value) {
                $result[$name] = $this->translate($value, $targetLocale, $sourceLocale);
            }
            return $result;
        }

        return array_combine($names, $parts);
    }

    protected function translateUncached(string $text, string $targetLocale, string $sourceLocale): ?string
    {
        $limiterKey = 'translation-service:' . request()->ip();

        if (RateLimiter::tooManyAttempts($limiterKey, 60)) {
            Log::warning('TranslationService: rate limit hit, skipping request.');
            return null;
        }

        RateLimiter::hit($limiterKey, 60);

        $target = self::LOCALE_MAP[$targetLocale] ?? $targetLocale;
        $source = self::LOCALE_MAP[$sourceLocale] ?? $sourceLocale;

        $chunks = mb_strlen($text) > self::MAX_CHUNK
            ? mb_str_split($text, self::MAX_CHUNK)
            : [$text];

        $out = '';

        foreach ($chunks as $chunk) {
            try {
                $response = Http::timeout(8)
                    ->retry(2, 300)
                    ->get(self::ENDPOINT, [
                        'client' => 'gtx',
                        'sl' => $source,
                        'tl' => $target,
                        'dt' => 't',
                        'q' => $chunk,
                    ]);

                if (! $response->successful()) {
                    Log::warning('TranslationService: non-2xx response.', [
                        'status' => $response->status(),
                        'source' => $source,
                        'target' => $target,
                    ]);
                    return null;
                }

                $segments = $response->json(0) ?? [];
                foreach ($segments as $segment) {
                    $out .= $segment[0] ?? '';
                }
            } catch (\Throwable $e) {
                Log::error('TranslationService: request failed.', [
                    'message' => $e->getMessage(),
                    'source' => $source,
                    'target' => $target,
                ]);
                return null;
            }
        }

        return $out;
    }
}
