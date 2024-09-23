<?php

declare(strict_types=1);

namespace App\Handlers\Vacancy;

readonly class RemoveVacancyHandler
{
    public function handle(RemoveVacancyCommand $command): void
    {
        $command->vacancy->fields()->delete();
        $command->vacancy->reviews()->delete();
        $command->vacancy->responses()->delete();
        $command->vacancy->delete();
    }
}
