<?php

declare(strict_types = 1);

namespace App\Handlers\Vacancy;

use App\Models\Vacancy;

class UpdateVacancyHandler
{
    public function handle(UpdateVacancyCommand $command): Vacancy
    {
        $command->vacancy->update([
            'category_id' => $command->categoryId,
            'organization_id' => $command->organizationId,
            'status' => $command->status,
        ]);

        $command->vacancy->fields()->delete();

        $fields = [];
        foreach ($command->languages as $language) {
            $fields[] = [
                'language' => $language,
                'attribute' => 'name',
                'value' => $command->names[$language]
            ];
            $fields[] = [
                'language' => $language,
                'attribute' => 'description',
                'value' => $command->descriptions[$language]
            ];
        }

        $command->vacancy->fields()->createMany($fields);
        $command->vacancy->skills()->sync($command->skills);

        return $command->vacancy;
    }
}
