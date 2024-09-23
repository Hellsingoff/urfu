<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\VacancyStatus;

readonly class VacancyFiltersDTO
{
    /**
     * @param int[] $skills
     */
    public function __construct(
        public ?VacancyStatus $status,
        public ?int $categoryId,
        public ?int $organizationId,
        public array $skills,
        public ?string $text,
    ){
    }
}
