<?php

namespace App\Http\Controllers\Password;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ResetRequest extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /**
         * @var User $requestedUser
         */
        $requestedUser = User::query()->where('email', $request->get('email'))->first();

        try {
            $requestedUser->createPasswordResetToken();
        } catch (Throwable $exception) {
        }

        return Output::success('Password Reset Mail Sent');
    }
}
