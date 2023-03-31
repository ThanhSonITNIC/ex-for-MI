<?php

namespace App\Http\Requests\Meilisearch;

use App\Http\Requests\FormRequest;
use App\Services\Meilisearch\ModelsSearchable;

class ImportByIdsRequest extends FormRequest
{
    protected array $permissions = ['configurations.*.*'];

    public function rules(): array
    {
        return [
            'model_name' => 'required|in:' . implode(',', ModelsSearchable::$register),
            'ids' => 'required|array',
            'ids.*' => 'required|string|max:50|min:1|distinct',
        ];
    }
}
