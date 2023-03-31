<?php

namespace App\Services\Tomoni\Models\Helper;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\HelperService;
use App\Services\Tomoni\Model;

class Log extends Model
{
    const TYPE_CREATED = 'created';
    const TYPE_UPDATED = 'updated';
    const TYPE_DELETED = 'deleted';
    const TYPE_READ = 'read';
    const TYPE_WRITE = 'write';

    public const ORDER_TYPE = 'order';
    public const CONTRACT_TYPE = 'contract';
    public const PURCHASE_TYPE = 'purchase';
    public const BOX_TYPE = 'box';

    protected static function apiResource(): string
    {
        return 'logs';
    }

    protected static function service(): MachineService
    {
        return new HelperService;
    }

    public static function order(array $content, string $id, string $type = Log::TYPE_UPDATED)
    {
        return static::writeLog($content, auth()->user()->id(), LOG::ORDER_TYPE, $id, $type);
    }

    public static function contract(array $content, string $id, string $type = Log::TYPE_UPDATED)
    {
        return static::writeLog($content, auth()->user()->id(), LOG::CONTRACT_TYPE, $id, $type);
    }

    public static function purchase(array $content, string $id, string $type = Log::TYPE_UPDATED)
    {
        return static::writeLog($content, auth()->user()->id(), LOG::PURCHASE_TYPE, $id, $type);
    }

    public static function box(array $content, string $id, string $type = Log::TYPE_CREATED)
    {
        return static::writeLog($content, auth()->user()->id(), LOG::BOX_TYPE, $id, $type);
    }

    public static function writeLog($content, string $creator_id, string $loggable_type, $loggable_id, string $type = Log::TYPE_UPDATED)
    {
        $data = [
            'content' => $content,
            'creator_id' => $creator_id,
            'logable_type' => $loggable_type,
            'logable_id' => $loggable_id,
            'type_id' => $type,
        ];

        return $content ? self::create($data) : null;
    }
}
