<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Meilisearch\ModelsSearchable;

class InstallMeilisearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set attributes for the model';

    public function handle()
    {
        $models = ModelsSearchable::$register;

        foreach ($models as $model) {
            $model::updateAttributes();
        }

        $this->info('Models have set attributes.');
    }
}
