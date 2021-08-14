<?php

namespace App\Http\Controllers\Password;

use App\Exceptions\NotFoundException;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class Reset extends Controller
{
    /**
     * @param \App\Http\Requests\Password\ResetRequest $request
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function __invoke(\App\Http\Requests\Password\ResetRequest $request): JsonResponse
    {
        /**
         * @var PasswordReset $token
         */
        $token = PasswordReset::query()->where('token', $request->get('token'))
            ->first();

        if ($token == null) throw new NotFoundException('Invalid Reset Token');

        if ($token->created_at->addMinutes(60)->lessThan(now())) throw new NotFoundException('Token Expired');

        $user = User::query()->where('email', $token->email)->first();

        $user->update([
            'password' => Hash::make($request->get('password'))
        ]);

        $token->forceDelete();

        event(new \Illuminate\Auth\Events\PasswordReset($user));

        return Output::success(trans('passwords.reset'));
    }
}
