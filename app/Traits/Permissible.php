<?php

namespace App\Traits;

trait Permissible
{
    // $->permissions

    public function existResourceScope(string $expression): bool
    {
        if (count(explode('.', $expression)) !== 2) {
            throw new \Exception("Invalid expression");
        }

        $resource = explode('.', $expression)[0];
        $scope = explode('.', $expression)[1];

        if ($resource !== '*') {
            $collection = $this->permissions->whereIn('resource_id', [$resource, '*']);
        } else {
            $collection = $this->permissions->where('resource_id', $resource);
        }

        if ($scope !== '*') {
            $collection = $collection->whereIn('scope', [$scope, '*']);
        } else {
            $collection = $collection->where('scope', $scope);
        }

        return (bool) $collection->first();
    }

    /**
     * Check has permission
     * 
     * @param $expression: {resource}.{scope}.{permission}
     * 
     * e.g: transactions.*.*
     * e.g: transactions.*.create
     * e.g: transactions.opening-balance.delete
     * 
     * @return bool
     */
    public function hasPermission(string $expression): bool
    {
        if (count(explode('.', $expression)) !== 3) {
            throw new \Exception("Invalid expression");
        }

        $resource = explode('.', $expression)[0];
        $scope = explode('.', $expression)[1];
        $permission = explode('.', $expression)[2];

        if ($resource !== '*') {
            $collection = $this->permissions->whereIn('resource_id', [$resource, '*']);
        } else {
            $collection = $this->permissions->where('resource_id', $resource);
        }

        if ($scope !== '*') {
            $collection = $collection->whereIn('scope', [$scope, '*']);
        } else {
            $collection = $collection->where('scope', $scope);
        }

        if ($permission !== '*') {
            $collection = $collection->where($permission, true);
        } else {
            $collection = $collection->where('code', '1111');
        }

        return (bool) $collection->first();
    }

    public function hasAllPermissions(array $expressions): bool
    {
        foreach ($expressions as $expression) {
            if (!$this->hasPermission($expression)) {
                return false;
            }
        }
        return true;
    }

    public function hasAnyPermissions(array $expressions): bool
    {
        foreach ($expressions as $expression) {
            if ($this->hasPermission($expression)) {
                return true;
            }
        }
        return false;
    }
}
