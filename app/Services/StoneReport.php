<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Back-office numbers about the stones: how many are available / reserved / sold, their tonnage and
 * what they were sold for, grouped by warehouse, owner, mine or main category.
 *
 * Filters (all optional): main_category_id, owner_id, mine_id, warehouse_id restrict the stones;
 * from / to (Y-m-d) restrict which SALES count (by sold_at) — stock that is not sold is always current.
 * A sold stone is attributed to the warehouse it left from (sold_warehouse_id), not where it may sit now.
 */
class StoneReport
{
    public const DIMENSIONS = [
        'warehouse'     => 'انبار',
        'owner'         => 'مالک',
        'mine'          => 'معدن',
        'main_category' => 'دسته اصلی',
    ];

    /** @var Collection<int, Product>|null */
    private ?Collection $stones = null;

    public function __construct(private array $filters = [])
    {
    }

    /** @return Collection<int, Product> the stones the filters leave, with what the report needs loaded */
    public function stones(): Collection
    {
        if ($this->stones) {
            return $this->stones;
        }

        $f = $this->filters;

        $stones = Product::query()
            ->with(['owner', 'mine', 'warehouse', 'soldFromWarehouse', 'mainCategory', 'allAttributes.attribute'])
            ->when(filled($f['main_category_id'] ?? null), fn ($q) => $q->where('main_category_id', $f['main_category_id']))
            ->when(filled($f['owner_id'] ?? null), fn ($q) => $q->where('owner_id', $f['owner_id']))
            ->when(filled($f['mine_id'] ?? null), fn ($q) => $q->where('mine_id', $f['mine_id']))
            ->get();

        if (filled($f['warehouse_id'] ?? null)) {
            $stones = $stones->filter(fn (Product $p) => (int) $this->warehouseIdOf($p) === (int) $f['warehouse_id']);
        }

        // the period only limits which sales count
        $from = filled($f['from'] ?? null) ? \Carbon\Carbon::parse($f['from'])->startOfDay() : null;
        $to   = filled($f['to'] ?? null) ? \Carbon\Carbon::parse($f['to'])->endOfDay() : null;

        if ($from || $to) {
            $stones = $stones->filter(function (Product $p) use ($from, $to) {
                if ($p->status !== 'sold') {
                    return true;
                }

                $at = $p->sold_at;

                return $at && (! $from || $at->gte($from)) && (! $to || $at->lte($to));
            });
        }

        return $this->stones = $stones->values();
    }

    /**
     * One row per warehouse / owner / mine / main category (plus a row for stones without one).
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function rows(string $dimension): Collection
    {
        abort_unless(array_key_exists($dimension, self::DIMENSIONS), 404);

        $groups = $this->stones()->groupBy(fn (Product $p) => $this->groupKey($p, $dimension));

        return $groups
            ->map(fn (Collection $stones, $key) => $this->summarize($stones, (int) $key ?: null, $this->groupName($stones->first(), $dimension)))
            ->sortBy(fn (array $row) => [$row['id'] === null ? 1 : 0, $row['name']])
            ->values();
    }

    /** The row for "everything the filters leave". */
    public function totals(): array
    {
        return $this->summarize($this->stones(), null, 'جمع');
    }

    /** UTF-8 CSV (with a BOM, so Excel shows Persian correctly) of one dimension's rows and the total. */
    public function toCsv(string $dimension): string
    {
        $out = fopen('php://temp', 'w+');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, [self::DIMENSIONS[$dimension], 'کل', 'موجود', 'رزرو', 'فروخته‌شده', 'ناموجود', 'تن موجود', 'تن رزرو', 'تن فروخته‌شده', 'درآمد']);

        foreach ($this->rows($dimension)->push($this->totals()) as $row) {
            fputcsv($out, [
                $row['name'], $row['total'], $row['available'], $row['reserved'], $row['sold'], $row['unavailable'],
                $row['available_tons'], $row['reserved_tons'], $row['sold_tons'], static::revenueLabel($row['revenue']),
            ]);
        }

        rewind($out);

        return (string) stream_get_contents($out);
    }

    /** "1,200 USD + 300 EUR" — sales are recorded in whatever currency they were made in. */
    public static function revenueLabel(array $revenue): string
    {
        return collect($revenue)->map(fn ($amount, $currency) => number_format($amount) . ' ' . $currency)->implode(' + ') ?: '—';
    }

    // ── internals ──────────────────────────────────────

    private function warehouseIdOf(Product $p): ?int
    {
        return $p->status === 'sold' ? ($p->sold_warehouse_id ?? $p->warehouse_id) : $p->warehouse_id;
    }

    private function groupKey(Product $p, string $dimension): int
    {
        return (int) match ($dimension) {
            'warehouse'     => $this->warehouseIdOf($p),
            'owner'         => $p->owner_id,
            'mine'          => $p->mine_id,
            'main_category' => $p->main_category_id,
        };
    }

    private function groupName(Product $p, string $dimension): string
    {
        $unassigned = ['warehouse' => 'بدون انبار', 'owner' => 'بدون مالک', 'mine' => 'بدون معدن', 'main_category' => 'بدون دسته'][$dimension];

        return match ($dimension) {
            'warehouse'     => ($p->status === 'sold' ? ($p->soldFromWarehouse ?? $p->warehouse) : $p->warehouse)?->name ?? $unassigned,
            'owner'         => $p->owner?->name ?? $unassigned,
            'mine'          => $p->mine?->name ?? $unassigned,
            'main_category' => $p->mainCategory
                ? ($p->mainCategory->getTranslation('name', 'fa', false) ?: $p->mainCategory->getTranslation('name', 'en', false))
                : $unassigned,
        };
    }

    /** @param Collection<int, Product> $stones */
    private function summarize(Collection $stones, ?int $id, string $name): array
    {
        $by = fn (string $status) => $stones->where('status', $status);
        $tons = fn (Collection $c) => round($c->sum(fn (Product $p) => $p->weightTons()), 2);

        $revenue = [];
        foreach ($by('sold') as $p) {
            if ((float) $p->sold_price > 0) {
                $currency = $p->sold_currency ?: '—';
                $revenue[$currency] = ($revenue[$currency] ?? 0) + (float) $p->sold_price;
            }
        }

        return [
            'id'             => $id,
            'name'           => $name,
            'total'          => $stones->count(),
            'available'      => $by('available')->count(),
            'reserved'       => $by('reserved')->count(),
            'sold'           => $by('sold')->count(),
            'unavailable'    => $by('unavailable')->count(),
            'available_tons' => $tons($by('available')),
            'reserved_tons'  => $tons($by('reserved')),
            'sold_tons'      => $tons($by('sold')),
            'revenue'        => $revenue,
        ];
    }
}
