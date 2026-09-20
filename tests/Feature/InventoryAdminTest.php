<?php

namespace Tests\Feature;

use App\Filament\Pages\StoneReports;
use App\Filament\Resources\MainCategoryResource;
use App\Filament\Resources\MainCategoryResource\Pages\ManageMainCategories;
use App\Filament\Resources\MineResource\Pages\ManageMines;
use App\Filament\Resources\OwnerResource;
use App\Filament\Resources\OwnerResource\Pages\ManageOwners;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Resources\WarehouseResource\Pages\ManageWarehouses;
use App\Filament\Widgets\StoneSalesOverview;
use App\Filament\Widgets\StoneSalesTableWidget;
use App\Filament\Widgets\WarehouseInventoryWidget;
use App\Models\MainCategory;
use App\Models\Mine;
use App\Models\Owner;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The admin side of the inventory: the owner / mine / warehouse / main-category lists, recording and
 * cancelling sales from the product list and the dashboard, bulk assignment, the dashboard widgets,
 * the report page (+ CSV) and who may see any of it. Needs a throw-away SQLite database:
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=InventoryAdminTest
 */
class InventoryAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: (RefreshDatabase would wipe a real database).');
        }

        $this->seed([LanguageSeeder::class, RolePermissionSeeder::class, AdminUserSeeder::class]);
        $this->actingAs(User::where('email', 'admin@example.com')->firstOrFail());
    }

    private function stone(array $overrides = []): Product
    {
        static $n = 0;
        $n++;

        return Product::create(array_merge([
            'name' => ['fa' => "سنگ {$n}", 'en' => "Stone {$n}"], 'slug' => ['en' => "stone-{$n}"],
            'sku' => "A-{$n}", 'status' => 'available', 'is_active' => true, 'price_on_request' => true,
        ], $overrides));
    }

    public function test_owners_mines_and_warehouses_can_be_defined_in_one_place_each(): void
    {
        Livewire::test(ManageOwners::class)->assertSuccessful()
            ->callAction('create', data: ['name' => 'Ali Rezaei', 'phone' => '09120000000', 'is_active' => true])
            ->assertHasNoActionErrors();
        $this->assertSame('Ali Rezaei', Owner::firstOrFail()->name);

        Livewire::test(ManageMines::class)->assertSuccessful()
            ->callAction('create', data: ['name' => 'Takab Quarry 1', 'location' => 'Takab', 'is_active' => true])
            ->assertHasNoActionErrors();
        $this->assertSame('Takab', Mine::firstOrFail()->location);

        Livewire::test(ManageWarehouses::class)->assertSuccessful()
            ->callAction('create', data: ['name' => 'Isfahan Yard', 'location' => 'Isfahan', 'is_active' => true])
            ->assertHasNoActionErrors();
        $this->assertSame('Isfahan Yard', Warehouse::firstOrFail()->name);
    }

    public function test_the_list_shows_how_many_stones_each_holds(): void
    {
        $yard = Warehouse::create(['name' => 'Yard A']);
        $this->stone(['warehouse_id' => $yard->id]);
        $sold = $this->stone(['warehouse_id' => $yard->id]);
        $sold->markAsSold();

        Livewire::test(ManageWarehouses::class)
            ->assertCanSeeTableRecords([$yard])
            ->assertTableColumnStateSet('products_count', 2, $yard->getKey())
            ->assertTableColumnStateSet('available_count', 1, $yard->getKey())
            ->assertTableColumnStateSet('sold_count', 1, $yard->getKey());
    }

    public function test_main_categories_are_editable_and_the_default_one_cannot_be_deleted(): void
    {
        $export = MainCategory::where('key', 'export')->firstOrFail();
        $saw = MainCategory::where('key', 'saw-cut')->firstOrFail();

        Livewire::test(ManageMainCategories::class)->assertSuccessful()
            ->assertCanSeeTableRecords([$export, $saw])
            ->assertTableActionHidden('delete', $export->getKey())
            ->assertTableActionVisible('delete', $saw->getKey());

        Livewire::test(ManageMainCategories::class)
            ->callTableAction('edit', $saw, data: ['name' => ['fa' => 'اره‌بری ویژه', 'en' => 'Special Saw-Cut']])
            ->assertHasNoTableActionErrors();
        $this->assertSame('Special Saw-Cut', $saw->fresh()->getTranslation('name', 'en'));
        $this->assertSame('saw-cut', $saw->fresh()->key, 'the key stays');

        $this->stone(['main_category_id' => $saw->id]);
        Livewire::test(ManageMainCategories::class)->assertTableActionHidden('delete', $saw->getKey());
    }

    public function test_a_sale_is_recorded_from_the_product_list_and_can_be_cancelled(): void
    {
        $yard = Warehouse::create(['name' => 'Yard A']);
        $stone = $this->stone(['warehouse_id' => $yard->id]);

        Livewire::test(ListProducts::class)->assertSuccessful()
            ->assertTableActionVisible('recordSale', $stone->getKey())
            ->assertTableActionHidden('cancelSale', $stone->getKey())
            ->callTableAction('recordSale', $stone, data: [
                'sold_at' => now()->format('Y-m-d H:i'), 'sold_price' => 8000, 'sold_currency' => 'USD', 'sold_to' => 'Some Buyer',
            ])
            ->assertHasNoTableActionErrors();

        $sold = $stone->fresh();
        $this->assertSame('sold', $sold->status);
        $this->assertSame('8000.00', (string) $sold->sold_price);
        $this->assertSame('USD', $sold->sold_currency);
        $this->assertSame('Some Buyer', $sold->sold_to);
        $this->assertSame($yard->id, $sold->sold_warehouse_id);

        Livewire::test(ListProducts::class)
            ->assertTableActionHidden('recordSale', $stone->getKey())
            ->assertTableActionVisible('cancelSale', $stone->getKey())
            ->callTableAction('cancelSale', $stone->getKey());

        $back = $stone->fresh();
        $this->assertSame('available', $back->status);
        $this->assertNull($back->sold_price);
    }

    public function test_the_product_list_filters_sold_and_unsold_stones(): void
    {
        $open = $this->stone();
        $done = $this->stone();
        $done->markAsSold();

        Livewire::test(ListProducts::class)
            ->filterTable('sold', true)->assertCanSeeTableRecords([$done])->assertCanNotSeeTableRecords([$open])
            ->filterTable('sold', false)->assertCanSeeTableRecords([$open])->assertCanNotSeeTableRecords([$done]);
    }

    public function test_bulk_assignment_only_touches_the_filled_fields(): void
    {
        $yard = Warehouse::create(['name' => 'Yard A']);
        $ali = Owner::create(['name' => 'Ali']);
        $mine = Mine::create(['name' => 'M1']);
        $a = $this->stone(['owner_id' => $ali->id]);
        $b = $this->stone();

        Livewire::test(ListProducts::class)
            ->callTableBulkAction('assignInventory', [$a, $b], data: ['warehouse_id' => $yard->id, 'mine_id' => $mine->id])
            ->assertHasNoTableBulkActionErrors();

        foreach ([$a->fresh(), $b->fresh()] as $stone) {
            $this->assertSame($yard->id, $stone->warehouse_id);
            $this->assertSame($mine->id, $stone->mine_id);
        }
        $this->assertSame($ali->id, $a->fresh()->owner_id, 'a field left empty is not overwritten');
    }

    public function test_the_dashboard_widgets_show_stock_sales_and_let_you_record_a_sale(): void
    {
        $yard = Warehouse::create(['name' => 'Yard Alpha']);
        $one = $this->stone(['warehouse_id' => $yard->id]);
        $two = $this->stone(['warehouse_id' => $yard->id]);
        $two->markAsSold(['sold_price' => 100, 'sold_currency' => 'USD']);
        $this->stone(['status' => 'reserved']);

        Livewire::test(StoneSalesOverview::class)->assertSuccessful()->assertSee('موجود برای فروش')->assertSee('فروخته‌شده');

        Livewire::test(WarehouseInventoryWidget::class)->assertSuccessful()
            ->assertCanSeeTableRecords([$yard])
            ->assertTableColumnStateSet('available_count', 1, $yard->getKey())
            ->assertTableColumnStateSet('sold_count', 1, $yard->getKey())
            ->assertSee('سنگ‌های بدون انبار: 1');

        Livewire::test(StoneSalesTableWidget::class)->assertSuccessful()
            ->assertCanSeeTableRecords([$one, $two])
            ->filterTable('sold', false)->assertCanSeeTableRecords([$one])->assertCanNotSeeTableRecords([$two])
            ->callTableAction('recordSale', $one, data: ['sold_at' => now()->format('Y-m-d H:i'), 'sold_price' => 50, 'sold_currency' => 'EUR'])
            ->assertHasNoTableActionErrors();

        $this->assertSame('sold', $one->fresh()->status);
    }

    public function test_the_report_page_filters_switches_dimension_and_downloads_a_csv(): void
    {
        $yard = Warehouse::create(['name' => 'Yard Beta']);
        $ali = Owner::create(['name' => 'Ali Owner']);
        $this->stone(['warehouse_id' => $yard->id, 'owner_id' => $ali->id]);

        Livewire::test(StoneReports::class)->assertSuccessful()
            ->assertSee('Yard Beta')
            ->call('setDimension', 'owner')->assertSee('Ali Owner')
            ->call('setDimension', 'main_category')->assertSee('صادراتی')
            ->call('setDimension', 'nonsense')->assertSet('dimension', 'main_category')
            ->set('filters.warehouse_id', Warehouse::create(['name' => 'Empty Yard'])->id)->assertSee('سنگی با این فیلترها پیدا نشد')
            ->set('filters.warehouse_id', null)->call('setDimension', 'warehouse')
            ->callAction('csv')->assertFileDownloaded();
    }

    public function test_only_administrators_get_any_of_it(): void
    {
        $this->assertTrue(OwnerResource::canViewAny());
        $this->assertTrue(MainCategoryResource::canViewAny());
        $this->assertTrue(StoneReports::canAccess());
        $this->assertTrue(StoneSalesOverview::canView());

        $editor = User::factory()->create();
        $editor->assignRole('editor');
        $this->actingAs($editor);

        $this->assertFalse(OwnerResource::canViewAny(), 'owners are business-sensitive');
        $this->assertFalse(MainCategoryResource::canViewAny());
        $this->assertFalse(StoneReports::canAccess());
        $this->assertFalse(StoneSalesOverview::canView());
        $this->assertFalse(WarehouseInventoryWidget::canView());
        $this->assertFalse(StoneSalesTableWidget::canView());
    }
    public function test_the_product_form_carries_main_category_owner_mine_warehouse_and_hides_the_private_ones_from_editors(): void
    {
        $stone = $this->stone(['slug' => ['fa' => 'sang-form', 'en' => 'stone-form']]);   // the form requires the Persian slug
        $yard = Warehouse::create(['name' => 'Yard A']);
        $ali = Owner::create(['name' => 'Ali']);
        $mine = Mine::create(['name' => 'M1']);
        $saw = MainCategory::where('key', 'saw-cut')->firstOrFail();

        Livewire::test(EditProduct::class, ['record' => $stone->getKey()])
            ->assertFormFieldExists('main_category_id')
            ->assertFormFieldIsVisible('owner_id')
            ->fillForm(['main_category_id' => $saw->id, 'owner_id' => $ali->id, 'mine_id' => $mine->id, 'warehouse_id' => $yard->id])
            ->call('save')
            ->assertHasNoFormErrors();

        $saved = $stone->fresh();
        $this->assertSame($saw->id, $saved->main_category_id);
        $this->assertSame($ali->id, $saved->owner_id);
        $this->assertSame($mine->id, $saved->mine_id);
        $this->assertSame($yard->id, $saved->warehouse_id);

        // an editor still classifies stones, but never sees who owns them or where they are
        $editor = User::factory()->create();
        $editor->assignRole('editor');
        $this->actingAs($editor);

        Livewire::test(EditProduct::class, ['record' => $stone->getKey()])
            ->assertFormFieldExists('main_category_id')
            ->assertFormFieldIsHidden('owner_id')
            ->assertFormFieldIsHidden('warehouse_id');
    }
}