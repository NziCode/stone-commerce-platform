<?php

namespace Tests\Feature;

use App\Filament\Resources\TranslationCacheResource\Pages\ListTranslationCaches;
use App\Models\TranslationCache;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Smoke-tests the read-only admin browser for the translation cache: listing,
 * search, locale filtering, and the delete/bulk-delete actions used to force
 * a string to be re-translated.
 */
class TranslationCacheResourceTest extends TestCase
{
    protected function tearDown(): void
    {
        TranslationCache::query()->where('source_text', 'like', 'TCRes::%')->delete();

        parent::tearDown();
    }

    protected function actingAsAdmin(): User
    {
        $admin = User::where('email', 'admin@en-tradinggroup.com')->firstOrFail();
        $this->actingAs($admin);

        return $admin;
    }

    public function test_admin_can_view_and_search_the_translation_cache_list(): void
    {
        $this->actingAsAdmin();

        $match = TranslationCache::create([
            'source_hash' => TranslationCache::hash('en', 'fa', 'text', 'TCRes::Findable Text'),
            'source_locale' => 'en',
            'target_locale' => 'fa',
            'context' => 'text',
            'source_text' => 'TCRes::Findable Text',
            'translated_text' => 'متن قابل جستجو',
        ]);

        $other = TranslationCache::create([
            'source_hash' => TranslationCache::hash('en', 'ar', 'text', 'TCRes::Unrelated Text'),
            'source_locale' => 'en',
            'target_locale' => 'ar',
            'context' => 'text',
            'source_text' => 'TCRes::Unrelated Text',
            'translated_text' => 'نص غير ذي صلة',
        ]);

        Livewire::test(ListTranslationCaches::class)
            ->assertCanSeeTableRecords([$match, $other])
            ->searchTable('Findable')
            ->assertCanSeeTableRecords([$match])
            ->assertCanNotSeeTableRecords([$other])
            ->searchTable('')
            ->filterTable('target_locale', 'ar')
            ->assertCanSeeTableRecords([$other])
            ->assertCanNotSeeTableRecords([$match]);
    }

    public function test_admin_can_delete_a_cached_translation_to_force_re_translation(): void
    {
        $this->actingAsAdmin();

        $entry = TranslationCache::create([
            'source_hash' => TranslationCache::hash('en', 'fa', 'text', 'TCRes::Deletable Text'),
            'source_locale' => 'en',
            'target_locale' => 'fa',
            'context' => 'text',
            'source_text' => 'TCRes::Deletable Text',
            'translated_text' => 'قابل حذف',
        ]);

        Livewire::test(ListTranslationCaches::class)
            ->callTableAction('delete', $entry);

        $this->assertModelMissing($entry);
    }

    public function test_the_resource_has_no_create_or_edit_routes(): void
    {
        $this->actingAsAdmin();

        $this->assertFalse(\App\Filament\Resources\TranslationCacheResource::canCreate());
    }
}
