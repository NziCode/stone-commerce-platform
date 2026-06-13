<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->json('name');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->json('short_description')->nullable();

            $table->string('sku', 100)->nullable()->unique();

            // Multi-currency pricing
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('price_usd', 15, 2)->nullable();
            $table->decimal('price_eur', 15, 2)->nullable();
            $table->boolean('price_on_request')->default(false); // price on request

            // Availability status — each stone is unique
            $table->enum('status', [
                'available',
                'unavailable',
                'reserved',
                'sold',
            ])->default('available');

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new')->default(false); // "new" badge
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);

            // SEO meta (consumed by artesaos/seotools on the frontend)
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('og_image')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('is_featured');
            $table->index('is_active');
            $table->index('is_new');
            $table->index('sort_order');
            $table->index('price');
            $table->index('price_usd');
            $table->index('deleted_at');
            $table->index(['is_active', 'status']);
            $table->index(['is_featured', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
