<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Translation;
use App\Models\Language;
use Illuminate\Support\Facades\File;

class GenerateLangFiles extends Command
{
    protected $signature = 'lang:generate
                            {--locale= : Generate for a specific locale only}
                            {--group=  : Generate for a specific group only}
                            {--force   : Overwrite existing files}';

    protected $description = 'Generate lang files from database translations';

    public function handle(): int
    {
        $locales = $this->option('locale')
            ? [$this->option('locale')]
            : Language::where('is_active', true)->pluck('code')->toArray();

        $groups = $this->option('group')
            ? [$this->option('group')]
            : Translation::distinct()->pluck('group')->toArray();

        $this->info('Generating lang files...');
        $bar = $this->output->createProgressBar(count($locales) * count($groups));

        foreach ($locales as $locale) {
            $dir = resource_path("lang/{$locale}");
            File::ensureDirectoryExists($dir);

            foreach ($groups as $group) {
                $translations = Translation::where('locale', $locale)
                    ->where('group', $group)
                    ->orderBy('key')
                    ->pluck('value', 'key')
                    ->toArray();

                if (empty($translations)) {
                    $bar->advance();
                    continue;
                }

                $filePath = "{$dir}/{$group}.php";

                if (File::exists($filePath) && !$this->option('force')) {
                    $bar->advance();
                    continue;
                }

                $content = $this->buildPhpFile($translations, $locale, $group);
                File::put($filePath, $content);

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Lang files generated successfully.');

        Translation::clearCache();
        $this->info('Translation cache cleared.');

        return self::SUCCESS;
    }

    private function buildPhpFile(array $translations, string $locale, string $group): string
    {
        $lines   = [];
        $lines[] = "<?php";
        $lines[] = "";
        $lines[] = "// Auto-generated from database — {$locale}/{$group}";
        $lines[] = "// Generated at: " . now()->format('Y-m-d H:i:s');
        $lines[] = "// DO NOT EDIT MANUALLY — use the admin panel";
        $lines[] = "";
        $lines[] = "return [";

        foreach ($translations as $key => $value) {
            $escapedKey   = addslashes($key);
            $escapedValue = addslashes($value ?? '');
            $lines[] = "    '{$escapedKey}' => '{$escapedValue}',";
        }

        $lines[] = "];";
        $lines[] = "";

        return implode("\n", $lines);
    }
}
