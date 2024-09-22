<?php

namespace App\Handlers\Category;

use App\Models\Category;

readonly class UpdateCategoryCommand
{
    /**
     * @param string[] $languages
     * @param array<string, string> $names
     */
    public function __construct(
        public Category $category,
        public array $languages,
        public array $names,
    ){
    }
}
