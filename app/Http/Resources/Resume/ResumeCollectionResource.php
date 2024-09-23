<?php

declare(strict_types=1);

namespace App\Http\Resources\Resume;

use App\Models\Resume;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResumeCollectionResource extends ResourceCollection
{

    /**
     * @param Collection<int, Resume> $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct($collection);
    }

    /**
     * @return array{
     *     data: ResumeResource[]
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ResumeResource::collection($this->resource),
        ];
    }
}
