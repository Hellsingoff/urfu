<?php

declare(strict_types=1);

namespace App\Http\Resources\Skill;

use App\Enum\Language;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Skill $resource
 */
class SkillResource extends JsonResource
{
    public function __construct(Skill $skill)
    {
        parent::__construct($skill);
    }

    /**
     * @return array{
     *     id: int,
     *     name: string
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->fieldValue('name', Language::from(app()->getLocale())),
        ];
    }
}
