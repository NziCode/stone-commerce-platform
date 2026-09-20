<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
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
 * The phone layout that is rendered on the server: the bottom navigation (localized links,
 * right active tab, cart tab only when the cart is in use, account → login for guests),
 * the search sheet, the products filters drawer and the order of the product page blocks.
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=MobileLayoutTest
 */
class MobileLayoutTest extends TestCase
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
            MobileUxTranslationSeeder::class, MainCategoryTranslationSeeder::class, RolePermissionSeeder::class,
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

    private function stone(array $overrides = []): Product
    {
        static $n = 0;
        $n++;

        return Product::create(array_merge([
            'name' => ['fa' => "سنگ {$n}", 'en' => "Stone {$n}"], 'slug' => ['fa' => "sang-{$n}", 'en' => "stone-{$n}"],
            'sku' => "M-{$n}", 'status' => 'available', 'is_active' => true, 'price_on_request' => true,
        ], $overrides));
    }

    /** @return array<string, array{href:string, active:bool, text:string}> tabs of the bottom nav, keyed by data-tab */
    private function tabs(string $html): array
    {
        preg_match('#<nav id="mt-bottom-nav".*?</nav>#s', $html, $nav);
        $this->assertNotEmpty($nav, 'the bottom navigation is rendered');

        preg_match_all('#<a href="([^"]+)"\s+class="mt-bnav-item( active)?"\s+data-tab="([a-z]+)".*?<span class="mt-bnav-label">([^<]*)</span>#s', $nav[0], $m, PREG_SET_ORDER);

        $tabs = [];
        foreach ($m as $row) {
            $tabs[$row[3]] = ['href' => html_entity_decode($row[1]), 'active' => $row[2] !== '', 'text' => trim($row[4])];
        }

        return $tabs;
    }

    public function test_guest_gets_localized_tabs_and_the_account_tab_leads_to_login(): void
    {
        $this->stone();   // price on request only → no cart

        $tabs = $this->tabs($this->visit('en', '/')->assertOk()->getContent());

        $this->assertSame(['home', 'products', 'search', 'account'], array_keys($tabs), 'no cart tab while nothing is sold by fixed price');
        $this->assertSame(['Home', 'Products', 'Search', 'Sign In'], array_column($tabs, 'text'));
        $this->assertSame(route('login'), $tabs['account']['href'], 'a guest is taken to the login page, never a dead end');
        $this->assertSame(route('home'), $tabs['home']['href']);
        $this->assertSame(route('products.index'), $tabs['products']['href']);
        $this->assertSame(route('search'), $tabs['search']['href'], 'the no-JS fallback of the search tab');

        // links carry the language prefix and are not the old hard-coded "/profile" / "/cart"
        foreach ($tabs as $tab) {
            $this->assertStringNotContainsString('javascript:', $tab['href']);
        }
    }

    public function test_only_the_current_section_is_highlighted(): void
    {
        $this->stone();

        $home = $this->tabs($this->visit('fa', '/')->assertOk()->getContent());
        $this->assertSame(['home'], array_keys(array_filter($home, fn ($t) => $t['active'])));

        $products = $this->tabs($this->visit('fa', '/products')->assertOk()->getContent());
        $this->assertSame(['products'], array_keys(array_filter($products, fn ($t) => $t['active'])), 'home must not stay lit on other pages');

        $login = $this->tabs($this->visit('fa', '/login')->assertOk()->getContent());
        $this->assertSame(['account'], array_keys(array_filter($login, fn ($t) => $t['active'])));
    }

    public function test_logged_in_user_gets_the_profile_link_and_the_cart_tab_appears_with_priced_stones(): void
    {
        $this->stone(['price_on_request' => false, 'price' => 1500000]);
        $user = User::factory()->create();

        $html = $this->actingAs($user)->withSession(['locale' => 'fa'])
            ->withoutMiddleware(LocaleSessionRedirect::class)->get('/')->assertOk()->getContent();
        $tabs = $this->tabs($html);

        $this->assertSame(['home', 'products', 'search', 'cart', 'account'], array_keys($tabs));
        $this->assertSame(route('profile.index'), $tabs['account']['href']);
        $this->assertSame(route('cart.index'), $tabs['cart']['href']);
        $this->assertSame('حساب کاربری', $tabs['account']['text'], 'translated, not the old hard-coded Persian');

        $profile = $this->tabs($this->actingAs($user)->withSession(['locale' => 'fa'])
            ->withoutMiddleware(LocaleSessionRedirect::class)->get('/profile')->assertOk()->getContent());
        $this->assertSame(['account'], array_keys(array_filter($profile, fn ($t) => $t['active'])), 'the account tab lights up on the profile page');
    }

    public function test_search_sheet_posts_to_the_search_page_and_offers_category_shortcuts(): void
    {
        Category::create(['name' => ['fa' => 'تراورتن تست', 'en' => 'Test Travertine'], 'slug' => ['fa' => 'test-fa', 'en' => 'test-travertine'], 'is_active' => true]);

        $html = $this->visit('en', '/')->assertOk()->getContent();

        $this->assertStringContainsString('id="mtSearchSheet"', $html);
        $this->assertMatchesRegularExpression('#<form action="' . preg_quote(route('search'), '#') . '" method="GET" role="search"#', $html);
        $this->assertStringContainsString('name="q"', $html);
        $this->assertStringContainsString('Test Travertine', $html, 'category chips under the search field');
        $this->assertStringContainsString('viewport-fit=cover', $html, 'so the bar clears the iPhone home indicator');
        $this->assertStringNotContainsString('messages.', $html, 'no untranslated keys in the phone chrome');
    }

    public function test_products_page_has_a_filters_drawer_and_the_button_that_opens_it(): void
    {
        $this->stone();

        $html = $this->visit('en', '/products')->assertOk()->getContent();

        $this->assertStringContainsString('id="plFilters"', $html);
        $this->assertStringContainsString('data-mt-filters-open', $html);
        $this->assertStringContainsString('data-mt-filters-close', $html);
        $this->assertStringContainsString('Show results', $html);
        $this->assertStringNotContainsString('messages.filters', $html);
    }

    public function test_product_page_puts_price_and_actions_right_after_the_photos(): void
    {
        $stone = $this->stone();

        $html = $this->visit('en', '/products/' . $stone->getTranslation('slug', 'en'))->assertOk()->getContent();

        $positions = [];
        foreach (['pd-gallery', 'pd-info', 'pd-contact', 'pd-tabs', 'pd-cats'] as $class) {
            $pos = strpos($html, $class);
            $this->assertNotFalse($pos, "$class is present");
            $positions[$class] = $pos;
        }

        // the CSS order (see .pd-* in theme-modern.css) decides the phone order; the classes must exist on the right blocks
        $this->assertStringContainsString('class="pd-layout"', $html);
        $this->assertStringContainsString('pd-side-inner', $html);
    }

    public function test_the_old_script_built_navigation_is_gone(): void
    {
        $js = file_get_contents(public_path('assets/js/mobile-ux.js'));

        $this->assertStringNotContainsString("createElement('nav')", $js);
        $this->assertStringNotContainsString('href="/profile"', $js);
        $this->assertStringNotContainsString('href="/cart"', $js);
        $this->assertStringContainsString('mtSearchSheet', $js);
    }
}
