<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general');
            $table->string('key', 150)->unique();
            $table->longText('value')->nullable();
            $table->string('type', 20)->default('string'); // string|json|boolean|integer|array
            $table->boolean('is_public')->default(false);  // قابل دسترس در فرانت بدون auth
            $table->timestamps();

            $table->index('group');
            $table->index(['group', 'key']);
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
