<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VehicleResource;
use App\Models\Policy;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
{
    public function __construct(Policy $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource
            ->load(['company', 'vehicle', 'user']);
    }

    public function toArray($request): array
    {
        $data = $this->resource->only([
            'id', 'number', 'status', 'type', 'created_at', 'expires_at','user_can_create_claim'
        ]);

        $data['company'] = new CompanyResource($this->resource->company);
        $data['user'] = new UserResource($this->resource->user);
        $data['vehicle'] = new VehicleResource($this->resource->vehicle);

        return $data;
    }
}
