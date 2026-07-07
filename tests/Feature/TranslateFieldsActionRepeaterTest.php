<?php

namespace Tests\Feature;

use App\Filament\Resources\MenuResource\Pages\CreateMenu;
use App\Models\User;
use App\Services\TranslationService;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

/**
 * Exercises the "Translate Automatically" action against a real Repeater field
 * (MenuResource's `allItems`, whose `label` is a translatable field per item).
 * Stubs TranslationService so the assertions are deterministic and don't depend
 * on the real network endpoint.
 */
class TranslateFieldsActionRepeaterTest extends TestCase
{
    protected function fakeTranslator(): void
    {
        $fake = Mockery::mock(TranslationService::class);
        $fake->shouldReceive('translateFields')
            ->andReturnUsing(fn (array $fields, string $target, string $source) => array_map(
                fn ($value) => "[{$target}] {$value}",
                $fields
            ));
        $this->app->instance(TranslationService::class, $fake);
    }

    public function test_translate_automatically_recurses_into_repeater_items_and_preserves_structure(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->actingAs($admin);

        $this->fakeTranslator();

        $livewire = Livewire::test(CreateMenu::class)
            ->fillForm([
                'name' => 'Repeater Test Menu',
                'location' => 'header',
                'allItems' => [
                    'item-one' => [
                        'label' => ['fa' => 'خانه'],
                        'url' => '/',
                        'target' => '_self',
                        'sort_order' => 0,
                        'is_active' => true,
                    ],
                    'item-two' => [
                        'label' => ['fa' => 'محصولات'],
                        'url' => '/products',
                        'target' => '_self',
                        'sort_order' => 1,
                        'is_active' => true,
                    ],
                ],
            ])
            ->callFormComponentAction('translateAutomaticallyAction', 'translateAutomatically');

        // Each repeater item's label was translated individually, keyed to its own item.
        $livewire->assertSet('data.allItems.item-one.label.en', '[en] خانه');
        $livewire->assertSet('data.allItems.item-two.label.en', '[en] محصولات');

        // The source-locale values and every non-translatable field are untouched.
        $livewire->assertSet('data.allItems.item-one.label.fa', 'خانه');
        $livewire->assertSet('data.allItems.item-two.label.fa', 'محصولات');
        $livewire->assertSet('data.allItems.item-one.url', '/');
        $livewire->assertSet('data.allItems.item-two.url', '/products');
    }

    public function test_translate_automatically_does_not_overwrite_repeater_labels_already_filled(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->actingAs($admin);

        $this->fakeTranslator();

        $manualEnglishLabel = 'Manually Entered Home';

        $livewire = Livewire::test(CreateMenu::class)
            ->fillForm([
                'name' => 'Repeater No Overwrite Menu',
                'location' => 'footer',
                'allItems' => [
                    'item-one' => [
                        'label' => ['fa' => 'خانه', 'en' => $manualEnglishLabel],
                        'url' => '/',
                        'target' => '_self',
                        'sort_order' => 0,
                        'is_active' => true,
                    ],
                ],
            ])
            ->callFormComponentAction('translateAutomaticallyAction', 'translateAutomatically');

        $livewire->assertSet('data.allItems.item-one.label.en', $manualEnglishLabel);
    }
}
