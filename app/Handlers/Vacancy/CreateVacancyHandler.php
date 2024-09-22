<?php

declare(strict_types=1);

namespace App\Handlers\Vacancy;

use App\Enum\VacancyStatus;
use App\Models\Vacancy;

class CreateVacancyHandler
{
    public function handle(CreateVacancyCommand $command): Vacancy
    {
        $vacancy = Vacancy::create([
            'user_id' => $command->userId,
            'category_id' => $command->categoryId,
            'organization_id' => $command->organizationId,
            'status' => VacancyStatus::Open,
        ]);
        $fields = [];
        foreach ($command->languages as $language) {
            $fields[] = [
                'language' => $language,
                'attribute' => 'name',
                'value' => $command->names[$language] ?? '',
            ];
            $fields[] = [
                'language' => $language,
                'attribute' => 'description',
                'value' => $command->descriptions[$language] ?? '',
            ];
        }

        $vacancy->fields()->createMany($fields);
        $vacancy->skills()->sync($command->skills);

        return $vacancy;
    }
}
