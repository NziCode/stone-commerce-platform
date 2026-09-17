<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds the client's
 * real exhibition/event content. Add events from the admin panel after a
 * fresh install.
 */
class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('events')->delete();
    }
}
