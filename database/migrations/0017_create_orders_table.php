<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Snapshot اطلاعات مشتری
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 30)->nullable();
            $table->string('customer_company')->nullable();
            $table->string('customer_country', 5)->nullable();
            $table->text('customer_address')->nullable();
            $table->string('customer_postal_code', 20)->nullable();

            $table->enum('status', [
                'pending',
                'processing',
                'confirmed',
                'shipped',
                'delivered',
                'cancelled',
                'refunded',
            ])->default('pending');

            $table->enum('payment_type', [
                'online',   // درگاه ایرانی
                'receipt',  // آپلود فیش خارجی
            ]);

            $table->string('coupon_code', 50)->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('currency', 10)->default('IRR');

            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->string('tracking_code')->nullable();        // کد رهگیری مرسوله
            $table->string('shipping_method', 100)->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
            $table->index('payment_type');
            $table->index('customer_email');
            $table->index('customer_country');
            $table->index('total');
            $table->index('deleted_at');
            $table->index('confirmed_at');
            $table->index(['status', 'payment_type']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
