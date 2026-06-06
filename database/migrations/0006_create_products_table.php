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
            $table->string('mine_code', 100)->nullable();
            $table->string('origin_country', 5)->nullable();  // کشور استخراج

            // قیمت‌گذاری چند ارزی
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('price_usd', 15, 2)->nullable();
            $table->decimal('price_eur', 15, 2)->nullable();
            $table->boolean('price_on_request')->default(false); // قیمت با تماس

            // موجودی — هر سنگ یکتاست
            $table->enum('status', [
                'available',
                'unavailable',
                'reserved',
                'sold',
            ])->default('available');

            // ابعاد فیزیکی
            $table->decimal('length_cm', 8, 2)->nullable();
            $table->decimal('width_cm', 8, 2)->nullable();
            $table->decimal('height_cm', 8, 2)->nullable();
            $table->decimal('weight_kg', 10, 2)->nullable();
            $table->decimal('area_m2', 8, 4)->nullable();       // متراژ

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new')->default(false);          // نشانه «جدید»
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);

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
            $table->index('mine_code');
            $table->index('origin_country');
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
