<?php

namespace App\Http\Controllers;

use App\Http\Requests\Meilisearch\{ImportRequest, ImportByIdsRequest, ImportMissingRequest};
use Illuminate\Database\Eloquent\Model;

class MeilisearchController extends Controller
{
    public function import(ImportRequest $request)
    {
        $model = resolve($request->model_name);
        $start = $request->input('start') ?? 1;
        $end = $request->input('end') ?? $model->count();
        $ids_to_searchable = $model->skip($start - 1)->take($end - ($start - 1))->pluck($model->getKeyName())->toArray();

        $this->searchable($model, $ids_to_searchable);

        return response()->json(['message' => 'Meilisearch imported data from models.'], 200);
    }

    public function importByIds(ImportByIdsRequest $request)
    {
        $model = resolve($request->model_name);
        $this->searchable($model, $request->ids);
        return response()->json(['message' => 'Meilisearch imported data from models.'], 200);
    }

    public function importMissing(ImportMissingRequest $request)
    {
        $eloquent_model = resolve($request->eloquent_model);
        $tomoni_model = resolve($request->tomoni_model);
        $key_name = $eloquent_model->getKeyName();

        $imported_ids = $tomoni_model::get()->pluck($key_name);
        $missed_ids = $eloquent_model::whereNotIn($key_name, $imported_ids)->pluck($key_name);

        $start = $request->input('start') ?? 1;
        $end = $request->input('end') ?? count($missed_ids);
        $ids_to_searchable = $eloquent_model::whereNotIn($key_name, $imported_ids)
            ->skip($start - 1)
            ->take($end - ($start - 1))
            ->pluck($key_name)
            ->toArray();

        $this->searchable($eloquent_model, $ids_to_searchable);

        return response()->json([
            'message' => 'Meilisearch imported data from models.',
            'total_missed_ids' => count($missed_ids),
        ], 200);
    }

    public function searchable(Model $model, array $ids_to_searchable = [])
    {
        foreach (array_chunk($ids_to_searchable, 500) as $ids_chunked) {
            $model->whereIn($model->getKeyName(), $ids_chunked)->searchable();
        }
    }
}
