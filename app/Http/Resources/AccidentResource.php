<?php

namespace App\Http\Resources;

use App\Models\Accident;
use Illuminate\Http\Resources\Json\JsonResource;

class AccidentResource extends JsonResource
{
    public function __construct(Accident $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    public function toArray($request): array
    {
        $data = $this->resource->only([
            'id', 'description', 'occurred_at', 'type', 'involved_third_party'
        ]);

        $data['documents'] = AccidentMediaResource::collection($this->resource->media);

        if ($this->resource->involved_third_party) {
            $data['third_party'] = new ThirdPartyResource($this->resource->thirdParty);
        }

        return $data;
    }
}
