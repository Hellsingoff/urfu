<?php

namespace App\Handlers\Category;

readonly class GetCategoryCollectionCommand
{
    public function __construct(
        public bool $withoutPagination,
        public ?int $page,
    ){
    }
}
