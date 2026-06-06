<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->json('key');    // {"fa":"رنگ","en":"Color","hi":"...","it":"...","ar":"..."}
            $table->json('value');  // {"fa":"عسلی","en":"Honey","hi":"...","it":"...","ar":"..."}
            $table->string('unit', 30)->nullable();  // kg, cm, m2 و...
            $table->boolean('is_filterable')->default(false); // قابل فیلتر در لیست
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('product_id');
            $table->index('sort_order');
            $table->index('is_filterable');
            $table->index(['product_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
