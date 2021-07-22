<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatedResource extends JsonResource
{

    /**
     * @var $resource LengthAwarePaginator
     */
    public $resource;
    private string $responseClass;

    public function __construct(LengthAwarePaginator $paginated, string $class)
    {
        parent::__construct($paginated);
        $this->responseClass = $class;
    }

    public function toArray($request): array
    {
        return [
            'meta' => [
                'last' => $this->resource->lastPage(),
                'perPage' => $this->resource->perPage(),
                'hasMorePages' => $this->resource->hasMorePages(),
                'total' => $this->resource->total(),
                'currentPage' => $this->resource->currentPage()
            ],
            'data' => collect($this->resource->items())->map(fn($resource) => new $this->responseClass($resource))
        ];
    }
}
