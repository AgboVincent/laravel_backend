<?php

namespace App\Helpers\Integrations;

use App\Models\User;
use App\Models\Company;

class Baloon
{
    /**
     * The user's email key in the token payload
     * 
     * @var string
     */
    const USER_EMAIL_KEY = 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress';
    
    /**
     * The user's name key in the token payload
     * 
     * @var string
     */
    const USER_NAME_KEY = 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name';

    /**
     * The user's phone key in the token payload
     * 
     * @var string
     */
    const USER_MOBILE_KEY = 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/mobilephone';

    /**
     * Create a new user instance from Baloon SSO JWT.
     *
     * @param  array  $payload - decoded JWT's payload
     * @return \App\User
     */
    public static function createUser(array $payload)
    {
        $email = $payload[static::USER_EMAIL_KEY];
        $name = $payload[static::USER_NAME_KEY];
        $mobile = $payload[static::USER_MOBILE_KEY];
        $name = explode(' ', $name);

        $baloonId = Company::firstOrCreate([
            'name' => 'Baloon',
            'code' => 'baloon',
        ])->id;

        $brokerId = User::firstOrCreate([
            'email' => 'baloon.notreal@example.com',
            'first_name' => 'Broker',
            'last_name' => 'Baloon',
            'mobile' => '234234234234',
            'company_id' => $baloonId,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
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
                'email_verified_at' => now(),
                'meta' => [
                    'broker_id' => $brokerId,
                ],
            ]);
    }

    /**
     * Check if the JWT contains valid fields.
     *
     * @param  array  $payload - decoded JWT's payload
     * @return boolean
     */
    public static function hasValidToken(array $payload){
        return isset($payload[static::USER_EMAIL_KEY]) && trim($payload[static::USER_EMAIL_KEY]) != ''
            && isset($payload[static::USER_NAME_KEY]) && trim($payload[static::USER_NAME_KEY]) != '';
    }

}