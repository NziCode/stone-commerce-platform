<?php

namespace App\Console\Commands\Support;

/**
 * A seeder that knows how to regenerate its own seed data from the current
 * database state, so admin-panel edits survive the next `migrate:fresh --seed`.
 *
 * Implementations only rewrite the block between the `// BEGIN: db-sync` /
 * `// END: db-sync` marker comments in their seeder file — everything else
 * in the file (namespace, class wrapper, the loop that applies $settings,
 * etc.) is left untouched.
 */
interface SeederSync
{
    /**
     * Absolute path to the seeder file this syncer maintains.
     */
    public function seederPath(): string;

    /**
     * Freshly generated PHP source for the marked block, built from the
     * current database state. Does not include the marker comments
     * themselves — the command inserts this between them.
     */
    public function generateBlock(): string;

    /**
     * Short human-readable summary of the current database state, shown
     * after a successful sync, e.g. "42 settings across 9 groups".
     */
    public function describe(): string;

    /**
     * Full seeder file contents to create at seederPath() when it doesn't
     * exist yet (e.g. it's gitignored and this is a fresh checkout) —
     * including the `// BEGIN: db-sync` / `// END: db-sync` markers around
     * an empty block.
     */
    public function template(): string;
}
