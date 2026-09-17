<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds the client's
 * real product catalog (pricing, SKUs, descriptions). Add products from the
 * admin panel after a fresh install.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->delete();
    }
}
