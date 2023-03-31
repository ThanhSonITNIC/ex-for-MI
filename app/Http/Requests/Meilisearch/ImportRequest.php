<?php

namespace App\Http\Requests\Meilisearch;

use App\Http\Requests\FormRequest;
use App\Services\Meilisearch\ModelsSearchable;

class ImportRequest extends FormRequest
{
    protected array $permissions = ['configurations.*.*'];

    public function rules(): array
    {
        return [
            'model_name' => 'required|in:' . implode(',', ModelsSearchable::$register),
            'start' => 'nullable|numeric|min:1',
            'end' => 'nullable|numeric|min:1',
        ];
    }
}
