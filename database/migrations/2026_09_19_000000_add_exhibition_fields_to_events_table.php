<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Translatable (JSON keyed by locale), same convention as title/location.
            $table->json('organizer_name')->nullable()->after('location');
            // Free-text date shown instead of the formatted starts_at/ends_at,
            // e.g. "Mehr 1405 — exact dates to be announced".
            $table->json('date_label')->nullable()->after('organizer_name');

            $table->boolean('is_published')->default(true)->after('status');
            // When true, `events:sync-status` moves the event between
            // upcoming → ongoing → finished according to starts_at/ends_at.
            $table->boolean('auto_status')->default(true)->after('is_published');

            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['is_published']);
            $table->dropColumn(['organizer_name', 'date_label', 'is_published', 'auto_status']);
        });
    }
};
