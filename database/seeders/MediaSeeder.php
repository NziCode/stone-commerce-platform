<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds media-library
 * records for the client's real uploaded files (product photos, etc.), which
 * aren't in this repo either. Upload media from the admin panel.
 */
class MediaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('media')->delete();
    }
}
