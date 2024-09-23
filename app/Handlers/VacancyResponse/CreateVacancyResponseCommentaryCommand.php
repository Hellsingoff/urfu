<?php

namespace App\Handlers\VacancyResponse;

use App\Models\VacancyResponse;

readonly class CreateVacancyResponseCommentaryCommand
{
    public function __construct(
        public VacancyResponse $vacancyResponse,
        public string $commentary,
        public int $userId,
        public bool $mustNotifyVacancyOwner,
    ){
    }
}
