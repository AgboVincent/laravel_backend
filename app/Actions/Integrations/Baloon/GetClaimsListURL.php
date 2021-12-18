<?php

namespace App\Actions\Integrations\Baloon;

use App\Helpers\JWT;
use App\Models\User;
use App\Helpers\Output;
use App\Helpers\Integrations\Baloon;
use App\Http\Resources\LoginResource;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Integrations\Baloon\ListClaimsURLRequest;

/**
 * @mixin AsAction
 */
class GetClaimsListURL
{   
    use AsAction;

    public function handle(array $requestPayload)
    {
        $jwtPayload = JWT::decodePayload($requestPayload['baloonSsoInfo']['token']);

        $user = Baloon::createUserForAuth($jwtPayload);

        $data = [];

        $data['url'] = $this->getClaimsURL($user);
        $data['token'] = $this->getCuracelAuthToken($user);

        // always include Baloon's SSO info
        $data['baloonSsoInfo'] = $requestPayload['baloonSsoInfo'];

        return $data;

    }

    public function AsController(ListClaimsURLRequest $request)
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
     * Get the list-claims URL for the user.
     * 
     * @param  User  $user
     * @return string
     */
    protected function getClaimsURL(User $user)
    {
        return \config('app.front') . "claims";;
    }
}