<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Handlers\Category\CreateCategoryCommand;
use App\Handlers\Category\CreateCategoryHandler;
use App\Handlers\Category\GetCategoryCollectionCommand;
use App\Handlers\Category\GetCategoryCollectionHandler;
use App\Handlers\Category\RemoveCategoryCommand;
use App\Handlers\Category\RemoveCategoryHandler;
use App\Handlers\Category\UpdateCategoryCommand;
use App\Handlers\Category\UpdateCategoryHandler;
use App\Http\Requests\Category\CategoryCollectionRequest;
use App\Http\Requests\Category\CategoryShowRequest;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\Category\CategoryCollectionResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryTranslationsResource;
use App\Http\Resources\Category\PaginatedCategoryCollectionResource;
use App\Http\Resources\SuccessResource;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class CategoryController
{
    public function __construct(
        private CreateCategoryHandler $createCategoryHandler,
        private UpdateCategoryHandler $updateCategoryHandler,
        private RemoveCategoryHandler $removeCategoryHandler,
        private GetCategoryCollectionHandler $getCategoryCollectionHandler,
    ){
    }

    public function store(CategoryStoreRequest $request): CategoryTranslationsResource
    {
        $data = $request->validated();
        $languages = $data['languages'];
        $names = [];
        foreach ($languages as $language) {
            $names[$language] = $data['name'][$language];
        }
        $category = $this->createCategoryHandler->handle(
            new CreateCategoryCommand(
                $languages,
                $names,
            )
        );

        return new CategoryTranslationsResource($category);
    }

    public function show(CategoryShowRequest $request, Category $category): CategoryTranslationsResource|CategoryResource
    {
        if ($request->with_translations ?? false) {
            return new CategoryTranslationsResource($category);
        }

        return new CategoryResource($category);
    }

    public function update(CategoryUpdateRequest $request, Category $category): CategoryTranslationsResource
    {
        $data = $request->validated();
        $languages = $data['languages'];
        $names = [];
        foreach ($languages as $language) {
            $names[$language] = $data['name'][$language];
        }
        $category = $this->updateCategoryHandler->handle(
            new UpdateCategoryCommand(
                $category,
                $languages,
                $names,
            )
        );

        return new CategoryTranslationsResource($category);
    }

    public function destroy(Category $category): SuccessResource
    {
        $this->removeCategoryHandler->handle(
            new RemoveCategoryCommand($category)
        );

        return new SuccessResource("Category #$category->id successfully deleted");
    }

    public function index(CategoryCollectionRequest $request): CategoryCollectionResource|PaginatedCategoryCollectionResource
    {
        $data = $request->validated();
        $withoutPagination = isset($data['without_pagination']) && (boolean) $data['without_pagination'];
        $page = $withoutPagination ? null : (int) $data['page'] ?? 1;
        $categories = $this->getCategoryCollectionHandler->handle(
            new GetCategoryCollectionCommand(
                $withoutPagination,
                $page
            )
        );

        if ($categories instanceof LengthAwarePaginator) {
            return new PaginatedCategoryCollectionResource($categories);
        }

        return new CategoryCollectionResource($categories);
    }
}
