<?php

namespace App\Http\Resources;

use App\Models\Claim;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
{
    public function __construct(Claim $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource
            ->load([
                'policy', 'accident.media', 'accident.thirdParty', 'accident.media.file',
                'items'
            ]);
    }

    public function toArray($request): array
    {
        return collect($this->resource->only([
            'id', 'status', 'status', 'involves_insurer',
            'created_at', 'updated_at'
        ]))
            ->put('policy', new PolicyResource($this->resource->policy))
            ->put('accident', new AccidentResource($this->resource->accident))
            ->put('items', ClaimItemResource::collection($this->resource->items))
            ->toArray();
    }
}
