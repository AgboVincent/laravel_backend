<?php

namespace App\Http\Resources;

use App\Models\Policy;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
{
    public function __construct(Policy $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource
            //todo: remove ->load methods from resources, and migrate to class instance for better performance
            ->load(['company', 'vehicle']);
    }

    public function toArray($request): array
    {
        $data = $this->resource->only([
            'id', 'number', 'status', 'type', 'created_at', 'expires_at'
        ]);

        $data['company'] = new CompanyResource($this->resource->company);
        $data['vehicle'] = new VehicleResource($this->resource->vehicle);

        return $data;
    }
}
