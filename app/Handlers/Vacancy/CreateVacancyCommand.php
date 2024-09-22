<?php

namespace App\Handlers\Vacancy;

readonly class CreateVacancyCommand
{
    /**
     * @param string[] $languages
     * @param array<string, string> $names
     * @param array<string, string> $descriptions
     * @param int[] $skills
     */
    public function __construct(
        public array $languages,
        public array $names,
        public array $descriptions,
        public array $skills,
        public int $categoryId,
        public int $organizationId,
        public int $userId,
    ){
    }
}
