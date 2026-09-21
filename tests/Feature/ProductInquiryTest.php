<?php

namespace Tests\Feature;

use App\Filament\Resources\ProductInquiryResource;
use App\Filament\Resources\ProductInquiryResource\Pages\ManageProductInquiries;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
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
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * "Request a quote": a visitor leaves a number for a stone; the request is saved, the owner is told at once, and the
 * sales team follows it up in the admin panel (the button and the pop-up are in ProductInquiryStorefrontTest).
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=ProductInquiryTest
 */
class ProductInquiryTest extends TestCase
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

    private function ask(Product $stone, array $data = [])
    {
        return $this->withSession(['locale' => 'en'])->withoutMiddleware(LocaleSessionRedirect::class)
            ->postJson("/products/{$stone->id}/inquiry", array_merge([
                'name' => 'Ali', 'phone_country' => '+98', 'phone' => '912 345 6789', 'contact_method' => 'whatsapp', 'note' => 'Two blocks please',
            ], $data));
    }

    private function stone(string $name, string $status = 'available', array $overrides = []): Product
    {
        static $n = 0;
        $n++;

        return Product::create(array_merge([
            'name' => ['fa' => "سنگ {$n}", 'en' => $name], 'slug' => ['fa' => "sang-{$n}", 'en' => 'inquiry-stone-' . $n],
            'sku' => "Q-{$n}", 'status' => $status, 'is_active' => true, 'price_on_request' => true,
        ], $overrides));
    }

    public function test_a_visitor_leaves_a_number_and_the_owner_is_told(): void
    {
        Setting::set('contact_notify_bot_token', 't', 'contact');
        Setting::set('contact_notify_bot_chat_id', '1', 'contact');

        $stone = $this->stone('Asked Stone');

        $this->ask($stone)->assertOk()->assertJson(['ok' => true, 'message' => trans('messages.inquiry_sent', [], 'en')]);

        $saved = ProductInquiry::firstOrFail();
        $this->assertSame($stone->id, $saved->product_id);
        $this->assertSame('Ali', $saved->name);
        $this->assertSame('+98', $saved->phone_country);
        $this->assertSame('912 345 6789', $saved->phone);
        $this->assertSame('whatsapp', $saved->contact_method);
        $this->assertSame('new', $saved->status);
        $this->assertSame('+989123456789', $saved->call_number);
        $this->assertStringStartsWith('https://wa.me/989123456789', (string) $saved->whatsapp_url);

        Http::assertSent(fn ($request) => str_contains($request->data()['text'] ?? '', 'استعلام قیمت جدید')
            && str_contains($request->data()['text'] ?? '', 'Q-')
            && str_contains($request->data()['text'] ?? '', '+98 912 345 6789')
            && str_contains($request->data()['text'] ?? '', '/admin/product-inquiries'));
    }

    public function test_the_number_is_required_and_a_stone_that_is_sold_or_hidden_cannot_be_asked_about(): void
    {
        $stone = $this->stone('Plain Stone');

        $this->ask($stone, ['phone' => ''])->assertStatus(422)->assertJsonValidationErrors('phone');
        $this->ask($stone, ['phone' => 'abc'])->assertStatus(422)->assertJsonValidationErrors('phone');
        $this->ask($stone, ['contact_method' => 'fax'])->assertStatus(422)->assertJsonValidationErrors('contact_method');

        $this->ask($this->stone('Sold Stone', 'sold'))->assertStatus(422)->assertJson(['ok' => false]);
        $this->ask($this->stone('Hidden Stone', 'available', ['is_active' => false]))->assertStatus(422)->assertJson(['ok' => false]);

        $this->assertSame(0, ProductInquiry::count());
    }

    public function test_the_same_number_asking_again_within_a_day_is_one_request(): void
    {
        $stone = $this->stone('Twice Asked Stone');

        $this->ask($stone)->assertOk();
        $this->ask($stone)->assertOk()->assertJson(['ok' => true]);   // the visitor is told all is well, but nothing new is created (nor announced)

        $this->assertSame(1, ProductInquiry::count());

        // another stone, or a number the sales team already dealt with, is a new request
        $this->ask($this->stone('Another Stone'))->assertOk();
        ProductInquiry::where('product_id', $stone->id)->update(['status' => 'contacted']);
        $this->ask($stone)->assertOk();

        $this->assertSame(3, ProductInquiry::count());
    }

    public function test_the_sales_team_sees_inquiries_and_follows_them_up_in_the_admin_panel(): void
    {
        $this->seed(AdminUserSeeder::class);
        $this->actingAs(User::where('email', 'admin@example.com')->firstOrFail());

        $stone = $this->stone('Panel Stone');
        $inquiry = ProductInquiry::create([
            'product_id' => $stone->id, 'name' => 'Sara', 'phone_country' => '+98', 'phone' => '912 345 6789', 'contact_method' => 'call', 'note' => 'Price for 3 blocks',
        ]);

        $this->assertSame('1', ProductInquiryResource::getNavigationBadge());

        Livewire::test(ManageProductInquiries::class)->assertSuccessful()
            ->assertCanSeeTableRecords([$inquiry])
            ->assertTableActionVisible('whatsapp', $inquiry->getKey())
            ->assertTableActionVisible('call', $inquiry->getKey())
            ->assertTableActionVisible('markContacted', $inquiry->getKey())
            ->callTableAction('markContacted', $inquiry->getKey())
            ->assertTableActionHidden('markContacted', $inquiry->getKey());

        $contacted = $inquiry->fresh();
        $this->assertSame('contacted', $contacted->status);
        $this->assertNotNull($contacted->contacted_at);
        $this->assertNull(ProductInquiryResource::getNavigationBadge(), 'nothing new is left');

        Livewire::test(ManageProductInquiries::class)
            ->callTableAction('close', $inquiry->getKey())
            ->assertHasNoTableActionErrors();
        $this->assertSame('closed', $inquiry->fresh()->status);

        Livewire::test(ManageProductInquiries::class)
            ->callTableAction('edit', $inquiry->getKey(), data: ['status' => 'contacted', 'admin_note' => 'Quoted 900 USD per ton'])
            ->assertHasNoTableActionErrors();
        $this->assertSame('Quoted 900 USD per ton', $inquiry->fresh()->admin_note);
    }

    public function test_administrators_and_sales_staff_may_see_the_inquiries_but_editors_may_not(): void
    {
        $sales = User::factory()->create();
        $sales->assignRole('sales');
        $this->actingAs($sales);
        $this->assertTrue(ProductInquiryResource::canViewAny());

        $editor = User::factory()->create();
        $editor->assignRole('editor');
        $this->actingAs($editor);
        $this->assertFalse(ProductInquiryResource::canViewAny(), 'customers\' phone numbers are not for editors');
        $this->assertNull(ProductInquiryResource::getNavigationBadge());
    }
}
