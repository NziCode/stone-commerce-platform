<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A reservation now follows the sales flow: request → approved (stone held) →
 * prepayment received → final payment received (stone sold). Additive only —
 * the existing status column and its values are untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservation_requests', function (Blueprint $table) {
            $table->decimal('deposit_amount', 15, 2)->nullable()->after('admin_note');
            $table->string('deposit_currency', 8)->nullable()->after('deposit_amount');
            $table->timestamp('deposit_received_at')->nullable()->after('deposit_currency');
            $table->timestamp('final_paid_at')->nullable()->after('deposit_received_at');
        });
    }

    public function down(): void
    {
        Schema::table('reservation_requests', function (Blueprint $table) {
            $table->dropColumn(['deposit_amount', 'deposit_currency', 'deposit_received_at', 'final_paid_at']);
        });
    }
};
