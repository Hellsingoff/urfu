<?php

namespace App\Handlers\VacancyResponse;

use App\Models\VacancyResponse;

readonly class CancelVacancyResponseCommand
{
    public function __construct(
        public VacancyResponse $vacancyResponse,
    ){
    }
}
