<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

abstract class FormRequest extends BaseFormRequest
{
    protected array $permissions = [];
    protected array $anyPermissions = [];

    public function authorize(): bool
    {
        if (count($this->permissions) || count($this->anyPermissions)) {
            if (!$this->user()) {
                throw new \Illuminate\Auth\AuthenticationException;
            }
        }

        if (count($this->permissions)) {
            return $this->user()->hasAllPermissions($this->permissions);
        }

        if (count($this->anyPermissions)) {
            return $this->user()->hasAnyPermissions($this->anyPermissions);
        }

        return true;
    }

    public function rules(): array
    {
        return [];
    }

    protected function fixedParams(): array
    {
        return [];
    }

    protected function defaultParams(): array
    {
        return [];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        return collect($this->defaultParams())->merge($data)->merge($this->fixedParams())->toArray();
    }
}
