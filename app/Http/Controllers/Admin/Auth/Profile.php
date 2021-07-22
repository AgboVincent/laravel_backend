<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthUserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Profile extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return Output::success(new AuthUserResource(Auth::admin()));
    }
}
