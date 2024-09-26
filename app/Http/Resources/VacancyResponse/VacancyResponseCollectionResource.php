<?php

declare(strict_types=1);

namespace App\Http\Resources\VacancyResponse;

use App\Models\VacancyResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VacancyResponseCollectionResource extends ResourceCollection
{

    /**
     * @param Collection<int, VacancyResponse> $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct($collection);
    }

    /**
     * @return array{
     *     data: VacancyResponseResource[]
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => VacancyResponseResource::collection($this->resource),
        ];
    }
}
