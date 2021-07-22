<?php

namespace App\Http\Resources;

use App\Models\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    public function __construct(Admin $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    public function toArray($request): array
    {
        return $this->resource->only([
            'id',
            'first_name',
            'last_name',
            'email',
            'created_at',
            'updated_at'
        ]);
    }
}
