<?php

declare(strict_types=1);

namespace App\Handlers\Skill;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class GetSkillCollectionHandler
{
    public function handle(GetSkillCollectionCommand $command): Collection|LengthAwarePaginator
    {
        $query = Skill::with('fields');

        if (false === $command->withoutPagination) {
            return $query->paginate(24, ['*'], 'page', $command->page);
        }

        return $query->get();
    }
}
