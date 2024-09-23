<?php

namespace App\Handlers\VacancyResponse;

use App\Enum\VacancyResponseStatus;
use App\Models\VacancyResponse;

readonly class UpdateVacancyResponseCommand
{
    public function __construct(
        public VacancyResponse $vacancyResponse,
        public VacancyResponseStatus $status,
    ){
    }
}
