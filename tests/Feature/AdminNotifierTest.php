<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSettings;
use App\Mail\AdminAlert;
use App\Models\Product;
use App\Models\ReservationRequest;
use App\Models\Setting;
use App\Models\User;
use App\Services\AdminNotifier;
use App\Support\MailSettings;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\ReservationFlowTranslationSeeder;
use Database\Seeders\ReservationTranslationSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * Owner notifications (email / SMS / Telegram-compatible bot) and the settings that drive them.
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=AdminNotifierTest
 */
class AdminNotifierTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: (RefreshDatabase would wipe a real database).');
        }

        $this->seed([
            LanguageSeeder::class, SettingSeeder::class,
            TranslationSeeder::class, ReservationTranslationSeeder::class, ReservationFlowTranslationSeeder::class,
        ]);

        Http::preventStrayRequests();
        RateLimiter::clear('admin-notify:sms');
        RateLimiter::clear('admin-notify:bot');
    }

    public function test_nothing_configured_means_nothing_is_sent_and_nothing_breaks(): void
    {
        Setting::set('site_email', '');
        Mail::fake();
        Http::fake();

        $results = AdminNotifier::send('Title', ['line one']);

        $this->assertStringStartsWith('skipped', $results['email']);
        $this->assertStringStartsWith('skipped', $results['sms']);
        $this->assertStringStartsWith('skipped', $results['bot']);
        Mail::assertNothingSent();
        Http::assertNothingSent();
    }

    public function test_email_goes_to_the_notification_address_or_the_site_email(): void
    {
        Mail::fake();

        Setting::set('site_email', 'site@example.com');
        $this->assertSame('ok', AdminNotifier::send('Hello', ['body text'])['email']);
        Mail::assertSent(AdminAlert::class, fn ($m) => $m->hasTo('site@example.com') && $m->alertTitle === 'Hello');

        Setting::set('contact_notify_email', 'owner@example.com, boss@example.com', 'contact');
        AdminNotifier::send('Second', ['x']);
        Mail::assertSent(AdminAlert::class, fn ($m) => $m->hasTo('owner@example.com') && $m->hasTo('boss@example.com'));
    }

    public function test_bot_message_is_posted_to_the_configured_api(): void
    {
        Http::fake(['*' => Http::response(['ok' => true])]);
        Setting::set('contact_notify_bot_token', '123:SECRET', 'contact');
        Setting::set('contact_notify_bot_chat_id', '555', 'contact');

        $results = AdminNotifier::send('New request', ['Stone: X'], 'https://example.com/admin');

        $this->assertSame('ok', $results['bot']);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.telegram.org/bot123:SECRET/sendMessage'
            && $request['chat_id'] === '555'
            && str_contains($request['text'], 'New request')
            && str_contains($request['text'], 'Stone: X')
            && str_contains($request['text'], 'https://example.com/admin'));

        // Bale (or any compatible service) via the API address
        Setting::set('contact_notify_bot_api', 'https://tapi.bale.ai/', 'contact');
        AdminNotifier::send('Again', ['y']);
        Http::assertSent(fn ($request) => $request->url() === 'https://tapi.bale.ai/bot123:SECRET/sendMessage');
    }

    public function test_a_failing_bot_is_reported_without_leaking_the_token_or_throwing(): void
    {
        Http::fake(['*' => fn () => throw new \Illuminate\Http\Client\ConnectionException('cURL error 28 for https://api.telegram.org/bot123:SECRET/sendMessage')]);
        Setting::set('contact_notify_bot_token', '123:SECRET', 'contact');
        Setting::set('contact_notify_bot_chat_id', '555', 'contact');

        $result = AdminNotifier::send('T', ['b'])['bot'];

        $this->assertStringStartsWith('error', $result);
        $this->assertStringNotContainsString('SECRET', $result);
    }

    public function test_a_bot_http_error_is_reported(): void
    {
        Http::fake(['*' => Http::response('nope', 500)]);
        Setting::set('contact_notify_bot_token', '123:SECRET', 'contact');
        Setting::set('contact_notify_bot_chat_id', '555', 'contact');

        $this->assertSame('error: HTTP 500', AdminNotifier::send('T', ['b'])['bot']);
    }

    public function test_sms_uses_the_panel_settings_and_numbers(): void
    {
        Http::fake(['*' => Http::response(['return' => ['status' => 200]])]);
        Setting::set('sms_provider', 'kavenegar', 'sms');
        Setting::set('sms_api_key', 'KEY123', 'sms');
        Setting::set('sms_sender', '10004346', 'sms');
        Setting::set('contact_notify_sms', "09120000001, 09120000002\n09120000003", 'contact');

        $this->assertSame('ok', AdminNotifier::send('SMS title', ['b'])['sms']);

        Http::assertSent(fn ($request) => str_starts_with($request->url(), 'https://api.kavenegar.com/v1/KEY123/sms/send.json')
            && $request['receptor'] === '09120000001,09120000002,09120000003'
            && $request['sender'] === '10004346'
            && str_contains($request['message'], 'SMS title'));
    }

    public function test_messages_are_capped_per_hour_and_stripped_of_control_characters(): void
    {
        Http::fake(['*' => Http::response(['ok' => true])]);
        Setting::set('contact_notify_bot_token', 't', 'contact');
        Setting::set('contact_notify_bot_chat_id', '1', 'contact');

        for ($i = 0; $i < 40; $i++) {
            $this->assertSame('ok', AdminNotifier::send('T', ['b'])['bot']);
        }
        $this->assertSame('skipped: hourly limit reached', AdminNotifier::send('T', ['b'])['bot']);

        RateLimiter::clear('admin-notify:bot');
        AdminNotifier::send('T', ["evil\x07 name\r\nline2 می‌خواهم"]);
        Http::assertSent(fn ($request) => str_contains($request->data()['text'] ?? '', "evil name\nline2 می‌خواهم"));   // BEL and CR gone, ZWNJ kept
    }

    public function test_smtp_settings_from_the_panel_configure_the_mailer(): void
    {
        MailSettings::apply();
        $this->assertNotSame('smtp', config('mail.default'), 'no host → the .env mailer is untouched');

        Setting::set('smtp_host', 'mail.example.com', 'smtp');
        Setting::set('smtp_port', '465', 'smtp');
        Setting::set('smtp_username', 'user@example.com', 'smtp');
        Setting::set('smtp_password', 'pw', 'smtp');
        Setting::set('smtp_from_address', 'no-reply@example.com', 'smtp');
        MailSettings::apply();

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('mail.example.com', config('mail.mailers.smtp.host'));
        $this->assertSame(465, config('mail.mailers.smtp.port'));
        $this->assertSame('no-reply@example.com', config('mail.from.address'));
    }

    public function test_storefront_events_notify_the_owner_after_the_response(): void
    {
        Mail::fake();
        Http::fake(['*' => Http::response(['ok' => true])]);
        Setting::set('site_email', 'owner@example.com');
        Setting::set('contact_notify_bot_token', 't', 'contact');
        Setting::set('contact_notify_bot_chat_id', '1', 'contact');

        $stone = Product::create([
            'name' => ['fa' => 'سنگ تست', 'en' => 'Test stone'], 'slug' => ['en' => 'test-stone'], 'sku' => 'T-1',
            'status' => 'available', 'is_active' => true, 'price_on_request' => true,
        ]);

        $this->withSession(['locale' => 'en'])->withoutMiddleware(LocaleSessionRedirect::class)
            ->post("/products/{$stone->id}/reserve", [
                'name' => 'Ali', 'phone_country' => '+98', 'phone' => '912 345 6789', 'contact_method' => 'whatsapp',
            ])->assertSessionHas('success');

        Http::assertSent(fn ($request) => str_contains($request->data()['text'] ?? '', 'درخواست رزرو جدید')
            && str_contains($request->data()['text'] ?? '', 'T-1')
            && str_contains($request->data()['text'] ?? '', '+98 912 345 6789')
            && str_contains($request->data()['text'] ?? '', '/admin/reservation-requests'));
        Mail::assertSent(AdminAlert::class);
        $this->assertSame(1, ReservationRequest::count());

        // the contact form as well
        $this->withSession(['locale' => 'en'])->withoutMiddleware(LocaleSessionRedirect::class)
            ->post('/contact', ['name' => 'Sara', 'email' => 'sara@example.com', 'message' => 'I need a quote for 20 blocks.'])
            ->assertSessionHas('success');

        Http::assertSent(fn ($request) => str_contains($request->data()['text'] ?? '', 'پیام جدید از فرم تماس') && str_contains($request->data()['text'] ?? '', 'Sara'));
    }

    public function test_settings_page_shows_the_bot_fields_and_the_test_button_reports_each_channel(): void
    {
        $this->seed([RolePermissionSeeder::class, AdminUserSeeder::class]);
        $this->actingAs(User::where('email', 'admin@example.com')->firstOrFail());

        Http::fake(['*' => Http::response(['ok' => true])]);
        Setting::set('contact_notify_bot_token', 't', 'contact');
        Setting::set('contact_notify_bot_chat_id', '1', 'contact');

        Livewire::test(ManageSettings::class)
            ->assertSuccessful()
            ->assertSee(__('admin.notify_bot_token'))
            ->assertSet('contact_notify_bot_chat_id', '1')
            ->call('sendTestNotification')
            ->assertNotified(__('admin.notify_test_sent'));

        Http::assertSent(fn ($request) => str_contains($request->data()['text'] ?? '', 'تست اعلان سایت'));
    }
}
