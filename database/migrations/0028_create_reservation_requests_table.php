<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Guest / contact info — captured even for logged-in users so admin
            // always has a direct line to reach the buyer.
            $table->string('name', 150)->nullable();
            $table->string('phone_country', 6)->nullable(); // dial code, e.g. +98
            $table->string('phone', 30);
            $table->enum('contact_method', ['call', 'whatsapp'])->default('call');
            $table->text('note')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected', 'expired', 'cancelled'])
                ->default('pending');

            // Set only once approved — computed from the settings-configurable
            // reservation duration at the moment of approval.
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_requests');
    }
};
