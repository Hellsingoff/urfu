<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $resource
 */
class SuccessResource extends JsonResource
{
    public function __construct(string $message = 'success')
    {
        parent::__construct($message);
    }

    /**
     * @return array{
     *     message: string
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->resource,
        ];
    }
}
