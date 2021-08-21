<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class Update extends Controller
{
    public function __invoke(\App\Http\Requests\Profile\Update $request): JsonResponse
    {
        Auth::user()->update($request->validated());

        return Output::success(new UserResource(Auth::user()->refresh()));
    }
}
