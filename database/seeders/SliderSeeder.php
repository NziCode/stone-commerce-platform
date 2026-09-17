<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds the client's
 * real homepage slider content. Add sliders from the admin panel after a
 * fresh install.
 */
class SliderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sliders')->delete();
    }
}
