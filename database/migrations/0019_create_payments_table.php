<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->enum('type', ['online', 'receipt']);
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'cancelled',
                'refunded',
            ])->default('pending');

            // درگاه آنلاین
            $table->string('gateway', 50)->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('reference_id')->nullable();
            $table->json('gateway_response')->nullable();

            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('IRR');

            // فیش خارجی
            $table->string('receipt_file')->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_country', 5)->nullable();
            $table->string('transfer_reference', 100)->nullable();
            $table->text('receipt_notes')->nullable();
            $table->date('receipt_date')->nullable();

            // تأیید ادمین
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('type');
            $table->index('status');
            $table->index('gateway');
            $table->index('transaction_id');
            $table->index('paid_at');
            $table->index('verified_by');
            $table->index(['order_id', 'status']);
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
