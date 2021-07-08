<?php

namespace App\Http\Resources;

use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class Login extends JsonResource
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
        $token = $this->resource->createToken('token_name');

        return [
            'user' => new User($this->resource),
            'token' => Crypt::encryptString($token->plainTextToken),
            'expires_at' => now()->addDays(config('auth.guards.api.expires'))
        ];
    }
}
