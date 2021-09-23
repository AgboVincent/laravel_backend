<?php

namespace App\Http\Resources;

use App\Models\User as UserModel;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function __construct(UserModel $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    public function toArray($request): array
    {
        $data = $this->resource->only([
            'id', 'first_name', 'last_name', 'mobile', 'email', 'created_at', 'updated_at', 'type', 'meta'
        ]);

        $data['addresses'] = Address::collection($this->resource->load(['addresses'])->addresses);

        return $data;
    }
}
