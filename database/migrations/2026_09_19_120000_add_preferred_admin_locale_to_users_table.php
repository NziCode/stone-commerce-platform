<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The admin panel language switcher (routes/web.php) saves the choice in users.preferred_admin_locale and
 * SetAdminLocale reads it, but no migration ever created the column — switching the admin language
 * failed with "unknown column" wherever the column had not been added by hand. Guarded so it is a no-op
 * on databases that already have it.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'preferred_admin_locale')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('preferred_admin_locale', 10)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'preferred_admin_locale')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('preferred_admin_locale');
            });
        }
    }
};
