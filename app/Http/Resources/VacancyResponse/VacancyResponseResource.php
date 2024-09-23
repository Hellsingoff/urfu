<?php

declare(strict_types=1);

namespace App\Http\Resources\VacancyResponse;

use App\Enum\Language;
use App\Http\Resources\Commentary\CommentaryResource;
use App\Http\Resources\Resume\ResumeResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\Vacancy\VacancyResource;
use App\Models\VacancyResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property VacancyResponse $resource
 */
class VacancyResponseResource extends JsonResource
{
    public function __construct(VacancyResponse $response)
    {
        parent::__construct($response);
    }

    /**
     * @return array{
     *     id: int,
     *     status: string,
     *     resume: ResumeResource,
     *     vacancy: VacancyResource,
     *     owner: UserResource,
     *     commentaries:
     * }
     */
    public function toArray(Request $request): array
    {
        $language = Language::from(app()->getLocale());

        return [
            'id' => $this->resource->id,
            'status' => $this->resource->status->label($language),
            'resume' => new ResumeResource($this->resource->resume),
            'vacancy' => new VacancyResource($this->resource->vacancy),
            'owner' => new UserResource($this->resource->owner),
        ];
    }
}
