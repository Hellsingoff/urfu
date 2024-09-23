<?php

namespace App\Handlers\VacancyResponse;

readonly class CreateVacancyResponseCommand
{
    public function __construct(
        public int $vacancyId,
        public int $resumeId,
        public int $userId,
        public ?string $commentary,
    ){
    }
}
