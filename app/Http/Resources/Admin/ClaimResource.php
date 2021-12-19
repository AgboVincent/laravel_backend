<?php

namespace App\Http\Resources\Admin;

use App\Models\Claim;
use App\Http\Resources\UserResource;
use App\Http\Resources\PolicyResource;
use App\Http\Resources\AccidentResource;
use App\Http\Resources\ClaimItemResource;
use App\Http\Resources\GuaranteeResource;
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
        return collect($this->resource->load([
            'clientResponsibility', 'guarantees'
            ])->only([
            'id', 'status', 'status', 'involves_insurer', 'clientResponsibility',
            'requires_expert','created_at', 'updated_at'
        ]))
            ->put('user', new UserResource($this->resource->user))
            ->put('policy', new PolicyResource($this->resource->policy))
            ->put('accident', new AccidentResource($this->resource->accident))
            ->put('items', ClaimItemResource::collection($this->resource->items))
            ->put('guarantees', GuaranteeResource::collection($this->resource->guarantees))
            ->put('user_can_edit', $this->resource->user_can_edit) //mock
            ->toArray();
    }
}
