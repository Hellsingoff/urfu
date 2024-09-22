<?php

declare(strict_types=1);

namespace App\Http\Resources\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollectionResource extends ResourceCollection
{

    /**
     * @param Collection<int, Category> $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct($collection);
    }

    /**
     * @return array{
     *     data: CategoryResource[]
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CategoryResource::collection($this->resource),
        ];
    }
}
