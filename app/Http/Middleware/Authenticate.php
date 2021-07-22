<?php

namespace App\Http\Middleware;

use App\Helpers\Output;
use App\Models\Admin;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;
use Throwable;

class Authenticate
{
    public function handle(Request $request, Closure $next, $role = 'user')
    {

        try {
            $accessTokenInstance = PersonalAccessToken::findToken(
                Crypt::decryptString($request->bearerToken())
            );

            /**
             * @var User|Admin $user
             */
            $user = $accessTokenInstance->tokenable;

            if ($role === 'admin' && $accessTokenInstance->tokenable_type !== Admin::class) {
                return Output::error('Invalid Token: Administrator User Only Allowed', Response::HTTP_UNAUTHORIZED);
            }

            // Check if token is expired
            if (now()->greaterThanOrEqualTo(
                $accessTokenInstance->created_at->addDays(config('auth.guards.api.expires'))
            )) {
                return Output::error('Token Expired', Response::HTTP_UNAUTHORIZED);
            }

            Auth::shouldUse('sanctum');
            Auth::setUser($user);

            return $next($request);
        } catch (Throwable | Exception $exception) {
            return Output::error('Invalid Token', Response::HTTP_UNAUTHORIZED);
        }
    }
}
