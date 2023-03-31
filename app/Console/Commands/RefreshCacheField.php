<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshCacheField extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache-field:refresh {model} {--queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh cache field';

    public function handle()
    {
        $class = $this->argument('model');
        $using_queue = $this->option('queue');
        $model = new $class;

        $model::makeAllCacheable($using_queue);

        $this->info('Refreshed successfully');
    }
}
