<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\Language;

readonly class TranslatedFieldsDTO
{
    /**
     * @param Language[] $languages
     * @param array<string, <string, string>> $fields
     */
    public function __construct(
        public array $languages,
        public array $fields,
    ){
    }
}
