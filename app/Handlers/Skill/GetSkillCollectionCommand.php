<?php

namespace App\Handlers\Skill;

readonly class GetSkillCollectionCommand
{
    public function __construct(
        public bool $withoutPagination,
        public ?int $page,
    ){
    }
}
