<?php

namespace App\Helpers\Integrations;

use App\Models\User;
use App\Models\Company;

class Baloon
{
    /**
     * Create a new user instance from Baloon SSO JWT.
     *
     * @param  array  $payload - decoded JWT's payload
     * @return \App\User
     */
    public static function createUser(array $payload)
    {
        $email = $payload['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'];
        $name = $payload['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'];
        $mobile = $payload['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/mobilephone'];
        $name = explode(' ', $name);

        $baloonId = Company::firstOrCreate([
            'name' => 'Baloon',
            'code' => 'baloon',
        ])->id;

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

}