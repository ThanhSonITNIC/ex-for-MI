<?php

namespace App\Services\Tomoni\Notification;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\HelperService;
use App\Services\Tomoni\Exceptions\CommunicationException;

class Notification
{
    protected static function service(): MachineService
    {
        return new HelperService;
    }

    public static function order(array $data, string $description, array $user_ids)
    {
        $title = 'Order ' . $data['id'];
        $message = new Message('order', $user_ids, new DataMessage($title, $description, $data));
        return static::send($message);
    }

    protected static function send(Message $message)
    {
        if (!count($message->user_ids)) {
            return;
        }

        try {
            return static::service()->post('notifications', $message->toArray());
        } catch (CommunicationException $ex) {
            return null;
        }
    }
}
