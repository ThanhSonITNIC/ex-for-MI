<?php

namespace App\Traits;

use App\Services\MQ\PublishModel;

trait Eventable
{
    protected static function eventCreating($model)
    {
        $model->refreshCacheFields();
    }

    protected static function eventCreated($model)
    {
        if ($model->autoPublish) {
            PublishModel::publish($model, 'created');
        }
        $model->touch();
    }

    protected static function eventUpdating($model)
    {
        $model->refreshCacheFields();
    }

    protected static function eventUpdated($model)
    {
        if ($model->autoPublish) {
            PublishModel::publish($model, 'updated');
        }
        $model->touch();
    }

    protected static function eventDeleted($model)
    {
        if ($model->autoPublish) {
            PublishModel::publish($model, 'deleted');
        }
        $model->touch();
    }
}
