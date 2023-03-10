<?php

namespace App\Http\Controllers\User;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Profile extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return Output::success(new UserResource($request->user()));
    }
}
