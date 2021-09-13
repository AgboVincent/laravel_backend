<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->resource->only(['id', 'type','data','created_at','read_at']);
    }
}
