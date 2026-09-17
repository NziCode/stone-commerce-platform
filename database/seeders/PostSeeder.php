<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Intentionally empty for the public repo — this table holds the client's
 * real blog/news content. Add posts from the admin panel after a fresh install.
 */
class PostSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('posts')->delete();
    }
}
