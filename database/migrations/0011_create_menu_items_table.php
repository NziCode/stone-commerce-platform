<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                ->constrained('menus')
                ->cascadeOnDelete();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menu_items')
                ->cascadeOnDelete();
            $table->json('label');
            $table->string('url')->nullable();
            $table->string('route_name', 100)->nullable();
            $table->json('route_params')->nullable();
            $table->string('target', 10)->default('_self');
            $table->string('icon', 100)->nullable();
            $table->string('css_class', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('menu_id');
            $table->index('parent_id');
            $table->index('is_active');
            $table->index('sort_order');
            $table->index(['menu_id', 'parent_id']);
            $table->index(['menu_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
