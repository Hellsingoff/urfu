<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Handlers\Category\CreateCategoryCommand;
use App\Handlers\Category\CreateCategoryHandler;
use App\Http\Requests\Category\CategoryStoreRequest;
use Illuminate\Http\JsonResponse;

readonly class CategoryController
{
    public function __construct(
        private CreateCategoryHandler $createCategoryHandler,
    ){
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $languages = $request->get('languages');
        $names = [];
        foreach ($languages as $language) {
            $names[$language] = $request->get('name')[$language];
        }
        $this->createCategoryHandler->handle(
            new CreateCategoryCommand(
                $languages,
                $names
            )
        );
    }
}
