<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cache\{RefreshRequest, RefreshByIdsRequest};
use Illuminate\Database\Eloquent\Model;

class CacheController extends Controller
{
    public function refresh(RefreshRequest $request)
    {
        $model = resolve($request->model_name);
        $start = $request->input('start') ?? 1;
        $end = $request->input('end') ?? $model->count();
        $ids_to_cache = $model->skip($start - 1)->take($end - ($start - 1))->pluck($model->getKeyName())->toArray();

        $this->cacheable($model, $ids_to_cache);

        return response()->json(['message' => 'Refreshed successfully.'], 200);
    }

    public function refreshByIds(RefreshByIdsRequest $request)
    {
        $this->cacheable(resolve($request->model_name), $request->ids);

        return response()->json(['message' => 'Refreshed successfully.'], 200);
    }

    public function cacheable(Model $model, array $ids_to_cacheable = [])
    {
        foreach (array_chunk($ids_to_cacheable, 500) as $ids_chunked) {
            $model->makeCacheable(
                models: $model->whereIn($model->getKeyName(), $ids_chunked)->get(),
                using_queue: true
            );
        }
    }
}
