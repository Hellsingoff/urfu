<?php

declare(strict_types=1);

namespace App\Http\Resources\Vacancy;

use App\Enum\Language;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Organization\OrganizationResource;
use App\Http\Resources\Skill\SkillResource;
use App\Http\Resources\UserResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Vacancy $resource
 */
class VacancyResource extends JsonResource
{
    public function __construct(Vacancy $user)
    {
        parent::__construct($user);
    }

    /**
     * @return array{
     *     id: int,
     *     status: string,
     *     name: string,
     *     description: string,
     *     category: CategoryResource,
     *     organization: OrganizationResource,
     *     skills: SkillResource[],
     *     owner: UserResource,
     *     rating: ?float
     * }
     */
    public function toArray(Request $request): array
    {
        $language = Language::from(app()->getLocale());

        return [
            'id' => $this->resource->id,
            'status' => $this->resource->status->label($language),
            'name' => $this->resource->fieldValue('name', $language),
            'description' => $this->resource->fieldValue('description', $language),
            'category' => new CategoryResource($this->resource->category),
            'organization' => new OrganizationResource($this->resource->organization),
            'skills' => SkillResource::collection($this->resource->skills),
            'owner' => new UserResource($this->resource->owner),
            'rating' => $this->resource->rating(),
        ];
    }
}
