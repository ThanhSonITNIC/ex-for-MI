<?php

namespace App\Models;

use App\Casts\Money;
use App\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use App\Traits\Searchable;
use App\Traits\HasInternalRelationships;
use App\Traits\Scopeable;
use App\Traits\Touchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    use HasTranslations, Searchable, HasInternalRelationships, Scopeable, Touchable, Eventable;

    public $translatable = [];
    protected $cacheFields = [];
    protected $touchableRelationships = [];
    public bool $autoPublish = false;
    // public string $publishModelName = 'modelName';

    protected static $timestamp_casts = [self::CREATED_AT => 'timestamp', self::UPDATED_AT => 'timestamp'];

    protected static $customCastTypes = [
        Money::class,
    ];

    public function __construct(array $attributes = [])
    {
        $this->mergeCasts(static::$timestamp_casts);
        parent::__construct($attributes);
    }

    public function getDateFormat()
    {
        return 'U';
    }

    public function validateHasColumns(array $columns): void
    {
        $table = (new static)->getTable();
        if (!Schema::hasColumns($table, $columns)) {
            $columns_not_exist = collect();
            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column)) {
                    $columns_not_exist->push($column);
                }
            }

            throw new \App\Exceptions\ColumnsDoesNotExist('The fields ' . $columns_not_exist->implode(', ') . ' do not exist in the table ' . $table);
        }
    }

    public static function boot()
    {
        self::creating(function ($model) {
            static::eventCreating($model);
        });

        self::created(function ($model) {
            static::eventCreated($model);
        });

        self::updating(function ($model) {
            static::eventUpdating($model);
        });

        self::updated(function ($model) {
            static::eventUpdated($model);
        });

        self::deleted(function ($model) {
            static::eventDeleted($model);
        });

        parent::boot();
    }

    /**
     * Determine if the new and old values for a given key are equivalent.
     *
     * @param  string  $key
     * @return bool
     */
    public function originalIsEquivalent($key)
    {
        if (!array_key_exists($key, $this->original)) {
            return false;
        }

        $attribute = Arr::get($this->attributes, $key);
        $original = Arr::get($this->original, $key);

        $castTypes = collect($this->customCastTypes)->map(function ($type) {
            return strtolower($type);
        })->toArray();

        if ($this->hasCast($key, $castTypes)) {
            return $this->castAttribute($key, $attribute) ===
                $this->castAttribute($key, $original);
        }

        return parent::originalIsEquivalent($key);
    }
}
