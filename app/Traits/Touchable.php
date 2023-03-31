<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait Touchable
{
    public function getTouches(): array
    {
        return $this->touchableRelationships ?? [];
    }

    public function touch()
    {
        foreach ($this->getTouches() as $relation) {
            try {
                $touch_model = $this->$relation;
            } catch (\LogicException $ex) {
                $touch_model = $this->$relation()->get();
            }
            if ($touch_model) {
                if ($touch_model instanceof Collection) {
                    $touch_model->each(function ($model) {
                        $model->refreshCacheFields();
                        $model->save();
                    });
                } else {
                    $touch_model->refreshCacheFields();
                    $touch_model->save();
                }
            }
        }
    }
}
