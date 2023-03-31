<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

abstract class APIResourceController extends Controller
{
    protected bool $pagination = true;
    protected string $model;
    protected string $indexRequest = Request::class;
    protected string $createRequest = Request::class;
    protected string $updateRequest = Request::class;
    protected string $destroyRequest = Request::class;

    public function boot()
    {
        $this->builder = $this->builder();
    }

    abstract protected function builder(): Model|QueryBuilder;

    public function index()
    {
        resolve($this->indexRequest);
        if ($this->pagination) {
            return $this->builder->paginate(30);
        }
        return $this->builder->get();
    }

    public function storing(Request $request)
    {
    }

    public function stored(Request $request, $model, Collection $appends)
    {
    }

    public function store(Request $request)
    {
        $request = resolve($this->createRequest);
        $this->storing($request);
        $model = $this->model::create($request->all());
        $appends = collect();
        $this->stored($request, $model, $appends);
        $model = $this->builder->findOrFail($model->getKey());
        $appends->each(function ($append) use ($model) {
            foreach ($append as $key => $value) {
                $model->setAttribute($key, $value);
            }
        });

        return $model;
    }

    public function show($id)
    {
        resolve($this->indexRequest);
        return $this->builder->findOrFail($id);
    }

    public function updating(Request $request, $model)
    {
    }

    public function updated(Request $request, $model, $old_model, Collection $appends)
    {
    }

    public function update(Request $request, $id)
    {
        $model = $this->builder->findOrFail($id);
        $request = resolve($this->updateRequest);
        $this->updating($request, $model);
        $old_model = $model->replicate();
        $model->update($request->all());
        $appends = collect();
        $this->updated($request, $model, $old_model, $appends);
        $model = $this->builder()->findOrFail($model->getKey());
        $appends->each(function ($append) use ($model) {
            foreach ($append as $key => $value) {
                $model->setAttribute($key, $value);
            }
        });

        return $model;
    }

    public function destroying(Request $request, $model)
    {
    }

    public function destroyed(Request $request, $model, Collection $appends)
    {
    }

    public function destroy(Request $request, $id)
    {
        $model = $this->builder->findOrFail($id);
        $request = resolve($this->destroyRequest);
        $this->destroying($request, $model);
        $model->delete();
        $appends = collect();
        $this->destroyed($request, $model, $appends);
        $appends->each(function ($append) use ($model) {
            foreach ($append as $key => $value) {
                $model->setAttribute($key, $value);
            }
        });

        return $model;
    }
}
