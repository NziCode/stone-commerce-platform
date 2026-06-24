<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->index();         // fa, en, ar, ...
            $table->string('group', 100)->index();         // admin, messages, validation
            $table->string('key', 100)->index();           // dashboard, save, delete
            $table->text('value')->nullable();             // داشبورد
            $table->boolean('is_auto')->default(false);    // ترجمه خودکار یا دستی
            $table->timestamps();

            $table->unique(['locale', 'group', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
