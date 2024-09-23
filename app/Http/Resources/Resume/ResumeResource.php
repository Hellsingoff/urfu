<?php

declare(strict_types=1);

namespace App\Http\Resources\Resume;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Resume $resource
 */
class ResumeResource extends JsonResource
{
    public function __construct(Resume $resume)
    {
        parent::__construct($resume);
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     filename: string
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'filename' => "storage/resumes/{$this->resource->filename}.pdf",
        ];
    }
}
