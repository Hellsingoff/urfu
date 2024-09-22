<?php

declare(strict_types=1);

namespace App\Handlers\Organization;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetOrganizationCollectionHandler
{
    public function handle(GetOrganizationCollectionCommand $command): Collection|LengthAwarePaginator
    {
        $query = Organization::with('fields');

        if (false === $command->withoutPagination) {
            return $query->paginate(24, ['*'], 'page', $command->page);
        }

        return $query->get();
    }
}
