<?php

declare(strict_types=1);

namespace App\Handlers\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetCategoryCollectionHandler
{
    public function handle(GetCategoryCollectionCommand $command): Collection|LengthAwarePaginator
    {
        $query = Category::with('fields');

        if (false === $command->withoutPagination) {
            return $query->paginate(24, ['*'], 'page', $command->page);
        }

        return $query->get();
    }
}
