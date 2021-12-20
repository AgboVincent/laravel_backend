<?php

namespace App\Http\Resources;

use App\Models\Claim;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimListResource extends JsonResource
{
    public function __construct(Claim $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    public function toArray($request): array
    {
        return collect($this->resource->only([
            'id', 'status', 'status', 'involves_insurer',
            'requires_expert','created_at', 'updated_at','user','policy'
        ]))
            ->toArray();
    }
}
