<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\AccidentResource;
use App\Http\Resources\ClaimItemResource;
use App\Http\Resources\PolicyResource;
use App\Http\Resources\UserResource;
use App\Models\Claim;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
{
    public function __construct(Claim $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    public function toArray($request): array
    {
        return collect($this->resource->only([
            'id', 'status', 'status',
            'created_at', 'updated_at'
        ]))
            ->put('user', new UserResource($this->resource->user))
            ->put('policy', new PolicyResource($this->resource->policy))
            ->put('accident', new AccidentResource($this->resource->accident))
            ->put('items', ClaimItemResource::collection($this->resource->items))
            ->toArray();
    }
}
