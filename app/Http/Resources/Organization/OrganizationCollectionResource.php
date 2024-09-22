<?php

declare(strict_types=1);

namespace App\Http\Resources\Organization;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganizationCollectionResource extends ResourceCollection
{

    /**
     * @param Collection<int, Organization> $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct($collection);
    }

    /**
     * @return array{
     *     data: OrganizationResource[]
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => OrganizationResource::collection($this->resource),
        ];
    }
}
