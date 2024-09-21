<?php

namespace App\Handlers\Category;

use App\Models\Category;

class CreateCategoryHandler
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
