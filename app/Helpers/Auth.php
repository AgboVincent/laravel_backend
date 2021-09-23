<?php

namespace App\Helpers;

use App\Models\User;

class Auth
{
    /**
     * Get authenticated user instance.
     *
     * @return null | User
     */
    public static function user(): ?User
    {
        return request()->user();
    }
}
