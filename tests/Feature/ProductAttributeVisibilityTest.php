<?php

namespace Tests\Feature;

use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Regression test for: deactivating an Attribute (is_active = false) must hide
 * it from the product create/edit repeater and from the public product pages,
 * without ever deleting ProductAttribute rows that already reference it.
 *
 * Runs against the shared dev database (no RefreshDatabase) — every fixture
 * is uniquely prefixed and removed again in tearDown().
 */
class ProductAttributeVisibilityTest extends TestCase
{
    private ?Product $product = null;
    private ?Attribute $activeAttribute = null;
    private ?Attribute $inactiveAttribute = null;

    protected function tearDown(): void
    {
        $this->product?->forceDelete();
        $this->activeAttribute?->delete();
        $this->inactiveAttribute?->delete();

        parent::tearDown();
    }

    private function actingAsAdmin(): User
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->actingAs($admin);

        return $admin;
    }

    private function makeFixtures(): void
    {
        $suffix = uniqid('pav_');

        $this->activeAttribute = Attribute::create([
            'key' => "{$suffix}_active",
            'label' => ['fa' => 'رنگ فعال', 'en' => 'Active Color'],
            'type' => 'text',
            'is_active' => true,
            'is_filterable' => true,
        ]);

        $this->inactiveAttribute = Attribute::create([
            'key' => "{$suffix}_inactive",
            'label' => ['fa' => 'رنگ غیرفعال', 'en' => 'Inactive Color'],
            'type' => 'text',
            'is_active' => false,
            'is_filterable' => true,
        ]);

        $this->product = Product::create([
            'name' => ['fa' => "محصول تست {$suffix}", 'en' => "Test Product {$suffix}"],
            'slug' => ['fa' => "{$suffix}-fa", 'en' => "{$suffix}-en"],
        ]);

        ProductAttribute::create([
            'product_id' => $this->product->id,
            'attribute_id' => $this->activeAttribute->id,
            'value' => ['fa' => 'قرمز', 'en' => 'Red'],
            'sort_order' => 0,
        ]);

        ProductAttribute::create([
            'product_id' => $this->product->id,
            'attribute_id' => $this->inactiveAttribute->id,
            'value' => ['fa' => 'آبی', 'en' => 'Blue'],
            'sort_order' => 1,
        ]);
    }

    public function test_attributes_relation_hides_inactive_attributes(): void
    {
        $this->makeFixtures();

        $loaded = $this->product->fresh()->attributes;

        $this->assertCount(1, $loaded);
        $this->assertTrue($loaded->contains('attribute_id', $this->activeAttribute->id));
        $this->assertFalse($loaded->contains('attribute_id', $this->inactiveAttribute->id));
    }

    public function test_filterable_attributes_relation_hides_inactive_attributes(): void
    {
        $this->makeFixtures();

        $loaded = $this->product->fresh()->filterableAttributes;

        $this->assertCount(1, $loaded);
        $this->assertTrue($loaded->contains('attribute_id', $this->activeAttribute->id));
        $this->assertFalse($loaded->contains('attribute_id', $this->inactiveAttribute->id));
    }

    public function test_inactive_attribute_value_is_not_deleted_from_the_database(): void
    {
        $this->makeFixtures();

        $this->assertDatabaseHas('product_attributes', [
            'product_id' => $this->product->id,
            'attribute_id' => $this->inactiveAttribute->id,
        ]);
    }

    public function test_admin_edit_form_hides_inactive_attribute_and_keeps_its_saved_value_on_save(): void
    {
        $this->makeFixtures();
        $this->actingAsAdmin();

        $livewire = Livewire::test(EditProduct::class, ['record' => $this->product->id]);

        // Only the active attribute's row was loaded into the repeater.
        $attributeIds = collect($livewire->get('data.attributes'))->pluck('attribute_id')->all();
        $this->assertSame([$this->activeAttribute->id], $attributeIds);

        // Saving the form (repeater relationship sync) must not delete the
        // inactive attribute's row, since it was never loaded into the form.
        $livewire->call('save');

        $this->assertDatabaseHas('product_attributes', [
            'product_id' => $this->product->id,
            'attribute_id' => $this->inactiveAttribute->id,
        ]);

        $this->assertDatabaseHas('product_attributes', [
            'product_id' => $this->product->id,
            'attribute_id' => $this->activeAttribute->id,
        ]);
    }
}
