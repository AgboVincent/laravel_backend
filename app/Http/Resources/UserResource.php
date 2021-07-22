<?php

namespace App\Http\Resources;

use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function __construct(UserModel $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->resource->only([
            'id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at'
        ]);

        $data['addresses'] = Address::collection($this->resource->load(['addresses'])->addresses);

        return $data;
    }
}
