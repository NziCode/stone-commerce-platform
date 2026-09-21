<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Price inquiries: a visitor asks for the price of a stone (whose price is "on request") by leaving a phone
 * number; the sales team sees the request in the admin panel, contacts the customer and closes it.
 * Additive only. Guarded so that a run that stopped half-way (MyISAM has no transactional DDL) can continue.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_inquiries')) {
            return;
        }

        Schema::create('product_inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name', 150)->nullable();
            $table->string('phone_country', 8);
            $table->string('phone', 30);
            $table->string('contact_method', 10)->default('call');   // call | whatsapp
            $table->text('note')->nullable();
            $table->string('status', 12)->default('new')->index();       // new | contacted | closed
            $table->text('admin_note')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->unsignedBigInteger('contacted_by')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_inquiries');
    }
};
