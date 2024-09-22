<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $resource
 */
class TokenResource extends JsonResource
{
    public function __construct(string $token)
    {
        parent::__construct($token);
    }

    /**
     * @return array{
     *     token: string
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->resource,
        ];
    }
}
