<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ResetDemoData extends Command
{
    protected $signature = 'demo:reset';

    protected $description = 'Drop every table and restore the sample data';

    /** Changes whenever a migration file is added, removed or edited. */
    public static function schemaVersion(): string
    {
        $files = glob(database_path('migrations/*.php'));

        return md5(implode('|', array_map(fn ($file) => basename($file).':'.md5_file($file), $files)));
    }

    public function handle(): int
    {
        $this->call('migrate:fresh', ['--seed' => true, '--force' => true]);
        Cache::forever('demo:last-reset', now()->timestamp);
        Cache::forever('demo:schema', self::schemaVersion());

        return self::SUCCESS;
    }
}
