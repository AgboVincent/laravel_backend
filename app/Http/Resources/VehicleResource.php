<?php

namespace App\Http\Resources;

use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function __construct(Vehicle $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
