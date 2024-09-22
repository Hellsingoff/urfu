<?php

namespace App\Handlers\Organization;

readonly class GetOrganizationCollectionCommand
{
    public function __construct(
        public bool $withoutPagination,
        public ?int $page,
    ){
    }
}
