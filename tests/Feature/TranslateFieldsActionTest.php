<?php

namespace Tests\Feature;

use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Models\User;
use App\Services\TranslationService;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

/**
 * Exercises the "Translate Automatically" Filament action end-to-end against the
 * real free Google translation endpoint. Uses CreateProduct (a blank, unsaved form)
 * so nothing is ever written to the database.
 */
class TranslateFieldsActionTest extends TestCase
{
    public function test_translate_automatically_fills_empty_fields_without_overwriting_and_regenerates_slug(): void
    {
        $admin = User::where('email', 'admin@en-tradinggroup.com')->firstOrFail();

        $this->actingAs($admin);

        $sourceName = 'محصول تست نمونه اتوماتیک';
        $sourceShortDescription = 'یک توضیح کوتاه برای تست ترجمه خودکار';
        $sourceDescription = '<p>این یک <b>پاراگراف</b> توضیحات کامل محصول است.</p><p>این پاراگراف دوم است.</p>';
        $manualEnglishName = 'Manually Entered English Name';

        $livewire = Livewire::test(CreateProduct::class)
            ->fillForm([
                'name.fa' => $sourceName,
                'short_description.fa' => $sourceShortDescription,
                'description.fa' => $sourceDescription,
                // Simulates an editor who already translated English by hand before clicking the button.
                'name.en' => $manualEnglishName,
            ])
            ->callFormComponentAction('translateAutomaticallyAction', 'translateAutomatically');

        // 1 & 2: the Livewire action ran and the free translation endpoint returned real values.
        $translatedTurkishName = $livewire->get('data.name.tr');
        $this->assertNotEmpty($translatedTurkishName, 'Expected name.tr to be translated');
        $this->assertNotEquals($sourceName, $translatedTurkishName);

        // 3: the pre-existing manual translation must survive untouched.
        $livewire->assertSet('data.name.en', $manualEnglishName);

        // 3: every previously-empty locale got populated, across all field types (text, textarea, richeditor).
        foreach (['hi', 'it', 'ar', 'zh', 'tr'] as $locale) {
            $this->assertNotEmpty($livewire->get("data.name.{$locale}"), "name.{$locale} should be filled");
            $this->assertNotEmpty($livewire->get("data.short_description.{$locale}"), "short_description.{$locale} should be filled");
            $this->assertNotEmpty($livewire->get("data.description.{$locale}"), "description.{$locale} should be filled");
        }

        // RichEditor HTML: paragraph structure preserved (2 separate <p> tags, not glued together).
        $this->assertEquals(2, substr_count($livewire->get('data.description.tr'), '<p>'));

        // 4: slug regenerated per-locale from whichever name ended up in that locale
        // (translated for tr, manually-entered for en) — and only because slug was blank.
        $livewire->assertSet('data.slug.en', Str::slug($manualEnglishName));
        $this->assertEquals(Str::slug($translatedTurkishName), $livewire->get('data.slug.tr'));
    }

    public function test_translate_automatically_does_not_touch_fields_already_filled_in_every_target_locale(): void
    {
        $admin = User::where('email', 'admin@en-tradinggroup.com')->firstOrFail();

        $this->actingAs($admin);

        $manualValues = [
            'name.fa' => 'محصول تست دو',
            'name.en' => 'English Name',
            'name.hi' => 'हिंदी नाम',
            'name.it' => 'Nome Italiano',
            'name.ar' => 'اسم عربي',
            'name.zh' => '中文名称',
            'name.tr' => 'Türkçe İsim',
        ];

        $livewire = Livewire::test(CreateProduct::class)
            ->fillForm($manualValues)
            ->callFormComponentAction('translateAutomaticallyAction', 'translateAutomatically');

        foreach ($manualValues as $path => $value) {
            $livewire->assertSet("data.{$path}", $value);
        }
    }

    public function test_confirm_and_overwrite_replaces_fields_that_already_have_values(): void
    {
        $admin = User::where('email', 'admin@en-tradinggroup.com')->firstOrFail();

        $this->actingAs($admin);

        // Stub the translator so this test doesn't depend on the real network endpoint;
        // it only needs to prove the "overwrite" argument reaches the translation logic.
        $fake = Mockery::mock(TranslationService::class);
        $fake->shouldReceive('translateFields')
            ->andReturnUsing(fn (array $fields, string $target, string $source) => array_map(
                fn ($value) => "[{$target}] {$value}",
                $fields
            ));
        $fake->shouldReceive('translateHtml')
            ->andReturnUsing(fn (?string $html, string $target, string $source) => '<p>[' . $target . '] ' . strip_tags((string) $html) . '</p>');
        $this->app->instance(TranslationService::class, $fake);

        $manualTurkishName = 'Manuel Türkçe İsim';

        $livewire = Livewire::test(CreateProduct::class)
            ->fillForm([
                'name.fa' => 'محصول تست نمونه',
                'name.tr' => $manualTurkishName,
                'slug.tr' => 'manuel-turkce-isim',
            ])
            ->callFormComponentAction('translateAutomaticallyAction', 'translateAutomatically', [], ['overwrite' => true]);

        // The pre-existing manual translation and slug are replaced, unlike the default "Confirm" behavior.
        $this->assertNotEquals($manualTurkishName, $livewire->get('data.name.tr'));
        $this->assertStringContainsString('[tr]', $livewire->get('data.name.tr'));
        $this->assertNotEquals('manuel-turkce-isim', $livewire->get('data.slug.tr'));
    }
}
