<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds the client's
 * real product category tree. Add categories from the admin panel after a
 * fresh install.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->delete();
    }
}
