<?php

namespace App\Actions\Integrations\Baloon;

use App\Models\User;
use App\Helpers\Output;
use App\Models\Company;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\LoginResource;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Integrations\Baloon\ClaimURLRequest;

class GetClaimsURL
{
    use AsAction;

    public function handle(array $requestPayload)
    {
        $jwtPayload = $this->decodeBaloonJWTPayload($requestPayload['baloonSsoInfo']['token']);

        $user = $this->createUser($jwtPayload);

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
     * Create a new user instance from Baloon SSO JWT.
     *
     * @param  array  $payload - decoded JWT's payload
     * @return \App\User
     */
    protected function createUser(array $payload)
    {
        $email = $payload['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'];
        $name = $payload['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'];
        $mobile = $payload['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/mobilephone'];
        $name = explode(' ', $name);

        $baloonId = Company::where('code', 'baloon')->pluck('id')->first();

        return 
            User::firstOrCreate([
                'email' => $email,
            ], [
                'first_name' => $name[0],
                'last_name' => $name[1],
                'company_id' => $baloonId,
                'password' => bcrypt('baloon'),
                'mobile' => $mobile,
            ]);
    }

    /**
     * Decode the Baloon SSO JWT.
     * 
     * @param  string  $token
     * @return array - the payload of the decoded JWT
     */
    protected function decodeBaloonJWTPayload(string $token)
    {   
        $tokenParts = explode('.', $token);

        return  json_decode(
                    base64_decode($tokenParts[1]),
                    true
                );

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