<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Mine;
use App\Models\Owner;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Warehouse;
use Database\Seeders\InquiryTranslationSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\MainCategoryTranslationSeeder;
use Database\Seeders\MenuItemSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\MobileUxTranslationSeeder;
use Database\Seeders\ReservationFlowTranslationSeeder;
use Database\Seeders\ReservationTranslationSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * What visitors see of the main categories (export / saw-cut / top-cut): the home slider, the
 * filter on the product list, the menu entries and the product-page label — and that the back-office
 * data (owner, mine, warehouse, sale) never reaches a public page.
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=MainCategoryFrontTest
 */
class MainCategoryFrontTest extends TestCase
{
    use RefreshDatabase;

    private int $obLevel = 0;

    protected function setUp(): void
    {
        $this->obLevel = ob_get_level();

        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: (RefreshDatabase would wipe a real database).');
        }

        Http::fake();

        $this->seed([
            LanguageSeeder::class, SettingSeeder::class, MenuSeeder::class, MenuItemSeeder::class,
            TranslationSeeder::class, ReservationTranslationSeeder::class, ReservationFlowTranslationSeeder::class,
            MobileUxTranslationSeeder::class, MainCategoryTranslationSeeder::class, InquiryTranslationSeeder::class, RolePermissionSeeder::class,
        ]);

        Cache::forget('cart.enabled');
    }

    protected function tearDown(): void
    {
        // the home page leaves one output buffer open (pre-existing); PHPUnit flags that as risky
        while (ob_get_level() > $this->obLevel) {
            ob_end_clean();
        }

        parent::tearDown();
    }

    private function visit(string $locale, string $uri)
    {
        return $this->withSession(['locale' => $locale])
            ->withoutMiddleware(LocaleSessionRedirect::class)
            ->get($uri);
    }

    private function stone(string $group = 'export', array $overrides = []): Product
    {
        static $n = 0;
        $n++;

        return Product::create(array_merge([
            'name' => ['fa' => "سنگ {$n}", 'en' => "Front Stone {$n}"], 'slug' => ['fa' => "sang-{$n}", 'en' => "front-stone-{$n}"],
            'sku' => "F-{$n}", 'status' => 'available', 'is_active' => true, 'price_on_request' => true,
            'main_category_id' => MainCategory::where('key', $group)->value('id'),
        ], $overrides));
    }

    public function test_home_page_shows_the_main_category_slider(): void
    {
        $this->stone('export');
        $this->stone('export');
        $this->stone('saw-cut');
        Category::create(['name' => ['fa' => 'تراورتن تست', 'en' => 'Test Travertine'], 'slug' => ['fa' => 'test-fa', 'en' => 'test-travertine'], 'is_active' => true]);

        $html = $this->visit('en', '/')->assertOk()->getContent();

        // slider 1: the three main categories, each a link to the filtered product list, with a picture
        foreach (['export', 'saw-cut', 'top-cut'] as $key) {
            $this->assertStringContainsString('href="' . route('products.index', ['group' => $key]) . '"', $html, "{$key} card links to the filtered list");
            $this->assertStringContainsString('assets/images/groups/' . $key . '.svg', $html, "{$key} card has its picture");
        }
        $this->assertStringContainsString('Export Grade', $html);
        $this->assertStringContainsString('Saw-Cut', $html);
        $this->assertStringContainsString('Top-Cut', $html);
        $this->assertStringContainsString('2 stones', $html, 'export count');
        $this->assertStringContainsString('1 stones', $html, 'saw-cut count');
        $this->assertStringContainsString('aria-label="Shop by main category"', $html, 'labelled for screen readers');
        $this->assertStringNotContainsString('<h2 class="mt-quick-title"', $html, 'no visible title above the cards');

        // the stone types stay in the categories grid below — the hero has no second slider for them
        $this->assertStringNotContainsString('Shop by stone type', $html);
        $hero = substr($html, strpos($html, 'class="mt-container mt-quick"'), strpos($html, 'mt-stats-float') - strpos($html, 'class="mt-container mt-quick"'));
        $this->assertStringNotContainsString('Test Travertine', $hero);
        $this->assertStringContainsString(route('categories.show', 'test-travertine'), $html, 'the category is still in the grid');
    }

    public function test_the_main_category_slider_replaces_the_banner_slider_and_the_old_categories_grid_stays(): void
    {
        Category::create(['name' => ['fa' => 'تراورتن تست', 'en' => 'Test Travertine'], 'slug' => ['fa' => 'test-fa', 'en' => 'test-travertine'], 'is_active' => true]);
        Slider::create(['title' => ['fa' => 'بنر تست', 'en' => 'Banner Test Title'], 'button_text' => ['en' => 'See products'], 'button_link' => '/products', 'is_active' => true]);

        $html = $this->visit('en', '/')->assertOk()->getContent();

        $hero = strpos($html, 'class="mt-hero"');
        $sliders = strpos($html, 'class="mt-container mt-quick"');
        $stats = strpos($html, 'mt-stats-float');
        $grid = strpos($html, 'class="mt-cats"');

        // the filter takes the place of the admin-managed banner, inside the hero, above the stats card …
        $this->assertNotFalse($sliders);
        $this->assertTrue($hero < $sliders && $sliders < $stats, 'the slider sits in the hero');
        $this->assertStringNotContainsString('Banner Test Title', $html, 'the banner slider is no longer shown');
        $this->assertStringNotContainsString('main-slider', $html);

        // … and the categories section keeps its previous look, after the stats card
        $this->assertNotFalse($grid);
        $this->assertGreaterThan($stats, $grid);
        $this->assertMatchesRegularExpression('#<a href="[^"]*test-travertine" class="mt-cat">#', $html);
    }

    public function test_the_home_slider_uses_the_persian_names_in_persian(): void
    {
        $html = $this->visit('fa', '/')->assertOk()->getContent();

        $this->assertStringContainsString('صادراتی', $html);
        $this->assertStringContainsString('اره‌بری', $html);
        $this->assertStringContainsString('قله‌بری', $html);
    }

    public function test_an_inactive_main_category_disappears_from_the_public_site(): void
    {
        MainCategory::where('key', 'top-cut')->update(['is_active' => false]);

        $home = $this->visit('en', '/')->assertOk()->getContent();
        $this->assertStringNotContainsString('group=top-cut', $home);

        $list = $this->visit('en', '/products')->assertOk()->getContent();
        $this->assertStringNotContainsString('group=top-cut', $list);
    }

    public function test_product_list_filters_by_main_category(): void
    {
        $export = $this->stone('export', ['name' => ['en' => 'Export Only Stone', 'fa' => 'سنگ صادراتی تنها']]);
        $saw = $this->stone('saw-cut', ['name' => ['en' => 'Saw Only Stone', 'fa' => 'سنگ اره‌بری تنها']]);

        $all = $this->visit('en', '/products')->assertOk()->getContent();
        $this->assertStringContainsString('Export Only Stone', $all);
        $this->assertStringContainsString('Saw Only Stone', $all);

        $onlySaw = $this->visit('en', '/products?group=saw-cut')->assertOk()->getContent();
        $this->assertStringContainsString('Saw Only Stone', $onlySaw);
        $this->assertStringNotContainsString('Export Only Stone', $onlySaw);
        $this->assertMatchesRegularExpression('#class="pl-group is-active"[^>]*>\s*Saw-Cut#', $onlySaw, 'the chosen pill is highlighted');
        $this->assertStringContainsString('name="group" value="saw-cut"', $onlySaw, 'search and sort keep the filter');

        $onlyExport = $this->visit('en', '/products?group=export')->assertOk()->getContent();
        $this->assertStringContainsString('Export Only Stone', $onlyExport);
        $this->assertStringNotContainsString('Saw Only Stone', $onlyExport);

        // an unknown key is ignored instead of emptying the page
        $unknown = $this->visit('en', '/products?group=nonsense')->assertOk()->getContent();
        $this->assertStringContainsString('Export Only Stone', $unknown);
        $this->assertStringContainsString('Saw Only Stone', $unknown);
    }

    public function test_category_page_can_be_narrowed_by_main_category(): void
    {
        $category = Category::create(['name' => ['fa' => 'تراورتن', 'en' => 'Travertine Cat'], 'slug' => ['fa' => 'traverten', 'en' => 'travertine-cat'], 'is_active' => true]);
        $export = $this->stone('export', ['name' => ['en' => 'Cat Export Stone', 'fa' => 'الف']]);
        $saw = $this->stone('saw-cut', ['name' => ['en' => 'Cat Saw Stone', 'fa' => 'ب']]);
        $category->products()->attach([$export->id, $saw->id]);

        $html = $this->visit('en', '/categories/travertine-cat?group=saw-cut')->assertOk()->getContent();

        $this->assertStringContainsString('Cat Saw Stone', $html);
        $this->assertStringNotContainsString('Cat Export Stone', $html);
        $this->assertStringContainsString('travertine-cat?group=saw-cut', $html, 'pills keep the category');
    }

    public function test_menu_lists_the_three_groups_in_the_products_dropdown(): void
    {
        // the client's real menu is not in the repo: build the usual "Products" item (its children are generated)
        $menu = Menu::firstOrCreate(['location' => 'header'], ['name' => 'Header', 'is_active' => true]);
        MenuItem::create(['menu_id' => $menu->id, 'label' => ['en' => 'Products', 'fa' => 'محصولات'], 'route_name' => 'products.index', 'is_active' => true, 'sort_order' => 1]);
        Cache::forget('menu.header');

        $html = $this->visit('en', '/')->assertOk()->getContent();

        foreach (['<ul class="drop-menu">', '<ul class="sub-menu">'] as $opening) {   // desktop dropdown, phone menu
            $this->assertMatchesRegularExpression('#' . preg_quote($opening, '#') . '(.*?)</ul>#s', $html);
            preg_match('#' . preg_quote($opening, '#') . '(.*?)</ul>#s', $html, $dropdown);

            foreach (['export', 'saw-cut', 'top-cut'] as $key) {
                $this->assertStringContainsString('group=' . $key, $dropdown[1], "{$key} in {$opening}");
            }
        }
    }

    public function test_product_page_labels_the_main_category(): void
    {
        $stone = $this->stone('saw-cut', ['slug' => ['fa' => 'sang-badge', 'en' => 'badge-stone']]);

        $html = $this->visit('en', '/products/badge-stone')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#class="pd-group-badge"[^>]*>\s*Saw-Cut\s*<#', $html);
        $this->assertStringContainsString(route('products.index', ['group' => 'saw-cut']), $html);
    }

    public function test_no_public_page_reveals_owner_mine_warehouse_or_sale_details(): void
    {
        $owner = Owner::create(['name' => 'ZZOWNERNAME', 'phone' => '09120000777', 'email' => 'zzowner@example.test']);
        $mine = Mine::create(['name' => 'ZZMINENAME', 'location' => 'ZZMINEPLACE']);
        $warehouse = Warehouse::create(['name' => 'ZZWAREHOUSENAME', 'location' => 'ZZWAREHOUSEPLACE']);

        $category = Category::create(['name' => ['fa' => 'تراورتن', 'en' => 'Secret Cat'], 'slug' => ['fa' => 'secret-fa', 'en' => 'secret-cat'], 'is_active' => true]);

        $available = $this->stone('export', [
            'slug' => ['fa' => 'avail-fa', 'en' => 'avail-stone'],
            'owner_id' => $owner->id, 'mine_id' => $mine->id, 'warehouse_id' => $warehouse->id,
        ]);
        $sold = $this->stone('saw-cut', [
            'slug' => ['fa' => 'sold-fa', 'en' => 'sold-stone'],
            'owner_id' => $owner->id, 'mine_id' => $mine->id, 'warehouse_id' => $warehouse->id,
        ]);
        $sold->markAsSold(['sold_price' => 987654321, 'sold_currency' => 'USD', 'sold_to' => 'ZZBUYERNAME']);
        $category->products()->attach([$available->id, $sold->id]);

        $forbidden = ['ZZOWNERNAME', '09120000777', 'zzowner@example.test', 'ZZMINENAME', 'ZZMINEPLACE',
            'ZZWAREHOUSENAME', 'ZZWAREHOUSEPLACE', 'ZZBUYERNAME', '987654321', '987,654,321'];

        foreach (['/', '/products', '/products?group=export', '/products?group=saw-cut', '/categories/secret-cat',
                     '/products/avail-stone', '/products/sold-stone'] as $uri) {
            $html = $this->visit('en', $uri)->assertOk()->getContent();

            foreach ($forbidden as $secret) {
                $this->assertStringNotContainsString($secret, $html, "{$secret} must not appear on {$uri}");
            }
        }

        // …nor in the serialized model, in case a page or API ever prints one
        $json = json_encode(Product::find($sold->id)->toArray());
        foreach (['owner_id', 'mine_id', 'warehouse_id', 'sold_at', 'sold_price', 'sold_currency', 'sold_to', 'sold_warehouse_id'] as $field) {
            $this->assertStringNotContainsString('"' . $field . '"', $json, "{$field} is hidden from serialization");
        }
    }
}
