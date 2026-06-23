<?php

namespace App\Console\Commands;

use App\Support\SuperUser;
use Illuminate\Console\Command;

class SuperUserHash extends Command
{
    protected $signature   = 'superuser:hash';
    protected $description = 'Generate a bcrypt hash for the SuperUser password to paste in .env';

    public function handle(): void
    {
        $password = $this->secret('Enter SuperUser password (input is hidden)');
        $confirm  = $this->secret('Confirm password');

        if ($password !== $confirm) {
            $this->error('Passwords do not match.');
            return;
        }

        $hash = SuperUser::generateHash($password);

        $this->newLine();
        $this->info('Add this to your .env file:');
        $this->newLine();
        $this->line("SUPER_USER_PASSWORD_HASH={$hash}");
        $this->newLine();
        $this->warn('Never share this hash. Never commit .env to version control.');
    }
}
