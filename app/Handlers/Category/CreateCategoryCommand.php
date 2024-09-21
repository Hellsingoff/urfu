<?php

namespace App\Handlers\Category;

readonly class CreateCategoryCommand
{
    /**
     * @param string[] $languages
     * @param array<string, string> $names
     */
    public function __construct(
        public array $languages,
        public array $names,
    ){
    }
}
