<?php

declare(strict_types=1);

namespace App\Http\Resources\Category;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedCategoryCollectionResource extends ResourceCollection
{
    private int $total;
    private int $currentPage;
    private int $lastPage;

    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->total = $paginator->total();
        $this->currentPage = $paginator->currentPage();
        $this->lastPage = $paginator->lastPage();

        parent::__construct($paginator);
    }

    /**
     * @return array{
     *     data: CategoryResource[],
     *     pagination: array{
     *         total: int,
     *         currentPage: int,
     *         lastPage: int
     *     }
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CategoryResource::collection($this->resource),
            'pagination' => [
                'total' => $this->total,
                'currentPage' => $this->currentPage,
                'lastPage' => $this->lastPage,
            ]
        ];
    }
}
