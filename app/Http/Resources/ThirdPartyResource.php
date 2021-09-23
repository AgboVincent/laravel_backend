<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThirdPartyResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->resource->only([
           'full_name', 'mobile','company','policy_number'
        ]);
    }
}
