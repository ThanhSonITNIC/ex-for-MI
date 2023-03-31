<?php

namespace App\Services\Meilisearch;

use MeiliSearch\Endpoints\indexes;
use Illuminate\Database\Eloquent\Model;

class AttributesIndex
{
    public function __construct(indexes $index)
    {
        $this->index = $index;
    }

    public function update(Model $model)
    {
        $this->index->updateRankingRules($model->rankingRules());
        $this->index->updateDistinctAttribute($model->distinctAttribute());
        $this->index->updateSearchableAttributes($model->searchableAttributes());
        $this->index->updateDisplayedAttributes($model->displayedAttributes());
        $this->index->updateStopWords($model->stopWords());
        $this->index->updateSynonyms($model->synonyms());
        $this->index->updateFilterableAttributes($model->filterableAttributes());
        $this->index->updateSortableAttributes($model->sortableAttributes());
    }

    public function reset()
    {
        $this->index->resetRankingRules();
        $this->index->resetDistinctAttribute();
        $this->index->resetSearchableAttributes();
        $this->index->resetDisplayedAttributes();
        $this->index->resetStopWords();
        $this->index->resetSynonyms();
        $this->index->resetFilterableAttributes();
        $this->index->resetSortableAttributes();
    }
}
