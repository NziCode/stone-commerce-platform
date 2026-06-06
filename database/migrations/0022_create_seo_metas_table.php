<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable'); // این خودش ایندکس می‌سازه
            $table->string('locale', 10)->default('fa');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type', 50)->default('website');
            $table->string('canonical_url')->nullable();
            $table->json('schema_org')->nullable();
            $table->enum('robots', [
                'index,follow',
                'noindex,follow',
                'index,nofollow',
                'noindex,nofollow',
            ])->default('index,follow');
            $table->timestamps();

            $table->unique(['seoable_type', 'seoable_id', 'locale']);
            $table->index('locale');
            // خط index(['seoable_type', 'seoable_id']) رو حذف کردیم
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
    }
};
