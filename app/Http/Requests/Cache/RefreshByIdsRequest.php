<?php

namespace App\Http\Requests\Cache;

use App\Http\Requests\FormRequest;

class RefreshByIdsRequest extends FormRequest
{
    protected array $permissions = ['configurations.*.*'];

    public function rules(): array
    {
        return [
            'model_name' => 'required|string|max:191|starts_with:App\Models\\',
            'ids' => 'required|array',
            'ids.*' => 'required|string|max:50|min:1|distinct',
        ];
    }
}
