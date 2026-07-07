<?php

namespace App\Filament\Support;

use App\Services\LanguageService;
use App\Services\TranslationService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

/**
 * Reusable "Translate Automatically" button for translatable Filament form fields.
 *
 * Drop it above a per-locale Tabs block that follows this app's `field.{locale_code}`
 * naming convention (see ProductResource's NameTranslations tabs). Reads the primary
 * locale's values and fills the other active locales via the free TranslationService.
 *
 * The confirmation modal offers two ways to proceed: the default "Confirm" only fills
 * target fields that are still empty, while the extra "Confirm and Overwrite Existing
 * Translations" button re-translates and replaces every target field regardless of
 * whether an editor already filled it in by hand.
 *
 * Repeater fields: give a nested array instead of a bool to describe a Repeater whose
 * items each contain translatable fields, e.g.:
 *
 *     TranslateFieldsAction::make(fields: [
 *         'name' => false,
 *         'attributes' => [          // a Repeater field
 *             'value' => false,      // translatable field inside each repeater item
 *         ],
 *     ]);
 *
 * Repeater specs nest arbitrarily deep (a repeater inside a repeater item works the
 * same way) and the repeater's own item structure (keys, item count, order) is left
 * untouched — only the leaf field values are translated.
 */
class TranslateFieldsAction
{
    /**
     * @param  array<string, bool|array>  $fields  ['field_name' => isHtml] for a leaf field, or
     *                                              ['repeater_name' => [nested fields...]] for a Repeater
     * @param  string|null  $slugField  If set, regenerated from $slugSourceField via Str::slug() instead of being machine-translated
     * @param  string  $slugSourceField  Field the slug is derived from (default: 'name')
     */
    public static function make(array $fields, ?string $slugField = null, string $slugSourceField = 'name'): Action
    {
        return Action::make('translateAutomatically')
            ->label(__('admin.translate_automatically'))
            ->icon('heroicon-o-language')
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription(__('admin.translate_confirm_body'))
            ->extraModalFooterActions(fn (Action $action): array => [
                $action->makeModalSubmitAction('translateOverwrite', arguments: ['overwrite' => true])
                    ->label(__('admin.translate_confirm_overwrite'))
                    ->color('danger'),
            ])
            ->action(function (Get $get, Set $set, array $arguments) use ($fields, $slugField, $slugSourceField) {
                $overwrite = (bool) ($arguments['overwrite'] ?? false);

                $sourceCode = LanguageService::getDefault()?->code ?? 'fa';

                $targets = LanguageService::getActive()
                    ->pluck('code')
                    ->reject(fn ($code) => $code === $sourceCode)
                    ->values();

                $translator = app(TranslationService::class);
                $filledCount = 0;
                $failedLocales = [];

                foreach ($targets as $targetCode) {
                    $toTranslate = static::collectPlainFields($get, '', $fields, $sourceCode, $targetCode, $overwrite);

                    if (! empty($toTranslate)) {
                        $results = $translator->translateFields($toTranslate, $targetCode, $sourceCode);

                        foreach ($results as $path => $value) {
                            if ($value === null) {
                                $failedLocales[$targetCode] = true;
                                continue;
                            }

                            $set("{$path}.{$targetCode}", $value);
                            $filledCount++;
                        }
                    }

                    foreach (static::collectHtmlPaths($get, '', $fields) as $path) {
                        $sourceValue = $get("{$path}.{$sourceCode}");

                        if (blank($sourceValue) || (! $overwrite && filled($get("{$path}.{$targetCode}")))) {
                            continue;
                        }

                        $result = $translator->translateHtml($sourceValue, $targetCode, $sourceCode);

                        if ($result === null) {
                            $failedLocales[$targetCode] = true;
                            continue;
                        }

                        $set("{$path}.{$targetCode}", $result);
                        $filledCount++;
                    }

                    if ($slugField) {
                        $translatedName = $get("{$slugSourceField}.{$targetCode}");

                        if (filled($translatedName) && ($overwrite || blank($get("{$slugField}.{$targetCode}")))) {
                            $set("{$slugField}.{$targetCode}", Str::slug($translatedName));
                        }
                    }
                }

                Notification::make()
                    ->title($failedLocales
                        ? __('admin.translate_partial')
                        : __('admin.translate_success'))
                    ->body($failedLocales ? implode(', ', array_keys($failedLocales)) : null)
                    ->color($failedLocales ? 'warning' : 'success')
                    ->send();
            });
    }

    /**
     * Walk $fields (recursing into Repeater specs) and collect every plain-text leaf
     * field path that still needs a translation for $targetCode, keyed by its full
     * dot-notation path (e.g. "attributes.3f2a...uuid.value").
     *
     * @param  array<string, bool|array>  $fields
     * @return array<string, string>
     */
    protected static function collectPlainFields(Get $get, string $prefix, array $fields, string $sourceCode, string $targetCode, bool $overwrite): array
    {
        $out = [];

        foreach ($fields as $field => $spec) {
            $path = "{$prefix}{$field}";

            if (is_array($spec)) {
                foreach (static::eachRepeaterItemPath($get, $path) as $itemPath) {
                    $out += static::collectPlainFields($get, "{$itemPath}.", $spec, $sourceCode, $targetCode, $overwrite);
                }

                continue;
            }

            $isHtml = $spec;

            if ($isHtml) {
                continue;
            }

            $sourceValue = $get("{$path}.{$sourceCode}");

            if (blank($sourceValue) || (! $overwrite && filled($get("{$path}.{$targetCode}")))) {
                continue;
            }

            $out[$path] = $sourceValue;
        }

        return $out;
    }

    /**
     * Same traversal as collectPlainFields(), but returns the dot-notation paths of
     * every HTML/rich-text leaf field (translated one at a time via translateHtml()).
     *
     * @param  array<string, bool|array>  $fields
     * @return array<int, string>
     */
    protected static function collectHtmlPaths(Get $get, string $prefix, array $fields): array
    {
        $out = [];

        foreach ($fields as $field => $spec) {
            $path = "{$prefix}{$field}";

            if (is_array($spec)) {
                foreach (static::eachRepeaterItemPath($get, $path) as $itemPath) {
                    array_push($out, ...static::collectHtmlPaths($get, "{$itemPath}.", $spec));
                }

                continue;
            }

            if ($spec) {
                $out[] = $path;
            }
        }

        return $out;
    }

    /**
     * @return array<int, string> the dot-notation path (e.g. "attributes.{itemKey}") of every item currently in the Repeater at $path
     */
    protected static function eachRepeaterItemPath(Get $get, string $path): array
    {
        $items = $get($path);

        if (! is_array($items)) {
            return [];
        }

        return array_map(fn ($itemKey) => "{$path}.{$itemKey}", array_keys($items));
    }
}
