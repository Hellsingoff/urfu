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
class OrganizationResource extends JsonResource
{
    public function __construct(Organization $organization)
    {
        parent::__construct($organization);
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
