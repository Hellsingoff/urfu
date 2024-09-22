<?php

declare(strict_types=1);

namespace App\Handlers\Organization;

use App\Models\Field;
use App\Models\Vacancy;

class RemoveOrganizationHandler
{
    public function handle(RemoveOrganizationCommand $command): void
    {
        $command->organization->fields()->delete();
        $vacancyIds = $command->organization->vacancies()->pluck('id')->toArray();
        Field::whereIn('entity_id', $vacancyIds)
            ->where('entity_type', Vacancy::class)
            ->delete();
        $command->organization->vacancies()->delete();
        $command->organization->delete();
    }
}
