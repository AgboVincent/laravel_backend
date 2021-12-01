<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClaimItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return $this->resource->only([
            'id', 'type', 'status', 'quantity', 'quote', 'amount', 'ml_prediction', 'created_at'
        ]);
    }
}
