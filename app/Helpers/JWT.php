<?php

namespace App\Helpers;

class JWT
{
    /**
     * Decode the payload segment of the JWT.
     * 
     * @param  string  $token
     * @return array - the payload of the decoded JWT
     */
    public static function decodePayload(string $token)
    {
        $tokenParts = explode('.', $token);

        return  json_decode(
                    base64_decode($tokenParts[1]),
                    true
                );
    }
}