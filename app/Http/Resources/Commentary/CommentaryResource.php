<?php

declare(strict_types=1);

namespace App\Http\Resources\Commentary;

use App\Http\Resources\UserResource;
use App\Models\Commentary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Commentary $resource
 */
class CommentaryResource extends JsonResource
{
    public function __construct(Commentary $commentary)
    {
        parent::__construct($commentary);
    }

    /**
     * @return array{
     *     id: int,
     *     owner: UserResource,
     *     text: string
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'owner' => new UserResource($this->resource->owner),
            'text' => $this->resource->text,
            'created_at' => $this->resource->created_at,
        ];
    }
}
