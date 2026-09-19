<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Setting;
use App\Support\GuidePage;
use Database\Seeders\AboutPageTranslationSeeder;
use Database\Seeders\GuidePageTranslationSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\MenuItemSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

/**
 * The step-by-step "guide" page template (buying guide, payment process).
 * Needs a throw-away SQLite database (RefreshDatabase):
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=GuidePageTest
 */
class GuidePageTest extends TestCase
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
            TranslationSeeder::class, AboutPageTranslationSeeder::class, GuidePageTranslationSeeder::class,
        ]);
    }

    private function visit(string $locale, string $uri)
    {
        return $this->withSession(['locale' => $locale])
            ->withoutMiddleware(LocaleSessionRedirect::class)
            ->get($uri);
    }

    private function makeGuide(array $overrides = []): Page
    {
        return Page::create(array_merge([
            'title'    => ['fa' => 'راهنمای آزمایشی', 'en' => 'Test Guide'],
            'slug'     => ['fa' => 'test-guide', 'en' => 'test-guide'],
            'excerpt'  => ['en' => 'A one-line summary'],
            'content'  => ['en' => '<p>Intro text.</p><ol>'
                . '<li><strong>Pick a stone</strong> — browse the products.</li>'
                . '<li><strong>Call us</strong>: we answer the phone.</li>'
                . '<li><strong>Pay</strong> — prepayment first.</li></ol><p>Closing note.</p>'],
            'template'  => 'guide',
            'is_active' => true,
        ], $overrides));
    }

    public function test_parse_splits_intro_steps_and_note(): void
    {
        $parts = GuidePage::parse('<p>Intro text.</p><ol><li><strong>Pick a stone</strong> — browse <em>them</em>.</li>'
            . '<li><strong>Call us</strong>: we answer.</li><li>No bold title here</li></ol><p>Closing note.</p>');

        $this->assertSame('<p>Intro text.</p>', $parts['intro']);
        $this->assertSame('<p>Closing note.</p>', $parts['outro']);
        $this->assertCount(3, $parts['steps']);

        $this->assertSame('Pick a stone', $parts['steps'][0]['title']);
        $this->assertSame('browse <em>them</em>.', $parts['steps'][0]['body'], 'the dash after the title is dropped');
        $this->assertSame('we answer.', $parts['steps'][1]['body'], 'so is a colon');
        $this->assertNull($parts['steps'][2]['title']);
        $this->assertSame('No bold title here', $parts['steps'][2]['body']);
    }

    public function test_parse_handles_editor_markup_and_plain_content(): void
    {
        // TipTap-style lists wrap the item text in a <p>
        $parts = GuidePage::parse('<ol><li><p><strong>Step</strong> — text</p></li></ol>');
        $this->assertSame('Step', $parts['steps'][0]['title']);
        $this->assertStringContainsString('text', $parts['steps'][0]['body']);

        $plain = GuidePage::parse('<p>Only prose</p>');
        $this->assertSame('<p>Only prose</p>', $plain['intro']);
        $this->assertSame([], $plain['steps']);

        $this->assertSame(['intro' => '', 'steps' => [], 'outro' => ''], GuidePage::parse(''));
    }

    public function test_parse_keeps_persian_text_intact(): void
    {
        $parts = GuidePage::parse('<ol><li><strong>سنگ را انتخاب کنید</strong> — از بخش «محصولات».</li></ol>');

        $this->assertSame('سنگ را انتخاب کنید', $parts['steps'][0]['title']);
        $this->assertSame('از بخش «محصولات».', $parts['steps'][0]['body']);
    }

    public function test_whatsapp_url_is_built_from_settings(): void
    {
        $this->assertNull(GuidePage::whatsappUrl('hi'), 'nothing configured');

        Setting::set('site_phone', '989140000000');
        $this->assertSame('https://wa.me/989140000000?text=' . rawurlencode('Hello there'), GuidePage::whatsappUrl('Hello there'));

        Setting::set('site_phone', '09140000000');
        $this->assertSame('https://wa.me/989140000000', GuidePage::whatsappUrl(''), 'local 09… number gets the country code');

        Setting::set('social_whatsapp', '+98 915 111 2233', 'social');
        $this->assertStringStartsWith('https://wa.me/989151112233?text=', GuidePage::whatsappUrl('x'), 'the social setting wins');

        Setting::set('social_whatsapp', 'https://wa.me/447700900123', 'social');
        $this->assertSame('https://wa.me/447700900123?text=x', GuidePage::whatsappUrl('x'));

        Setting::set('social_whatsapp', 'https://chat.whatsapp.com/AbCdEf', 'social');
        $this->assertSame('https://chat.whatsapp.com/AbCdEf', GuidePage::whatsappUrl('x'), 'group invites take no text');
    }

    public function test_guide_page_renders_steps_and_contact_panel(): void
    {
        Setting::set('site_phone', '989140000000');
        $this->makeGuide();

        $html = $this->visit('en', '/test-guide')->assertOk()->getContent();

        $this->assertStringContainsString('A one-line summary', $html);
        $this->assertStringContainsString('Intro text.', $html);
        $this->assertSame(3, substr_count($html, 'class="gd-step"'), 'one card per step');
        $this->assertStringContainsString('Pick a stone', $html);
        $this->assertStringContainsString('Closing note.', $html);
        $this->assertStringContainsString('https://wa.me/989140000000?text=', $html);
        $this->assertStringContainsString(rawurlencode('Hello, I have chosen a stone'), $html);
        $this->assertStringContainsString('tel:+989140000000', $html);
        $this->assertStringContainsString('Talk to our sales team', $html);
        $this->assertStringNotContainsString('messages.gd_', $html, 'no untranslated keys');
    }

    public function test_persian_steps_use_persian_digits_and_no_phone_hides_the_buttons(): void
    {
        $this->makeGuide([
            'excerpt' => ['fa' => 'خلاصه'],
            'content' => ['fa' => '<ol><li><strong>یک</strong> — الف</li><li><strong>دو</strong> — ب</li></ol>'],
        ]);

        $html = $this->visit('fa', '/test-guide')->assertOk()->getContent();

        $this->assertStringContainsString('>۱</span>', $html);
        $this->assertStringContainsString('>۲</span>', $html);
        $this->assertStringNotContainsString('wa.me', $html, 'no phone configured → no WhatsApp button');
        $this->assertStringNotContainsString('gd-btn-call', $html);
        $this->assertStringContainsString('gd-btn-stones', $html, 'the products button is always there');
    }
}
