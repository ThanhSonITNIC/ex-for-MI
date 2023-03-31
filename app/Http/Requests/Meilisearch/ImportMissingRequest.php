<?php

namespace App\Http\Requests\Meilisearch;

use App\Http\Requests\FormRequest;
use App\Services\Meilisearch\ModelsSearchable;

class ImportMissingRequest extends FormRequest
{
    protected array $permissions = ['configurations.*.*'];

    public function rules(): array
    {
        return [
            'eloquent_model' => 'required|in:' . implode(',', ModelsSearchable::$register),
            'tomoni_model' => 'required|string',
            'start' => 'nullable|numeric|min:1',
            'end' => 'nullable|numeric|min:1',
        ];
    }
}
