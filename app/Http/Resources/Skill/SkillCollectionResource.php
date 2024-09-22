<?php

declare(strict_types=1);

namespace App\Http\Resources\Skill;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SkillCollectionResource extends ResourceCollection
{

    /**
     * @param Collection<int, Skill> $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct($collection);
    }

    /**
     * @return array{
     *     data: SkillResource[]
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => SkillResource::collection($this->resource),
        ];
    }
}
