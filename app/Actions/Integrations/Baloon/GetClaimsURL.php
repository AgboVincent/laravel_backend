<?php

namespace App\Actions\Integrations\Baloon;

use App\Helpers\JWT;
use App\Models\User;
use App\Models\Policy;
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
        $userForAuth = Baloon::createUserForAuth($requestPayload);

        // create policies
        Baloon::createPolicies($requestPayload['dossierContact']);

        Baloon::setCustomerBroker($userForAuth);

        $data = [];

        $data['url'] = $this->getClaimsURL();
        $data['token'] = $this->getCuracelAuthToken($userForAuth);

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
    protected function getClaimsURL()
    {
        $randomPolicyId = Baloon::getPolicyIds()[0];
        return \config('app.front') . "customers/{$randomPolicyId}/claims/create";;
    }

}
