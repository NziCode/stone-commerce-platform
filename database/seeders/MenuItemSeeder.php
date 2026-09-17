<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds the client's
 * real site navigation. Add menu items from the admin panel after a fresh
 * install.
 */
class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menu_items')->delete();
    }
}
