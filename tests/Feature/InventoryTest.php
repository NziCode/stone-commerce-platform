<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\MainCategory;
use App\Models\Mine;
use App\Models\Owner;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Warehouse;
use App\Services\StoneReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Owners, mines, warehouses and main categories on stones, the sale bookkeeping and the numbers
 * built from them. Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=InventoryTest
 */
class InventoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: (RefreshDatabase would wipe a real database).');
        }
    }

    private function stone(array $overrides = [], ?float $tons = null): Product
    {
        static $n = 0;
        $n++;

        $stone = Product::create(array_merge([
            'name' => ['fa' => "سنگ {$n}", 'en' => "Stone {$n}"], 'slug' => ['en' => "stone-{$n}"],
            'sku' => "S-{$n}", 'status' => 'available', 'is_active' => true, 'price_on_request' => true,
        ], $overrides));

        if ($tons !== null) {
            $weight = Attribute::firstOrCreate(['key' => 'weight'], ['label' => ['en' => 'Weight'], 'type' => 'number', 'unit' => 'tons', 'is_active' => true]);
            ProductAttribute::create(['product_id' => $stone->id, 'attribute_id' => $weight->id, 'value' => ['value' => $tons], 'sort_order' => 1]);
        }

        return $stone->fresh();
    }

    public function test_the_three_main_categories_exist_and_every_new_stone_is_an_export_stone(): void
    {
        $this->assertSame(['export', 'saw-cut', 'top-cut'], MainCategory::ordered()->pluck('key')->all());
        $this->assertSame('صادراتی', MainCategory::where('key', 'export')->first()->getTranslation('name', 'fa'));

        $stone = $this->stone();

        $this->assertSame('export', $stone->mainCategory->key, 'export is the default');
        $this->assertSame(1, Product::inMainCategory('export')->count());
        $this->assertSame(0, Product::inMainCategory('saw-cut')->count());
        $this->assertSame(1, Product::inMainCategory(null)->count(), 'no key = no filter');
    }

    public function test_a_stone_has_an_owner_a_mine_and_a_warehouse(): void
    {
        $owner = Owner::create(['name' => 'Ali Owner', 'phone' => '0911']);
        $mine = Mine::create(['name' => 'Takab Quarry 1', 'location' => 'Takab']);
        $warehouse = Warehouse::create(['name' => 'Isfahan Yard']);

        $stone = $this->stone(['owner_id' => $owner->id, 'mine_id' => $mine->id, 'warehouse_id' => $warehouse->id]);

        $this->assertSame('Ali Owner', $stone->owner->name);
        $this->assertSame('Takab Quarry 1', $stone->mine->name);
        $this->assertSame('Isfahan Yard', $stone->warehouse->name);
        $this->assertSame(1, $warehouse->products()->count());

        // deleting a warehouse keeps the history of what was stored there
        $warehouse->delete();
        $this->assertSame('Isfahan Yard', $stone->fresh()->warehouse->name);
    }

    public function test_back_office_fields_never_serialize(): void
    {
        $owner = Owner::create(['name' => 'Secret Owner']);
        $stone = $this->stone(['owner_id' => $owner->id, 'mine_id' => Mine::create(['name' => 'M'])->id, 'warehouse_id' => Warehouse::create(['name' => 'W'])->id]);
        $stone->markAsSold(['sold_price' => 5000, 'sold_currency' => 'USD', 'sold_to' => 'Buyer']);

        $array = $stone->fresh()->toArray();

        foreach (['owner_id', 'mine_id', 'warehouse_id', 'sold_at', 'sold_price', 'sold_currency', 'sold_to', 'sold_warehouse_id'] as $field) {
            $this->assertArrayNotHasKey($field, $array);
        }
        $this->assertStringNotContainsString('Secret Owner', $stone->fresh()->toJson());
    }

    public function test_selling_records_the_sale_and_remembers_the_warehouse_it_left(): void
    {
        $warehouse = Warehouse::create(['name' => 'Yard A']);
        $stone = $this->stone(['warehouse_id' => $warehouse->id]);

        $stone->markAsSold(['sold_price' => 12000, 'sold_currency' => 'USD', 'sold_to' => 'Some Buyer']);
        $stone = $stone->fresh();

        $this->assertSame('sold', $stone->status);
        $this->assertNotNull($stone->sold_at);
        $this->assertSame('12000.00', (string) $stone->sold_price);
        $this->assertSame('Some Buyer', $stone->sold_to);
        $this->assertSame($warehouse->id, $stone->sold_warehouse_id);

        // moved afterwards: the sale still belongs to the warehouse it left
        $stone->update(['warehouse_id' => Warehouse::create(['name' => 'Yard B'])->id]);
        $this->assertSame($warehouse->id, $stone->fresh()->sold_warehouse_id);
    }

    public function test_marking_sold_through_a_plain_status_change_stamps_the_date_and_unselling_clears_the_sale(): void
    {
        $stone = $this->stone();

        $stone->update(['status' => 'sold']);
        $this->assertNotNull($stone->fresh()->sold_at, 'the admin form / order confirmation only change the status');

        $stone->fresh()->update(['sold_price' => 900, 'sold_currency' => 'EUR']);
        $stone->fresh()->markAsAvailable();

        $back = $stone->fresh();
        $this->assertSame('available', $back->status);
        $this->assertNull($back->sold_at);
        $this->assertNull($back->sold_price);
        $this->assertNull($back->sold_warehouse_id);
    }

    public function test_weight_is_read_in_tons_and_kilograms_are_converted(): void
    {
        $this->assertSame(23.5, $this->stone([], 23.5)->weightTons());
        $this->assertSame(0.0, $this->stone()->weightTons(), 'no weight attribute');

        Attribute::where('key', 'weight')->update(['unit' => 'kg']);
        $this->assertSame(2.5, $this->stone([], 2500)->fresh()->weightTons());
    }

    public function test_report_groups_stock_and_sales_by_warehouse_owner_mine_and_category(): void
    {
        $yardA = Warehouse::create(['name' => 'Yard A']);
        $yardB = Warehouse::create(['name' => 'Yard B']);
        $ali = Owner::create(['name' => 'Ali']);
        $sara = Owner::create(['name' => 'Sara']);
        $mine = Mine::create(['name' => 'Takab 1']);
        $sawCut = MainCategory::where('key', 'saw-cut')->first();

        $this->stone(['warehouse_id' => $yardA->id, 'owner_id' => $ali->id, 'mine_id' => $mine->id], 20);            // available
        $this->stone(['warehouse_id' => $yardA->id, 'owner_id' => $ali->id, 'mine_id' => $mine->id, 'status' => 'reserved'], 10);
        $sold = $this->stone(['warehouse_id' => $yardB->id, 'owner_id' => $sara->id, 'main_category_id' => $sawCut->id], 15);
        $sold->markAsSold(['sold_price' => 3000, 'sold_currency' => 'USD']);
        $this->stone([], 5);                                                                                            // nothing assigned

        $report = new StoneReport();

        $byWarehouse = $report->rows('warehouse')->keyBy('name');
        $this->assertSame(['Yard A', 'Yard B', 'بدون انبار'], $report->rows('warehouse')->pluck('name')->all(), 'unassigned stones come last');
        $this->assertSame(1, $byWarehouse['Yard A']['available']);
        $this->assertSame(1, $byWarehouse['Yard A']['reserved']);
        $this->assertSame(20.0, $byWarehouse['Yard A']['available_tons']);
        $this->assertSame(1, $byWarehouse['Yard B']['sold']);
        $this->assertSame(15.0, $byWarehouse['Yard B']['sold_tons']);
        $this->assertSame(['USD' => 3000.0], $byWarehouse['Yard B']['revenue']);

        $byOwner = $report->rows('owner')->keyBy('name');
        $this->assertSame(2, $byOwner['Ali']['total']);
        $this->assertSame(1, $byOwner['Sara']['sold']);

        $this->assertSame(2, $report->rows('mine')->keyBy('name')['Takab 1']['total']);

        $byCategory = $report->rows('main_category')->keyBy('name');
        $this->assertSame(3, $byCategory['صادراتی']['total']);
        $this->assertSame(1, $byCategory['اره‌بری']['sold']);

        $totals = $report->totals();
        $this->assertSame(4, $totals['total']);
        $this->assertSame(2, $totals['available']);
        $this->assertSame(1, $totals['sold']);
        $this->assertSame(50.0, round($totals['available_tons'] + $totals['reserved_tons'] + $totals['sold_tons'], 2));
    }

    public function test_report_filters_and_attributes_a_sale_to_the_warehouse_it_left(): void
    {
        $yardA = Warehouse::create(['name' => 'Yard A']);
        $yardB = Warehouse::create(['name' => 'Yard B']);

        $stone = $this->stone(['warehouse_id' => $yardA->id], 8);
        $stone->markAsSold(['sold_price' => 100, 'sold_currency' => 'USD']);
        $stone->fresh()->update(['warehouse_id' => $yardB->id]);   // moved after the sale
        $this->stone(['warehouse_id' => $yardB->id], 4);

        $report = new StoneReport(['warehouse_id' => $yardA->id]);
        $this->assertSame(1, $report->totals()['sold'], 'the sale left from Yard A');
        $this->assertSame(1, $report->totals()['total']);

        $this->assertSame(1, (new StoneReport(['warehouse_id' => $yardB->id]))->totals()['total'], 'Yard B holds only the unsold stone now');

        // a sales period only limits which sales count
        $this->assertSame(0, (new StoneReport(['from' => now()->addDay()->toDateString()]))->totals()['sold']);
        $this->assertSame(1, (new StoneReport(['from' => now()->subDay()->toDateString(), 'to' => now()->addDay()->toDateString()]))->totals()['sold']);
        $this->assertSame(1, (new StoneReport(['from' => now()->addDay()->toDateString()]))->totals()['available'], 'stock is always current');
    }

    public function test_csv_export_has_a_bom_headers_rows_and_a_total(): void
    {
        $this->stone(['warehouse_id' => Warehouse::create(['name' => 'انبار مرکزی'])->id], 12);

        $csv = (new StoneReport())->toCsv('warehouse');

        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $lines = array_values(array_filter(explode("\n", trim(substr($csv, 3)))));
        $this->assertStringContainsString('انبار', $lines[0]);
        $this->assertStringContainsString('انبار مرکزی', $lines[1]);
        $this->assertStringContainsString('جمع', end($lines));
        $this->assertSame('—', StoneReport::revenueLabel([]));
        $this->assertSame('1,200 USD + 300 EUR', StoneReport::revenueLabel(['USD' => 1200, 'EUR' => 300]));
    }
}
