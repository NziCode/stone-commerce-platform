<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);

            $table->primary(['product_id', 'category_id']);
            $table->index('category_id');
            $table->index('is_primary');
            $table->index(['category_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_category');
    }
};
