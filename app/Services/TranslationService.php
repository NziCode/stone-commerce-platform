<?php

namespace App\Services;

use App\Models\TranslationCache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Free machine translation via Google Translate's public "gtx" web endpoint
 * (the same one translate.google.com uses in the browser). No API key, no billing.
 * It's an undocumented endpoint, so every call is cached and failures degrade to null
 * rather than breaking the admin form.
 *
 * Every translated string is persisted in the `translation_caches` table (see
 * App\Models\TranslationCache) rather than the general-purpose cache store, so
 * identical source text is only ever sent to the external translator once —
 * reused across repeater rows, records, and future runs — and previously
 * cached translations remain available even when the external service is
 * rate-limited or unreachable.
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

    /** In-process memo so repeated identical lookups in one run skip the DB too. */
    protected static array $memo = [];

    /**
     * Translate a single plain-text value.
     */
    public function translate(?string $text, string $targetLocale, string $sourceLocale): ?string
    {
        $text = trim((string) $text);

        if ($text === '' || $targetLocale === $sourceLocale) {
            return $text;
        }

        $cached = $this->cachedLookup($text, $targetLocale, $sourceLocale);

        if ($cached !== null) {
            return $cached;
        }

        $translated = $this->translateUncached($text, $targetLocale, $sourceLocale);

        if ($translated !== null) {
            $this->rememberTranslation($text, $targetLocale, $sourceLocale, $translated);
        }

        return $translated;
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
     * Fields already found in the local cache are resolved immediately without any
     * network call, and fields sharing identical source text are only translated
     * once — the result is fanned out to every field name that shares that text.
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

        $result = [];
        $pending = []; // unique source text => [field names sharing it]

        foreach ($fields as $name => $value) {
            $text = trim((string) $value);
            $cached = $this->cachedLookup($text, $targetLocale, $sourceLocale);

            if ($cached !== null) {
                $result[$name] = $cached;
                continue;
            }

            $pending[$text][] = $name;
        }

        if (empty($pending)) {
            return $result;
        }

        $uniqueTexts = array_keys($pending);
        $separator = "\n@@@\n";
        $joined = implode($separator, $uniqueTexts);

        if (strlen($joined) > self::MAX_CHUNK) {
            foreach ($uniqueTexts as $text) {
                $translated = $this->translate($text, $targetLocale, $sourceLocale);

                foreach ($pending[$text] as $name) {
                    $result[$name] = $translated;
                }
            }

            return $result;
        }

        $translatedJoined = $this->translateUncached($joined, $targetLocale, $sourceLocale);

        if ($translatedJoined === null) {
            foreach ($uniqueTexts as $text) {
                foreach ($pending[$text] as $name) {
                    $result[$name] = null;
                }
            }

            return $result;
        }

        $parts = preg_split('/\s*@@@\s*/', $translatedJoined);

        if (count($parts) !== count($uniqueTexts)) {
            foreach ($uniqueTexts as $text) {
                $translated = $this->translate($text, $targetLocale, $sourceLocale);

                foreach ($pending[$text] as $name) {
                    $result[$name] = $translated;
                }
            }

            return $result;
        }

        foreach ($uniqueTexts as $i => $text) {
            $translated = $parts[$i];
            $this->rememberTranslation($text, $targetLocale, $sourceLocale, $translated);

            foreach ($pending[$text] as $name) {
                $result[$name] = $translated;
            }
        }

        return $result;
    }

    /**
     * Check the in-process memo, then the persistent DB cache, for an existing
     * translation of $text. Returns null on a miss — callers fall through to
     * the external translator.
     */
    protected function cachedLookup(string $text, string $targetLocale, string $sourceLocale): ?string
    {
        $memoKey = "{$sourceLocale}|{$targetLocale}|{$text}";

        if (array_key_exists($memoKey, static::$memo)) {
            return static::$memo[$memoKey];
        }

        $cached = TranslationCache::lookup($sourceLocale, $targetLocale, $text);

        if ($cached !== null) {
            static::$memo[$memoKey] = $cached;
        }

        return $cached;
    }

    protected function rememberTranslation(string $text, string $targetLocale, string $sourceLocale, string $translated): void
    {
        TranslationCache::store($sourceLocale, $targetLocale, $text, $translated);
        static::$memo["{$sourceLocale}|{$targetLocale}|{$text}"] = $translated;
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
