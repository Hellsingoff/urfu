<?php

declare(strict_types=1);

namespace App\Handlers\Vacancy;

use App\Models\Vacancy;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class GetVacancyCollectionHandler
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

        if (isset($command->filters->status)) {
            $query->where('status', $command->filters->status);
        }

        if (isset($command->filters->categoryId)) {
            $query->where('category_id', $command->filters->categoryId);
        }

        if (isset($command->filters->organizationId)) {
            $query->where('organization_id', $command->filters->organizationId);
        }

        if (0 !== count($command->filters->skills)) {
            $query->select('vacancies.*')
                ->join('skill_vacancy', 'vacancies.id', '=', 'skill_vacancy.vacancy_id')
                ->whereIn('skill_vacancy.skill_id', $command->filters->skills)
                ->groupBy('vacancies.id')
                ->havingRaw('COUNT(DISTINCT skill_vacancy.skill_id) = ?', [count($command->filters->skills)]);
        }

        if (isset($command->filters->text)) {
            $query->join('fields', static function (JoinClause $join): void {
                $join->on('vacancies.id', '=', 'fields.entity_id')
                    ->where('fields.entity_type', '=', Vacancy::class);
            })
            ->where('fields.value', 'ILIKE', "%{$command->filters->text}%")
            ->distinct()
            ->select('vacancies.*');
        }

        return $query->paginate(24, ['*'], 'page', $command->page);
    }
}
