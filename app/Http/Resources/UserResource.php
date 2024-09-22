<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enum\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserResource extends JsonResource
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     role: string
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'role' => $this->resource->role->label(Language::from(app()->getLocale())),
        ];
    }
}
