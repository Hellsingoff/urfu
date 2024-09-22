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
class SkillTranslationsResource extends JsonResource
{
    public function __construct(Skill $category)
    {
        parent::__construct($category);
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
