<?php

namespace App\Handlers\Vacancy;

use App\DTO\VacancyFiltersDTO;

readonly class GetVacancyCollectionCommand
{
    public function __construct(
        public int $page,
        public VacancyFiltersDTO $filters,
    ){
    }
}
