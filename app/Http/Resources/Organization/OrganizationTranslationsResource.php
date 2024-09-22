<?php

declare(strict_types=1);

namespace App\Http\Resources\Organization;

use App\Enum\Language;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Organization $resource
 */
class OrganizationTranslationsResource extends JsonResource
{
    public function __construct(Organization $organization)
    {
        parent::__construct($organization);
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
