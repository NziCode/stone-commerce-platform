<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Nested Set — kalnoy/nestedset
            $table->unsignedBigInteger('_lft')->default(0);
            $table->unsignedBigInteger('_rgt')->default(0);
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->json('name');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->json('excerpt')->nullable();

            // ویژگی‌های دینامیک این دسته‌بندی
            // مثال: [{"key":"color","label":{"fa":"رنگ","en":"Color"},"type":"select","options":["عسلی","کرم","سفید"],"unit":null,"filterable":true}]
            $table->json('attribute_schema')->nullable();

            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('og_image')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('_lft');
            $table->index('_rgt');
            $table->index('parent_id');
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
