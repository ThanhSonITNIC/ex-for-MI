<?php

namespace App\Traits;

trait Scopeable
{
    public static function removeGlobalScopes(array $scopes)
    {
        foreach ($scopes as $scope) {
            if (static::hasGlobalScope($scope)) {
                $scope = is_string($scope) ? $scope : get_class($scope);
                unset(static::$globalScopes[static::class][$scope]);
            }
        }

        return new static;
    }
}
