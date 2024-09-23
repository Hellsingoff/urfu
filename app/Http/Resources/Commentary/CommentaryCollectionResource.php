<?php

declare(strict_types=1);

namespace App\Http\Resources\Commentary;

use App\Models\Commentary;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentaryCollectionResource extends ResourceCollection
{

    /**
     * @param Collection<int, Commentary> $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct($collection);
    }

    /**
     * @return array{
     *     data: CommentaryResource[]
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CommentaryResource::collection($this->resource),
        ];
    }
}
