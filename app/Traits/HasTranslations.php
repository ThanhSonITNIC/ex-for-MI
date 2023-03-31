<?php

namespace App\Traits;

use Spatie\Translatable\HasTranslations as TranslatableHasTranslations;

trait HasTranslations
{
    use TranslatableHasTranslations;

    public function attributesToArray()
    {
        $translatedAttributes = collect($this->getTranslatableAttributes())
            ->mapWithKeys(function (string $key) {
                return [$key => $this->getAttributeValue($key)];
            })
            ->toArray();

        return array_merge(parent::attributesToArray(), $translatedAttributes, $this->getAllRawTranslatable());
    }

    public function getAllRawTranslatable(): array
    {
        $data = [];

        foreach ($this->translatable as $attribute) {
            $data['_' . $attribute] = $this->getRawTranslatable($attribute);
        }

        return $data;
    }

    public function getRawTranslatable($attribute)
    {
        return json_decode($this->getRawOriginal($attribute));
    }
}
