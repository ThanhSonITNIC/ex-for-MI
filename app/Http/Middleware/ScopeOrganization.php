<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class ScopeOrganization
{
    protected static $registerModels = [
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $organization_param = 'X-Organization-Scope';

        if ($organization_of_user = $request->user()->organizationId()) {
            $request->headers->set($organization_param, $organization_of_user);
        } elseif (!$request->hasHeader($organization_param)) {
            $request->headers->set($organization_param, null);
        }

        $organization_header = $request->header($organization_param);
        Validator::make([$organization_param => $organization_header], [
            $organization_param => 'nullable|service.exists:auth,organizations',
        ])->validate();

        self::registerScopes($organization_header);

        return $next($request);
    }

    protected static function registerScopes(string|null $organization_id = null)
    {
        foreach (self::$registerModels as $model) {
            if (!method_exists($model, 'buildScopeOrganization')) {
                throw new \Exception("Must be define static method 'buildScopeOrganization' in " . $model . " when implement 'ScopeOrganization' middleware.");
            }
            $model::addGlobalScope('scopeOrganization', function ($builder) use ($model, $organization_id) {
                return $model::buildScopeOrganization($builder, $organization_id);
            });
        }
    }
}
