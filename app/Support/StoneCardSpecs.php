<?php

namespace App\Support;

use App\Models\Product;

/**
 * The spec rows every stone card of the storefront prints (home sliders, product list, category pages,
 * search, wishlist), so a stone looks the same wherever it appears:
 *
 *   Dimensions: 320 × 165 × 145 cm     - length, thickness and width merged into one line
 *   Weight:     19 tons
 *   …                                   - any other attribute flagged "show in card", in its own order
 */
class StoneCardSpecs
{
    /** The attributes merged into the single dimensions line, in the order they are written. */
    private const DIMENSION_KEYS = ['length', 'thickness', 'width'];

    /**
     * @return list<array{label: string, value: string}>
     */
    public static function rows(Product $product, ?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        $shown = $product->attributes
            ->filter(fn ($pa) => $pa->attribute?->show_in_card && $pa->attribute?->is_active)
            ->sortBy(fn ($pa) => $pa->attribute?->sort_order ?? 999);

        $label = fn ($pa) => $pa->attribute->getTranslation('label', $locale, false) ?: $pa->attribute->getTranslation('label', 'en', false);

        $rows = [];

        // dimensions
        $dimensions = collect(self::DIMENSION_KEYS)
            ->map(fn ($key) => $shown->first(fn ($pa) => $pa->attribute?->key === $key))
            ->filter();

        $line = $dimensions
            ->map(fn ($pa) => $pa->value['value'] ?? null)
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->implode(' × ');

        if ($line !== '') {
            $unit = $shown->first(fn ($pa) => in_array($pa->attribute?->key, self::DIMENSION_KEYS, true))?->attribute?->unit;
            $rows[] = ['label' => __('messages.dimensions', [], $locale), 'value' => $line . ($unit ? ' ' . $unit : '')];
        }

        // weight
        if ($weight = $shown->first(fn ($pa) => $pa->attribute?->key === 'weight')) {
            $rows[] = ['label' => $label($weight), 'value' => $weight->display_value];
        }

        // everything else that is meant to be on the card
        foreach ($shown->reject(fn ($pa) => in_array($pa->attribute?->key, [...self::DIMENSION_KEYS, 'weight'], true)) as $pa) {
            $rows[] = ['label' => $label($pa), 'value' => $pa->display_value];
        }

        return $rows;
    }
}
