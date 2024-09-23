<?php

declare(strict_types=1);

namespace App\Handlers\Category;

use App\Models\Field;
use App\Models\Vacancy;

readonly class RemoveCategoryHandler
{
    public function handle(RemoveCategoryCommand $command): void
    {
        $command->category->fields()->delete();
        $vacancyIds = $command->category->vacancies()->pluck('id')->toArray();
        Field::whereIn('entity_id', $vacancyIds)
            ->where('entity_type', Vacancy::class)
            ->delete();
        $command->category->vacancies()->delete();
        $command->category->delete();
    }
}
