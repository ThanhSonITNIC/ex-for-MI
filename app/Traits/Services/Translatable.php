<?php

namespace App\Traits\Services;

trait Translatable
{
    protected function getTranslatable()
    {
        return $this->translatable;
    }

    protected function translate(array $hits)
    {
        $hits = collect($hits);
        $translatable = $this->getTranslatable();
        if (count($translatable)) {
            return $hits->map(function ($hit) use ($translatable) {
                $hit = json_decode(json_encode($hit), true);
                foreach($translatable as $field) {
                    $raw_data = $hit['_'. $field] ?? [];
                    $hit = array_merge($hit, [$field => $this->getTranslation($raw_data)]);
                }
                return json_decode(json_encode($hit));
            });
        } else {
            return $hits;
        }
    }

    protected function getTranslation($hit)
    {
        return $hit[app()->getLocale()] ?? "";
    }
}
