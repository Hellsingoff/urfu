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
class CategoryTranslationsResource extends JsonResource
{
    public function __construct(Category $user)
    {
        parent::__construct($user);
    }

    /**
     * @return array{
     *     id: int,
     *     languages: Language[],
     *     name: array<string, string>
     * }
     */
    public function toArray(Request $request): array
    {
        $translations = $this->resource->mappedFields();

        return [
            'id' => $this->resource->id,
            'languages' => $translations->languages,
            'name' => $translations->fields['name'] ?? [],
        ];
    }
}
