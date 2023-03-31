<?php

namespace App\Traits;

use App\Services\Meilisearch\AttributesIndex;
use MeiliSearch\Client as MeiliSearch;
use Illuminate\Support\Facades\Schema;
use Laravel\Scout\Searchable as SearchableService;
use App\Services\Meilisearch\ModelsSearchable;

trait Searchable
{
    use SearchableService {
        SearchableService::searchable as addDocument;
        SearchableService::unsearchable as removeDocument;
    }

    public function displayedAttributes(): array
    {
        return ["*"];
    }

    public function searchableAttributes(): array
    {
        return ["*"];
    }

    public function filterableAttributes(): array
    {
        return $this->getTableColumns();
    }

    public function sortableAttributes(): array
    {
        return $this->getTableColumns();
    }

    public function rankingRules(): array
    {
        return [
            "words",
            "typo",
            "proximity",
            "attribute",
            "sort",
            "exactness"
        ];
    }

    public function stopWords(): array
    {
        return [];
    }

    public function synonyms(): array
    {
        return [];
    }

    public function distinctAttribute(): string
    {
        return $this->getKeyName();
    }

    public function getTableColumns(): array
    {
        return Schema::getColumnListing($this->getTable());
    }

    public static function indexKey(): string
    {
        return (new static)->searchableAs();
    }

    public static function index()
    {
        return app(MeiliSearch::class)->index(static::indexKey());
    }

    public static function updateAttributes()
    {
        return (new AttributesIndex(static::index()))->update(new static);
    }

    public static function resetAttributes()
    {
        return (new AttributesIndex(static::index()))->reset();
    }

    public function checkRegisteredSearchable(): bool
    {
        return in_array(get_class($this), ModelsSearchable::$register);
    }

    public function searchable()
    {
        if ($this->checkRegisteredSearchable()) {
            $this->addDocument();
        }
    }

    public function unsearchable()
    {
        if ($this->checkRegisteredSearchable()) {
            $this->removeDocument();
        }
    }
}
