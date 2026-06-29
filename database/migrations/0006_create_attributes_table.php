<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();

            // Internal key (English, used for filtering/programmatic access)
            $table->string('key', 100)->unique(); // e.g. "color", "thickness"

            // Translatable label, e.g. {"fa":"رنگ","en":"Color",...}
            $table->json('label');

            // Optional translatable grouping, e.g. {"fa":"ابعاد و وزن","en":"Dimensions & Weight",...}
            $table->json('group')->nullable();

            // Field type
            $table->enum('type', ['text', 'number', 'select', 'bool'])->default('text');

            // Options for select type:
            // [{"key":"honey","label":{"fa":"عسلی","en":"Honey",...}}, ...]
            $table->json('options')->nullable();

            // Optional unit, e.g. "cm", "kg", "m2"
            $table->string('unit', 30)->nullable();

            // Validation rules for number type
            $table->decimal('min_value', 12, 4)->nullable();
            $table->decimal('max_value', 12, 4)->nullable();
            $table->decimal('step_value', 12, 4)->nullable();

            // Default value (plain string, interpreted per type)
            $table->string('default_value')->nullable();

            $table->boolean('is_filterable')->default(false);
            $table->boolean('show_in_product_page')->default(true);
            $table->boolean('show_in_card')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('is_filterable');
            $table->index('is_active');
            $table->index('show_in_product_page');
            $table->index('show_in_card');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
