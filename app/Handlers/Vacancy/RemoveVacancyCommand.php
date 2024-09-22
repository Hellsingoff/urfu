<?php

namespace App\Handlers\Vacancy;

use App\Models\Vacancy;

readonly class RemoveVacancyCommand
{
    public function __construct(
        public Vacancy $vacancy,
    ){
    }
}
