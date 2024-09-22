<?php

namespace App\Handlers\Vacancy;

readonly class GetVacancyCollectionCommand
{
    public function __construct(
        public int $page,
    ){
    }
}
