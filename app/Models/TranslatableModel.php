<?php

namespace App\Models;

use App\DTO\TranslatedFieldsDTO;
use App\Enum\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @property-read Collection<int, Field> $fields
 */
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

    public function mappedFields(): TranslatedFieldsDTO
    {
        $languages = [];
        $mappedFields = [];
        foreach ($this->fields as $field) {
            if (false === in_array($field->language, $languages)) {
                $languages[] = $field->language;
            }
            $mappedFields[$field->attribute][$field->language->value] = $field->value;
        }

        return new TranslatedFieldsDTO($languages, $mappedFields);
    }

    public function fieldValue(string $field, Language $language): string
    {
        $fieldTranslations = $this->fields()
            ->where('attribute', $field)
            ->get()
            ->mapWithKeys(fn (Field $item) => [$item->language->value => $item]);

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
