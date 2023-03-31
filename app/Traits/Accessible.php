<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait Accessible
{
    // protected $accessibleFields = [];
    // protected $accessibleResource = '';

    /**
     *
     * @param $scopeField. ex: director.type_id
     * @param $scopes. ex: [retail, wholesale]
     */
    public function scopeAccessible($query, string $scopeField = null, array $scopes = [])
    {
        if ($this->accessibleResource) {
            if (Auth::user()->existResourceScope($this->accessibleResource . '.*')) {
                if (Auth::user()->hasPermission($this->accessibleResource . '.*.read')) {
                    return $query;
                }
            }
        }

        return $query->where(function ($query) use ($scopes, $scopeField) {
            $query->where(function ($query) {
                $query->own()->orWhere->ofRole(Auth::user()->role);
            });

            if ($this->accessibleResource) {
                $readScopes = collect();

                $field = $scopeField;
                $relation = null;

                if (Str::contains($scopeField, '.')) {
                    $ex = explode('.', $scopeField);
                    $field = end($ex);
                    $relation = substr($scopeField, 0, strlen($scopeField) - strlen($field) - 1);
                }

                foreach ($scopes as $scope) {
                    if (Auth::user()->existResourceScope($this->accessibleResource . '.' . $scope)) {
                        if (Auth::user()->hasPermission($this->accessibleResource . '.' . $scope . '.read')) {
                            $readScopes->push($scope);
                        }
                    }
                }

                if (!$relation) {
                    $query->orWhereIn($field, $readScopes->toArray());
                } else {
                    $query->whereHas($relation, function ($query) use ($field, $readScopes) {
                        $query->whereIn($field, $readScopes->toArray());
                    });
                }
            }
        });
    }

    public function scopeOwn($query)
    {
        foreach ($this->accessibleFields as $index => $field) {
            if ($index <= 0) {
                $query->where($field, Auth::user()->id());
            } else {
                $query->orWhere($field, Auth::user()->id());
            }
        }

        return $query;
    }

    public function scopeOfRole($query, object $role)
    {
        $scope = $role->scope;
        $priority = $role->priority;

        foreach ($this->accessibleFields as $index => $field) {
            $prioritySql = 'SUBSTRING_INDEX(SUBSTRING_INDEX(' . $field . ', "-", 2), "-", -1) < ' . $priority;
            if ($index <= 0) {
                $query->where($field, 'like', $scope . '%')
                    ->whereRaw($prioritySql);
            } else {
                $query->orWhere($field, 'like', $scope . '%')
                    ->whereRaw($prioritySql);
            }
        }

        return $query;
    }
}
