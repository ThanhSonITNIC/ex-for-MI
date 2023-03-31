<?php

namespace App\Services\Spatie\SubAppend\Traits;

use App\Services\Spatie\SubAppend\Exceptions\InvalidSubAppendQuery;

trait SubAppendsQuery
{
    protected $allowedSubAppends;

    public function allowedSubAppends($appends): self
    {
        $appends = is_array($appends) ? $appends : func_get_args();
        $this->allowedSubAppends = collect($appends);
        $this->ensureAllSubAppendsExist();

        $this->setDefaultAppendsToRelations($this->request->subAppends()->toArray());

        return $this;
    }

    protected function setDefaultAppendsToRelations(array $subAppendsRequest)
    {
        foreach ($subAppendsRequest as $subAppend) {
            $subAppendToArray = explode(".", $subAppend);
            $relations = array_splice($subAppendToArray, 0, -1);
            $keyAppend = $subAppendToArray[array_key_last($subAppendToArray)];

            $model = $this->getQueryBuilderLastRelation($this, $relations)->getModel();

            if (!in_array('App\Traits\SubAppenable', class_uses($model))) {
                throw new \Exception('The class ' . get_class($model) . ' must use App\Traits\SubAppenable trait', 1);
            }

            $model->setDefaultAppends($keyAppend);
        }
    }

    protected function getQueryBuilderLastRelation($builder, array $relations)
    {
        $relationBuilder = count($relations) >= 1 ? $builder->getRelation($relations[0])->getRelated()->query() : $builder;

        if (count($relations) == 1) {
            return $relationBuilder;
        }

        return $this->getQueryBuilderLastRelation($relationBuilder, array_splice($relations, 1));
    }

    protected function ensureAllSubAppendsExist()
    {
        $appends = $this->request->subAppends();

        $diff = $appends->diff($this->allowedSubAppends);

        if ($diff->count()) {
            throw InvalidSubAppendQuery::subAppendsNotAllowed($diff, $this->allowedSubAppends);
        }
    }
}
