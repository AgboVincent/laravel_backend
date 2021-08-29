<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->resource->only(['id', 'name', 'number', 'bank', 'created_at', 'updated_at']);
    }
}
