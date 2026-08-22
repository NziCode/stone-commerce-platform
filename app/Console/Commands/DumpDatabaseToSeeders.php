<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * php artisan db:dump-seeders
 *
 * Reads current data from selected tables and OVERWRITES the matching
 * seeder file in database/seeders/ with a fresh version that inserts
 * exactly what is currently in the database.
 *
 * This replaces the old hand-written seeder content — use with care.
 */
class DumpDatabaseToSeeders extends Command
{
    protected $signature = 'db:dump-seeders
        {--only= : Comma separated list of table keys to dump (default: all)}
        {--chunk=200 : Rows per insert() batch inside the generated seeder}';

    protected $description = 'Regenerate seeder files from the current contents of the database tables';

    /**
     * table => SeederClassName
     * Add/remove entries here to control what gets dumped.
     */
    protected array $map = [
        'languages'           => 'LanguageSeeder',
        'categories'          => 'CategorySeeder',
        'attributes'          => 'AttributeSeeder',
        'products'            => 'ProductSeeder',
        'product_attributes'  => 'ProductAttributeSeeder',
        'product_category'    => 'ProductCategorySeeder',
        'pages'               => 'PageSeeder',
        'posts'               => 'PostSeeder',
        'events'              => 'EventSeeder',
        'sliders'             => 'SliderSeeder',
        'menus'               => 'MenuSeeder',
        'menu_items'          => 'MenuItemSeeder',
        'settings'            => 'SettingSeeder',
        'translations'        => 'TranslationSeeder',
        'translation_caches'  => 'TranslationCacheSeeder',
        'users'               => 'AdminUserSeeder',
        'seo_metas'           => 'SeoMetaSeeder',
        'coupons'             => 'CouponSeeder',
        'redirects'           => 'RedirectSeeder',
        'reviews'             => 'ReviewSeeder',
        'orders'              => 'OrderSeeder',
        'order_items'         => 'OrderItemSeeder',
        'payments'            => 'PaymentSeeder',
        'carts'               => 'CartSeeder',
        'cart_items'          => 'CartItemSeeder',
        'wishlists'           => 'WishlistSeeder',
        'contact_messages'    => 'ContactMessageSeeder',
        'newsletters'         => 'NewsletterSeeder',
        'activity_log'        => 'ActivityLogSeeder',
        'media'               => 'MediaSeeder',
    ];

    /**
     * Tables that must share ONE seeder file, written in this order.
     * (roles must be inserted before role_has_permissions/model_has_roles
     * reference them.)
     */
    protected array $groupedMap = [
        'RolePermissionSeeder' => ['roles', 'permissions', 'role_has_permissions', 'model_has_roles'],
    ];

    public function handle(): int
    {
        $only = $this->option('only');
        $allKeys = array_merge(array_keys($this->map), array_keys($this->groupedMap));
        $wanted = $only ? array_map('trim', explode(',', $only)) : $allKeys;
        $chunkSize = (int) $this->option('chunk');

        $path = database_path('seeders');

        // ── Single-table seeders ─────────────────────────────
        foreach ($wanted as $table) {
            if (!isset($this->map[$table])) {
                continue;
            }

            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $this->warn("Table does not exist, skipping: {$table}");
                continue;
            }

            $seederClass = $this->map[$table];
            $rows = DB::table($table)->get();

            if ($rows->isEmpty()) {
                $this->warn("Table '{$table}' is empty — writing an empty seeder.");
            }

            $code = $this->buildSeederFile($seederClass, [$table => $rows], $chunkSize);

            $file = "{$path}/{$seederClass}.php";
            file_put_contents($file, $code);

            $this->info("✔ {$seederClass}.php written ({$rows->count()} rows from '{$table}')");
        }

        // ── Grouped (multi-table) seeders ────────────────────
        foreach ($this->groupedMap as $seederClass => $tables) {
            if ($only && !in_array($seederClass, $wanted, true)) {
                continue;
            }

            $tableData = [];
            $totalRows = 0;

            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) {
                    $this->warn("Table does not exist, skipping: {$table}");
                    continue;
                }
                $rows = DB::table($table)->get();
                $tableData[$table] = $rows;
                $totalRows += $rows->count();
            }

            if (empty($tableData)) {
                $this->warn("No tables found for {$seederClass}, skipping.");
                continue;
            }

            $code = $this->buildSeederFile($seederClass, $tableData, $chunkSize);

            $file = "{$path}/{$seederClass}.php";
            file_put_contents($file, $code);

            $this->info("✔ {$seederClass}.php written ({$totalRows} rows from " . implode(', ', array_keys($tableData)) . ')');
        }

        $this->newLine();
        $this->comment('Done. Review the generated files, then run: php artisan migrate:fresh --seed');

        return self::SUCCESS;
    }

    /**
     * @param array<string, \Illuminate\Support\Collection> $tableData table => rows
     */
    protected function buildSeederFile(string $class, array $tableData, int $chunkSize): string
    {
        $deleteBlocks = '';
        $insertBlocks = '';
        $tableList = implode(', ', array_keys($tableData));

        // Delete in reverse order (children before parents) to respect FKs.
        foreach (array_reverse(array_keys($tableData)) as $table) {
            $deleteBlocks .= "        DB::table('{$table}')->delete();\n";
        }

        foreach ($tableData as $table => $rows) {
            if ($rows->isEmpty()) {
                continue;
            }

            $chunks = $rows->chunk($chunkSize);
            foreach ($chunks as $chunk) {
                $arrayRows = $chunk->map(fn ($row) => $this->rowToArrayLiteral((array) $row))->implode(",\n");

                $insertBlocks .= <<<PHP

        DB::table('{$table}')->insert([
{$arrayRows}
        ]);

PHP;
            }
        }

        return <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Auto-generated by: php artisan db:dump-seeders
 * Source table(s): {$tableList}
 * Generated at: {$this->now()}
 *
 * This file was regenerated from the live database contents.
 * Any hand-written structure that was here before has been replaced.
 */
class {$class} extends Seeder
{
    public function run(): void
    {
{$deleteBlocks}
{$insertBlocks}
    }
}

PHP;
    }

    protected function rowToArrayLiteral(array $row): string
    {
        $pairs = [];
        foreach ($row as $key => $value) {
            $pairs[] = "                '{$key}' => " . $this->valueToPhp($value);
        }

        return "            [\n" . implode(",\n", $pairs) . "\n            ]";
    }

    protected function valueToPhp($value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_numeric($value) && !preg_match('/^0[0-9]/', (string) $value)) {
            // keep numeric-looking strings that start with 0 (e.g. phone numbers) as strings
            return (string) $value;
        }

        // Everything else (including JSON columns from translatable fields) as a raw string.
        return var_export((string) $value, true);
    }

    protected function now(): string
    {
        return now()->format('Y-m-d H:i:s');
    }
}
