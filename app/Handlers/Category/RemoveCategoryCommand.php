<?php

namespace App\Handlers\Category;

use App\Models\Category;

readonly class RemoveCategoryCommand
{
    public function __construct(
        public Category $category,
    ){
    }
}
