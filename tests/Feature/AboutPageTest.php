<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use App\Support\AboutPage;
use Database\Seeders\AboutPageTranslationSeeder;
use Database\Seeders\EventTranslationSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\MenuItemSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * The "About us" page template and the owner profile template.
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=AboutPageTest
 */
class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: (RefreshDatabase would wipe a real database).');
        }

        $this->seed([
            LanguageSeeder::class, SettingSeeder::class, MenuSeeder::class, MenuItemSeeder::class,
            TranslationSeeder::class, EventTranslationSeeder::class, AboutPageTranslationSeeder::class,
        ]);
    }

    private function visit(string $locale, string $uri)
    {
        return $this->withSession(['locale' => $locale])
            ->withoutMiddleware(LocaleSessionRedirect::class)
            ->get($uri);
    }

    private function enc(array $v): string
    {
        return json_encode($v, JSON_UNESCAPED_UNICODE);
    }

    private function makeAboutPage(array $overrides = []): Page
    {
        return Page::create(array_merge([
            'title'    => ['fa' => 'درباره گروه', 'en' => 'About the Group'],
            'slug'     => ['fa' => 'about', 'en' => 'about'],
            'excerpt'  => ['fa' => 'خلاصه فارسی', 'en' => 'A short lead sentence'],
            'content'  => ['fa' => '<p>متن داستان</p>', 'en' => '<p>The story paragraph</p>'],
            'template' => 'about',
            'is_active' => true,
        ], $overrides));
    }

    private function seedSettings(): void
    {
        Setting::set('about_years', '15');
        Setting::set('about_title', $this->enc(['fa' => 'تیتر داستان', 'en' => 'Story headline']), 'about');
        Setting::set('about_feature_1', $this->enc(['fa' => 'دسترسی مستقیم: بدون واسطه', 'en' => 'Direct Access: no middlemen']), 'about');
        Setting::set('about_feature_2', $this->enc(['fa' => 'سورت دقیق: بدون ترک', 'en' => 'Precise Sorting: crack free']), 'about');
        Setting::set('site_address', $this->enc(['en' => "North Office: North industrial town\nSouth Office: South Street 24"]), 'general');
        Setting::set('site_phone', '989140000000');
        Setting::set('site_email', 'sales@example.com');
    }

    private function addPhoto(Event $event, string $caption): void
    {
        Queue::fake();

        $path = sys_get_temp_dir() . '/about-test-' . uniqid() . '.png';
        imagepng(imagecreatetruecolor(60, 40), $path);

        $event->addMedia($path)->withCustomProperties(['caption' => ['en' => $caption]])->toMediaCollection('gallery');
    }

    public function test_feature_settings_are_split_into_heading_and_text(): void
    {
        $this->assertSame(['Direct Access', 'Middleman-free loading'], AboutPage::splitFeature('Direct Access: Middleman-free loading'));
        $this->assertSame(['直通矿山', '无中间商'], AboutPage::splitFeature('直通矿山：无中间商'));
        $this->assertSame([null, 'No colon here at all'], AboutPage::splitFeature('No colon here at all'));
        $this->assertSame([null, ''], AboutPage::splitFeature(''));
    }

    public function test_about_page_renders_every_data_driven_section(): void
    {
        Storage::fake('public');
        $this->seedSettings();
        $this->makeAboutPage();
        Page::create([
            'title' => ['fa' => 'مالک آزمایشی', 'en' => 'Jane Founder'], 'slug' => ['en' => 'jane-founder'],
            'excerpt' => ['en' => 'Owner, the Group'], 'content' => ['en' => '<p>Bio text</p>'],
            'template' => 'profile', 'is_active' => true,
        ]);

        $event = Event::create([
            'title' => ['en' => 'Big Expo'], 'slug' => ['en' => 'big-expo'], 'status' => 'finished',
            'starts_at' => '2025-10-07', 'ends_at' => '2025-10-10', 'is_published' => true,
        ]);
        foreach (['One', 'Two', 'Three', 'Four', 'Five', 'Six'] as $caption) {
            $this->addPhoto($event, $caption);
        }

        Product::create([
            'name' => ['en' => 'Titanium Block 1001'], 'slug' => ['en' => 'titanium-1001'],
            'status' => 'available', 'is_active' => true,
        ]);

        $html = $this->visit('en', '/about')->assertOk()->getContent();

        $this->assertStringContainsString('A short lead sentence', $html);          // hero lead
        $this->assertStringContainsString('Story headline', $html);                 // about_title
        $this->assertStringContainsString('The story paragraph', $html);            // page content
        $this->assertStringContainsString('Direct Access', $html);                  // pillar heading
        $this->assertStringContainsString('no middlemen', $html);                   // pillar text
        $this->assertStringContainsString('Jane Founder', $html);                    // owner card
        $this->assertStringContainsString('ab-monogram', $html);                    // no portrait uploaded → monogram
        $this->assertStringContainsString('>JF<', $html);
        $this->assertStringContainsString('Titanium Block 1001', $html);            // product showcase
        $this->assertStringContainsString('Big Expo', $html);                       // exhibition strip
        $this->assertStringContainsString('North Office', $html);                   // offices from the address setting
        $this->assertStringContainsString('South Office', $html);
        $this->assertStringContainsString('tel:+989140000000', $html);
        $this->assertStringContainsString('Looking for the right block for your project?', $html);
        $this->assertStringNotContainsString('messages.ab_', $html, 'no untranslated keys');
    }

    public function test_blocks_without_data_are_hidden(): void
    {
        $this->makeAboutPage();

        $html = $this->visit('en', '/about')->assertOk()->getContent();

        $this->assertStringContainsString('A short lead sentence', $html);
        $this->assertStringNotContainsString('ab-founder-card', $html);
        $this->assertStringNotContainsString('ab-stones-grid', $html);
        $this->assertStringNotContainsString('ab-field', $html);
        $this->assertStringNotContainsString('ab-pillars', $html);
    }

    public function test_persian_about_page_uses_persian_digits(): void
    {
        $this->seedSettings();
        $this->makeAboutPage();

        $this->visit('fa', '/about')->assertOk()->assertSee('۱۵+', false);
    }

    public function test_profile_page_renders_and_generic_pages_are_unaffected(): void
    {
        Page::create([
            'title' => ['en' => 'Jane Founder'], 'slug' => ['en' => 'jane-founder'],
            'excerpt' => ['en' => 'Owner'], 'content' => ['en' => '<p>Bio text</p>'],
            'template' => 'profile', 'is_active' => true,
        ]);
        Page::create([
            'title' => ['en' => 'Shipping'], 'slug' => ['en' => 'shipping'],
            'content' => ['en' => '<p>Shipping details</p>'], 'template' => 'sidebar', 'is_active' => true,
        ]);

        $this->visit('en', '/jane-founder')->assertOk()->assertSee('Bio text', false)->assertSee('ab-founder-card', false);
        $this->visit('en', '/shipping')->assertOk()->assertSee('Shipping details', false)->assertDontSee('ab-hero', false);
    }
}
