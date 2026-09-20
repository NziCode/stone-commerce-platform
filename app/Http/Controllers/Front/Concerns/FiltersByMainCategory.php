<?php

namespace App\Http\Controllers\Front\Concerns;

use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/** The ?group=export|saw-cut|top-cut filter of the products / category pages. */
trait FiltersByMainCategory
{
    /**
     * @return array{0: MainCategory|null, 1: Collection<int, MainCategory>} the group asked for (null when
     *         absent or unknown) and all active groups with their stone counts, for the tabs
     */
    protected function mainCategoryFilter(Request $request): array
    {
        $groups = MainCategory::active()->ordered()
            ->withCount(['products as active_products_count' => fn ($q) => $q->where('is_active', true)])
            ->get();

        $key = (string) $request->query('group', '');

        return [$key !== '' ? $groups->firstWhere('key', $key) : null, $groups];
    }
}
