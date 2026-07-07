<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Local, permanent cache of machine-translated strings so identical source
 * text is only ever sent to the external translator once — reused across
 * repeater rows, records, and future runs, and as a fallback when the
 * external service is rate-limited or unavailable.
 */
class TranslationCache extends Model
{
    protected $fillable = [
        'source_hash', 'source_locale', 'target_locale', 'context', 'source_text', 'translated_text',
    ];

    public static function hash(string $sourceLocale, string $targetLocale, string $context, string $text): string
    {
        return hash('sha256', "{$sourceLocale}|{$targetLocale}|{$context}|{$text}");
    }

    public static function lookup(string $sourceLocale, string $targetLocale, string $text, string $context = 'text'): ?string
    {
        return static::query()
            ->where('source_hash', static::hash($sourceLocale, $targetLocale, $context, $text))
            ->value('translated_text');
    }

    public static function store(string $sourceLocale, string $targetLocale, string $text, string $translated, string $context = 'text'): void
    {
        static::query()->updateOrCreate(
            ['source_hash' => static::hash($sourceLocale, $targetLocale, $context, $text)],
            [
                'source_locale' => $sourceLocale,
                'target_locale' => $targetLocale,
                'context' => $context,
                'source_text' => $text,
                'translated_text' => $translated,
            ]
        );
    }
}
