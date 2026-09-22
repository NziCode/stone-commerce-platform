<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use Database\Seeders\InquiryTranslationSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\MainCategoryTranslationSeeder;
use Database\Seeders\MenuItemSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\MobileUxTranslationSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\SettingsHelpTranslationSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * The site logo / favicon setting: falls back to plain text and the theme's default icon when
 * unset, shows the uploaded image (turned white by CSS on the dark header/footer/mobile menu) and
 * the real favicon once set, is editable from Settings, and feeds the Organization JSON-LD.
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=BrandingTest
 */
class BrandingTest extends TestCase
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
            TranslationSeeder::class, MobileUxTranslationSeeder::class, MainCategoryTranslationSeeder::class,
            InquiryTranslationSeeder::class, SettingsHelpTranslationSeeder::class, RolePermissionSeeder::class,
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

    public function test_with_no_logo_set_the_site_falls_back_to_text_and_the_default_favicon(): void
    {
        $html = $this->visit('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#<a href="[^"]*" class="mt-logo">\s*<span class="mt-logo-text">#', $html, 'header falls back to text');
        $this->assertStringContainsString('rel="shortcut icon" type="image/x-icon" href="' . asset('assets/images/favicon.ico') . '"', $html);
        $this->assertStringNotContainsString('apple-touch-icon', $html);
        $this->assertStringNotContainsString('mt-logo-invert', $html);
    }

    public function test_once_a_logo_and_favicon_are_set_they_replace_the_text_and_default_icon_everywhere(): void
    {
        Setting::set('site_logo', 'assets/images/brand-en/logo.png', 'general');
        Setting::set('site_favicon', 'assets/images/brand-en/favicon.png', 'general');

        $html = $this->visit('/')->assertOk()->getContent();

        // header: an inverted (white-on-dark) logo image, not the text fallback
        $this->assertMatchesRegularExpression(
            '#<a href="[^"]*" class="mt-logo">\s*<img src="' . preg_quote(asset('assets/images/brand-en/logo.png'), '#') . '"[^>]*class="mt-logo-invert"#',
            $html
        );

        // footer: the same treatment
        $this->assertStringContainsString('class="mt-logo-invert"', $html);
        $footerLogoCount = substr_count($html, 'mt-logo-invert');
        $this->assertGreaterThanOrEqual(2, $footerLogoCount, 'header and footer both show the inverted logo');

        // favicon + apple touch icon, the old default is gone
        $this->assertStringContainsString('rel="icon" type="image/png" href="' . asset('assets/images/brand-en/favicon.png') . '"', $html);
        $this->assertStringContainsString('rel="apple-touch-icon" href="' . asset('assets/images/brand-en/favicon.png') . '"', $html);
        $this->assertStringNotContainsString('assets/images/favicon.ico', $html);

        // the JSON-LD Organization schema carries the mark too
        $this->assertMatchesRegularExpression('#"logo":\s*"' . preg_quote(asset('assets/images/brand-en/logo.png'), '#') . '"#', $html);
    }

    public function test_the_mobile_offcanvas_menu_shows_the_logo_once_it_is_set(): void
    {
        Setting::set('site_logo', 'assets/images/brand-en/logo.png', 'general');

        $html = $this->visit('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#offcanvas-top[^"]*"[^>]*>\s*<img src="[^"]*brand-en/logo\.png"[^>]*class="mt-logo-invert"#s', $html);
    }

    public function test_the_logo_and_favicon_can_be_edited_from_settings(): void
    {
        Livewire::test(ManageSettings::class)
            ->assertSuccessful()
            ->assertSet('site_logo', '')
            ->set('site_logo', 'assets/images/brand-en/logo.png')
            ->set('site_favicon', 'assets/images/brand-en/favicon.png')
            ->call('save', 'general')
            ->assertHasNoErrors();

        $this->assertSame('assets/images/brand-en/logo.png', Setting::get('site_logo'));
        $this->assertSame('assets/images/brand-en/favicon.png', Setting::get('site_favicon'));
    }
}
