<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class SeedIfEmpty extends Command
{
    protected $signature = 'db:seed-if-empty';

    protected $description = 'Run the database seeders only if the database has no projects yet — prevents wiping admin edits on every container restart.';

    public function handle(): int
    {
        if (Project::count() > 0) {
            $this->info('Database already has data — skipping seed to preserve admin edits.');

            return self::SUCCESS;
        }

        $this->info('Database is empty — running seeders for the first time.');
        $this->call('db:seed', ['--force' => true]);

        return self::SUCCESS;
    }
}