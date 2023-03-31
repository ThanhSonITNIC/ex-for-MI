<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\Tomoni\Models\Auth\User;

class ModelListener
{
    /**
     * Handle the event.
     *
     * @param  string  $event
     * @return void
     */
    public function handle($event, $payload)
    {
        $resource = explode('.', $event)[1];
        $resource_event = explode('.', $event)[2];

        if ($payload['user_id']) {
            $user = User::find($payload['user_id']);
            if ($user) {
                Auth::setUser(new \App\Services\Tomoni\Models\Auth\Me($user));
            }
        }

        $target = 'on' . Str::ucfirst($resource) . Str::ucfirst($resource_event);
        if (method_exists($this, $target)) {
            $this->$target($payload['data']);
        }
    }

    protected function onUserCreated($data)
    {
        // $created_id = $data['id'];
    }

    protected function onUserUpdated($data)
    {
        // $original_id = $data['original']['id'];
        // $changed_id = $data['changes']['id'];
    }

    protected function onUserDeleted($data)
    {
        // $deleted_id = $data['id'];
    }
}
