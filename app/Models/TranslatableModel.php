<?php

namespace App\Models;

use App\Enum\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class TranslatableModel extends Model
{
    private const LANGUAGES_PRIORITY = [
        Language::Russian,
        Language::English,
        Language::Deutsch,
    ];

    public function fields(): MorphMany
    {
        return $this->morphMany(Field::class, 'entity');
    }

    public function fieldValue(string $field, Language $language): string
    {
        $fieldTranslations = $this->fields()
            ->where('attribute', $field)
            ->get()
            ->keyBy('language');

        if (isset($fieldTranslations[$language->value])) {
            return $fieldTranslations[$language->value]->value;
        }

        foreach (self::LANGUAGES_PRIORITY as $language) {
            if (isset($fieldTranslations[$language->value])) {
                return $fieldTranslations[$language->value]->value;
            }
        }

        return '';
    }
}
