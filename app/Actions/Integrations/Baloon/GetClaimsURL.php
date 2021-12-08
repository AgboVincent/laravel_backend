<?php

namespace App\Actions\Integrations\Baloon;

use App\Helpers\JWT;
use App\Models\User;
use App\Helpers\Output;
use App\Models\Company;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Helpers\Integrations\Baloon;
use App\Http\Resources\LoginResource;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Integrations\Baloon\ClaimURLRequest;

class GetClaimsURL
{
    use AsAction;

    public function handle(array $requestPayload)
    {
        $jwtPayload = JWT::decodePayload($requestPayload['baloonSsoInfo']['token']);

        $user = Baloon::createUser($jwtPayload);

        $data = [];

        $data['url'] = $this->getClaimsURL($user);
        $data['token'] = $this->getCuracelAuthToken($user);

        // always include Baloon's SSO info
        $data['baloonSsoInfo'] = $requestPayload['baloonSsoInfo'];

        return $data;
    }

    public function asController(ClaimURLRequest $request)
    {
        $data = $this->handle($request->all());
       
        return Output::success($data);
        
    }

    
    /**
     * Get the bearer token for authentication on redirect.
     * 
     * @param  User $user
     * @return string
     */
    protected function getCuracelAuthToken(User $user)
    {
        return (new LoginResource($user))->toArray(null)['token'];
    }

    /**
     * Get the create-claims URL for the user.
     * 
     * @param  User  $user
     * @return string
     */
    protected function getClaimsURL(User $user)
    {
        return \config('app.front') . "customers/{$user->id}/claims/create";;
    }

}