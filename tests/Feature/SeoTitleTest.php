<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Setting;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\MenuItemSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * Exactly one <title> per page (the browser used the first one — the site-wide default — and ignored the
 * page's own), and no placeholder texts from the SEO package on pages without a description.
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=SeoTitleTest
 */
class SeoTitleTest extends TestCase
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
            TranslationSeeder::class, RolePermissionSeeder::class,
        ]);

        Setting::set('site_name', json_encode(['en' => 'Brand Name', 'fa' => 'نام برند'], JSON_UNESCAPED_UNICODE), 'general');
        Setting::set('meta_title', json_encode(['en' => 'Brand Name | Default Tagline', 'fa' => 'نام برند | شعار'], JSON_UNESCAPED_UNICODE), 'seo');
        Setting::set('meta_description', json_encode(['en' => 'The real site description.', 'fa' => 'توضیح واقعی سایت.'], JSON_UNESCAPED_UNICODE), 'seo');
    }

    protected function tearDown(): void
    {
        while (ob_get_level() > $this->obLevel) {
            ob_end_clean();
        }

        parent::tearDown();
    }

    private function visit(string $locale, string $uri): string
    {
        return $this->withSession(['locale' => $locale])
            ->withoutMiddleware(LocaleSessionRedirect::class)
            ->get($uri)->assertOk()->getContent();
    }

    /** @return list<string> */
    private function titles(string $html): array
    {
        preg_match_all('#<title>(.*?)</title>#su', $html, $m);

        return array_map(fn ($t) => trim(html_entity_decode($t, ENT_QUOTES)), $m[1]);
    }

    public function test_a_page_without_a_controller_title_shows_its_own_view_title_not_the_site_default(): void
    {
        $titles = $this->titles($this->visit('en', '/contact'));

        $this->assertCount(1, $titles, 'one <title> only');
        $this->assertStringContainsString('Brand Name', $titles[0]);
        $this->assertNotSame('Brand Name | Default Tagline', $titles[0], 'the view\'s "Contact — Brand Name" wins over the site-wide default');
        $this->assertStringContainsString('Contact', $titles[0]);
    }

    public function test_persian_pages_get_their_own_title_too(): void
    {
        $titles = $this->titles($this->visit('fa', '/login'));

        $this->assertCount(1, $titles);
        $this->assertStringContainsString('نام برند', $titles[0]);
        $this->assertNotSame('نام برند | شعار', $titles[0]);
    }

    public function test_a_title_set_by_the_controller_is_kept(): void
    {
        $stone = Product::create([
            'name' => ['en' => 'Titanium Block 1001'], 'slug' => ['en' => 'titanium-1001'], 'sku' => 'T-1',
            'status' => 'available', 'is_active' => true, 'price_on_request' => true,
            'meta_title' => ['en' => 'Custom SEO title for the stone'],
        ]);

        $titles = $this->titles($this->visit('en', '/products/' . $stone->getTranslation('slug', 'en')));

        $this->assertCount(1, $titles);
        $this->assertStringContainsString('Custom SEO title for the stone', $titles[0], 'a curated product title is not replaced by the generic view title');
    }

    public function test_the_home_page_carries_the_full_brand_title(): void
    {
        $titles = $this->titles($this->visit('en', '/'));

        $this->assertSame(['Brand Name | Default Tagline'], $titles);

        $this->assertSame(['نام برند | شعار'], $this->titles($this->visit('fa', '/')));

        $this->assertStringContainsString('"@type":"Organization","name":"Brand Name"', $this->visit('en', '/'), 'the organisation keeps its short name');
    }

    public function test_default_description_comes_from_settings_and_no_placeholder_text_leaks(): void
    {
        $html = $this->visit('en', '/contact');

        $this->assertStringContainsString('The real site description.', $html);
        $this->assertMatchesRegularExpression('/"@type":"Organization".*"description":"The real site description\."/s', $html, 'the structured-data organisation gets it too');
        $this->assertStringNotContainsString('Genki Dama', $html);
        $this->assertStringNotContainsString('Over 9000', $html);
        $this->assertStringNotContainsString('Stone Commerce', $html);

        // even with the description setting emptied the package's stock text must not come back
        Setting::set('meta_description', json_encode([]), 'seo');
        $bare = $this->visit('en', '/contact');
        $this->assertStringNotContainsString('Genki Dama', $bare);
        $this->assertStringNotContainsString('Over 9000', $bare);
    }
}
