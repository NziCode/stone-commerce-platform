<?php

namespace Tests\Feature;

use App\Models\Event;
use Database\Seeders\EventTranslationSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\MenuItemSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * Public exhibitions pages + status sync.
 *
 * Uses RefreshDatabase, so it only runs against a throw-away SQLite database
 * (it would otherwise wipe the developer's local MySQL data):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=EventPagesTest
 */
class EventPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: (RefreshDatabase would wipe a real database).');
        }

        $this->seed([
            LanguageSeeder::class,
            SettingSeeder::class,
            MenuSeeder::class,
            MenuItemSeeder::class,
            TranslationSeeder::class,
            EventTranslationSeeder::class,
        ]);
    }

    private function makeEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'title'       => ['fa' => 'نمایشگاه آزمایشی', 'en' => 'Test Expo'],
            'slug'        => ['fa' => 'test-expo-fa', 'en' => 'test-expo'],
            'description' => ['fa' => 'توضیح فارسی', 'en' => 'English description'],
            'location'    => ['fa' => 'محلات', 'en' => 'Mahallat'],
            'starts_at'   => '2025-10-07 00:00:00',
            'ends_at'     => '2025-10-10 00:00:00',
            'status'      => 'finished',
            'is_published' => true,
        ], $overrides));
    }

    /**
     * Routes are registered once at boot from the boot-time request, so the
     * /{locale} prefix never exists under test: pick the language by session.
     */
    private function visit(string $locale, string $uri)
    {
        return $this->withSession(['locale' => $locale])
            ->withoutMiddleware(LocaleSessionRedirect::class)
            ->get($uri);
    }

    private function addPhoto(Event $event, array $caption): void
    {
        // Conversions need a JPEG-capable GD; the views fall back to the original file anyway.
        Queue::fake();

        $path = sys_get_temp_dir() . '/expo-test-' . uniqid() . '.png';
        $img = imagecreatetruecolor(80, 60);
        imagepng($img, $path);

        $event->addMedia($path)
            ->withCustomProperties(['caption' => $caption])
            ->toMediaCollection('gallery');
    }

    public function test_list_splits_held_and_ongoing_and_hides_drafts(): void
    {
        $this->makeEvent();
        $this->makeEvent([
            'title' => ['fa' => 'دوره هجدهم', 'en' => '18th Edition'],
            'slug'  => ['fa' => 'edition-18-fa', 'en' => 'edition-18'],
            'status' => 'upcoming', 'starts_at' => null, 'ends_at' => null,
            'date_label' => ['en' => 'Mehr 1405 — exact dates to be announced'],
        ]);
        $this->makeEvent([
            'title' => ['en' => 'Hidden Draft'], 'slug' => ['en' => 'hidden-draft'],
            'is_published' => false,
        ]);

        $html = $this->visit('en', '/events')->assertOk()->getContent();

        $this->assertStringContainsString('data-exh-filter="all"', $html);
        $this->assertStringContainsString('Test Expo', $html);
        $this->assertStringContainsString('18th Edition', $html);
        $this->assertStringContainsString('Mehr 1405 — exact dates to be announced', $html);
        $this->assertStringContainsString('7–10 October 2025', $html);
        $this->assertStringNotContainsString('Hidden Draft', $html);

        $this->visit('en', '/events?filter=held')->assertOk()->assertSee('data-exh-filter="held"', false);
        $this->visit('en', '/events?filter=bogus')->assertOk()->assertSee('data-exh-filter="all"', false);
    }

    public function test_persian_list_uses_jalali_dates(): void
    {
        $this->makeEvent();

        $this->visit('fa', '/events')->assertOk()->assertSee('۱۵ تا ۱۸ مهر ۱۴۰۴');
    }

    public function test_detail_shows_gallery_with_localized_captions(): void
    {
        Storage::fake('public');
        $event = $this->makeEvent();
        $this->addPhoto($event, ['fa' => 'غرفه ما', 'en' => 'Our booth']);
        $this->addPhoto($event, ['fa' => 'بازدیدکنندگان']);

        $en = $this->visit('en', '/events/test-expo')->assertOk()->getContent();
        $this->assertStringContainsString('data-caption="Our booth"', $en);
        $this->assertSame(2, substr_count($en, 'data-exh-lightbox'));
        $this->assertStringContainsString('2 photos', $en);

        $fa = $this->visit('fa', '/events/test-expo-fa')->assertOk()->getContent();
        $this->assertStringContainsString('data-caption="غرفه ما"', $fa);
        $this->assertStringContainsString('۲ عکس', $fa);
    }

    public function test_finished_event_without_photos_shows_coming_soon_note(): void
    {
        $this->makeEvent();

        $this->visit('en', '/events/test-expo')->assertOk()
            ->assertSee('Photos from this exhibition will be published on this page soon.');
    }

    public function test_unpublished_event_is_not_reachable(): void
    {
        $this->makeEvent(['slug' => ['en' => 'secret'], 'is_published' => false]);

        $this->visit('en', '/events/secret')->assertNotFound();
    }

    public function test_sync_command_moves_events_by_date_and_respects_auto_status_flag(): void
    {
        $past    = $this->makeEvent(['slug' => ['en' => 'past'],    'status' => 'upcoming', 'starts_at' => now()->subDays(10), 'ends_at' => now()->subDays(5)]);
        $running = $this->makeEvent(['slug' => ['en' => 'running'], 'status' => 'upcoming', 'starts_at' => now()->subDay(),   'ends_at' => now()->addDays(2)]);
        $future  = $this->makeEvent(['slug' => ['en' => 'future'],  'status' => 'finished', 'starts_at' => now()->addDays(30), 'ends_at' => now()->addDays(33)]);
        $manual  = $this->makeEvent(['slug' => ['en' => 'manual'],  'status' => 'upcoming', 'starts_at' => now()->subDays(10), 'ends_at' => now()->subDays(5), 'auto_status' => false]);
        $undated = $this->makeEvent(['slug' => ['en' => 'undated'], 'status' => 'upcoming', 'starts_at' => null, 'ends_at' => null]);
        $cancelled = $this->makeEvent(['slug' => ['en' => 'cancelled'], 'status' => 'cancelled', 'starts_at' => now()->subDays(10), 'ends_at' => now()->subDays(5)]);

        Artisan::call('events:sync-status');

        $this->assertSame('finished', $past->fresh()->status);
        $this->assertSame('ongoing', $running->fresh()->status);
        $this->assertSame('upcoming', $future->fresh()->status);
        $this->assertSame('upcoming', $manual->fresh()->status);
        $this->assertSame('upcoming', $undated->fresh()->status);
        $this->assertSame('cancelled', $cancelled->fresh()->status);
    }

    public function test_missing_slugs_are_generated_when_saving(): void
    {
        $event = Event::create([
            'title' => ['en' => 'Big Stone Fair 2027', 'fa' => 'نمایشگاه بزرگ'],
            'status' => 'upcoming',
        ]);

        $this->assertSame('big-stone-fair-2027', $event->getTranslation('slug', 'en'));
        $this->assertNotEmpty($event->getTranslation('slug', 'fa', false));
    }
}
