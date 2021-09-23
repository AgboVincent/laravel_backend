<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Address extends JsonResource
{
    public function __construct(\App\Models\Address $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    public function toArray($request)
    {
        return $this->resource->only([
            'id', 'number', 'street', 'city', 'state', 'country', 'created_at', 'updated_at'
        ]);
    }
}
