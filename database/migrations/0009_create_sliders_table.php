<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->json('description')->nullable();
            $table->string('button_target', 10)->default('_self');
            $table->string('image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('video')->nullable();
            $table->enum('type', ['image', 'video'])->default('image');
            $table->string('overlay_color', 20)->nullable();  // رنگ overlay روی اسلاید
            $table->unsignedTinyInteger('overlay_opacity')->default(0); // 0-100
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_active');
            $table->index('sort_order');
            $table->index('type');
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
