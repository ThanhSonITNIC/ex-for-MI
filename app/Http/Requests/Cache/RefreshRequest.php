<?php

namespace App\Http\Requests\Cache;

use App\Http\Requests\FormRequest;

class RefreshRequest extends FormRequest
{
    protected array $permissions = ['configurations.*.*'];

    public function rules(): array
    {
        return [
            'model_name' => 'required|string|max:191|starts_with:App\Models\\',
            'start' => 'nullable|numeric|min:1',
            'end' => 'nullable|numeric|min:1',
        ];
    }
}
