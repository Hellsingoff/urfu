<?php

declare(strict_types=1);

namespace App\Http\Resources\Vacancy;

use App\Enum\Language;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Organization\OrganizationResource;
use App\Http\Resources\Skill\SkillResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Vacancy $resource
 */
class VacancyTranslationsResource extends JsonResource
{
    public function __construct(Vacancy $vacancy)
    {
        parent::__construct($vacancy);
    }

    /**
     * @return array{
     *      id: int,
     *      languages: Language[],
     *      status: string,
     *      name: array<string, string>,
     *      description: array<string, string>,
     *      category: CategoryResource,
     *      organization: OrganizationResource,
     *      skills: SkillResource[],
     * }
     */
    public function toArray(Request $request): array
    {
        $language = Language::from(app()->getLocale());
        $translations = $this->resource->mappedFields();

        return [
            'id' => $this->resource->id,
            'languages' => $translations->languages,
            'status' => $this->resource->status->label($language),
            'name' => $translations->fields['name'] ?? [],
            'description' => $translations->fields['description'] ?? [],
            'category' => new CategoryResource($this->resource->category),
            'organization' => new OrganizationResource($this->resource->organization),
            'skills' => SkillResource::collection($this->resource->skills),
        ];
    }
}
