<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class SyncEventStatus extends Command
{
    protected $signature = 'events:sync-status {--dry-run : Only report what would change}';
    protected $description = 'Move exhibitions between upcoming / ongoing / finished according to their dates (only those with "auto status" on)';

    public function handle(): int
    {
        $changed = 0;

        Event::query()
            ->where('auto_status', true)
            ->whereNotNull('starts_at')
            ->where('status', '!=', 'cancelled')
            ->each(function (Event $event) use (&$changed) {
                $expected = $event->computeStatus();

                if ($expected === null || $expected === $event->status) {
                    return;
                }

                $this->line("#{$event->id} {$event->getTranslation('title', 'en', true)}: {$event->status} → {$expected}");

                if (! $this->option('dry-run')) {
                    $event->status = $expected;
                    $event->save();
                }

                $changed++;
            });

        $this->info(($this->option('dry-run') ? 'Would update ' : 'Updated ') . "{$changed} exhibition(s).");

        return self::SUCCESS;
    }
}
