<?php

declare(strict_types=1);

namespace App\Handlers\Vacancy;

use App\Models\Vacancy;
use Illuminate\Pagination\LengthAwarePaginator;

class GetVacancyCollectionHandler
{
    public function handle(GetVacancyCollectionCommand $command): LengthAwarePaginator
    {
        $query = Vacancy::with([
            'fields',
            'skills',
            'category',
            'organization',
            'reviews',
            'owner',
            'skills.fields',
            'category.fields',
            'organization.fields'
        ]);

        // todo filters

        return $query->paginate(24, ['*'], 'page', $command->page);
    }
}
