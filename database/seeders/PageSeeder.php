<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds the client's
 * real CMS page content. Add pages from the admin panel after a fresh install.
 */
class PageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pages')->delete();
    }
}
