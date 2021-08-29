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
    public function handle(Request $request, Closure $next, $type = 'user')
    {

        try {
            $accessTokenInstance = PersonalAccessToken::findToken(
                Crypt::decryptString($request->bearerToken())
            );

            /**
             * @var User $user
             */
            $user = $accessTokenInstance->tokenable->load(['bankAccount', 'addresses']);

            switch ($type) {
                case User::TYPE_BROKER:
                    if ($user->type !== User::TYPE_BROKER)
                        return Output::error('Invalid Token: Broker Only Allowed', Response::HTTP_UNAUTHORIZED);
                    break;
                case 'admin':
                    if ($user->type !== User::TYPE_BROKER && $user->type !== User::TYPE_INSURANCE)
                        return Output::error('Invalid Token: Only Broker or Insurer Allowed', Response::HTTP_UNAUTHORIZED);
                    break;
                // Check not needed as user routes should also be accessible to other user types
//                case User::TYPE_POLICY_HOLDER:
//                    if ($user->type !== User::TYPE_POLICY_HOLDER)
//                        return Output::error('Invalid Token: User Only Allowed', Response::HTTP_UNAUTHORIZED);
//                    break;
                case 'insurance':
                    if ($user->type !== User::TYPE_INSURANCE)
                        return Output::error('Invalid Token: Administrator Only Allowed', Response::HTTP_UNAUTHORIZED);
                    break;
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
