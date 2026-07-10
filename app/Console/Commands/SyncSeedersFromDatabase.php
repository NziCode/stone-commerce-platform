<?php

namespace App\Console\Commands;

use App\Console\Commands\Support\SeederSync;
use App\Console\Commands\Support\SettingValuesSeederSync;
use Illuminate\Console\Command;

/**
 * Regenerates seeder files from the current database, so edits made through
 * the admin panel aren't lost the next time someone runs `migrate:fresh --seed`.
 *
 * Only rewrites the block between the `// BEGIN: db-sync` / `// END: db-sync`
 * markers in a seeder file — everything else is left untouched. If the file
 * doesn't exist yet (e.g. it's gitignored, like SettingValuesSeeder), it's
 * created from the syncer's template() first. To make a new seeder syncable,
 * add those markers around its seed-data array and register a SeederSync
 * implementation for it below.
 */
class SyncSeedersFromDatabase extends Command
{
    protected $signature = 'db:sync-seeders
        {seeder? : Sync only this one (e.g. SettingValuesSeeder) — syncs all registered seeders if omitted}
        {--dry-run : Report what would change without writing any files}';

    protected $description = 'Regenerate seeder files from the current database state';

    /** @var array<string, class-string<SeederSync>> */
    protected array $syncers = [
        'SettingValuesSeeder' => SettingValuesSeederSync::class,
    ];

    public function handle(): int
    {
        $target = $this->argument('seeder');
        $dryRun = (bool) $this->option('dry-run');

        if ($target && ! isset($this->syncers[$target])) {
            $this->error("No syncer registered for [{$target}]. Available: " . implode(', ', array_keys($this->syncers)));

            return self::FAILURE;
        }

        $syncers = $target ? [$target => $this->syncers[$target]] : $this->syncers;

        foreach ($syncers as $name => $class) {
            $this->syncOne($name, $this->laravel->make($class), $dryRun);
        }

        return self::SUCCESS;
    }

    protected function syncOne(string $name, SeederSync $syncer, bool $dryRun): void
    {
        $path = $syncer->seederPath();
        $isNewFile = ! file_exists($path);

        if ($isNewFile && $dryRun) {
            $this->comment("[{$name}] would be created — {$syncer->describe()}. Re-run without --dry-run to write it.");

            return;
        }

        if ($isNewFile) {
            file_put_contents($path, $syncer->template());
            $this->info("[{$name}] created {$this->relativePath($path)}.");
        }

        $original = file_get_contents($path);
        $updated = $this->replaceMarkedBlock($original, $syncer->generateBlock());

        if ($updated === null) {
            $this->error("[{$name}] no `// BEGIN: db-sync` / `// END: db-sync` markers found in {$path} — add them around its seed-data array first.");

            return;
        }

        if ($updated === $original) {
            $this->info("[{$name}] already up to date ({$syncer->describe()}).");

            return;
        }

        if ($dryRun) {
            $this->comment("[{$name}] would be updated — {$syncer->describe()}. Re-run without --dry-run to write it.");

            return;
        }

        file_put_contents($path, $updated);
        $this->info("[{$name}] synced — {$syncer->describe()}. Review with: git diff " . $this->relativePath($path));
    }

    protected function replaceMarkedBlock(string $original, string $freshBlock): ?string
    {
        $pattern = '/(\/\/ BEGIN: db-sync[^\n]*\n)(.*?)([ \t]*\/\/ END: db-sync)/s';

        if (! preg_match($pattern, $original)) {
            return null;
        }

        return preg_replace_callback(
            $pattern,
            fn (array $matches) => $matches[1] . rtrim($freshBlock, "\n") . "\n" . $matches[3],
            $original
        );
    }

    protected function relativePath(string $path): string
    {
        return ltrim(str_replace(base_path(), '', $path), '/\\');
    }
}
