<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Event;
use App\Models\Language;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

/**
 * Finds missing/empty/untranslated values across every multi-locale content
 * source in the app (translatable model fields, Setting::TRANSLATABLE_KEYS,
 * and the `translations` UI-string table), and optionally auto-fixes them
 * using the existing Google-Translate-backed TranslationService.
 *
 * Read-only by default; pass --fix to write corrections.
 */
class AuditTranslations extends Command
{
    protected $signature = 'translations:audit {--fix : Apply fixes using machine translation} {--limit=0 : Stop after N fixes (0 = unlimited)}';
    protected $description = 'Audit translatable content and DB-backed UI strings for missing/empty/untranslated values';

    /** Fields where an identical value across all locales is expected/correct. */
    protected const SKIP_IDENTICAL_CHECK = ['slug'];

    /** Setting keys that are brand/proper-noun-like — identical across locales is correct, not a bug. */
    protected const SKIP_IDENTICAL_SETTING_KEYS = ['site_name'];

    protected array $locales = [];
    protected int $fixCount = 0;
    protected int $fixLimit = 0;

    public function handle(TranslationService $translator): int
    {
        $this->locales = Language::query()->orderBy('id')->pluck('code')->all();
        $fix = (bool) $this->option('fix');
        $this->fixLimit = (int) $this->option('limit');

        $this->info('Locales: ' . implode(', ', $this->locales));
        $this->info($fix ? 'Mode: FIX (writing corrections)' : 'Mode: AUDIT (read-only)');
        $this->newLine();

        $issues = [];

        $models = [
            [Product::class, ['name', 'slug', 'description', 'short_description', 'meta_title', 'meta_description', 'meta_keywords']],
            [Category::class, ['name', 'slug', 'description', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords']],
            [Attribute::class, ['label', 'group']],
            [Event::class, ['title', 'slug', 'description', 'location', 'organizer_name', 'date_label', 'meta_title', 'meta_description']],
            [MenuItem::class, ['label']],
            [Page::class, ['title', 'slug', 'content', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords']],
            [Post::class, ['title', 'slug', 'excerpt', 'content', 'meta_title', 'meta_description', 'meta_keywords']],
            [Slider::class, ['title', 'subtitle', 'description', 'button_text']],
        ];

        foreach ($models as [$class, $fields]) {
            $issues = array_merge($issues, $this->auditModel($class, $fields, $translator, $fix));
        }

        $issues = array_merge($issues, $this->auditSettings($translator, $fix));
        $issues = array_merge($issues, $this->auditUiTranslations($translator, $fix));

        $this->newLine();
        $this->info('Total issues found: ' . count($issues));
        if ($fix) {
            $this->info('Total fixes applied: ' . $this->fixCount);
        }

        $byModel = [];
        foreach ($issues as $issue) {
            $byModel[$issue['source']] = ($byModel[$issue['source']] ?? 0) + 1;
        }
        foreach ($byModel as $source => $count) {
            $this->line("  {$source}: {$count}");
        }

        $path = storage_path('app/translation_audit_' . now()->format('Ymd_His') . '.json');
        file_put_contents($path, json_encode($issues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->info("Full report written to: {$path}");

        return self::SUCCESS;
    }

    /**
     * @param  class-string<Model>  $class
     */
    protected function auditModel(string $class, array $fields, TranslationService $translator, bool $fix): array
    {
        $issues = [];
        $table = (new $class)->getTable();
        $this->info("Auditing {$class} ({$table})...");

        $class::query()->chunkById(50, function ($rows) use (&$issues, $fields, $translator, $fix, $class) {
            foreach ($rows as $row) {
                $dirty = false;

                foreach ($fields as $field) {
                    $translations = $row->getTranslations($field);
                    $sourceLocale = $this->pickSourceLocale($translations);

                    if ($sourceLocale === null) {
                        continue; // field entirely empty in every locale — not a translation issue
                    }

                    $sourceText = (string) $translations[$sourceLocale];
                    $isHtml = in_array($field, ['description', 'content', 'excerpt'], true) && str_contains($sourceText, '<');

                    foreach ($this->locales as $locale) {
                        $value = $translations[$locale] ?? null;
                        $problem = $this->classify($field, $locale, $sourceLocale, $value, $translations);

                        if ($problem === null) {
                            continue;
                        }

                        $issues[] = [
                            'source' => class_basename($class),
                            'id' => $row->id,
                            'field' => $field,
                            'locale' => $locale,
                            'problem' => $problem,
                            'current' => is_string($value) ? mb_substr($value, 0, 80) : $value,
                        ];

                        if ($fix && $this->canKeepFixing()) {
                            if (in_array($field, self::SKIP_IDENTICAL_CHECK, true)) {
                                // Slugs (and similar locale-invariant fields) must never be
                                // machine-translated — copy the source value verbatim.
                                $row->setTranslation($field, $locale, $sourceText);
                                $dirty = true;
                                $this->fixCount++;
                                $this->line("  copied {$class}#{$row->id}.{$field}[{$locale}] from {$sourceLocale}");
                                continue;
                            }

                            $translated = $isHtml
                                ? $translator->translateHtml($sourceText, $locale, $sourceLocale)
                                : $translator->translate($sourceText, $locale, $sourceLocale);

                            if ($translated !== null && trim($translated) !== '') {
                                $row->setTranslation($field, $locale, $translated);
                                $dirty = true;
                                $this->fixCount++;
                                $this->line("  fixed {$class}#{$row->id}.{$field}[{$locale}]");
                            } else {
                                $this->warn("  could not translate {$class}#{$row->id}.{$field}[{$locale}]");
                            }
                        }
                    }
                }

                if ($dirty) {
                    $row->saveQuietly();
                }
            }
        });

        return $issues;
    }

    protected function auditSettings(TranslationService $translator, bool $fix): array
    {
        $issues = [];
        $this->info('Auditing Setting::TRANSLATABLE_KEYS...');

        $settings = Setting::query()->whereIn('key', Setting::TRANSLATABLE_KEYS)->get();

        foreach ($settings as $setting) {
            $decoded = json_decode((string) $setting->value, true);
            if (! is_array($decoded)) {
                continue; // legacy plain value, not yet migrated — separate concern
            }

            $sourceLocale = $this->pickSourceLocale($decoded);
            if ($sourceLocale === null) {
                continue;
            }
            $sourceText = strip_tags((string) $decoded[$sourceLocale]);
            $dirty = false;

            foreach ($this->locales as $locale) {
                $value = $decoded[$locale] ?? null;
                $problem = $this->classify($setting->key, $locale, $sourceLocale, $value, $decoded);

                if ($problem === null) {
                    continue;
                }

                $issues[] = [
                    'source' => 'Setting',
                    'id' => $setting->key,
                    'field' => $setting->key,
                    'locale' => $locale,
                    'problem' => $problem,
                    'current' => is_string($value) ? mb_substr($value, 0, 80) : $value,
                ];

                if ($fix && $this->canKeepFixing()) {
                    $hasHtml = str_contains((string) $decoded[$sourceLocale], '<');
                    $translated = $hasHtml
                        ? $translator->translateHtml((string) $decoded[$sourceLocale], $locale, $sourceLocale)
                        : $translator->translate($sourceText, $locale, $sourceLocale);

                    if ($translated !== null && trim($translated) !== '') {
                        $decoded[$locale] = $translated;
                        $dirty = true;
                        $this->fixCount++;
                        $this->line("  fixed Setting[{$setting->key}][{$locale}]");
                    } else {
                        $this->warn("  could not translate Setting[{$setting->key}][{$locale}]");
                    }
                }
            }

            if ($dirty) {
                $setting->value = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $setting->saveQuietly();
            }
        }

        return $issues;
    }

    protected function auditUiTranslations(TranslationService $translator, bool $fix): array
    {
        $issues = [];
        $this->info('Auditing translations table (UI strings)...');

        // Group by (group, key) so we can see, per string, which locales exist.
        $rows = Translation::query()->get(['id', 'locale', 'group', 'key', 'value']);
        $grouped = $rows->groupBy(fn ($r) => $r->group . '|' . $r->key);

        foreach ($grouped as $groupKey => $rowsForKey) {
            $byLocale = $rowsForKey->keyBy('locale');
            $sourceLocale = null;
            foreach (['fa', 'en'] as $preferred) {
                if (isset($byLocale[$preferred]) && trim((string) $byLocale[$preferred]->value) !== '') {
                    $sourceLocale = $preferred;
                    break;
                }
            }
            if ($sourceLocale === null) {
                foreach ($byLocale as $loc => $r) {
                    if (trim((string) $r->value) !== '') {
                        $sourceLocale = $loc;
                        break;
                    }
                }
            }
            if ($sourceLocale === null) {
                continue; // every locale empty — nothing to translate from
            }

            $sourceText = (string) $byLocale[$sourceLocale]->value;
            [$group, $key] = explode('|', $groupKey, 2);

            foreach ($this->locales as $locale) {
                $existing = $byLocale[$locale]->value ?? null;

                if ($existing !== null && trim((string) $existing) !== '') {
                    continue; // present — UI strings are short/labels, skip identical-value heuristic
                }

                $problem = $existing === null ? 'missing_row' : 'empty';

                $issues[] = [
                    'source' => 'Translation',
                    'id' => "{$group}.{$key}",
                    'field' => $key,
                    'locale' => $locale,
                    'problem' => $problem,
                    'current' => $existing,
                ];

                if ($fix && $this->canKeepFixing()) {
                    $translated = $translator->translate($sourceText, $locale, $sourceLocale);

                    if ($translated !== null && trim($translated) !== '') {
                        Translation::updateOrCreate(
                            ['locale' => $locale, 'group' => $group, 'key' => $key],
                            ['value' => $translated, 'is_auto' => true]
                        );
                        $this->fixCount++;
                        $this->line("  fixed Translation[{$group}.{$key}][{$locale}]");
                    } else {
                        $this->warn("  could not translate Translation[{$group}.{$key}][{$locale}]");
                    }
                }
            }
        }

        if ($fix) {
            Translation::clearCache();
        }

        return $issues;
    }

    /** Pick the best available locale to translate FROM: prefer fa, then en, then first non-empty. */
    protected function pickSourceLocale(array $translations): ?string
    {
        foreach (['fa', 'en'] as $preferred) {
            if (! empty($translations[$preferred]) && trim((string) $translations[$preferred]) !== '') {
                return $preferred;
            }
        }
        foreach ($translations as $locale => $value) {
            if (is_string($value) && trim($value) !== '') {
                return $locale;
            }
        }
        return null;
    }

    /** Decide whether (field, locale) has a real problem worth flagging. Returns null if fine. */
    protected function classify(string $field, string $locale, string $sourceLocale, mixed $value, array $allTranslations): ?string
    {
        if ($locale === $sourceLocale) {
            return null;
        }

        if ($value === null || ! array_key_exists($locale, $allTranslations)) {
            return 'missing_key';
        }

        if (! is_string($value) || trim($value) === '') {
            return 'empty';
        }

        if (in_array($field, self::SKIP_IDENTICAL_CHECK, true) || in_array($field, self::SKIP_IDENTICAL_SETTING_KEYS, true)) {
            return null; // slugs, brand names, etc. are meant to be identical across locales
        }

        $sourceText = (string) $allTranslations[$sourceLocale];

        // Skip the identical-value heuristic for very short / non-alphabetic content
        // (numbers, single words shared across languages, etc.) to reduce false positives.
        $plainSource = trim(strip_tags($sourceText));
        if (mb_strlen($plainSource) < 4 || ! preg_match('/\p{L}/u', $plainSource)) {
            return null;
        }

        if (trim(strip_tags($value)) === $plainSource) {
            return 'untranslated_copy';
        }

        return null;
    }

    protected function canKeepFixing(): bool
    {
        return $this->fixLimit <= 0 || $this->fixCount < $this->fixLimit;
    }
}
