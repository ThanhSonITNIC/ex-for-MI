<?php

namespace App\Services\Tomoni\Models\Auth;

use App\Contracts\OrganizationContract;
use App\Services\Tomoni\ApiService;
use App\Services\Tomoni\Api\AuthService;
use App\Services\Tomoni\Model;
use App\Traits\Permissible;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;

class Me extends Model implements Guard, OrganizationContract, Authenticatable
{
    use Permissible;

    protected $me;

    public object $role;
    protected Collection $permissions;

    protected static function apiResource(): string
    {
        return 'users';
    }

    protected static function service(): ApiService
    {
        return new AuthService;
    }

    public function __construct($data = null)
    {
        if(!$data) {
            $this->me = static::getFromOrigin([
                'filter[own]' => 1,
                'include' => 'role',
                'append' => 'permissions',
            ])[0];
    
            $this->role = $this->me->role;
            $this->permissions = collect($this->me->permissions);
        } else {
            $this->me = $data;
        }
    }

    public function check()
    {
        return !!$this->me;
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        if ($this->me) {
            return $this->me->id;
        }
    }

    public function organizationId()
    {
        if ($this->me) {
            return $this->me->organization_id;
        }
    }

    public function user()
    {
        return $this->me;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function setUser($user)
    {
        $this->me = $user;
        return $this;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return null;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return null;
    }
}
