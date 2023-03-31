<?php

namespace App\Services\Tomoni\Notification;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\HelperService;

class DataMessage
{
    public function __construct(protected string $title, protected string $body, protected array $data)
    {
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
        ];
    }
}
