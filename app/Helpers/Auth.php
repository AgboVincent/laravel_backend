<?php

namespace App\Helpers;

use App\Models\Admin;
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

    /**
     * Get the authenticated admin.
     *
     * @return Admin|null
     */
    public static function admin(): ?Admin
    {
        return request()->user();
    }
}
