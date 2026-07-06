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
 */
class TranslateFieldsAction
{
    /**
     * @param  array<string, bool>  $fields  ['field_name' => isHtml] e.g. ['name' => false, 'description' => true]
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
                    $toTranslate = [];

                    foreach ($fields as $field => $isHtml) {
                        if ($isHtml) {
                            continue;
                        }

                        $sourceValue = $get("{$field}.{$sourceCode}");

                        if (blank($sourceValue) || (! $overwrite && filled($get("{$field}.{$targetCode}")))) {
                            continue;
                        }

                        $toTranslate[$field] = $sourceValue;
                    }

                    if (! empty($toTranslate)) {
                        $results = $translator->translateFields($toTranslate, $targetCode, $sourceCode);

                        foreach ($results as $field => $value) {
                            if ($value === null) {
                                $failedLocales[$targetCode] = true;
                                continue;
                            }

                            $set("{$field}.{$targetCode}", $value);
                            $filledCount++;
                        }
                    }

                    foreach ($fields as $field => $isHtml) {
                        if (! $isHtml) {
                            continue;
                        }

                        $sourceValue = $get("{$field}.{$sourceCode}");

                        if (blank($sourceValue) || (! $overwrite && filled($get("{$field}.{$targetCode}")))) {
                            continue;
                        }

                        $result = $translator->translateHtml($sourceValue, $targetCode, $sourceCode);

                        if ($result === null) {
                            $failedLocales[$targetCode] = true;
                            continue;
                        }

                        $set("{$field}.{$targetCode}", $result);
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
}
