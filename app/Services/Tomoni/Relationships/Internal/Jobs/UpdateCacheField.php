<?php

namespace App\Services\Tomoni\Relationships\Internal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class UpdateCacheField implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1800;

    public function __construct(
        protected Collection $models,
    ) {
    }

    public function handle()
    {
        $this->models->first()?->syncCacheField($this->models);
    }
}
