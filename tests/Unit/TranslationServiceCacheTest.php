<?php

namespace Tests\Unit;

use App\Models\TranslationCache;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Exercises the local DB-backed translation cache in App\Models\TranslationCache:
 * persistence of successful translations, dedupe of duplicate source text, reuse
 * across separate "runs", and fallback when the external translator is unavailable.
 *
 * Uses distinct, prefixed source strings per test and cleans them up afterwards so
 * the tests stay reusable against the shared dev database (no RefreshDatabase here,
 * matching the rest of this suite).
 */
class TranslationServiceCacheTest extends TestCase
{
    protected function tearDown(): void
    {
        TranslationCache::query()->where('source_text', 'like', 'TSCache::%')->delete();

        parent::tearDown();
    }

    protected function fakeGoogleTranslate(): void
    {
        Http::fake(function ($request) {
            $query = $request->data()['q'] ?? '';

            return Http::response([[[
                'TR:' . $query, $query, null, null, 1,
            ]]]);
        });
    }

    public function test_translate_stores_a_successful_result_in_the_database_cache(): void
    {
        $this->fakeGoogleTranslate();

        $result = (new TranslationService())->translate('TSCache::Hello World', 'fa', 'en');

        $this->assertSame('TR:TSCache::Hello World', $result);
        $this->assertDatabaseHas('translation_caches', [
            'source_locale' => 'en',
            'target_locale' => 'fa',
            'source_text' => 'TSCache::Hello World',
            'translated_text' => 'TR:TSCache::Hello World',
        ]);

        Http::assertSentCount(1);
    }

    public function test_translate_fields_translates_duplicate_source_text_only_once(): void
    {
        $this->fakeGoogleTranslate();

        $results = (new TranslationService())->translateFields([
            'field_a' => 'TSCache::Repeated Text',
            'field_b' => 'TSCache::Repeated Text',
            'field_c' => 'TSCache::Unique Text',
        ], 'fa', 'en');

        $this->assertSame($results['field_a'], $results['field_b']);
        $this->assertNotSame($results['field_a'], $results['field_c']);

        // Only the two distinct texts were ever sent to the translator, in one request.
        Http::assertSentCount(1);

        $this->assertSame(1, TranslationCache::query()
            ->where('source_text', 'TSCache::Repeated Text')
            ->where('source_locale', 'en')
            ->where('target_locale', 'fa')
            ->count());
    }

    public function test_translate_reuses_a_cached_result_from_a_previous_run_without_calling_the_service(): void
    {
        TranslationCache::store('en', 'fa', 'TSCache::Already Translated', 'قبلا ترجمه‌شده');

        Http::fake();

        $result = (new TranslationService())->translate('TSCache::Already Translated', 'fa', 'en');

        $this->assertSame('قبلا ترجمه‌شده', $result);
        Http::assertNothingSent();
    }

    public function test_translate_falls_back_to_cache_when_the_external_service_is_unavailable(): void
    {
        TranslationCache::store('en', 'fa', 'TSCache::Fallback Text', 'ترجمه بازگشتی');

        // Simulate the free endpoint being rate-limited / down for every request.
        Http::fake([
            'translate.googleapis.com/*' => Http::response(null, 429),
        ]);

        $result = (new TranslationService())->translate('TSCache::Fallback Text', 'fa', 'en');

        $this->assertSame('ترجمه بازگشتی', $result);
        Http::assertNothingSent();
    }

    public function test_translate_returns_null_for_uncached_text_when_the_service_is_unavailable(): void
    {
        Http::fake([
            'translate.googleapis.com/*' => Http::response(null, 429),
        ]);

        $result = (new TranslationService())->translate('TSCache::Never Cached Before', 'fa', 'en');

        $this->assertNull($result);
        $this->assertDatabaseMissing('translation_caches', [
            'source_text' => 'TSCache::Never Cached Before',
        ]);
    }
}
