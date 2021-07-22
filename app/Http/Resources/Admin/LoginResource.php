<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\AuthUserResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class LoginResource extends JsonResource
{
    public function __construct(Admin $resource)
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
        $token = $this->resource->createToken('token_name');

        return [
            'user' => new AuthUserResource($this->resource),
            'token' => Crypt::encryptString($token->plainTextToken),
            'expires_at' => now()->addDays(config('auth.guards.api.expires'))
        ];
    }
}
