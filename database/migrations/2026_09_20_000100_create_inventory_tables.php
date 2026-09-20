<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Stone inventory: the three main categories (export / saw-cut / top-cut), and the internal
 * owners, mines and warehouses every stone can be assigned to, plus the sale details that make
 * "sold / not sold" reports possible.
 *
 * Owners, mines and warehouses are back-office data — nothing here is ever shown on the public site.
 * Additive only (nullable columns); every stone that exists today becomes an export stone.
 * Every step checks what is already there: production tables are MyISAM (no transactional DDL), so a
 * run that stopped half-way must be able to continue instead of failing on the tables it had created.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('main_categories')) {
            Schema::create('main_categories', function (Blueprint $table) {
                $table->id();
                $table->string('key', 40)->unique();          // export | saw-cut | top-cut — used in ?group=… URLs
                $table->json('name');                          // translatable
                $table->json('description')->nullable();       // translatable
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        foreach (['owners', 'mines', 'warehouses'] as $name) {
            if (Schema::hasTable($name)) {
                continue;
            }

            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id();
                $table->string('name', 150);
                if ($name === 'owners') {
                    $table->string('phone', 40)->nullable();
                    $table->string('email', 150)->nullable();
                } else {
                    $table->string('location', 191)->nullable();
                }
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $missing = fn (string $column) => ! Schema::hasColumn('products', $column);

            $missing('main_category_id') && $table->unsignedBigInteger('main_category_id')->nullable()->index();
            $missing('owner_id') && $table->unsignedBigInteger('owner_id')->nullable()->index();
            $missing('mine_id') && $table->unsignedBigInteger('mine_id')->nullable()->index();
            $missing('warehouse_id') && $table->unsignedBigInteger('warehouse_id')->nullable()->index();

            // the sale, filled in when the stone is marked as sold
            $missing('sold_at') && $table->timestamp('sold_at')->nullable()->index();
            $missing('sold_price') && $table->decimal('sold_price', 15, 2)->nullable();
            $missing('sold_currency') && $table->string('sold_currency', 8)->nullable();
            $missing('sold_to') && $table->string('sold_to', 191)->nullable();
            $missing('sold_warehouse_id') && $table->unsignedBigInteger('sold_warehouse_id')->nullable();   // where it was when it left
        });

        // the three main categories …
        $now = now();
        $groups = [
            ['export', 1, [
                'fa' => 'صادراتی', 'en' => 'Export Grade', 'ar' => 'للتصدير', 'hi' => 'निर्यात ग्रेड',
                'it' => 'Export', 'zh' => '出口级', 'tr' => 'İhracat',
            ]],
            ['saw-cut', 2, [
                'fa' => 'اره‌بری', 'en' => 'Saw-Cut', 'ar' => 'مقطوع بالمنشار', 'hi' => 'आरा-कटाई',
                'it' => 'Tagliato a sega', 'zh' => '锯切', 'tr' => 'Testere Kesim',
            ]],
            ['top-cut', 3, [
                'fa' => 'قله‌بری', 'en' => 'Top-Cut', 'ar' => 'قطع القمة', 'hi' => 'शिखर-कटाई',
                'it' => 'Taglio di cima', 'zh' => '山顶开采', 'tr' => 'Tepe Kesim',
            ]],
        ];

        foreach ($groups as [$key, $sort, $name]) {
            if (DB::table('main_categories')->where('key', $key)->exists()) {
                continue;
            }

            DB::table('main_categories')->insert([
                'key' => $key, 'name' => json_encode($name, JSON_UNESCAPED_UNICODE),
                'sort_order' => $sort, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // … and every stone that exists today is an export stone
        $exportId = DB::table('main_categories')->where('key', 'export')->value('id');
        DB::table('products')->whereNull('main_category_id')->update(['main_category_id' => $exportId]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'main_category_id', 'owner_id', 'mine_id', 'warehouse_id',
                'sold_at', 'sold_price', 'sold_currency', 'sold_to', 'sold_warehouse_id',
            ]);
        });

        foreach (['warehouses', 'mines', 'owners', 'main_categories'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
