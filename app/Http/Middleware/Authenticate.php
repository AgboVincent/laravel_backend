<?php

namespace App\Http\Middleware;

use App\Helpers\Output;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;

class Authenticate
{
    public function handle(Request $request, \Closure $next): JsonResponse
    {
        try {
            $accessTokenInstance = PersonalAccessToken::findToken(
                Crypt::decryptString($request->bearerToken())
            );

            /**
             * @var User $user
             */
            $user = $accessTokenInstance->tokenable;

            // Check if token is expired
            if (now()->greaterThanOrEqualTo(
                $accessTokenInstance->created_at->addDays(config('auth.guards.api.expires'))
            )) {
                return Output::error('Token Expired', Response::HTTP_UNAUTHORIZED);
            }

            Auth::shouldUse('sanctum');
            Auth::setUser($user);

            return $next($request);
        } catch (\Throwable | \Exception $exception) {
            return Output::error('Invalid Token', Response::HTTP_UNAUTHORIZED);
        }
    }
}
