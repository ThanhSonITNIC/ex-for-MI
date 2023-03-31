<?php

namespace App\Services\Tomoni\Notification;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\HelperService;

class Message
{
    public function __construct(protected string $type, public array $user_ids, protected DataMessage $data)
    {
        // except editor
        $this->user_ids = auth()->user()?->id() ? array_diff($user_ids, [auth()->user()->id()]) : $user_ids;
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'user_ids' => $this->user_ids,
            'data' => $this->data->toArray(),
        ];
    }
}
