<?php

namespace App\Services\Meilisearch;

use App\Traits\Services\Translatable;
use MeiliSearch\Client;

class Builder
{
    use Translatable;

    protected $query = [];

    protected $wheres = [];

    protected $whereIns = [];

    protected $orWheres = [];

    protected $limit = 4294967296;

    public function __construct(protected string $index, protected array $translatable)
    {
    }

    protected function builder()
    {
        return (new Client(
            config('scout.meilisearch.host'),
            config('scout.meilisearch.key')
        ))->index($this->index);
    }

    public function where(string $field, $operator, $value)
    {
        array_push($this->wheres, [
            'field' => $field, 
            'value' => $value,
            'operator' => $operator,
        ]);
        return $this;
    }

    /**
     * [
     *  ['key', 'condition', 'value'],
     * ]
     */
    public function orWhere(array $wheres)
    {
        $orWhere = collect($wheres)->map(function ($where) {
            return [
                'key' => $where[0],
                'operator' => $where[1],
                'value' => $where[2],
            ];
        })->toArray();

        array_push($this->orWheres, $orWhere);

        return $this;
    }

    public function whereIn(string $field, array $values)
    {
        $values = !$values ? [''] : $values;
        $this->whereIns[$field] = collect($values)->map(function ($value) {
            return [
                'operator' => '=',
                'value' => $value,
            ];
        })->toArray();
        return $this;
    }

    public function getQuery()
    {
        $query = collect($this->query);
        $where_query = collect();

        foreach ($this->wheres as $where) {
            $where_query->push($where['field'] . ' ' . $where['operator'] . ' ' . '"' . $where['value'] . '"');
        }

        foreach ($this->whereIns as $key => $values) {
            $where_in_field = collect();
            foreach ($values as $value) {
                $where_in_field->push($key . ' ' . $value['operator'] . ' ' . '"' . $value['value'] . '"');
            }

            $where_query->push($where_in_field->filter()->all());
        }

        foreach ($this->orWheres as $orWhere) {
            $or_wheres = collect();
            foreach ($orWhere as $where) {
                $or_wheres->push($where['key'] . ' ' . $where['operator'] . ' ' . '"' . $value['value'] . '"');
            }
            $where_query->push($or_wheres->filter()->all());
        }

        if ($where_query->filter()->all()) {
            $query = $query->mergeRecursive(['filter' => $where_query->filter()->all()]);
        }

        $query = $query->merge(['limit' => $this->limit]);

        return $query->all();
    }

    public function get()
    {
        try {
            $hits = json_decode($this->builder()->search(null, $this->getQuery())->toJSON())->hits;
        } catch (\MeiliSearch\Exceptions\ApiException $ex) {
            $hits = [];
        }

        return $this->translate($hits);
    }

    public function limit(int $limit = 20)
    {
        $this->limit = $limit;
        return $this->get();
    }

    public function first()
    {
        return $this->get()->first();
    }
}
