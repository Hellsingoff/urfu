<?php

declare(strict_types=1);

namespace App\Http\Resources\Category;

use App\Enum\Language;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Category $resource
 */
class CategoryResource extends JsonResource
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
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
