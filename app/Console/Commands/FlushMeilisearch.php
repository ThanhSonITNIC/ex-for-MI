<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Services\Meilisearch\ModelsSearchable;

class FlushMeilisearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all data from the models from Meilisearch';

    public function handle()
    {
        $models = ModelsSearchable::$register;

        foreach ($models as $model) {
            Artisan::call('scout:flush', [
                'model' => $model
            ]);
        }

        $this->info('Removed all data from Meilisearch');
    }
}
