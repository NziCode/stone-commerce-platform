<?php

namespace Tests\Feature;

use App\Console\Commands\ExpireReservations;
use App\Filament\Resources\ReservationRequestResource\Pages\ListReservationRequests;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ReservationRequest;
use App\Models\Setting;
use App\Models\User;
use App\Support\WhatsApp;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\MenuItemSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\ReservationFlowTranslationSeeder;
use Database\Seeders\ReservationTranslationSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * Cart vs. "price on request" stones, the WhatsApp button on the product page and the
 * reservation sales flow (request → approved → prepayment → final payment → sold).
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=ReservationFlowTest
 */
class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: (RefreshDatabase would wipe a real database).');
        }

        Http::fake();   // owner notifications must never leave the test

        $this->seed([
            LanguageSeeder::class, SettingSeeder::class, MenuSeeder::class, MenuItemSeeder::class,
            TranslationSeeder::class, ReservationTranslationSeeder::class, ReservationFlowTranslationSeeder::class,
        ]);
    }

    private function stone(array $overrides = []): Product
    {
        static $n = 0;
        $n++;

        return Product::create(array_merge([
            'name'     => ['fa' => "سنگ آزمایشی {$n}", 'en' => "Test Stone {$n}"],
            'slug'     => ['fa' => "sang-{$n}", 'en' => "stone-{$n}"],
            'sku'      => "T-100{$n}",
            'status'   => 'available',
            'is_active' => true,
            'price_on_request' => true,
        ], $overrides));
    }

    private function pricedStone(): Product
    {
        return $this->stone(['price_on_request' => false, 'price' => 1500000]);
    }

    private function visit(string $method, string $uri, array $data = [])
    {
        return $this->withSession(['locale' => 'en'])
            ->withoutMiddleware(LocaleSessionRedirect::class)
            ->{$method}($uri, $data);
    }

    private function reservation(Product $product, array $overrides = []): ReservationRequest
    {
        return ReservationRequest::create(array_merge([
            'product_id' => $product->id, 'name' => 'Ali', 'phone_country' => '+98', 'phone' => '0912 345 6789',
            'contact_method' => 'whatsapp', 'status' => 'pending',
        ], $overrides));
    }

    // ── cart ────────────────────────────────────────────────────────────────

    public function test_price_on_request_stone_is_not_added_to_the_cart(): void
    {
        $stone = $this->stone();

        $this->visit('post', "/cart/add/{$stone->id}")
            ->assertSessionHas('error', __('messages.cart_price_on_request'));

        $this->assertSame('available', $stone->fresh()->status, 'the stone must not be left reserved');
        $this->assertSame(0, CartItem::count());
    }

    public function test_priced_stone_goes_into_the_cart_and_is_held_for_a_while(): void
    {
        $stone = $this->pricedStone();

        $this->visit('post', "/cart/add/{$stone->id}")->assertSessionHas('success');

        $this->assertSame('reserved', $stone->fresh()->status);
        $this->assertSame(1, CartItem::count());
    }

    public function test_a_failure_while_filling_the_cart_does_not_leave_the_stone_reserved(): void
    {
        $stone = $this->pricedStone();

        CartItem::creating(fn () => throw new \RuntimeException('boom'));

        $this->visit('post', "/cart/add/{$stone->id}")->assertStatus(500);

        $this->assertSame('available', $stone->fresh()->status, 'the hold is rolled back together with the cart');
    }

    // ── product page & header ───────────────────────────────────────────────

    public function test_product_page_offers_whatsapp_and_reservation_but_no_cart_for_price_on_request(): void
    {
        Setting::set('site_phone', '989140000000');
        $stone = $this->stone();

        $html = $this->visit('get', '/products/' . $stone->getTranslation('slug', 'en'))->assertOk()->getContent();

        $this->assertStringContainsString('https://wa.me/989140000000?text=', $html);
        $this->assertStringContainsString(rawurlencode($stone->sku), $html, 'the stone code is in the prefilled message');
        $this->assertStringContainsString('Ask &amp; reserve on WhatsApp', $html);
        $this->assertStringContainsString('id="reserveModal"', $html);
        $this->assertStringNotContainsString('/cart/add/', $html);
        $this->assertStringNotContainsString('messages.pd_', $html, 'no untranslated keys');
    }

    public function test_priced_stone_still_has_the_cart_button_and_the_header_cart_appears(): void
    {
        $this->stone();   // price on request only → cart hidden
        $html = $this->visit('get', '/contact')->assertOk()->getContent();
        $this->assertStringNotContainsString('minicart-btn', $html);

        Cache::forget('cart.enabled');
        $priced = $this->pricedStone();

        $this->assertStringContainsString('/cart/add/' . $priced->id,
            $this->visit('get', '/products/' . $priced->getTranslation('slug', 'en'))->assertOk()->getContent());
        $this->assertStringContainsString('minicart-btn', $this->visit('get', '/contact')->assertOk()->getContent());
    }

    // ── WhatsApp links ──────────────────────────────────────────────────────

    public function test_customer_numbers_become_wa_me_links(): void
    {
        $this->assertSame('https://wa.me/989123456789', WhatsApp::chatUrl('+98', '0912 345 6789'), 'leading 0 dropped');
        $this->assertSame('https://wa.me/989123456789', WhatsApp::chatUrl('+98', '912-345-6789'));
        $this->assertSame('https://wa.me/971501234567', WhatsApp::chatUrl('+971', '050 123 4567'));
        $this->assertSame('https://wa.me/447700900123', WhatsApp::chatUrl('other', '00447700900123'), 'international number typed with 00');
        $this->assertSame('https://wa.me/447700900123', WhatsApp::chatUrl('other', '447700900123'));
        $this->assertNull(WhatsApp::chatUrl('+98', ''));
        $this->assertStringEndsWith('?text=' . rawurlencode('Hi there'), WhatsApp::chatUrl('+98', '912', 'Hi there'));
    }

    public function test_reservation_exposes_a_ready_whatsapp_link_and_call_number(): void
    {
        $stone = $this->stone();
        $r = $this->reservation($stone);

        $this->assertStringStartsWith('https://wa.me/989123456789?text=', $r->whatsapp_url);
        $this->assertStringContainsString(rawurlencode($stone->sku), $r->whatsapp_url);
        $this->assertSame('+989123456789', $r->call_number);

        $foreign = $this->reservation($this->stone(), ['phone_country' => '+44', 'phone' => '7700 900123']);
        $this->assertStringContainsString(rawurlencode('Hello, we received your reservation request'), $foreign->whatsapp_url);
    }

    // ── reservation sales flow ──────────────────────────────────────────────

    public function test_reservation_request_is_stored_from_the_product_page(): void
    {
        $stone = $this->stone();

        $this->visit('post', "/products/{$stone->id}/reserve", [
            'name' => 'Ali', 'phone_country' => '+98', 'phone' => '912 345 6789', 'contact_method' => 'whatsapp', 'note' => 'Need 3 blocks',
        ])->assertSessionHas('success');

        $this->assertSame('pending', ReservationRequest::firstOrFail()->stage);
        $this->assertSame('available', $stone->fresh()->status, 'a request alone does not hold the stone');
    }

    public function test_prepayment_then_final_payment_takes_the_stone_from_reserved_to_sold(): void
    {
        $stone = $this->stone();
        $r = $this->reservation($stone);

        $this->assertTrue($r->approve());
        $this->assertSame('awaiting_deposit', $r->fresh()->stage);
        $this->assertSame('reserved', $stone->fresh()->status);

        $r->fresh()->markDepositReceived(5000, 'USD', now()->addDays(14), 'bank transfer');
        $r = $r->fresh();
        $this->assertSame('deposit_paid', $r->stage);
        $this->assertSame('5000.00', (string) $r->deposit_amount);
        $this->assertStringContainsString('bank transfer', $r->admin_note);
        $this->assertSame('reserved', $stone->fresh()->status, 'stays reserved until the final payment');

        $r->markFinalPaid();
        $r = $r->fresh();
        $this->assertSame('completed', $r->stage);
        $this->assertNull($r->expires_at);
        $this->assertSame('sold', $stone->fresh()->status);
    }

    public function test_auto_expiry_releases_unpaid_holds_but_never_a_prepaid_stone(): void
    {
        $unpaid  = $this->stone();
        $prepaid = $this->stone();

        $a = $this->reservation($unpaid);
        $a->approve();
        $b = $this->reservation($prepaid, ['phone' => '0913 000 0000']);
        $b->approve();
        $b->fresh()->markDepositReceived(1000, 'USD', now()->subDay());   // deadline already passed

        $a->update(['expires_at' => now()->subHour()]);

        Artisan::call('reservations:expire');

        $this->assertSame('expired', $a->fresh()->status);
        $this->assertSame('available', $unpaid->fresh()->status);

        $this->assertSame('approved', $b->fresh()->status, 'a prepaid reservation is only released by an admin');
        $this->assertTrue($b->fresh()->isFinalPaymentOverdue());
        $this->assertSame('reserved', $prepaid->fresh()->status);
        $this->assertNotNull($prepaid->activeReservationRequest(), 'still blocks new requests');
    }

    public function test_releasing_a_prepaid_reservation_marks_it_cancelled(): void
    {
        $stone = $this->stone();
        $r = $this->reservation($stone);
        $r->approve();
        $r->fresh()->markDepositReceived(1000, 'EUR', null);

        $r->fresh()->release();

        $this->assertSame('cancelled', $r->fresh()->status);
        $this->assertSame('available', $stone->fresh()->status);
    }

    public function test_admin_can_record_prepayment_and_final_payment(): void
    {
        $this->seed([RolePermissionSeeder::class, AdminUserSeeder::class]);
        $this->actingAs(User::where('email', 'admin@example.com')->firstOrFail());

        $stone = $this->stone();
        $r = $this->reservation($stone);
        $r->approve();

        Livewire::test(ListReservationRequests::class)
            ->assertSuccessful()
            ->assertTableActionVisible('depositReceived', $r)
            ->assertTableActionHidden('finalPaid', $r)
            ->callTableAction('depositReceived', $r, data: [
                'deposit_amount' => 2500, 'deposit_currency' => 'USD', 'final_deadline' => now()->addDays(10)->format('Y-m-d H:i'), 'note' => 'paid',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertSame('deposit_paid', $r->fresh()->stage);

        Livewire::test(ListReservationRequests::class)
            ->assertTableActionHidden('depositReceived', $r->fresh())
            ->callTableAction('finalPaid', $r->fresh())
            ->assertHasNoTableActionErrors();

        $this->assertSame('completed', $r->fresh()->stage);
        $this->assertSame('sold', $stone->fresh()->status);
    }

    public function test_final_payment_records_the_sale_for_the_inventory_reports(): void
    {
        $this->seed([RolePermissionSeeder::class, AdminUserSeeder::class]);
        $this->actingAs(User::where('email', 'admin@example.com')->firstOrFail());

        $stone = $this->stone();
        $r = $this->reservation($stone);
        $r->approve();
        $r->fresh()->markDepositReceived(1000, 'USD', null);

        Livewire::test(ListReservationRequests::class)
            ->callTableAction('finalPaid', $r->fresh(), data: ['sold_price' => 12000, 'sold_currency' => 'EUR', 'sold_to' => 'Test Buyer'])
            ->assertHasNoTableActionErrors();

        $sold = $stone->fresh();
        $this->assertSame('sold', $sold->status);
        $this->assertNotNull($sold->sold_at, 'the sale date is stamped');
        $this->assertSame('12000.00', (string) $sold->sold_price);
        $this->assertSame('EUR', $sold->sold_currency);
        $this->assertSame('Test Buyer', $sold->sold_to);
    }
}
