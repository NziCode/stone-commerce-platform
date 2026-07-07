<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_caches', function (Blueprint $table) {
            $table->id();
            $table->string('source_hash', 64)->unique();
            $table->string('source_locale', 10);
            $table->string('target_locale', 10);
            $table->string('context', 50)->default('text');
            $table->text('source_text');
            $table->text('translated_text');
            $table->timestamps();

            $table->index(['source_locale', 'target_locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_caches');
    }
};
