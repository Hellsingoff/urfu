<?php

declare(strict_types=1);

namespace App\Handlers\Category;

use App\Models\Category;

readonly class CreateCategoryHandler
{
    public function handle(CreateCategoryCommand $command): Category
    {
        $category = Category::create();
        $fields = [];
        foreach ($command->languages as $language) {
            $fields[] = [
                'language' => $language,
                'attribute' => 'name',
                'value' => $command->names[$language]
            ];
        }

        $category->fields()->createMany($fields);

        return $category;
    }
}
