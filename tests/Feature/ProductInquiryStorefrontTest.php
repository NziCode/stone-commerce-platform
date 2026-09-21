<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
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
 * What visitors see of "Request a quote": the button that replaces the "price on request" text on every card and
 * on the product page, and the pop-up (WhatsApp, a call, or a number to be called back) shared by all of them.
 * The saving, alerting and admin side is in ProductInquiryTest. Needs a throw-away SQLite database:
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=ProductInquiryStorefrontTest
 */
class ProductInquiryStorefrontTest extends TestCase
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

        Http::fake(['*' => Http::response(['ok' => true])]);

        $this->seed([
            LanguageSeeder::class, SettingSeeder::class, MenuSeeder::class, MenuItemSeeder::class,
            TranslationSeeder::class, ReservationTranslationSeeder::class, ReservationFlowTranslationSeeder::class,
            MobileUxTranslationSeeder::class, MainCategoryTranslationSeeder::class, InquiryTranslationSeeder::class,
            RolePermissionSeeder::class,
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

    private function stone(string $name, string $status = 'available', array $overrides = []): Product
    {
        static $n = 0;
        $n++;

        return Product::create(array_merge([
            'name' => ['fa' => "سنگ {$n}", 'en' => $name], 'slug' => ['fa' => "sang-{$n}", 'en' => 'quote-stone-' . $n],
            'sku' => "QS-{$n}", 'status' => $status, 'is_active' => true, 'price_on_request' => true,
        ], $overrides));
    }

    public function test_every_card_and_the_product_page_offer_the_button_instead_of_the_price_on_request_text(): void
    {
        Setting::set('site_phone', '+98 912 000 0000');

        $category = Category::create(['name' => ['fa' => 'تراورتن', 'en' => 'Quote Cat'], 'slug' => ['fa' => 'quote-fa', 'en' => 'quote-cat'], 'is_active' => true]);
        $open = $this->stone('Open Stone', 'available', ['is_featured' => true]);
        $sold = $this->stone('Gone Stone', 'sold');
        $priced = $this->stone('Priced Stone', 'available', ['is_featured' => true, 'price_on_request' => false, 'price' => 1000000]);
        $category->products()->attach([$open->id, $sold->id, $priced->id]);

        $request = trans('messages.price_on_request', [], 'en');

        foreach (['/' => 'home', '/products' => 'product list', '/categories/quote-cat' => 'category page', '/products/' . $open->getTranslation('slug', 'en') => 'product page'] as $uri => $where) {
            $html = $this->visit($uri)->assertOk()->getContent();

            $this->assertStringContainsString('data-inquiry-url="' . route('products.inquiry', $open) . '"', $html, "the button on the {$where}");
            $this->assertStringContainsString('data-inquiry-code="' . $open->sku . '"', $html);
            $this->assertStringContainsString('data-inquiry-wa="https://wa.me/', $html, "asking on WhatsApp is ready on the {$where}");
            $this->assertStringNotContainsString($request, $html, "no \"{$request}\" text on the {$where}");
            $this->assertStringContainsString('id="inquiryModal"', $html, "the pop-up is on the {$where}");
            $this->assertStringNotContainsString(route('products.inquiry', $sold), $html, "a sold stone has no button on the {$where}");
        }
    }

    public function test_the_pop_up_offers_whatsapp_a_call_and_the_form(): void
    {
        Setting::set('site_phone', '+98 912 000 0000');

        $html = $this->visit('/products')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#<div class="modal fade" id="inquiryModal".*?data-inq-wa.*?href="tel:\+?98#s', $html, 'WhatsApp and call shortcuts');
        foreach (['name="phone_country"', 'name="phone"', 'name="contact_method" value="call"', 'name="contact_method" value="whatsapp"', 'name="note"'] as $field) {
            $this->assertStringContainsString($field, $html, $field);
        }
        $this->assertStringContainsString(trans('messages.inquiry_intro', [], 'en'), $html);
        $this->assertStringContainsString(trans('messages.inquiry_submit', [], 'en'), $html);
    }
}
