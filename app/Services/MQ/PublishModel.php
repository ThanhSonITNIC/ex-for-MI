<?php

namespace App\Services\MQ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RabbitEvents\Publisher\Support\Publishable;
use RabbitEvents\Publisher\ShouldPublish;

class PublishModel implements ShouldPublish
{
    use Publishable;

    public function __construct(protected Model $model, protected string $event, protected array $data_append = [])
    {
    }

    public function publishEventKey(): string
    {
        $model_name = $this->model->publishModelName ?? Str::camel(class_basename($this->model));
        return 'models.' . $model_name . '.' . $this->event;
    }

    public function toPublish(): mixed
    {
        return [
            'user_id' => auth()->user()?->id(),
            'data' => $this->getDataPublish(),
        ];
    }

    protected function getDataPublish()
    {
        switch ($this->event) {
            case 'updated':
                return array_merge([
                    'model' => $this->model->toArray(),
                    'original' => $this->model->getOriginal(),
                    'changes' => $this->model->getChanges(),
                ], $this->data_append);
            default:
                return array_merge([
                    'model' => $this->model->toArray(),
                ], $this->data_append);
        }
    }
}
