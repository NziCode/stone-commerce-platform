<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Support\StoneCardSpecs;
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
 * A stone shows the same specs on every card of the storefront (dimensions on one line, then the weight),
 * and the product list's status filter has a "sold" option, with "unavailable" including the sold stones.
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=StorefrontCardSpecsTest
 */
class StorefrontCardSpecsTest extends TestCase
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

    private function visit(string $uri)
    {
        return $this->withSession(['locale' => 'en'])->withoutMiddleware(LocaleSessionRedirect::class)->get($uri);
    }

    /** A stone with the four card attributes, like the real ones (length, thickness, width, weight). */
    private function stone(string $name, string $status = 'available', array $overrides = []): Product
    {
        static $n = 0;
        $n++;

        $stone = Product::create(array_merge([
            'name' => ['fa' => "سنگ {$n}", 'en' => $name], 'slug' => ['fa' => "sang-{$n}", 'en' => 'spec-stone-' . $n],
            'sku' => "SP-{$n}", 'status' => $status, 'is_active' => true, 'price_on_request' => true,
        ], $overrides));

        $attributes = [
            ['length', 'Length', 'cm', 3, 320], ['width', 'Width', 'cm', 4, 145], ['thickness', 'Height', 'cm', 5, 165], ['weight', 'Weight', 'tons', 6, 19],
        ];
        foreach ($attributes as [$key, $label, $unit, $order, $value]) {
            $attribute = Attribute::firstOrCreate(['key' => $key], [
                'label' => ['en' => $label, 'fa' => $label], 'type' => 'number', 'unit' => $unit,
                'show_in_card' => true, 'is_active' => true, 'sort_order' => $order,
            ]);
            ProductAttribute::create(['product_id' => $stone->id, 'attribute_id' => $attribute->id, 'value' => ['value' => $value], 'sort_order' => $order]);
        }

        return $stone->fresh();
    }

    public function test_the_specs_are_one_dimensions_line_then_the_weight(): void
    {
        $stone = $this->stone('Spec Stone');

        $rows = StoneCardSpecs::rows($stone->load('attributes.attribute'), 'en');

        $this->assertSame([
            ['label' => 'Dimensions', 'value' => '320 × 165 × 145 cm'],
            ['label' => 'Weight', 'value' => '19 tons'],
        ], $rows);
    }

    public function test_every_card_prints_the_specs_the_same_way(): void
    {
        $category = Category::create(['name' => ['fa' => 'تراورتن', 'en' => 'Spec Cat'], 'slug' => ['fa' => 'spec-fa', 'en' => 'spec-cat'], 'is_active' => true]);
        $stone = $this->stone('Card Spec Stone');
        $category->products()->attach($stone->id);

        foreach (['/' => 'home', '/products' => 'product list', '/categories/spec-cat' => 'category page'] as $uri => $where) {
            $html = $this->visit($uri)->assertOk()->getContent();

            $this->assertStringContainsString('320 × 165 × 145 cm', $html, "dimensions on one line on the {$where}");
            $this->assertMatchesRegularExpression('#mt-pcard-attr-label">\s*Dimensions:#', $html, "label on the {$where}");
            $this->assertMatchesRegularExpression('#mt-pcard-attr-label">\s*Weight:#', $html, "weight on the {$where}");
            $this->assertStringContainsString('19 tons', $html, "weight on the {$where}");
        }

        // the product list no longer uses the separate length / width / height chips
        $list = $this->visit('/products')->getContent();
        $this->assertStringNotContainsString('sc-dim', $list);
    }

    public function test_status_filter_has_a_sold_option_and_unavailable_includes_sold_stones(): void
    {
        $this->stone('Alpha Available', 'available');
        $this->stone('Bravo Reserved', 'reserved');
        $this->stone('Charlie Sold', 'sold');
        $this->stone('Delta Unavailable', 'unavailable');

        $list = $this->visit('/products')->assertOk()->getContent();
        foreach (['available', 'reserved', 'sold', 'unavailable'] as $status) {
            $this->assertStringContainsString('status=' . $status, $list, "the sidebar offers {$status}");
        }

        $cases = [
            'available'   => ['Alpha Available'],
            'reserved'    => ['Bravo Reserved'],
            'sold'        => ['Charlie Sold'],
            'unavailable' => ['Charlie Sold', 'Delta Unavailable'],   // anything that cannot be bought
        ];
        $all = ['Alpha Available', 'Bravo Reserved', 'Charlie Sold', 'Delta Unavailable'];

        foreach ($cases as $status => $expected) {
            $html = $this->visit('/products?status=' . $status)->assertOk()->getContent();

            foreach ($all as $name) {
                in_array($name, $expected, true)
                    ? $this->assertStringContainsString($name, $html, "{$name} is listed for status={$status}")
                    : $this->assertStringNotContainsString($name, $html, "{$name} is not listed for status={$status}");
            }
        }
    }

    public function test_the_category_page_filters_by_the_same_statuses(): void
    {
        $category = Category::create(['name' => ['fa' => 'تراورتن', 'en' => 'Filter Cat'], 'slug' => ['fa' => 'filter-fa', 'en' => 'filter-cat'], 'is_active' => true]);
        $category->products()->attach([
            $this->stone('Echo Sold', 'sold')->id,
            $this->stone('Foxtrot Available', 'available')->id,
        ]);

        $sold = $this->visit('/categories/filter-cat?status=sold')->assertOk()->getContent();
        $this->assertStringContainsString('Echo Sold', $sold);
        $this->assertStringNotContainsString('Foxtrot Available', $sold);

        $unavailable = $this->visit('/categories/filter-cat?status=unavailable')->assertOk()->getContent();
        $this->assertStringContainsString('Echo Sold', $unavailable);
        $this->assertStringNotContainsString('Foxtrot Available', $unavailable);
    }

    public function test_a_price_on_request_card_offers_the_quote_button_and_a_set_price_shows_the_amount(): void
    {
        $category = Category::create(['name' => ['fa' => 'تراورتن', 'en' => 'Price Cat'], 'slug' => ['fa' => 'price-fa', 'en' => 'price-cat'], 'is_active' => true]);
        $onRequest = $this->stone('On Request Stone', 'available', ['is_featured' => true]);
        $priced = $this->stone('Priced Stone', 'available', ['is_featured' => true, 'price_on_request' => false, 'price' => 5000000, 'price_usd' => 120]);
        $category->products()->attach([$onRequest->id, $priced->id]);

        $request = trans('messages.price_on_request', [], 'en');
        $label = trans('messages.price', [], 'en');

        // home: the featured and latest cards
        $home = $this->visit('/')->assertOk()->getContent();
        $this->assertStringNotContainsString($request, $home, "no \"{$request}\" text: the button is the price line");
        $this->assertStringContainsString('class="mt-pcard-inquiry"', $home);
        $this->assertStringNotContainsString("<small>{$label}</small>", $home, "no separate \"{$label}\" label");
        $this->assertStringContainsString('5,000,000', $home);
        $this->assertStringContainsString('<small>$120</small>', $home, 'the dollar price sits under the amount');

        // product list and category page: the same
        foreach (['/products', '/categories/price-cat'] as $uri) {
            $html = $this->visit($uri)->assertOk()->getContent();

            $this->assertStringNotContainsString($request, $html, "no \"{$request}\" text on {$uri}");
            $this->assertStringContainsString('data-inquiry-url="' . route('products.inquiry', $onRequest) . '"', $html, "the quote button on {$uri}");
            $this->assertStringContainsString('5,000,000', $html, "the set price on {$uri}");
        }
    }
}
