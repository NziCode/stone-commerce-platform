<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds real per-product
 * attribute values tied to the client's real product catalog.
 */
class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_attributes')->delete();
    }
}
