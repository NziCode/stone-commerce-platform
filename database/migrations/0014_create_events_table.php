<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->json('title');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->json('location')->nullable();
            $table->string('city')->nullable();
            $table->string('country', 5)->nullable();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->enum('status', [
                'upcoming',
                'ongoing',
                'finished',
                'cancelled',
            ])->default('upcoming');
            $table->string('website_url')->nullable();
            $table->string('booth_number', 50)->nullable();
            $table->string('hall_number', 50)->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('country');
            $table->index('deleted_at');
            $table->index(['status', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
