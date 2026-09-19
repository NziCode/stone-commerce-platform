<?php

namespace Tests\Feature;

use App\Filament\Resources\EventResource\Pages\EditEvent;
use App\Filament\Resources\EventResource\Pages\ListEvents;
use App\Filament\Resources\EventResource\RelationManagers\GalleryRelationManager;
use App\Models\Event;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Admin tooling for exhibitions (list tabs, edit form, gallery captions, duplicate).
 * Like EventPagesTest it needs a throw-away SQLite database:
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=EventResourceTest
 */
class EventResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: (RefreshDatabase would wipe a real database).');
        }

        $this->seed([LanguageSeeder::class, RolePermissionSeeder::class, AdminUserSeeder::class]);
        $this->actingAs(User::where('email', 'admin@example.com')->firstOrFail());
    }

    private function makeEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'title'  => ['fa' => 'نمایشگاه آزمایشی', 'en' => 'Test Expo'],
            'slug'   => ['fa' => 'test-expo-fa', 'en' => 'test-expo'],
            'status' => 'finished',
            'starts_at' => '2025-10-07 00:00:00',
            'ends_at'   => '2025-10-10 00:00:00',
        ], $overrides));
    }

    public function test_list_tabs_separate_held_current_and_draft(): void
    {
        $held    = $this->makeEvent();
        $current = $this->makeEvent(['slug' => ['en' => 'next'], 'status' => 'upcoming']);
        $draft   = $this->makeEvent(['slug' => ['en' => 'draft'], 'is_published' => false]);

        Livewire::test(ListEvents::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$held, $current, $draft])
            ->set('activeTab', 'held')
            ->assertCanSeeTableRecords([$held, $draft])
            ->assertCanNotSeeTableRecords([$current])
            ->set('activeTab', 'current')
            ->assertCanSeeTableRecords([$current])
            ->assertCanNotSeeTableRecords([$held])
            ->set('activeTab', 'draft')
            ->assertCanSeeTableRecords([$draft])
            ->assertCanNotSeeTableRecords([$held, $current]);
    }

    public function test_edit_form_saves_new_exhibition_fields(): void
    {
        $event = $this->makeEvent();

        Livewire::test(EditEvent::class, ['record' => $event->getRouteKey()])
            ->assertSuccessful()
            ->fillForm([
                'organizer_name.fa' => 'برگزارکننده آزمایشی',
                'date_label.fa'     => 'مهر ۱۴۰۵',
                'is_published'      => false,
                'auto_status'       => false,
                'starts_at'         => null,
                'ends_at'           => null,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $event->refresh();
        $this->assertSame('برگزارکننده آزمایشی', $event->getTranslation('organizer_name', 'fa'));
        $this->assertSame('مهر ۱۴۰۵', $event->getTranslation('date_label', 'fa'));
        $this->assertFalse($event->is_published);
        $this->assertFalse($event->auto_status);
        $this->assertNull($event->starts_at);
    }

    public function test_end_date_cannot_precede_start_date(): void
    {
        $event = $this->makeEvent();

        Livewire::test(EditEvent::class, ['record' => $event->getRouteKey()])
            ->fillForm(['starts_at' => '2025-10-10 10:00:00', 'ends_at' => '2025-10-01 10:00:00'])
            ->call('save')
            ->assertHasFormErrors(['ends_at']);
    }

    public function test_gallery_captions_can_be_edited_per_language(): void
    {
        Storage::fake('public');
        Queue::fake();

        $event = $this->makeEvent();
        $path = sys_get_temp_dir() . '/expo-admin-' . uniqid() . '.png';
        imagepng(imagecreatetruecolor(40, 30), $path);
        $media = $event->addMedia($path)->toMediaCollection('gallery');

        Livewire::test(GalleryRelationManager::class, ['ownerRecord' => $event, 'pageClass' => EditEvent::class])
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$media])
            ->callTableAction('edit', $media, data: ['caption' => ['fa' => 'غرفه ما', 'en' => 'Our booth', 'ar' => '']])
            ->assertHasNoTableActionErrors();

        $caption = $media->fresh()->getCustomProperty('caption');
        $this->assertSame(['fa' => 'غرفه ما', 'en' => 'Our booth'], $caption);
        $this->assertSame('Our booth', $event->fresh()->galleryCaption($media->fresh(), 'en'));
        $this->assertSame('Our booth', $event->fresh()->galleryCaption($media->fresh(), 'tr'), 'falls back to English');
    }

    public function test_duplicate_creates_a_hidden_draft_with_unique_slugs(): void
    {
        $event = $this->makeEvent(['views_count' => 50]);

        Livewire::test(ListEvents::class)
            ->callTableAction('replicate', $event)
            ->assertHasNoTableActionErrors();

        $copy = Event::where('id', '!=', $event->id)->firstOrFail();
        $this->assertFalse($copy->is_published);
        $this->assertSame(0, $copy->views_count);
        $this->assertStringStartsWith('test-expo-copy-', $copy->getTranslation('slug', 'en'));
        $this->assertSame('test-expo', $event->fresh()->getTranslation('slug', 'en'));
    }
}
