<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Services\Meilisearch\ModelsSearchable;

class ImportMeilisearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The data from the models has been imported into meilisearch';

    public function handle()
    {
        $models = ModelsSearchable::$register;

        foreach ($models as $model) {
            Artisan::call('scout:import', [
                'model' => $model
            ]);
        }

        $this->info('Meilisearch imported data from models.');
    }
}
