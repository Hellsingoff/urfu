<?php

declare(strict_types = 1);

namespace App\Handlers\Category;

use App\Models\Category;

readonly class UpdateCategoryHandler
{
    public function handle(UpdateCategoryCommand $command): Category
    {
        $command->category->fields()->delete();

        $fields = [];
        foreach ($command->languages as $language) {
            $fields[] = [
                'language' => $language,
                'attribute' => 'name',
                'value' => $command->names[$language]
            ];
        }

        $command->category->fields()->createMany($fields);

        return $command->category;
    }
}
