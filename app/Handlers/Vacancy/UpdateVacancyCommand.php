<?php

namespace App\Handlers\Vacancy;

use App\Enum\VacancyStatus;
use App\Models\Vacancy;

readonly class UpdateVacancyCommand
{
    /**
     * @param string[] $languages
     * @param array<string, string> $names
     * @param array<string, string> $descriptions
     * @param int[] $skills
     */
    public function __construct(
        public Vacancy $vacancy,
        public array $languages,
        public array $names,
        public array $descriptions,
        public array $skills,
        public int $categoryId,
        public int $organizationId,
        public VacancyStatus $status,
    ){
    }
}
